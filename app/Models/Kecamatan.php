<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $guarded = [];

    public function posyandus()
    {
        return $this->hasMany(Posyandu::class);
    }

    public function saranaKesehatans()
    {
        return $this->hasMany(SaranaKesehatan::class);
    }
}
