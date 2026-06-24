<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kecamatan;
use App\Models\Stunting;
use App\Models\Imunisasi;

$kecamatans = Kecamatan::all();
$tahun = 2024;

foreach ($kecamatans as $k) {
    // Generate dummy Stunting
    Stunting::updateOrCreate(
        ['kecamatan_id' => $k->id, 'tahun' => $tahun],
        [
            'jumlah_balita' => rand(500, 2000),
            'jumlah_balita_diukur' => rand(400, 1900),
            'jumlah_stunting' => rand(10, 150),
            'jumlah_gizi_buruk' => rand(5, 50),
            'jumlah_gizi_kurang' => rand(20, 100),
            'jumlah_gizi_lebih' => rand(5, 30),
            'prevalensi_stunting' => rand(5, 25) + (rand(0, 99) / 100),
        ]
    );
    
    // Generate dummy Imunisasi
    Imunisasi::updateOrCreate(
        ['kecamatan_id' => $k->id, 'tahun' => $tahun],
        [
            'target_bayi' => rand(100, 500),
            'bcg' => rand(90, 480),
            'polio4' => rand(85, 450),
            'campak_mr1' => rand(80, 420),
            'persentase_dasar_lengkap' => rand(70, 99) + (rand(0, 99) / 100),
        ]
    );
}

echo "Dummy data generated for Stunting & Imunisasi!";
