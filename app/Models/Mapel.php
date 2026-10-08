<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $fillable = ['kode', 'nama', 'is_mapel_berat'];

    protected $casts = ['is_mapel_berat' => 'boolean'];

    public function bebanMengajars()
    {
        return $this->hasMany(BebanMengajar::class);
    }
}