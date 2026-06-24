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
            
        return view('imunisasi.index', compact('data'));
    }
}
