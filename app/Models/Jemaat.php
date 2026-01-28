<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jemaat extends Model
{
    use HasFactory;

    protected $fillable = [
        'keluarga_id',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'no_telp',
        'hubungan_keluarga',
        'foto',
        'tanggal_sidi',
        'file_sidi',
        'tanggal_baptis',
        'file_baptis',
    ];

    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class);
    }
}
