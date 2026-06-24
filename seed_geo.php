<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kecamatan;

$coords = [
    'Susukan' => [-7.4784, 109.4312],
    'Purwareja Klampok' => [-7.4642, 109.4344],
    'Mandiraja' => [-7.4678, 109.4972],
    'Purwanegara' => [-7.4639, 109.5678],
    'Bawang' => [-7.4121, 109.6200],
    'Banjarnegara' => [-7.3941, 109.6958],
    'Pagedongan' => [-7.4674, 109.6808],
    'Sigaluh' => [-7.4021, 109.7490],
    'Madukara' => [-7.3591, 109.7196],
    'Banjarmangu' => [-7.3400, 109.6917],
    'Wanadadi' => [-7.3751, 109.6384],
    'Rakit' => [-7.4150, 109.5398],
    'Punggelan' => [-7.3396, 109.5542],
    'Karangkobar' => [-7.2798, 109.7121],
    'Pagentan' => [-7.2842, 109.7570],
    'Pejawaran' => [-7.2343, 109.7788],
    'Batur' => [-7.2140, 109.8183],
    'Wanayasa' => [-7.2312, 109.7424],
    'Kalibening' => [-7.2393, 109.6377],
    'Pandanarum' => [-7.2104, 109.6875]
];

foreach ($coords as $nama => $c) {
    // We update exact matches and also lowercase variants because of previous imports
    Kecamatan::where('nama', 'like', "%$nama%")->update([
        'latitude' => $c[0],
        'longitude' => $c[1]
    ]);
}

echo "Kecamatan coordinates seeded!";
