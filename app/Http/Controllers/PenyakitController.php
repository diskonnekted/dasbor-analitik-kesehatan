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
            
        return view('penyakit.index', compact('data'));
    }
}
