<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = new App\Services\OpenDataBanjarnegaraService();
$csv = Http::get("https://opendata.banjarnegarakab.go.id/dataset/883b828f-7c96-4e4c-a2d6-edf3c01f119b/resource/3e146372-8765-450a-8122-9def72c4f581/download/4.7-jumlah-balita-stunting-per-desa-di-kecamatan-wanayasa.csv")->body();
$data = $s->parseCSV($csv);

$total_balita = 0;
$total_stunting = 0;
foreach($data as $row) {
    $total_balita += (int) ($row['Jumlah Seluruh Balita'] ?? 0);
    $total_stunting += (int) ($row['Jumlah Balita Stunting'] ?? 0);
}
echo "Balita: $total_balita, Stunting: $total_stunting\n";
