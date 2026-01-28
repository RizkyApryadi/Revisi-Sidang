<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penatua extends Model
{
    use HasFactory;

    protected $table = 'penatuas';

    protected $fillable = [
        'user_id',
        'jemaat_id',
        'tanggal_tahbis',
        'status',
        'keterangan',
        'wijk_id',
    ];

    public function jemaat()
    {
        return $this->belongsTo(Jemaat::class);
    }

    public function wijk()
    {
        return $this->belongsTo(Wijk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
