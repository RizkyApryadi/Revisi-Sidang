<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Wijk;

class Penatua extends Model
{
    protected $table = 'penatuas';

    protected $fillable = [
        'user_id',
        'wijk_id',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'foto',
    ];

    /**
     * The user account related to this Penatua (1:1).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The wijk this Penatua is assigned to.
     */
    public function wijk(): BelongsTo
    {
        return $this->belongsTo(Wijk::class);
    }
}
