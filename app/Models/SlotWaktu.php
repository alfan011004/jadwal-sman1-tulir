<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlotWaktu extends Model
{
    protected $fillable = ['hari', 'jam_ke', 'jam_mulai', 'jam_selesai', 'is_istirahat'];

    protected $casts = ['is_istirahat' => 'boolean'];

    public function scopePelajaran($query)
    {
        return $query->where('is_istirahat', false);
    }
}