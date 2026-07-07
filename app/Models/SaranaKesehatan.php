<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaranaKesehatan extends Model
{
    protected $fillable = [
        'kecamatan_id',
        'tahun',
        'puskesmas_pembantu',
        'puskesmas_keliling',
        'toko_obat',
        'laborat',
        'apotek'
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
