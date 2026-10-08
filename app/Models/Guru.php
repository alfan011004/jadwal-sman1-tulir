<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = ['kode', 'nama', 'nip', 'is_bk', 'jabatan_tambahan'];

    protected $casts = ['is_bk' => 'boolean'];

    public function bebanMengajars()
    {
        return $this->hasMany(BebanMengajar::class);
    }

    public function scopePengajar($query)
    {
        return $query->where('is_bk', false);
    }
}