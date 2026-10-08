<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilOptimasi extends Model
{
    protected $fillable = [
        'algoritma', 'parameter', 'fitness_terbaik', 'waktu_komputasi',
        'iterasi_konvergen', 'pelanggaran_hard', 'pelanggaran_soft',
        'jadwal', 'log_konvergensi',
    ];

    protected $casts = [
        'parameter'       => 'array',
        'jadwal'          => 'array',
        'log_konvergensi' => 'array',
    ];
}