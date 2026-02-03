<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
