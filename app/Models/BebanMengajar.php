<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BebanMengajar extends Model
{
    protected $fillable = ['guru_id', 'mapel_id', 'kelas_id', 'jumlah_jp'];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}