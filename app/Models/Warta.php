<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warta extends Model
{
    protected $table = 'wartas';

    protected $fillable = [
        'user_id',
        'tanggal',
        'nama_minggu',
        'pengumuman',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ibadahs()
    {
        return $this->hasMany(Ibadah::class, 'warta_id');
    }
}
