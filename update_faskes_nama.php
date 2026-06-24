<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Faskes;
use App\Models\Kecamatan;

$faskes = Faskes::all();
foreach($faskes as $f) {
    if ($f->nama == null) {
        $kec = Kecamatan::find($f->kecamatan_id);
        if ($kec) {
            $f->update([
                'nama' => 'Faskes Kec. ' . $kec->nama,
                'jenis' => 'Puskesmas: ' . $f->puskesmas . ', RS: ' . $f->rs_umum . ', Klinik: ' . $f->klinik,
                'alamat' => 'Total Posyandu: ' . $f->posyandu
            ]);
        }
    }
}
echo "Faskes data updated!";
