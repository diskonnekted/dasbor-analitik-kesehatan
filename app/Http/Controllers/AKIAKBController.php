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
            ->select('akis.*', 'kecamatans.nama as kecamatan_nama')
            ->paginate(15);
            
        return view('aki-akb.index', compact('data'));
    }
}
