<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranSidi extends Model
{
    protected $table = 'pendaftaran_sidis';

    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'no_hp',
        'email',
        'nama_ayah',
        'nama_ibu',
        'alamat',
        'wijk',
        'foto_4x6',
        'foto_3x4',
        'surat_baptis',
        'kartu_keluarga',
        'surat_pengantar_sintua',
        'status_pengajuan',
        'catatan_admin',
        'jenis_pendaftar',
        'jemaat_id',
        'katekisasi_id',
        // jemaat_id removed; relation is one PendaftaranSidi -> many Jemaats
    ];

    /**
     * All jemaats attached to this pendaftaran (one-to-many).
     */
    public function jemaats()
    {
        return $this->hasMany(\App\Models\Jemaat::class, 'pendaftaran_sidi_id');
    }
}
