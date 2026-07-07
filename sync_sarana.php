<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\OpenDataBanjarnegaraService;

$service = new OpenDataBanjarnegaraService();
$csvContent = $service->downloadDataset('sarana_kesehatan');
$data = $service->parseCSV($csvContent);

$reflection = new \ReflectionClass($service);
$method = $reflection->getMethod('importSaranaKesehatan');
$method->setAccessible(true);
$imported = $method->invokeArgs($service, [$data]);

echo "Berhasil mengimpor $imported rekaman data Sarana Kesehatan.\n";
