<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Faskes;
print_r(Faskes::select('kecamatan_id', 'tahun')->orderBy('kecamatan_id')->orderBy('tahun')->get()->toArray());
