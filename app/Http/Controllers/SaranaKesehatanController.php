<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaranaKesehatan;
use App\Models\Kecamatan;

class SaranaKesehatanController extends Controller
{
    public function index()
    {
        $data = SaranaKesehatan::with('kecamatan')->orderBy('tahun', 'desc')->paginate(15);
        
        // Data for choropleth mapping (Kecamatan aggregates)
        $mapData = Kecamatan::with(['saranaKesehatans' => function($query) {
            $query->orderBy('tahun', 'desc');
        }])->get()->map(function($kec) {
            $latest = $kec->saranaKesehatans->first();
            return [
                'id' => $kec->id,
                'nama' => $kec->nama,
                'apotek' => $latest ? $latest->apotek : 0,
                'toko_obat' => $latest ? $latest->toko_obat : 0,
                'puskesmas_pembantu' => $latest ? $latest->puskesmas_pembantu : 0,
                'puskesmas_keliling' => $latest ? $latest->puskesmas_keliling : 0,
                'laborat' => $latest ? $latest->laborat : 0,
            ];
        });
        
        return view('sarana-kesehatan.index', compact('data', 'mapData'));
    }
}
