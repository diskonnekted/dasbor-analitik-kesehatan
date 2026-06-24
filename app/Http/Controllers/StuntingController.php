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
            
        return view('stunting.index', compact('data'));
    }
}
