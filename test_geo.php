<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kecamatan;
$kec = Kecamatan::all(['nama', 'latitude', 'longitude']);
print_r($kec->toArray());
