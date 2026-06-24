<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasusPenyakit extends Model
{
    protected $guarded = [];

    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class);
    }
}
