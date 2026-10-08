<?php

namespace Database\Seeders;

use App\Models\Mapel;
use Illuminate\Database\Seeder;

class MapelSeeder extends Seeder
{
    public function run(): void
    {
        $mapels = [
            ['kode' => 'A', 'nama' => 'Pendidikan Agama & BP', 'is_mapel_berat' => false],
            ['kode' => 'B', 'nama' => 'PPKn', 'is_mapel_berat' => false],
            ['kode' => 'C', 'nama' => 'Bahasa Indonesia', 'is_mapel_berat' => false],
            ['kode' => 'D', 'nama' => 'Bahasa Inggris', 'is_mapel_berat' => false],
            ['kode' => 'E', 'nama' => 'Matematika', 'is_mapel_berat' => true],
            ['kode' => 'F', 'nama' => 'Seni Budaya', 'is_mapel_berat' => false],
            ['kode' => 'G', 'nama' => 'Sejarah', 'is_mapel_berat' => false],
            ['kode' => 'H', 'nama' => 'PenjasOrkes', 'is_mapel_berat' => false],
            ['kode' => 'I', 'nama' => 'Fisika', 'is_mapel_berat' => true],
            ['kode' => 'J', 'nama' => 'Kimia', 'is_mapel_berat' => true],
            ['kode' => 'K', 'nama' => 'Biologi', 'is_mapel_berat' => true],
            ['kode' => 'L', 'nama' => 'Ekonomi', 'is_mapel_berat' => false],
            ['kode' => 'M', 'nama' => 'Sosiologi', 'is_mapel_berat' => false],
            ['kode' => 'N', 'nama' => 'Geografi', 'is_mapel_berat' => false],
            ['kode' => 'O', 'nama' => 'PKWU', 'is_mapel_berat' => false],
            ['kode' => 'P', 'nama' => 'Informatika', 'is_mapel_berat' => false],
            ['kode' => 'Q', 'nama' => 'Mulok', 'is_mapel_berat' => false],
            ['kode' => 'R', 'nama' => 'MTK Tingkat Lanjut', 'is_mapel_berat' => true],
            ['kode' => 'S', 'nama' => 'Sejarah TL', 'is_mapel_berat' => false],
        ];

        foreach ($mapels as $mapel) {
            Mapel::updateOrCreate(['kode' => $mapel['kode']], $mapel);
        }

        $this->command?->info('19 Mata Pelajaran berhasil di-seed.');
    }
}
