<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenagaKesehatanController extends Controller
{
    public function index()
    {
        $data = DB::table('tenaga_kesehatans')
            ->leftJoin('kecamatans', 'tenaga_kesehatans.kecamatan_id', '=', 'kecamatans.id')
            ->select('tenaga_kesehatans.*', 'kecamatans.nama as kecamatan_nama')
            ->paginate(15);
            
        // Chart Data (Sum by Kecamatan)
        $chartData = DB::table('tenaga_kesehatans')
            ->leftJoin('kecamatans', 'tenaga_kesehatans.kecamatan_id', '=', 'kecamatans.id')
            ->selectRaw('kecamatans.nama as kecamatan_nama, SUM(dokter_umum) as dokter_umum, SUM(perawat) as perawat, SUM(bidan) as bidan, SUM(farmasi) as farmasi, SUM(dokter_umum + perawat + bidan + farmasi) as total')
            ->groupBy('kecamatans.nama')
            ->orderByDesc('total')
            ->get();
            
        $labels = $chartData->pluck('kecamatan_nama');
        $dokterData = $chartData->pluck('dokter_umum');
        $perawatData = $chartData->pluck('perawat');
        $bidanData = $chartData->pluck('bidan');
        
        // Basic Analysis
        $totalNakes = $chartData->sum('total');
        $highestKecamatan = $chartData->first();
        $lowestKecamatan = $chartData->last();
        $totalDokter = $chartData->sum('dokter_umum');
        $totalPerawat = $chartData->sum('perawat');
        
        $analysis = "Secara keseluruhan, terdapat <b>" . number_format($totalNakes, 0, ',', '.') . "</b> tenaga kesehatan di Banjarnegara. ";
        if ($highestKecamatan) {
            $analysis .= "Kecamatan dengan distribusi nakes terbanyak adalah <b>" . $highestKecamatan->kecamatan_nama . "</b> (" . number_format($highestKecamatan->total, 0, ',', '.') . " personel), ";
            $analysis .= "sedangkan yang paling minim adalah <b>" . $lowestKecamatan->kecamatan_nama . "</b> (" . number_format($lowestKecamatan->total, 0, ',', '.') . " personel). ";
        }
        $analysis .= "Mayoritas tenaga kesehatan didominasi oleh perawat sebanyak " . number_format($totalPerawat, 0, ',', '.') . " personel.";

        return view('tenaga-kesehatan.index', compact('data', 'labels', 'dokterData', 'perawatData', 'bidanData', 'analysis'));
    }
}
