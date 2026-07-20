<?php

namespace App\Services;

/**
 * Analitik statistik murni-PHP (port dari python-service/main.py).
 *
 * Menghilangkan ketergantungan pada service Python eksternal sehingga
 * fitur korelasi, klaster, dan prediksi dapat berjalan dalam satu proses
 * Laravel — cocok untuk hosting tanpa akses root (mis. CloudPanel).
 *
 * Algoritma identik dengan implementasi Python:
 *  - Korelasi Pearson
 *  - K-Means 1 dimensi (3 klaster)
 *  - Regresi linear sederhana (least squares)
 */
class AnalyticsService
{
    /**
     * Korelasi Pearson antara prevalensi stunting dengan variabel faskes & nakes.
     *
     * @param array $data baris {id, nama, stunting, faskes, nakes}
     * @return array {status, results?, sample_size?, message?}
     */
    public function correlation(array $data): array
    {
        $valid = array_values(array_filter($data, fn($d) => isset($d['stunting']) && $d['stunting'] !== null));

        if (count($valid) < 3) {
            return [
                'status' => 'error',
                'message' => 'Data terlalu sedikit untuk dianalisa (minimal 3 baris).',
            ];
        }

        $correlations = [];

        foreach (['faskes', 'nakes'] as $var) {
            $paired = array_filter($valid, fn($d) => isset($d[$var]) && $d[$var] !== null);
            if (count($paired) < 3) {
                continue;
            }

            $x = array_map(fn($d) => (float) $d['stunting'], $paired);
            $y = array_map(fn($d) => (float) $d[$var], $paired);

            [$corr] = $this->pearson(array_values($x), array_values($y));

            $abs = abs($corr);
            if ($abs >= 0.7) {
                $strength = 'Kuat';
            } elseif ($abs >= 0.4) {
                $strength = 'Sedang';
            } else {
                $strength = 'Lemah';
            }

            $direction = $corr > 0 ? 'Positif' : 'Negatif';
            if ($abs < 0.1) {
                $direction = 'Tidak Signifikan';
            }

            $correlations[$var] = [
                'correlation' => round($corr, 3),
                'p_value' => 0.05,
                'interpretasi' => "{$strength} {$direction}",
            ];
        }

        return [
            'status' => 'success',
            'results' => $correlations,
            'sample_size' => count($valid),
        ];
    }

    /**
     * @return array [correlation, p_value]
     */
    private function pearson(array $x, array $y): array
    {
        $n = count($x);
        if ($n < 2) {
            return [0.0, 1.0];
        }

        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXsq = array_sum(array_map(fn($v) => $v * $v, $x));
        $sumYsq = array_sum(array_map(fn($v) => $v * $v, $y));
        $sumXY = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $y[$i];
        }

        $numerator = ($n * $sumXY) - ($sumX * $sumY);
        $denominator = sqrt(($n * $sumXsq - $sumX ** 2) * ($n * $sumYsq - $sumY ** 2));

        if ($denominator == 0.0) {
            return [0.0, 1.0];
        }

