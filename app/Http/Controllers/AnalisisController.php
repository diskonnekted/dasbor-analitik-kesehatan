<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\Faskes;
use App\Models\Stunting;
use App\Models\TenagaKesehatan;
use App\Models\KasusPenyakit;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AnalisisController extends Controller
{
    private $pythonApiUrl;

    public function __construct()
    {
        $this->pythonApiUrl = rtrim(config('services.analytics.url'), '/');
    }

    public function index()
    {
        return view('analisis.index');
    }

    public function korelasi()
    {
        $data = $this->getKecamatanDataForAnalysis();

        try {
            $response = Http::post("{$this->pythonApiUrl}/analyze/correlation", [
                'data' => $data
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return view('analisis.korelasi', [
                    'status' => 'success',
                    'results' => $result['results'],
                    'sample_size' => $result['sample_size'],
                    'data' => $data
                ]);
            } else {
                Log::error('Python API Error: ' . $response->body());
                return view('analisis.korelasi', [
                    'status' => 'error',
                    'message' => 'Gagal terhubung ke service analitik. (Code: ' . $response->status() . ')'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Python API Exception: ' . $e->getMessage());
            return view('analisis.korelasi', [
                'status' => 'error',
                'message' => 'Service analitik tidak berjalan atau tidak dapat dijangkau.'
            ]);
        }
    }

    public function klaster()
    {
        $data = $this->getKecamatanDataForAnalysis();

        try {
            $response = Http::post("{$this->pythonApiUrl}/analyze/cluster", [
                'data' => $data
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return view('analisis.klaster', [
                    'status' => 'success',
                    'clusters' => $result['clusters']
                ]);
            } else {
                return view('analisis.klaster', [
                    'status' => 'error',
                    'message' => 'Gagal memproses klastering data.'
                ]);
            }
        } catch (\Exception $e) {
            return view('analisis.klaster', [
                'status' => 'error',
                'message' => 'Service analitik tidak berjalan.'
            ]);
        }
    }

    public function prediksi()
    {
        $canonical = $this->canonicalKecamatanIds();

        $historical = Stunting::whereIn('kecamatan_id', $canonical)
            ->select('tahun', DB::raw('SUM(jumlah_stunting) * 100.0 / NULLIF(SUM(jumlah_balita_diukur), 0) as stunting'))
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get()
            ->map(function ($item) {
                return [
                    'tahun' => (int) $item->tahun,
                    'stunting' => round((float) $item->stunting, 2)
                ];
            })
            ->toArray();

        try {
            $response = Http::post("{$this->pythonApiUrl}/analyze/predict", [
                'data' => $historical,
                'years_ahead' => 3
            ]);

            if ($response->successful() && isset($response->json()['historical'])) {
                $result = $response->json();
                return view('analisis.prediksi', [
                    'status' => 'success',
                    'historical' => $result['historical'],
                    'forecast' => $result['forecast'],
                    'trend' => $result['trend'],
                    'slope' => $result['slope']
                ]);
            } else {
                return view('analisis.prediksi', [
                    'status' => 'error',
                    'message' => 'Gagal memproses prediksi data.'
                ]);
            }
        } catch (\Exception $e) {
            return view('analisis.prediksi', [
                'status' => 'error',
                'message' => 'Service analitik tidak berjalan.'
            ]);
        }
    }

    public function spasial()
    {
        $tahun = $this->analysisYear();

        $kecamatans = Kecamatan::whereIn('id', $this->canonicalKecamatanIds())
            ->whereNotNull('latitude')->whereNotNull('longitude')->get();
        $mapData = [];

        foreach ($kecamatans as $kec) {
            $mapData[] = [
                'id' => $kec->id,
                'nama' => $kec->nama,
                'lat' => $kec->latitude,
                'lng' => $kec->longitude,
                'stunting' => $this->stuntingPrevalensi($kec->id, $tahun) ?? 0,
                'faskes' => $this->faskesTotal($kec->id, $tahun),
            ];
        }

        return view('analisis.spasial', [
            'title' => 'Analisis Spasial',
            'mapData' => $mapData
        ]);
    }

    /**
     * Early Warning System: deteksi lonjakan (outbreak) penyakit menular
     * dengan membandingkan tahun terakhir terhadap rata-rata baseline tahun sebelumnya.
     */
    public function earlyWarning()
    {
        $penyakitList = [
            'dbd' => 'Demam Berdarah (DBD)',
            'diare' => 'Diare',
            'pneumonia' => 'Pneumonia',
            'tb_paru' => 'TB Paru',
            'malaria' => 'Malaria',
            'kusta' => 'Kusta',
            'hiv_baru' => 'HIV (Kasus Baru)',
            'ims' => 'IMS',
        ];

        $tahunTerakhir = (int) KasusPenyakit::max('tahun');
        $kecamatans = Kecamatan::whereIn('id', $this->canonicalKecamatanIds())->orderBy('nama')->get();

        $alerts = [];

        foreach ($kecamatans as $kec) {
            $rows = KasusPenyakit::where('kecamatan_id', $kec->id)->orderBy('tahun')->get();
            if ($rows->isEmpty()) {
                continue;
            }

            foreach ($penyakitList as $kolom => $label) {
                $terkini = (int) ($rows->firstWhere('tahun', $tahunTerakhir)->{$kolom} ?? 0);
                $baselineRows = $rows->where('tahun', '<', $tahunTerakhir);
                if ($baselineRows->isEmpty()) {
                    continue;
                }
                $baseline = $baselineRows->avg($kolom);

                // Abaikan kasus yang terlalu kecil untuk menghindari noise statistik
                if ($terkini < 5) {
                    continue;
                }

                $rasio = $baseline > 0 ? $terkini / $baseline : ($terkini > 0 ? 99 : 0);
                $pctChange = $baseline > 0 ? round(($terkini - $baseline) / $baseline * 100, 1) : 100;

                // Ambang lonjakan: minimal 50% di atas baseline
                if ($rasio >= 1.5) {
                    if ($rasio >= 3) {
                        $level = 'Kritis';
                    } elseif ($rasio >= 2) {
                        $level = 'Tinggi';
                    } else {
                        $level = 'Waspada';
                    }

                    $alerts[] = [
                        'kecamatan' => $kec->nama,
                        'penyakit' => $label,
                        'terkini' => $terkini,
                        'baseline' => round($baseline, 1),
                        'pct_change' => $pctChange,
                        'rasio' => round($rasio, 2),
                        'level' => $level,
                    ];
                }
            }
        }

        // Urutkan: paling parah di atas
        usort($alerts, fn($a, $b) => $b['rasio'] <=> $a['rasio']);

        return view('analisis.early-warning', [
            'title' => 'Sistem Peringatan Dini Penyakit',
            'tahun' => $tahunTerakhir,
            'alerts' => $alerts,
        ]);
    }

    /**
     * Rasio beban penyakit menular terhadap jumlah tenaga kesehatan per kecamatan.
     * Semakin tinggi rasio, semakin besar beban kerja nakes (indikasi kekurangan SDM).
     */
    public function rasioNakes()
    {
        $tahun = $this->analysisYear();
        $kecamatans = Kecamatan::whereIn('id', $this->canonicalKecamatanIds())->orderBy('nama')->get();

        $rows = [];
        foreach ($kecamatans as $kec) {
            $nakes = $this->nakesTotal($kec->id, $tahun);
            $beban = $this->penyakitTotal($kec->id, $tahun);
            $faskes = $this->faskesTotal($kec->id, $tahun);

            $rasio = $nakes > 0 ? round($beban / $nakes, 1) : null;

            $rows[] = [
                'kecamatan' => $kec->nama,
                'nakes' => $nakes,
                'beban' => $beban,
                'faskes' => $faskes,
                'rasio' => $rasio,
            ];
        }

        // Rata-rata rasio sebagai acuan
        $valid = array_filter(array_column($rows, 'rasio'), fn($v) => $v !== null);
        $rataRasio = count($valid) ? round(array_sum($valid) / count($valid), 1) : 0;

        // Tandai status beban
        foreach ($rows as &$r) {
            if ($r['rasio'] === null) {
                $r['status'] = 'Tidak Ada Data';
            } elseif ($r['rasio'] >= $rataRasio * 1.5) {
                $r['status'] = 'Beban Berat';
            } elseif ($r['rasio'] >= $rataRasio) {
                $r['status'] = 'Di Atas Rata-rata';
            } else {
                $r['status'] = 'Memadai';
            }
        }
        unset($r);

        // Urutkan rasio tertinggi di atas
        usort($rows, function ($a, $b) {
            return ($b['rasio'] ?? -1) <=> ($a['rasio'] ?? -1);
        });

        return view('analisis.rasio-nakes', [
            'title' => 'Rasio Nakes vs Beban Penyakit',
            'tahun' => $tahun,
            'rows' => $rows,
            'rata_rasio' => $rataRasio,
        ]);
    }

    /**
     * Indeks Kerawanan Kesehatan multi-indikator.
     * Menggabungkan 3 indikator ternormalisasi (min-max) menjadi skor 0-100:
     *  - Prevalensi stunting (bobot 40%)
     *  - Beban penyakit per nakes (bobot 35%)
     *  - Kelangkaan faskes / inversi jumlah faskes (bobot 25%)
     */
    public function indeksKerawanan()
    {
        $tahun = $this->analysisYear();
        $kecamatans = Kecamatan::whereIn('id', $this->canonicalKecamatanIds())->orderBy('nama')->get();

        $raw = [];
        foreach ($kecamatans as $kec) {
            $stunting = $this->stuntingPrevalensi($kec->id, $tahun);
            $nakes = $this->nakesTotal($kec->id, $tahun);
            $beban = $this->penyakitTotal($kec->id, $tahun);
            $faskes = $this->faskesTotal($kec->id, $tahun);
            $bebanPerNakes = $nakes > 0 ? $beban / $nakes : 0;

            $raw[] = [
                'kecamatan' => $kec->nama,
                'stunting' => $stunting ?? 0,
                'beban_per_nakes' => round($bebanPerNakes, 2),
                'faskes' => $faskes,
                'nakes' => $nakes,
            ];
        }

        // Min-max normalisasi tiap indikator
        $norm = function (array $values) {
            $min = min($values);
            $max = max($values);
            $range = $max - $min;
            return array_map(fn($v) => $range > 0 ? ($v - $min) / $range : 0, $values);
        };

        $stuntingVals = array_column($raw, 'stunting');
        $bebanVals = array_column($raw, 'beban_per_nakes');
        $faskesVals = array_column($raw, 'faskes');

        $nStunting = $norm($stuntingVals);
        $nBeban = $norm($bebanVals);
        $nFaskes = $norm($faskesVals);

        $rows = [];
        foreach ($raw as $i => $r) {
            // Faskes: makin sedikit makin rawan -> inversi
            $skorFaskesRawan = 1 - $nFaskes[$i];
            $indeks = ($nStunting[$i] * 0.40 + $nBeban[$i] * 0.35 + $skorFaskesRawan * 0.25) * 100;

            if ($indeks >= 66) {
                $kategori = 'Rawan Tinggi';
            } elseif ($indeks >= 33) {
                $kategori = 'Rawan Sedang';
            } else {
                $kategori = 'Rawan Rendah';
            }

            $rows[] = array_merge($r, [
                'indeks' => round($indeks, 1),
                'kategori' => $kategori,
            ]);
        }

        // Urutkan indeks tertinggi (paling rawan) di atas
        usort($rows, fn($a, $b) => $b['indeks'] <=> $a['indeks']);

        return view('analisis.indeks-kerawanan', [
            'title' => 'Indeks Kerawanan Kesehatan',
            'tahun' => $tahun,
            'rows' => $rows,
        ]);
    }

    // ----------------------------------------------------------------
    // Helper
    // ----------------------------------------------------------------

    /**
     * ID kecamatan kanonik: hanya yang memiliki data tenaga kesehatan.
     * Menyingkirkan duplikat (nama huruf kecil) yang tidak punya data lengkap
     * sehingga agregat tidak double-count.
     */
    private function canonicalKecamatanIds()
    {
        return TenagaKesehatan::distinct()->pluck('kecamatan_id')->all();
    }

    /**
     * Tahun analisis: tahun terbaru yang tersedia di data nakes & penyakit.
     */
    private function analysisYear()
    {
        $ny = (int) TenagaKesehatan::max('tahun');
        $py = (int) KasusPenyakit::max('tahun');
        return min($ny, $py);
    }

    private function stuntingPrevalensi($kecamatanId, $tahun)
    {
        $row = Stunting::where('kecamatan_id', $kecamatanId)
            ->where('tahun', $tahun)
            ->selectRaw('SUM(jumlah_stunting) as s, SUM(jumlah_balita_diukur) as d')
            ->first();
        if (!$row || !$row->d) {
            return null;
        }
        return round($row->s * 100.0 / $row->d, 2);
    }

    private function nakesTotal($kecamatanId, $tahun)
    {
        return (int) TenagaKesehatan::where('kecamatan_id', $kecamatanId)
            ->where('tahun', $tahun)
            ->sum('total');
    }

    private function faskesTotal($kecamatanId, $tahun)
    {
        $f = Faskes::where('kecamatan_id', $kecamatanId)
            ->where('tahun', $tahun)
            ->first();
        if (!$f) {
            return 0;
        }
        return (int) ($f->rs_umum + $f->puskesmas + $f->klinik + $f->poskesdes);
    }

    /**
     * Total beban penyakit menular utama pada satu kecamatan/tahun.
     */
    private function penyakitTotal($kecamatanId, $tahun)
    {
        $k = KasusPenyakit::where('kecamatan_id', $kecamatanId)
            ->where('tahun', $tahun)
            ->first();
        if (!$k) {
            return 0;
        }
        return (int) ($k->tb_paru + $k->pneumonia + $k->diare + $k->dbd
            + $k->kusta + $k->malaria + $k->hiv_baru + $k->ims);
    }

    /**
     * Data per kecamatan untuk korelasi & klaster.
     *
     * Nakes & faskes dinormalisasi terhadap jumlah penduduk (kepadatan per kapita)
     * agar korelasi valid: angka absolut bias oleh ukuran wilayah, sedangkan
     * kepadatan mencerminkan ketersediaan layanan yang sebenarnya dirasakan warga.
     *  - nakes  : jumlah tenaga kesehatan per 1.000 penduduk
     *  - faskes : jumlah fasilitas kesehatan per 100.000 penduduk
     */
    private function getKecamatanDataForAnalysis()
    {
        $tahun = $this->analysisYear();
        $kecamatans = Kecamatan::whereIn('id', $this->canonicalKecamatanIds())
            ->where('jumlah_penduduk', '>', 0)
            ->orderBy('nama')
            ->get();
        $result = [];

        foreach ($kecamatans as $kec) {
            $prevalensi = $this->stuntingPrevalensi($kec->id, $tahun);
            if ($prevalensi === null) {
                continue;
            }

            $penduduk = (int) $kec->jumlah_penduduk;
            $nakesAbs = $this->nakesTotal($kec->id, $tahun);
            $faskesAbs = $this->faskesTotal($kec->id, $tahun);

            $result[] = [
                'id' => strval($kec->id),
                'nama' => $kec->nama,
                'stunting' => floatval($prevalensi),
                'penduduk' => $penduduk,
                'nakes_absolut' => $nakesAbs,
                'faskes_absolut' => $faskesAbs,
                // Kepadatan per kapita (dibulatkan 2 desimal)
                'nakes' => round($nakesAbs / $penduduk * 1000, 2),
                'faskes' => round($faskesAbs / $penduduk * 100000, 2),
            ];
        }

        return $result;
    }
}
