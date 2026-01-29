<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendeta extends Model
{
    use HasFactory;

    protected $table = 'pendetas';

    protected $fillable = [
        'user_id',
        'jemaat_id',
        'tanggal_tahbis',
        'status',
        'no_sk_tahbis',
        'keterangan',
    ];

    public function jemaat()
    {
        return $this->belongsTo(Jemaat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