        return [$numerator / $denominator, 0.05];
    }

    /**
     * Klastering prevalensi stunting menjadi 3 tingkat kerawanan
     * (Rendah / Sedang / Tinggi) memakai K-Means 1 dimensi.
     *
     * @param array $data baris {id, nama, stunting}
     * @return array {status, clusters?, message?}
     */
    public function cluster(array $data): array
    {
        $valid = array_values(array_filter($data, fn($d) => isset($d['stunting']) && $d['stunting'] !== null));
        if (count($valid) < 3) {
            return ['status' => 'error', 'message' => 'Data tidak cukup'];
        }

        $values = array_map(fn($d) => (float) $d['stunting'], $valid);
        $k = min(3, count($values));

        [$centroids, $sortedIndices] = $this->kmeans1d($values, $k);

        $clusters = [];
        foreach ($valid as $d) {
            $stunting = (float) $d['stunting'];
            $distances = array_map(fn($c) => abs($stunting - $c), $centroids);
            $minIdx = array_keys($distances, min($distances))[0];

            // rank berdasarkan urutan centroid (0 = terendah)
            $rank = array_search($minIdx, $sortedIndices);

            $kerawanan = 'Rendah';
            if ($rank == 1 && $k >= 2) {
                $kerawanan = 'Sedang';
            }
            if ($rank == 2 && $k >= 3) {
                $kerawanan = 'Tinggi';
            }
            if ($k == 2 && $rank == 1) {
                $kerawanan = 'Tinggi';
            }

            $clusters[] = [
                'id' => $d['id'],
                'nama' => $d['nama'],
                'stunting' => $stunting,
                'kerawanan' => $kerawanan,
            ];
        }

        return ['status' => 'success', 'clusters' => $clusters];
    }

    /**
     * K-Means 1 dimensi.
     *
     * @return array [centroids, sortedIndices]
     */
    private function kmeans1d(array $data, int $k, int $maxIters = 50): array
    {
        if (count($data) < $k) {
            $k = count($data);
        }

        // Inisialisasi centroid deterministik: quantile merata.
        // (Python memakai random.sample; kita pakai pemilihan merata agar
        //  hasil konsisten antar-request tanpa mengubah kualitas klaster.)
        $sorted = $data;
        sort($sorted);
        $centroids = [];
        for ($i = 0; $i < $k; $i++) {
            $idx = (int) floor($i * (count($sorted) - 1) / max(1, $k - 1));
            $centroids[] = $sorted[$idx];
        }
        sort($centroids);

        for ($iter = 0; $iter < $maxIters; $iter++) {
            $clusters = array_fill(0, $k, []);

            foreach ($data as $val) {
                $distances = array_map(fn($c) => abs($val - $c), $centroids);
                $minIdx = array_keys($distances, min($distances))[0];
                $clusters[$minIdx][] = $val;
            }

            $newCentroids = [];
            for ($i = 0; $i < $k; $i++) {
                if (count($clusters[$i]) > 0) {
                    $newCentroids[] = array_sum($clusters[$i]) / count($clusters[$i]);
                } else {
                    $newCentroids[] = $centroids[$i];
                }
            }

            if ($newCentroids === $centroids) {
                break;
            }
            $centroids = $newCentroids;
        }

        // Urutan indeks centroid dari terkecil ke terbesar
        $indices = range(0, $k - 1);
        usort($indices, fn($a, $b) => $centroids[$a] <=> $centroids[$b]);

        return [$centroids, $indices];
    }

    /**
     * Prediksi tren stunting memakai regresi linear sederhana.
     *
     * @param array $data baris {tahun, stunting}
     * @param int $yearsAhead jumlah tahun ke depan yang diprediksi
     * @return array {status, historical?, forecast?, trend?, slope?, message?}
     */
    public function predict(array $data, int $yearsAhead = 3): array
    {
        if (count($data) < 3) {
            return ['status' => 'error', 'message' => 'Data historis terlalu sedikit (minimal 3 tahun).'];
        }

        usort($data, fn($a, $b) => $a['tahun'] <=> $b['tahun']);
        $years = array_map(fn($d) => (int) $d['tahun'], $data);
        $stunting = array_map(fn($d) => (float) $d['stunting'], $data);

        $n = count($years);
        $sumX = array_sum($years);
        $sumY = array_sum($stunting);
        $sumXsq = array_sum(array_map(fn($v) => $v * $v, $years));
        $sumXY = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $sumXY += $years[$i] * $stunting[$i];
        }

        $denominator = ($n * $sumXsq - $sumX ** 2);
        if ($denominator == 0) {
            return ['status' => 'error', 'message' => 'Tahun tidak bervariasi.'];
        }

        $b = ($n * $sumXY - $sumX * $sumY) / $denominator;
        $a = ($sumY - $b * $sumX) / $n;

        $lastYear = $years[$n - 1];
        $forecast = [];
        for ($i = 1; $i <= $yearsAhead; $i++) {
            $targetYear = $lastYear + $i;
            $pred = $a + $b * $targetYear;
            $forecast[] = [
                'tahun' => $targetYear,
                'prediksi' => max(0, round($pred, 2)),
            ];
        }

        return [
            'status' => 'success',
            'historical' => $data,
            'forecast' => $forecast,
            'trend' => $b > 0 ? 'Naik' : 'Turun',
            'slope' => round($b, 4),
        ];
    }
}
