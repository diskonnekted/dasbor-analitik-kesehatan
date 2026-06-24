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
            
        return view('tenaga-kesehatan.index', compact('data'));
    }
}
