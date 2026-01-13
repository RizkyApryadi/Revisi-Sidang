<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Katekisasi extends Model
{
    protected $table = 'katekisasis';

    protected $fillable = [
        'periode_ajaran',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_pendaftaran_tutup',
        'pendeta_id',
        'deskripsi',
    ];

    public function pendeta(): BelongsTo
    {
        return $this->belongsTo(Pendeta::class);
    }

    public function pendaftaranSidis(): HasMany
    {
        return $this->hasMany(PendaftaranSidi::class, 'katekisasi_id');
    }
}
