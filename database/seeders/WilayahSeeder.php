<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kecamatan;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatans = [
            'Banjarmangu',
            'Banjarnegara',
            'Batur',
            'Bawang',
            'Kalibening',
            'Karangkobar',
            'Madukara',
            'Mandiraja',
            'Pagedongan',
            'Pagentan',
            'Pandanarum',
            'Pejawaran',
            'Punggelan',
            'Purwanegara',
            'Purworejo Klampok',
            'Rakit',
            'Sigaluh',
            'Susukan',
            'Wanadadi',
            'Wanayasa',
            'Susukan',
            'Klaten', // just in case
        ];

        foreach ($kecamatans as $i => $nama) {
            Kecamatan::firstOrCreate(
                ['nama' => $nama],
                [
                    'kode' => '33.04.' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                    'latitude' => -7.3888,
                    'longitude' => 109.6960,
                    'jumlah_penduduk' => 50000,
                    'jumlah_kk' => 15000,
                    'luas_wilayah' => 100,
                ]
            );
        }
    }
}
