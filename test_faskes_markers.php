<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\DashboardController;

$ctrl = new DashboardController();
$data = $ctrl->index()->getData();
print_r($data['faskesMarkers'][0]);
