<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wijk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_wijk',
        'keterangan',
        'user_id',
    ];
}
