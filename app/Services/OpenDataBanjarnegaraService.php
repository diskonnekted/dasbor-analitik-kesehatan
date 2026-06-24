<?php
// app/Services/OpenDataBanjarnegaraService.php

namespace App\Services;

use App\Models\Kecamatan;
use App\Models\Faskes;
use App\Models\TenagaKesehatan;
use App\Models\KasusPenyakit;
use App\Models\AKI;
use App\Models\AKB;
use App\Models\Persalinan;
use App\Models\Posyandu;
use App\Models\PasienRawat;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OpenDataBanjarnegaraService
{
    private $baseUrl = 'https://opendata.banjarnegarakab.go.id';
    private $apiUrl = 'https://opendata.banjarnegarakab.go.id/api/3/action';
    
    // Daftar dataset kesehatan
    private $datasets = [
        'tenaga_kesehatan' => [
            'id' => 'eec1bcd1-ddf4-4762-84d1-20741629e42f',
            'resource_id' => 'd5bd29e4-1fe3-4876-9f1c-fa922bfebb21',
            'model' => TenagaKesehatan::class,
        ],
        'faskes' => [
            'id' => 'db593cb1-6a35-4505-81a3-9cecc86edc9f',
            'resource_id' => '3b69a652-bdf9-41b0-a98e-338234ffe606',
            'model' => Faskes::class,
        ],
        'kasus_penyakit' => [
            'id' => '5644d9b5-3635-422a-ade6-4497104a1c39',
            'resource_id' => '50a67e62-9794-49ca-8432-1da53cc7b338',
            'model' => KasusPenyakit::class,
        ],
        'kematian_ibu_bayi' => [
            'id' => '3e5e6c04-5879-46ca-a212-fe225e20d80c',
            'resource_id' => 'ec44a204-e8f6-4f60-947f-81691fcd6e4e',
            'model' => AKI::class,
        ],
        'persalinan' => [
            'id' => 'd481d744-e995-4a49-8625-c52eaabb2f88',
            'resource_id' => '8ea75350-0103-4113-91eb-61193da46952',
            'model' => Persalinan::class,
        ],
        'pasien_rawat' => [
            'id' => '49b4802f-0898-454a-aba5-e45d0428e979',
            'resource_id' => '3c5913a4-c2e0-4e72-9645-db79f719cfeb',
            'model' => PasienRawat::class,
        ],
        'posyandu' => [
            'id' => 'jumlah-posyandu-dan-kadernya-di-kabupaten-banjarnegara',
            'resource_id' => '690b15ac-ec28-42ca-a866-4901b07800ef',
            'model' => Posyandu::class,
        ],
    ];
    
    /**
     * Download CSV dari OpenData
     */
    public function downloadDataset($datasetKey)
    {
        if (!isset($this->datasets[$datasetKey])) {
            throw new \Exception("Dataset {$datasetKey} tidak ditemukan");
        }
        
        $dataset = $this->datasets[$datasetKey];
        $url = "{$this->baseUrl}/dataset/{$dataset['id']}/resource/{$dataset['resource_id']}/download";
        
        Log::info("Downloading dataset: {$datasetKey} from {$url}");
        
        try {
            $response = Http::timeout(30)->get($url);
            
            if ($response->successful()) {
                return $response->body();
            }
            
            throw new \Exception("Gagal download: HTTP " . $response->status());
        } catch (\Exception $e) {
            Log::error("Error downloading {$datasetKey}: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Parse CSV dengan auto-detect delimiter
     */
    public function parseCSV($csvContent)
    {
        $lines = explode("\n", trim($csvContent));
        $header = str_getcsv($lines[0], ';');
        
        // Detect delimiter
        $delimiter = ';';
        if (count($header) <= 1) {
            $header = str_getcsv($lines[0], ',');
            $delimiter = ',';
        }
        
        $data = [];
        for ($i = 1; $i < count($lines); $i++) {
            if (empty(trim($lines[$i]))) continue;
            $row = str_getcsv($lines[$i], $delimiter);
            if (count($row) === count($header)) {
                $data[] = array_combine($header, $row);
            }
        }
        
        return $data;
    }
    
    /**
     * Import semua dataset kesehatan
     */
    public function importAll()
    {
        $results = [];
        
        foreach ($this->datasets as $key => $dataset) {
            try {
                Log::info("Starting import for: {$key}");
                
                $csvContent = $this->downloadDataset($key);
                $data = $this->parseCSV($csvContent);
                
                $imported = $this->importDataset($key, $data);
                
                $results[$key] = [
                    'success' => true,
                    'imported' => $imported,
                    'message' => "Berhasil import {$imported} record"
                ];
                
                Log::info("Import {$key} completed: {$imported} records");
                
            } catch (\Exception $e) {
                $results[$key] = [
                    'success' => false,
                    'imported' => 0,
                    'message' => "Error: " . $e->getMessage()
                ];
                
                Log::error("Import {$key} failed: " . $e->getMessage());
            }
        }
        
        return $results;
    }
    
    /**
     * Import dataset spesifik
     */
    private function importDataset($key, $data)
    {
        switch ($key) {
            case 'tenaga_kesehatan':
                return $this->importTenagaKesehatan($data);
            case 'faskes':
                return $this->importFaskes($data);
            case 'kasus_penyakit':
                return $this->importKasusPenyakit($data);
            case 'kematian_ibu_bayi':
                return $this->importKematianIbuBayi($data);
            case 'persalinan':
                return $this->importPersalinan($data);
            case 'pasien_rawat':
                return $this->importPasienRawat($data);
            case 'posyandu':
                return $this->importPosyandu($data);
            default:
                return 0;
        }
    }
    
    /**
     * Import Tenaga Kesehatan
     */
    private function importTenagaKesehatan($data)
    {
        $imported = 0;
        
        DB::transaction(function () use ($data, &$imported) {
            foreach ($data as $row) {
                $kecamatan = Kecamatan::firstOrCreate(['nama' => trim($row['Kecamatan'])], ['kode' => uniqid()]);
                
                $tahun = (int) $row['Tahun'];
                
                // Hapus data lama untuk tahun dan kecamatan yang sama
                TenagaKesehatan::where('kecamatan_id', $kecamatan->id)
                    ->where('tahun', $tahun)
                    ->delete();
                
                // Insert data baru
                TenagaKesehatan::create([
                    'kecamatan_id' => $kecamatan->id,
                    'tahun' => $tahun,
                    'dokter_umum' => $this->parseInt($row['Dokter'] ?? 0),
                    'dokter_gigi' => $this->parseInt($row['Dokter Gigi'] ?? 0),
                    'perawat' => $this->parseInt($row['Perawat'] ?? 0),
                    'bidan' => $this->parseInt($row['Bidan'] ?? 0),
                    'farmasi' => $this->parseInt($row['Farmasi'] ?? 0),
                    'kesmas' => $this->parseInt($row['Tenaga Kesehatan Masyarakat'] ?? $row['Kesehatan Masyarakat'] ?? 0),
                    'kesling' => $this->parseInt($row['Tenaga Kesehatan Lingkungan'] ?? $row['Sanitarian'] ?? $row['Tenaga Sanitasi Lingkungan'] ?? 0),
                    'gizi' => $this->parseInt($row['Ahli  Gizi'] ?? $row['Ahli Gizi'] ?? $row['Gizi'] ?? 0),
                    'atl_m' => $this->parseInt($row['Ahli Teknologi Laboratorium Medik'] ?? $row['Ahli Teknologi Laboratorium'] ?? 0),
                    'total' => $this->parseInt($row['Total'] ?? 0),
                ]);
                
                $imported++;
            }
        });
        
        return $imported;
    }
    
    /**
     * Import Faskes
     */
    private function importFaskes($data)
    {
        $imported = 0;
        
        DB::transaction(function () use ($data, &$imported) {
            foreach ($data as $row) {
                $kecamatan = Kecamatan::firstOrCreate(['nama' => trim($row['Kecamatan'])], ['kode' => uniqid()]);
                
                $tahun = (int) $row['Tahun'];
                
                // Hapus data lama
                Faskes::where('kecamatan_id', $kecamatan->id)
                    ->where('tahun', $tahun)
                    ->delete();
                
                // Insert data baru
                Faskes::create([
                    'kecamatan_id' => $kecamatan->id,
                    'tahun' => $tahun,
                    'rs_umum' => $this->parseInt($row[' Rumah Sakit Umum '] ?? $row['Rumah Sakit Umum'] ?? $row['RS Umum'] ?? 0),
                    'puskesmas' => $this->parseInt($row[' Puskesmas '] ?? $row['Puskesmas'] ?? 0),
                    'klinik' => $this->parseInt($row[' Klinik Balai Kesehatan '] ?? $row['Klinik Balai Kesehatan'] ?? $row['Klinik'] ?? 0),
                    'posyandu' => $this->parseInt($row[' Posyandu '] ?? $row['Posyandu'] ?? 0),
                    'poskesdes' => $this->parseInt($row[' Poskesdes '] ?? $row['Poskesdes'] ?? 0),
                    'total' => $this->parseInt($row[' Jumlah '] ?? $row['Jumlah'] ?? $row['Total'] ?? 0),
                ]);
                
                $imported++;
            }
        });
        
        return $imported;
    }
    
    /**
     * Import Kasus Penyakit
     */
    private function importKasusPenyakit($data)
    {
        $imported = 0;
        
        DB::transaction(function () use ($data, &$imported) {
            foreach ($data as $row) {
                $kecamatan = Kecamatan::where('nama', trim($row['Kecamatan']))->first();
                if (!$kecamatan) continue;
                
                $tahun = (int) $row['Tahun'];
                
                // Hapus data lama
                KasusPenyakit::where('kecamatan_id', $kecamatan->id)
                    ->where('tahun', $tahun)
                    ->delete();
                
                // Insert data baru
                KasusPenyakit::create([
                    'kecamatan_id' => $kecamatan->id,
                    'tahun' => $tahun,
                    'malaria' => $this->parseInt($row['Malaria (positif)']),
                    'tb_paru' => $this->parseInt($row['TB Paru']),
                    'pneumonia' => $this->parseInt($row['Pneumonia']),
                    'kusta' => $this->parseInt($row['Kusta']),
                    'tetanus_neonatorum' => $this->parseInt($row['Tetanus Neonatorum']),
                    'campak' => $this->parseInt($row['Campak']),
                    'diare' => $this->parseInt($row['Diare']),
                    'dbd' => $this->parseInt($row['Demam Berdarah']),
                    'hiv_baru' => $this->parseInt($row['Kasus Baru HIV']),
                    'hiv_kumulatif' => $this->parseInt($row['Kasus Kumulatif HIV']),
                    'ims' => $this->parseInt($row['Infeksi Menular Seksual (IMS)']),
                ]);
                
                $imported++;
            }
        });
        
        return $imported;
    }
    
    /**
     * Import Kematian Ibu dan Bayi
     */
    private function importKematianIbuBayi($data)
    {
        $imported = 0;
        
        DB::transaction(function () use ($data, &$imported) {
            foreach ($data as $row) {
                $puskesmas = trim($row['PUSKESMAS']);
                $tahun = (int) $row['TAHUN'];
                
                // Cari kecamatan berdasarkan nama puskesmas
                $kecamatan = $this->getKecamatanByPuskesmas($puskesmas);
                if (!$kecamatan) continue;
                
                // Hapus data lama
                AKI::where('kecamatan_id', $kecamatan->id)
                    ->where('tahun', $tahun)
                    ->where('puskesmas', $puskesmas)
                    ->delete();
                
                AKB::where('kecamatan_id', $kecamatan->id)
                    ->where('tahun', $tahun)
                    ->where('puskesmas', $puskesmas)
                    ->delete();
                
                // Insert AKI
                AKI::create([
                    'kecamatan_id' => $kecamatan->id,
                    'tahun' => $tahun,
                    'puskesmas' => $puskesmas,
                    'jumlah_kematian_ibu' => $this->parseInt($row['KEMATIAN IBU']),
                ]);
                
                // Insert AKB
                AKB::create([
                    'kecamatan_id' => $kecamatan->id,
                    'tahun' => $tahun,
                    'puskesmas' => $puskesmas,
                    'jumlah_kematian_bayi' => $this->parseInt($row['KEMATIAN BAYI']),
                ]);
                
                $imported++;
            }
        });
        
        return $imported;
    }
    
    /**
     * Import Persalinan
     */
    private function importPersalinan($data)
    {
        $imported = 0;
        
        DB::transaction(function () use ($data, &$imported) {
            foreach ($data as $row) {
                $kecamatan = Kecamatan::where('nama', 'like', '%' . trim($row['Kecamatan']) . '%')->first();
                if (!$kecamatan) continue;
                
                $tahun = (int) $row['Tahun'];
                
                Persalinan::updateOrCreate(
                    [
                        'kecamatan_id' => $kecamatan->id,
                        'tahun' => $tahun,
                        'puskesmas' => trim($row['Kecamatan']),
                    ],
                    [
                        'tenaga_kesehatan' => $this->parseInt($row['Tenaga Kesehatan']),
                        'dukun' => $this->parseInt($row['Dukun']),
                        'sendiri' => $this->parseInt($row['Sendiri']),
                        'total' => $this->parseInt($row['Total']),
                    ]
                );
                
                $imported++;
            }
        });
        
        return $imported;
    }
    
    /**
     * Import Pasien Rawat
     */
    private function importPasienRawat($data)
    {
        $imported = 0;
        
        DB::transaction(function () use ($data, &$imported) {
            foreach ($data as $row) {
                $tahun = (int) $row['Tahun'];
                $puskesmas = trim($row['Puskesmas']);
                
                PasienRawat::updateOrCreate(
                    [
                        'tahun' => $tahun,
                        'puskesmas' => $puskesmas,
                    ],
                    [
                        'rawat_jalan' => $this->parseInt($row['Jumlah Kunjungan Rawat Jalan']),
                        'rawat_inap' => $this->parseInt($row['Jumlah Kunjungan Rawat Inap']),
                    ]
                );
                
                $imported++;
            }
        });
        
        return $imported;
    }
    
    /**
     * Import Posyandu
     */
    private function importPosyandu($data)
    {
        $imported = 0;
        
        DB::transaction(function () use ($data, &$imported) {
            foreach ($data as $row) {
                $puskesmas = trim($row['Puskesmas']);
                $tahun = (int) $row['Tahun'];
                
                $kecamatan = $this->getKecamatanByPuskesmas($puskesmas);
                if (!$kecamatan) continue;
                
                Posyandu::updateOrCreate(
                    [
                        'kecamatan_id' => $kecamatan->id,
                        'tahun' => $tahun,
                        'puskesmas' => $puskesmas,
                    ],
                    [
                        'jumlah_posyandu' => $this->parseInt($row['Jumlah Posyandu']),
                        'jumlah_kader' => $this->parseInt($row['Jumlah Kader Posyandu']),
                    ]
                );
                
                $imported++;
            }
        });
        
        return $imported;
    }
    
    /**
     * Helper: Parse integer dari string
     */
    private function parseInt($value)
    {
        if (empty($value)) return 0;
        return (int) preg_replace('/[^0-9]/', '', $value);
    }
    
    /**
     * Helper: Cari kecamatan berdasarkan nama puskesmas
     */
    private function getKecamatanByPuskesmas($puskesmas)
    {
        // Bersihkan angka (Arab & Romawi) dan spasi berlebih
        $cleanName = trim(preg_replace('/[0-9]+| I+| II+| III+/', '', $puskesmas));
        
        $mapping = [
            'Susukan' => 'Susukan',
            'Pwj Klampok' => 'Purwareja Klampok',
            'Mandiraja' => 'Mandiraja',
            'Purwonegoro' => 'Purwanegara',
            'Bawang' => 'Bawang',
            'Banjarnegara' => 'Banjarnegara',
            'Pagedongan' => 'Pagedongan',
            'Sigaluh' => 'Sigaluh',
            'Madukara' => 'Madukara',
            'Banjarmangu' => 'Banjarmangu',
            'Wanadadi' => 'Wanadadi',
            'Rakit' => 'Rakit',
            'Punggelan' => 'Punggelan',
            'Karangkobar' => 'Karangkobar',
            'Pagentan' => 'Pagentan',
            'Pejawaran' => 'Pejawaran',
            'Batur' => 'Batur',
            'Wanayasa' => 'Wanayasa',
            'Kalibening' => 'Kalibening',
            'Pandanarum' => 'Pandanarum',
        ];
        
        $kecamatanName = $mapping[$cleanName] ?? $cleanName;
        
        return Kecamatan::where('nama', 'like', "%{$kecamatanName}%")->first();
    }
}

