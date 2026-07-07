<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenyakitController extends Controller
{
    public function index()
    {
        $data = DB::table('kasus_penyakits')
            ->leftJoin('kecamatans', 'kasus_penyakits.kecamatan_id', '=', 'kecamatans.id')
            ->select('kasus_penyakits.*', 'kecamatans.nama as kecamatan_nama')
            ->paginate(15);
            
        // Chart Data (Sum of top diseases)
        $chartData = DB::table('kasus_penyakits')
            ->selectRaw('SUM(malaria) as malaria, SUM(tb_paru) as tb_paru, SUM(pneumonia) as pneumonia, SUM(diare) as diare, SUM(dbd) as dbd')
            ->first();
            
        $labels = ['Malaria', 'TB Paru', 'Pneumonia', 'Diare', 'DBD'];
        $dataset = [
            $chartData->malaria ?? 0,
            $chartData->tb_paru ?? 0,
            $chartData->pneumonia ?? 0,
            $chartData->diare ?? 0,
            $chartData->dbd ?? 0
        ];
        
        // Basic Analysis
        $diseasesAssoc = [
            'Malaria' => $chartData->malaria ?? 0,
            'TB Paru' => $chartData->tb_paru ?? 0,
            'Pneumonia' => $chartData->pneumonia ?? 0,
            'Diare' => $chartData->diare ?? 0,
            'DBD' => $chartData->dbd ?? 0
        ];
        arsort($diseasesAssoc);
        $topDiseaseName = array_key_first($diseasesAssoc);
        $topDiseaseValue = $diseasesAssoc[$topDiseaseName];
        
        $totalCases = array_sum($diseasesAssoc);
        
        $analysis = "Secara akumulatif dari 5 kategori penyakit menular utama, tercatat <b>" . number_format($totalCases, 0, ',', '.') . "</b> kasus di Banjarnegara. ";
        if ($topDiseaseValue > 0) {
            $analysis .= "Penyakit dengan jumlah kasus tertinggi adalah <b>" . $topDiseaseName . "</b> sebanyak " . number_format($topDiseaseValue, 0, ',', '.') . " kejadian. ";
        }
        $analysis .= "Grafik di bawah ini mengilustrasikan perbandingan frekuensi kejadian antar penyakit.";

        return view('penyakit.index', compact('data', 'labels', 'dataset', 'analysis'));
    }
}
