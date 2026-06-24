<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posyandu extends Model
{
    protected $fillable = [
        'kecamatan_id',
        'tahun',
        'puskesmas',
        'jumlah_posyandu',
        'jumlah_kader',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
