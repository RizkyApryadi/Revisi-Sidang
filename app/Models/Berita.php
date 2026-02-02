<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'beritas';

    protected $fillable = [
        'user_id',
        'tanggal',
        'judul',
        'ringkasan',
        'file',
        'foto',
    ];
}
