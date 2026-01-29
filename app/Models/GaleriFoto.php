<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleriFoto extends Model
{
    use HasFactory;

    protected $table = 'galeri_fotos';

    protected $fillable = [
        'galeri_id',
        'foto',
    ];

    public function galeri()
    {
        return $this->belongsTo(Galeri::class, 'galeri_id');
    }
}
