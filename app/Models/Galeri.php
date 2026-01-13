<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $table = 'galeris';

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function fotos()
    {
        return $this->hasMany(GaleriFoto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
