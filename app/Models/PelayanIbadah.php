<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelayanIbadah extends Model
{
    protected $table = 'pelayan_ibadahs';

    protected $fillable = [
        'ibadah_id',
        'jenis_pelayanan',
        'petugas',
    ];

    public function ibadah()
    {
        return $this->belongsTo(Ibadah::class);
    }
}
