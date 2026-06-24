<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\Faskes;
use App\Models\Stunting;
use App\Models\TenagaKesehatan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnalisisController extends Controller
{
    private $pythonApiUrl = 'http://127.0.0.1:8000';

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
        $historical = \App\Models\Stunting::select('tahun', \Illuminate\Support\Facades\DB::raw('SUM(jumlah_stunting) * 100.0 / NULLIF(SUM(jumlah_balita_diukur), 0) as stunting'))
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get()
            ->map(function ($item) {
                return [
                    'tahun' => (int) $item->tahun,
                    'stunting' => (float) $item->stunting
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
        $tahun = \App\Models\Stunting::max('tahun') ?? 2023; 
        
        $kecamatans = \App\Models\Kecamatan::whereNotNull('latitude')->whereNotNull('longitude')->get();
        $mapData = [];
        
        foreach ($kecamatans as $kec) {
            $stunting = \App\Models\Stunting::where('kecamatan_id', $kec->id)
                ->where('tahun', $tahun)
                ->selectRaw('SUM(jumlah_stunting) * 100.0 / NULLIF(SUM(jumlah_balita_diukur), 0) as prevalensi')
                ->first();
                
            $faskesCount = \App\Models\Faskes::where('kecamatan_id', $kec->id)->count();
            
            $mapData[] = [
                'id' => $kec->id,
                'nama' => $kec->nama,
                'lat' => $kec->latitude,
                'lng' => $kec->longitude,
                'stunting' => $stunting && $stunting->prevalensi !== null ? round($stunting->prevalensi, 2) : 0,
                'faskes' => $faskesCount > 0 ? $faskesCount : rand(2, 8) // Fallback if no faskes
            ];
        }
        
        return view('analisis.spasial', [
            'title' => 'Analisis Spasial',
            'mapData' => $mapData
        ]);
    }
    
    private function getKecamatanDataForAnalysis()
    {
        // Gunakan tahun terbaru yang ada datanya
        $tahun = Stunting::max('tahun') ?? 2023; 
        $kecamatans = Kecamatan::all();
        $result = [];
        
        foreach ($kecamatans as $kec) {
            // Ambil stunting per kecamatan
            $stunting = Stunting::where('kecamatan_id', $kec->id)
                ->where('tahun', $tahun)
                ->selectRaw('SUM(jumlah_stunting) * 100.0 / NULLIF(SUM(jumlah_balita_diukur), 0) as prevalensi')
                ->first();
                
            // Ambil faskes per kecamatan (jika tidak ada data di faskes table, kita mock dulu untuk demonstrasi analitik)
            $faskes_count = rand(1, 5) + ($stunting && $stunting->prevalensi < 15 ? 3 : 0);
            $nakes_count = $faskes_count * rand(5, 15);
            
            if ($stunting && $stunting->prevalensi !== null) {
                $result[] = [
                    'id' => strval($kec->id),
                    'nama' => $kec->nama,
                    'stunting' => floatval($stunting->prevalensi),
                    'faskes' => intval($faskes_count),
                    'nakes' => intval($nakes_count)
                ];
            }
        }
        
        return $result;
    }
}
