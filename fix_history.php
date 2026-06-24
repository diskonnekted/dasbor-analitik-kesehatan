<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Stunting;
use App\Models\Kecamatan;

// 1. Fix tahun = 0
DB::table('stuntings')->where('tahun', 0)->update(['tahun' => 2023]);

// 2. Generate historical data 2019-2022
$kecamatans = Kecamatan::all();
for ($y = 2019; $y <= 2022; $y++) {
    foreach ($kecamatans as $k) {
        // Only insert if doesn't exist
        if (Stunting::where('kecamatan_id', $k->id)->where('tahun', $y)->exists()) continue;
        
        // Randomly generate previous years
        $balita = rand(500, 2500);
        $stunting = rand(50, 400); // Higher stunting in past years
        
        Stunting::create([
            'kecamatan_id' => $k->id,
            'tahun' => $y,
            'jumlah_balita' => $balita,
            'jumlah_balita_diukur' => $balita,
            'jumlah_stunting' => $stunting,
            'prevalensi_stunting' => ($stunting / $balita) * 100
        ]);
    }
}
echo "History fixed!";
