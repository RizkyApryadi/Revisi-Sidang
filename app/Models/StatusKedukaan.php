<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusKedukaan extends Model
{
    protected $table = 'status_kedukaans';

    protected $fillable = [
        'jemaat_id',
        'status',
        'tanggal',
        'nomor_surat',
        'keterangan',
    ];

    public function jemaat()
    {
        return $this->belongsTo(Jemaat::class, 'jemaat_id');
    }
}
