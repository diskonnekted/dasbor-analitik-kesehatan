<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = new App\Services\OpenDataBanjarnegaraService();
$csv = $s->downloadDataset('faskes');
$data = $s->parseCSV($csv);
print_r(array_slice($data, 0, 5));
