<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posyandu;
use App\Models\Kecamatan;

class PosyanduController extends Controller
{
    public function index()
    {
        $data = Posyandu::with('kecamatan')->orderBy('tahun', 'desc')->paginate(15);
        
        // Data for mapping (Kecamatan coordinates + Aggregated Posyandu data)
        $mapData = Kecamatan::with(['posyandus' => function($query) {
            $query->orderBy('tahun', 'desc');
        }])->get()->map(function($kec) {
            $latest = $kec->posyandus->first();
            return [
                'id' => $kec->id,
                'nama' => $kec->nama,
                'latitude' => $kec->latitude,
                'longitude' => $kec->longitude,
                'jumlah_posyandu' => $latest ? $latest->jumlah_posyandu : 0,
                'jumlah_kader' => $latest ? $latest->jumlah_kader : 0,
            ];
        });
        
        return view('posyandu.index', compact('data', 'mapData'));
    }
}
