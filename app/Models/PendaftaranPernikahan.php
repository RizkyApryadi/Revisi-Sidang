<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranPernikahan extends Model
{
    protected $table = 'pendaftaran_pernikahans';

    protected $fillable = [
        'tanggal_perjanjian', 'jam_perjanjian', 'keterangan_perjanjian',
        'tanggal_pemberkatan', 'jam_pemberkatan', 'keterangan_pemberkatan',
        'surat_keterangan_gereja_asal', 'surat_baptis_pria', 'surat_baptis_wanita',
        'surat_sidi_pria', 'surat_sidi_wanita', 'foto', 'surat_pengantar'
    ];

    public function mempelais()
    {
        return $this->hasMany(DataMempelai::class, 'pendaftaran_pernikahans_id');
    }
}
