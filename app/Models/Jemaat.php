<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Keluarga;
use App\Models\StatusBaptis;
use App\Models\StatusSidi;
use App\Models\StatusPernikahan;
use App\Models\StatusPindah;
use App\Models\StatusKedukaan;

class Jemaat extends Model
{
    protected $table = 'jemaats';

    protected static function booted()
    {
        static::creating(function ($jemaat) {
            // If status not provided, set default based on authenticated user's role
            if (empty($jemaat->status)) {
                $user = Auth::user();
                if ($user && property_exists($user, 'role') && $user->role === 'admin') {
                    $jemaat->status = 'approved';
                } else {
                    $jemaat->status = 'pending';
                }
            }
        });
    }

    protected $fillable = [
        'nomor_jemaat',
        'status',
        'nama_lengkap',
        'keluarga_id',
        'email',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_hp',
        'nama_ayah',
        'nama_ibu',
        'status_pernikahan',
        'hubungan_keluarga',
        'anak_ke',
        'keterangan',
        'foto',
        'pendaftaran_sidi_id',
    ];

    /**
     * Default attribute values.
     * Ensure pendaftaran_sidi_id is null when not provided.
     */
    protected $attributes = [
        'pendaftaran_sidi_id' => null,
    ];

    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class);
    }

    public function statusBaptis()
    {
        return $this->hasOne(StatusBaptis::class, 'jemaat_id');
    }

    public function statusSidi()
    {
        return $this->hasOne(StatusSidi::class, 'jemaat_id');
    }

    public function statusPernikahan()
    {
        return $this->hasOne(StatusPernikahan::class, 'jemaat_id');
    }

    public function statusPindah()
    {
        return $this->hasOne(StatusPindah::class, 'jemaat_id');
    }

    public function statusKedukaan()
    {
        return $this->hasOne(StatusKedukaan::class, 'jemaat_id');
    }

    /**
     * The pendaftaran this jemaat belongs to (many jemaats per pendaftaran).
     */
    public function pendaftaranSidi()
    {
        return $this->belongsTo(\App\Models\PendaftaranSidi::class, 'pendaftaran_sidi_id');
    }
}
