<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Penatua;

class Wijk extends Model
{
    protected $table = 'wijks';

    protected $fillable = [
        'nama_wijk',
        'keterangan',
    ];

    public function penatuas(): HasMany
    {
        return $this->hasMany(Penatua::class);
    }
}
