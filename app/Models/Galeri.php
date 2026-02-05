<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeris';

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'tanggal',
    ];

    public function fotos()
    {
        return $this->hasMany(GaleriFoto::class, 'galeri_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
