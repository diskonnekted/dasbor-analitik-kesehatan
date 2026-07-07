<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaskesController extends Controller
{
    public function index()
    {
        $data = DB::table('faskes')
            ->leftJoin('kecamatans', 'faskes.kecamatan_id', '=', 'kecamatans.id')
            ->select('faskes.*', 'kecamatans.nama as kecamatan_nama')
            ->paginate(15);
            
        // Chart Data (Sum by Kecamatan)
        $chartData = DB::table('faskes')
            ->leftJoin('kecamatans', 'faskes.kecamatan_id', '=', 'kecamatans.id')
            ->selectRaw('kecamatans.nama as kecamatan_nama, SUM(rs_umum) as rs, SUM(puskesmas) as puskesmas, SUM(klinik) as klinik, SUM(poskesdes) as poskesdes, SUM(rs_umum + puskesmas + klinik + poskesdes) as total')
            ->groupBy('kecamatans.nama')
            ->orderByDesc('total')
            ->get();
            
        $labels = $chartData->pluck('kecamatan_nama');
        $rsData = $chartData->pluck('rs');
        $puskesmasData = $chartData->pluck('puskesmas');
        $klinikData = $chartData->pluck('klinik');
        
        // Basic Analysis
        $totalFaskes = $chartData->sum('total');
        $highestKecamatan = $chartData->first();
        
        $analysis = "Secara keseluruhan, terdapat <b>" . number_format($totalFaskes, 0, ',', '.') . "</b> unit fasilitas kesehatan (di luar Posyandu). ";
        if ($highestKecamatan) {
            $analysis .= "Kecamatan dengan ketersediaan infrastruktur kesehatan tertinggi adalah <b>" . $highestKecamatan->kecamatan_nama . "</b> (" . number_format($highestKecamatan->total, 0, ',', '.') . " unit). ";
        }
        $analysis .= "Grafik di bawah ini merinci komposisi jenis fasilitas kesehatan di setiap kecamatan.";

        return view('faskes.index', compact('data', 'labels', 'rsData', 'puskesmasData', 'klinikData', 'analysis'));
    }
}
