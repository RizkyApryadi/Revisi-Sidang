<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataMempelai extends Model
{
    protected $table = 'data_mempelais';

    protected $fillable = [
        'pendaftaran_pernikahans_id', 'nama', 'no_hp', 'email', 'tempat_lahir',
        'tanggal_lahir', 'tanggal_baptis', 'tanggal_sidi', 'nama_ayah', 'nama_ibu',
        'asal_gereja', 'wijk', 'alamat'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranPernikahan::class, 'pendaftaran_pernikahans_id');
    }
}
