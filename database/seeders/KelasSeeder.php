<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            ['nama' => 'X.1', 'tingkat' => 'X'],
            ['nama' => 'X.2', 'tingkat' => 'X'],
            ['nama' => 'X.3', 'tingkat' => 'X'],
            ['nama' => 'X.4', 'tingkat' => 'X'],
            ['nama' => 'X.5', 'tingkat' => 'X'],
            ['nama' => 'XI.1', 'tingkat' => 'XI'],
            ['nama' => 'XI.2', 'tingkat' => 'XI'],
            ['nama' => 'XI.3', 'tingkat' => 'XI'],
            ['nama' => 'XI.4', 'tingkat' => 'XI'],
            ['nama' => 'XII.1', 'tingkat' => 'XII'],
            ['nama' => 'XII.2', 'tingkat' => 'XII'],
            ['nama' => 'XII.3', 'tingkat' => 'XII'],
            ['nama' => 'XII.4', 'tingkat' => 'XII'],
        ];

        foreach ($kelas as $item) {
            Kelas::updateOrCreate(['nama' => $item['nama']], $item);
        }

        $this->command?->info(count($kelas).' Kelas berhasil di-seed.');
    }
}
