<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AKI;

$akis = AKI::all();
foreach ($akis as $a) {
    if ($a->jumlah_kelahiran_hidup == 0) {
        $a->jumlah_kelahiran_hidup = rand(200, 900);
        $a->save();
    }
}
echo "Kelahiran hidup seeded!";
