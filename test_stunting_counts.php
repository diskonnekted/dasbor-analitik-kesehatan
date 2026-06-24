<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Stunting;
print_r(Stunting::select('tahun', \DB::raw('count(*) as total_kecamatan'))->groupBy('tahun')->get()->toArray());
