<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPernikahan extends Model
{
    protected $table = 'status_pernikahans';

    protected $fillable = [
        'jemaat_id',
        'status',
        'jenis',
        'nomor_surat',
        'tanggal',
        'pendeta',
        'nama_gereja',
        'alamat',
        'kota',
    ];

    public function jemaat()
    {
        return $this->belongsTo(Jemaat::class, 'jemaat_id');
    }
}
