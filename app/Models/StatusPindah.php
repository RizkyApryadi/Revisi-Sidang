<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPindah extends Model
{
    protected $table = 'status_pindahs';

    protected $fillable = [
        'jemaat_id',
        'status',
        'nomor_surat',
        'tanggal',
        'nama_gereja',
        'kota',
    ];

    public function jemaat()
    {
        return $this->belongsTo(Jemaat::class, 'jemaat_id');
    }
}
