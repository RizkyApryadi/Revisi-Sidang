<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renungan extends Model
{
    use HasFactory;

    protected $table = 'renungans';

    protected $fillable = [
        'judul',
        'tanggal',
        'konten',
        'status',
        'pendeta_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pendeta()
    {
        return $this->belongsTo(Pendeta::class);
    }
}
