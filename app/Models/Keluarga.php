<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wijk;

class Keluarga extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_registrasi',
        'tanggal_registrasi',
        'alamat',
        'wijk_id',
        'tanggal_pernikahan',
        'gereja_pemberkatan',
        'pendeta_pemberkatan',
        'akte_pernikahan',
    ];

    public function jemaats()
    {
        return $this->hasMany(Jemaat::class);
    }

    public function wijk()
    {
        return $this->belongsTo(Wijk::class, 'wijk_id');
    }
}
