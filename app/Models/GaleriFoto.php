<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriFoto extends Model
{
    protected $table = 'galeri_fotos';

    protected $fillable = [
        'galeri_id',
        'foto',
    ];

    public function galeri()
    {
        return $this->belongsTo(Galeri::class);
    }
}
