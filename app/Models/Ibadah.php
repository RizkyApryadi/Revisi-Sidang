<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ibadah extends Model
{
    protected $table = 'ibadahs';

    protected $fillable = [
        'warta_id',
        'pendeta_id',
        'waktu',
        'tema',
        'ayat',
        'file',
    ];

    public function warta()
    {
        return $this->belongsTo(Warta::class, 'warta_id');
    }

    public function pendeta()
    {
        return $this->belongsTo(Pendeta::class, 'pendeta_id');
    }
}
