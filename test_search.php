<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = new App\Services\OpenDataBanjarnegaraService();
$url = "https://opendata.banjarnegarakab.go.id/api/3/action/package_search?q=" . urlencode("Jumlah balita stunting per Desa");
$response = Http::get($url);
$data = $response->json();
print_r(count($data['result']['results']));
