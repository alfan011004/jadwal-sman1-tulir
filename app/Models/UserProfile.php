<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'nama',
        'jabatan',
        'foto_path',
        'foto_kepsek',
    ];

    public static function ambil(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'nama'    => 'Administrator',
                'jabatan' => 'Operator Sistem',
            ]
        );
    }

    public function fotoUrl(): ?string
    {
        if (!$this->foto_path) return null;
        $path = public_path($this->foto_path);
        if (!file_exists($path)) return null;
        return asset($this->foto_path);
    }

    public function fotoKepsekUrl(): ?string
    {
        if (!$this->foto_kepsek) return null;
        $path = public_path($this->foto_kepsek);
        if (!file_exists($path)) return null;
        return asset($this->foto_kepsek);
    }
}