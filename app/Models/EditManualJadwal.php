<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditManualJadwal extends Model
{
    protected $fillable = [
        'hasil_optimasi_id',
        'kelas_id',
        'slot_id',
        'kode_manual',
        'guru_id',
        'mapel_id',
    ];

    public function hasilOptimasi()
    {
        return $this->belongsTo(HasilOptimasi::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function slot()
    {
        return $this->belongsTo(SlotWaktu::class, 'slot_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
}