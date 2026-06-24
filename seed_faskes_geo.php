<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Faskes;
use App\Models\Kecamatan;

$faskes = Faskes::all();
foreach ($faskes as $f) {
    $kec = Kecamatan::find($f->kecamatan_id);
    if ($kec && $kec->latitude && $kec->longitude) {
        // Add tiny random offset so they don't overlap exactly
        $latOffset = (rand(-50, 50) / 10000);
        $lngOffset = (rand(-50, 50) / 10000);
        
        $f->update([
            'latitude' => $kec->latitude + $latOffset,
            'longitude' => $kec->longitude + $lngOffset
        ]);
    }
}
echo "Faskes geo coordinates seeded!";
