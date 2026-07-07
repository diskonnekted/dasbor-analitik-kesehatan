<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StuntingController extends Controller
{
    public function index()
    {
        $data = DB::table('stuntings')
            ->leftJoin('kecamatans', 'stuntings.kecamatan_id', '=', 'kecamatans.id')
            ->select('stuntings.*', 'kecamatans.nama as kecamatan_nama')
            ->paginate(15);
            
        // Chart Data (Sum by Kecamatan)
        $chartData = DB::table('stuntings')
            ->leftJoin('kecamatans', 'stuntings.kecamatan_id', '=', 'kecamatans.id')
            ->selectRaw('kecamatans.nama as kecamatan_nama, SUM(jumlah_stunting) as stunting, SUM(jumlah_gizi_buruk) as gizi_buruk, SUM(jumlah_stunting + jumlah_gizi_buruk) as total')
            ->groupBy('kecamatans.nama')
            ->orderByDesc('total')
            ->get();
            
        $labels = $chartData->pluck('kecamatan_nama');
        $stuntingData = $chartData->pluck('stunting');
        $giziBurukData = $chartData->pluck('gizi_buruk');
        
        // Basic Analysis
        $totalStunting = $chartData->sum('stunting');
        $totalGiziBuruk = $chartData->sum('gizi_buruk');
        
        $highestKecamatan = $chartData->first();
        
        $analysis = "Secara keseluruhan, terdapat <b>" . number_format($totalStunting, 0, ',', '.') . "</b> anak penderita Stunting dan <b>" . number_format($totalGiziBuruk, 0, ',', '.') . "</b> anak dengan Gizi Buruk di Banjarnegara. ";
        if ($highestKecamatan && $highestKecamatan->total > 0) {
            $analysis .= "Kecamatan dengan akumulasi kasus tertinggi adalah <b>" . $highestKecamatan->kecamatan_nama . "</b> (" . number_format($highestKecamatan->total, 0, ',', '.') . " anak). ";
        }
        $analysis .= "Grafik di bawah ini memetakan sebaran Stunting dan Gizi Buruk di setiap kecamatan untuk prioritas intervensi.";

        return view('stunting.index', compact('data', 'labels', 'stuntingData', 'giziBurukData', 'analysis'));
    }
}
