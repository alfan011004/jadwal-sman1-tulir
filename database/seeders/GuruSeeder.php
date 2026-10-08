<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $gurus = [
            ['kode' => '1', 'nama' => 'Dwi Maryanto, S.Pd.T., M.Pd', 'nip' => '198503112014031001', 'is_bk' => false, 'jabatan_tambahan' => 'Kepala Sekolah'],
            ['kode' => '2', 'nama' => 'Didik Nurkorolin, S.Pd', 'nip' => '198412092009021001', 'is_bk' => false, 'jabatan_tambahan' => 'Wakasek Kesiswaan'],
            ['kode' => '3', 'nama' => 'Ena Hastuty, S.T., M.T.', 'nip' => '197702232011012001', 'is_bk' => false, 'jabatan_tambahan' => 'Kepala Laboratorium'],
            ['kode' => '4', 'nama' => 'Fidro Susanto, S.Pd', 'nip' => '198408232011011003', 'is_bk' => false, 'jabatan_tambahan' => 'Wakasek Kurikulum'],
            ['kode' => '5', 'nama' => 'Wahyu Hidayat, S.Pd', 'nip' => '198705052011011008', 'is_bk' => false, 'jabatan_tambahan' => 'Wakasek SDM'],
            ['kode' => '6', 'nama' => 'Edi Susanto, S.Pd', 'nip' => '198404032022211016', 'is_bk' => false, 'jabatan_tambahan' => 'Wakasek Humas'],
            ['kode' => '7', 'nama' => 'Desi Purwati, S.Pd', 'nip' => '198609192022212009', 'is_bk' => false, 'jabatan_tambahan' => 'Wali XII.1'],
            ['kode' => '8', 'nama' => 'Jumin, S.Pd', 'nip' => '198907122022211012', 'is_bk' => false, 'jabatan_tambahan' => 'Wali XII.4'],
            ['kode' => '9', 'nama' => 'Siti Aminah, S.Pd', 'nip' => '198412312022212081', 'is_bk' => false, 'jabatan_tambahan' => 'Wali XII.3'],
            ['kode' => '10', 'nama' => 'Raharjo, S.Pd', 'nip' => '199509212022211004', 'is_bk' => false, 'jabatan_tambahan' => 'Wakasek Sarpras'],
            ['kode' => '11', 'nama' => 'Januar Andika, S.Pd.I', 'nip' => '198301082023211003', 'is_bk' => false, 'jabatan_tambahan' => 'Wali XII.2'],
            ['kode' => '12', 'nama' => 'Eny Rahayu, S.Pd', 'nip' => '198709222023212034', 'is_bk' => false, 'jabatan_tambahan' => 'Kepala Perpustakaan'],
            ['kode' => '13', 'nama' => 'Aditya Febrianto, S.Pd', 'nip' => '199005032023211008', 'is_bk' => false, 'jabatan_tambahan' => 'Wali X.2'],
            ['kode' => '14', 'nama' => 'Asri Handayani, S.Pd', 'nip' => '198808202023212029', 'is_bk' => false, 'jabatan_tambahan' => 'Wali XI.2'],
            ['kode' => '15', 'nama' => 'Alfiansyah, S.Pd', 'nip' => '199107092023211013', 'is_bk' => false, 'jabatan_tambahan' => 'Wali X.1'],
            ['kode' => '16', 'nama' => 'Dyah Putri Utari, S.Pd', 'nip' => '199310282023212036', 'is_bk' => false, 'jabatan_tambahan' => 'Wali X.3'],
            ['kode' => '17', 'nama' => 'Ayis Susanto, S.Pd', 'nip' => '199306272023211008', 'is_bk' => false, 'jabatan_tambahan' => 'Pembina OSIS'],
            ['kode' => '18', 'nama' => 'Yulparida, S.E', 'nip' => '199011182023212015', 'is_bk' => false, 'jabatan_tambahan' => 'Wali XI.4'],
            ['kode' => '19', 'nama' => 'Yayuk Setiarini, S.Pd.I', 'nip' => '199011182023212015', 'is_bk' => false, 'jabatan_tambahan' => 'Wali X.4'],
            ['kode' => '20', 'nama' => 'M. Abdullah, S.Pd', 'nip' => '199612152023211011', 'is_bk' => false, 'jabatan_tambahan' => 'Wali XI.1'],
            ['kode' => '21', 'nama' => 'I Gede Suastika Yasa, S.Pd', 'nip' => '199011182023212015', 'is_bk' => false, 'jabatan_tambahan' => 'Wali X.5'],
            ['kode' => '22', 'nama' => 'Putri Indarti, S.Pd', 'nip' => '199310282023212036', 'is_bk' => false, 'jabatan_tambahan' => 'Wali XI.3'],
            ['kode' => '23', 'nama' => 'Rentina Olaria, S.Pd', 'nip' => null, 'is_bk' => false, 'jabatan_tambahan' => null],
            ['kode' => '24', 'nama' => 'Haru Yuniana, S.Pd', 'nip' => null, 'is_bk' => false, 'jabatan_tambahan' => 'Pembina Pramuka Pi'],
            ['kode' => '25', 'nama' => 'Oktavia Widianingrum, S.Pd', 'nip' => null, 'is_bk' => false, 'jabatan_tambahan' => null],
            ['kode' => '26', 'nama' => 'Siti Lestari Hasibuan, S.Pd', 'nip' => null, 'is_bk' => true, 'jabatan_tambahan' => 'Guru BK'],
            ['kode' => '27', 'nama' => 'Lusy Pratiwi, S.Pd', 'nip' => '199009262024212016', 'is_bk' => true, 'jabatan_tambahan' => 'Guru BK'],
        ];

        foreach ($gurus as $guru) {
            Guru::updateOrCreate(['kode' => $guru['kode']], $guru);
        }

        $this->command?->info('27 Guru (25 pengajar + 2 BK) berhasil di-seed.');
    }
}
