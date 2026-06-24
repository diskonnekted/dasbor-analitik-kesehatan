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
            
        return view('faskes.index', compact('data'));
    }
}
