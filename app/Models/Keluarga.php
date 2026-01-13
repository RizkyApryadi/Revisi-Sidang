<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Keluarga extends Model
{
    protected $table = 'keluargas';

    protected $fillable = [
        'nomor_keluarga',
        'alamat',
        'rt',
        'rw',
        'wijk_id',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
    ];

    public function jemaats(): HasMany
    {
        return $this->hasMany(Jemaat::class);
    }
}
