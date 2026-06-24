<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\DashboardController;

// Change Map data to Stunting
$file = "I:\kesehatan\aplikasi\app\Http\Controllers\DashboardController.php";
$content = file_get_contents($file);

$oldMapLogic = "        // Map: Faskes Markers
        $tahunFaskes = Faskes::max('tahun') ?? date('Y');
        $faskesMarkers = Faskes::where('status', 'Aktif')
            ->where('tahun', $tahunFaskes)
            ->whereNotNull('faskes.latitude')
            ->whereNotNull('faskes.longitude')
            ->join('kecamatans', 'faskes.kecamatan_id', '=', 'kecamatans.id')
            ->select('faskes.nama', 'faskes.jenis', 'faskes.alamat', 'faskes.latitude', 'faskes.longitude', 'faskes.puskesmas', 'faskes.rs_umum', 'faskes.klinik', 'kecamatans.nama as kecamatan_nama')
            ->get()
            ->map(function ($f) {
                return [
                    'nama' => $f->nama ?: 'Rekap Faskes Kec. ' . $f->kecamatan_nama,
                    'jenis' => $f->jenis ?: 'Puskesmas: ' . $f->puskesmas . ' | RS: ' . $f->rs_umum . ' | Klinik: ' . $f->klinik,
                    'alamat' => $f->alamat ?: 'Kecamatan ' . $f->kecamatan_nama,
                    'latitude' => $f->latitude,
                    'longitude' => $f->longitude
                ];
            })
            ->toArray();";

$newMapLogic = "        // Map: Stunting Spasial Data
        $stuntingMapData = Stunting::where('tahun', $tahunStunting)
            ->join('kecamatans', 'stuntings.kecamatan_id', '=', 'kecamatans.id')
            ->whereNotNull('kecamatans.latitude')
            ->whereNotNull('kecamatans.longitude')
            ->select('kecamatans.nama', 'kecamatans.latitude', 'kecamatans.longitude',
                DB::raw('SUM(stuntings.jumlah_stunting) * 100.0 / NULLIF(SUM(stuntings.jumlah_balita_diukur), 0) as prevalensi')
            )
            ->groupBy('kecamatans.id', 'kecamatans.nama', 'kecamatans.latitude', 'kecamatans.longitude')
            ->get()
            ->toArray();";

$content = str_replace($oldMapLogic, $newMapLogic, $content);
$content = str_replace("'faskesMarkers',", "'stuntingMapData',", $content);

file_put_contents($file, $content);
echo "DashboardController updated!";
