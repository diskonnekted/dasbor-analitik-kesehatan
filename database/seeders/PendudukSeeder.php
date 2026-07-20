<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;

class PendudukSeeder extends Seeder
{
    /**
     * Data jumlah penduduk per kecamatan (data kependudukan mutakhir Banjarnegara).
     * Total ~1.078.166 jiwa untuk 20 kecamatan.
     */
    public function run(): void
    {
        $penduduk = [
            'Punggelan' => 93495,
            'Bawang' => 69967,
            'Banjarnegara' => 70618,
            'Purwanegara' => 68324,
            'Mandiraja' => 68618,
            'Wanayasa' => 57518,
            'Rakit' => 57253,
            'Susukan' => 53642,
            'Kalibening' => 52888,
            'Pejawaran' => 51520,
            'Batur' => 51050,
            'Banjarmangu' => 49336,
            'Madukara' => 47343,
            'Pagentan' => 46068,
            'Pagedongan' => 45310,
            'Wanadadi' => 37042,
            'Karangkobar' => 35816,
            'Purwareja Klampok' => 33511,
            'Sigaluh' => 32868,
            'Pandanarum' => 23867,
        ];

        foreach ($penduduk as $nama => $jumlah) {
            // Hanya update kecamatan kanonik (nama huruf besar yang benar)
            Kecamatan::where('nama', $nama)->update(['jumlah_penduduk' => $jumlah]);
        }
    }
}
