<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendeta extends Model
{
    protected $table = 'pendetas';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'foto',
    ];

    /**
     * The user account related to this Pendeta (1:1).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
