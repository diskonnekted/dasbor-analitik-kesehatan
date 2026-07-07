<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImunisasiController extends Controller
{
    public function index()
    {
        $data = DB::table('imunisasis')
            ->leftJoin('kecamatans', 'imunisasis.kecamatan_id', '=', 'kecamatans.id')
            ->select('imunisasis.*', 'kecamatans.nama as kecamatan_nama')
            ->paginate(15);
            
        // Chart Data (Sum by Kecamatan)
        $chartData = DB::table('imunisasis')
            ->leftJoin('kecamatans', 'imunisasis.kecamatan_id', '=', 'kecamatans.id')
            ->selectRaw('kecamatans.nama as kecamatan_nama, SUM(target_bayi) as target, SUM(ROUND(target_bayi * (persentase_dasar_lengkap / 100))) as realisasi')
            ->groupBy('kecamatans.nama')
            ->orderByDesc('realisasi')
            ->get();
            
        $labels = $chartData->pluck('kecamatan_nama');
        $targetData = $chartData->pluck('target');
        $realisasiData = $chartData->pluck('realisasi');
        
        // Basic Analysis
        $totalTarget = $chartData->sum('target');
        $totalRealisasi = $chartData->sum('realisasi');
        $persentaseOverall = $totalTarget > 0 ? round(($totalRealisasi / $totalTarget) * 100, 1) : 0;
        
        $highestKecamatan = $chartData->first();
        
        $analysis = "Secara kumulatif, realisasi Cakupan Imunisasi Dasar Lengkap (IDL) Kabupaten Banjarnegara mencapai <b>" . $persentaseOverall . "%</b> dari total target " . number_format($totalTarget, 0, ',', '.') . " bayi. ";
        if ($highestKecamatan && $highestKecamatan->target > 0) {
            $persentaseHighest = round(($highestKecamatan->realisasi / $highestKecamatan->target) * 100, 1);
            $analysis .= "Kecamatan <b>" . $highestKecamatan->kecamatan_nama . "</b> memimpin dengan jumlah bayi terimunisasi terbanyak (" . number_format($highestKecamatan->realisasi, 0, ',', '.') . " bayi atau " . $persentaseHighest . "% dari target lokal). ";
        }
        $analysis .= "Grafik di bawah ini membandingkan rasio realisasi terhadap target sasaran imunisasi di masing-masing kecamatan.";

        return view('imunisasi.index', compact('data', 'labels', 'targetData', 'realisasiData', 'analysis'));
    }
}
