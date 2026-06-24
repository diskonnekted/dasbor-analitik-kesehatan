<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
$data = DB::table('stuntings')
    ->select('tahun', DB::raw('SUM(jumlah_stunting) as total_stunting'), DB::raw('SUM(jumlah_balita_diukur) as total_balita'))
    ->groupBy('tahun')
    ->orderBy('tahun')
    ->get();
print_r($data);
