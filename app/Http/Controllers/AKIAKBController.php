<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AKIAKBController extends Controller
{
    public function index()
    {
        $data = DB::table('akis')
            ->leftJoin('kecamatans', 'akis.kecamatan_id', '=', 'kecamatans.id')
            ->leftJoin('akbs', function($join) {
                $join->on('akis.puskesmas', '=', 'akbs.puskesmas')
                     ->on('akis.tahun', '=', 'akbs.tahun');
            })
            ->select('akis.*', 'kecamatans.nama as kecamatan_nama', 'akbs.jumlah_kematian_bayi')
            ->paginate(15);
            
        // Chart Data (Sum by Puskesmas)
        $chartData = DB::table('akis')
            ->leftJoin('akbs', function($join) {
                $join->on('akis.puskesmas', '=', 'akbs.puskesmas')
                     ->on('akis.tahun', '=', 'akbs.tahun');
            })
            ->selectRaw('akis.puskesmas, SUM(akis.jumlah_kematian_ibu) as kematian_ibu, SUM(akbs.jumlah_kematian_bayi) as kematian_bayi, SUM(akis.jumlah_kematian_ibu + COALESCE(akbs.jumlah_kematian_bayi, 0)) as total')
            ->groupBy('akis.puskesmas')
            ->orderByDesc('total')
            ->get();
            
        $labels = $chartData->pluck('puskesmas');
        $akiData = $chartData->pluck('kematian_ibu');
        $akbData = $chartData->pluck('kematian_bayi');
        
        // Basic Analysis
        $totalIbu = $chartData->sum('kematian_ibu');
        $totalBayi = $chartData->sum('kematian_bayi');
        $totalKematian = $totalIbu + $totalBayi;
        
        $highestPuskesmas = $chartData->first();
        
        $analysis = "Secara keseluruhan, tercatat <b>" . number_format($totalKematian, 0, ',', '.') . "</b> kasus kematian ibu dan bayi (AKI & AKB) di Banjarnegara, dengan rincian " . number_format($totalIbu, 0, ',', '.') . " ibu dan " . number_format($totalBayi, 0, ',', '.') . " bayi. ";
        if ($highestPuskesmas && $highestPuskesmas->total > 0) {
            $analysis .= "Puskesmas dengan laporan kasus kematian tertinggi adalah <b>" . $highestPuskesmas->puskesmas . "</b> (" . number_format($highestPuskesmas->total, 0, ',', '.') . " kasus). ";
        }
        $analysis .= "Grafik di bawah ini merinci perbandingan AKI dan AKB pada tingkat Puskesmas.";

        return view('aki-akb.index', compact('data', 'labels', 'akiData', 'akbData', 'analysis'));
    }
}
