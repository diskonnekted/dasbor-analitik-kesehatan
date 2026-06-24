<?php
require 'vendor/autoload.php';
\ = require_once 'bootstrap/app.php';
\ = \->make(Illuminate\Contracts\Console\Kernel::class);
\->bootstrap();

\ = new App\Services\OpenDataBanjarnegaraService();
\ = \->downloadDataset('tenaga_kesehatan');
\ = \->parseCSV(\);
print_r(array_keys(\[0]));

