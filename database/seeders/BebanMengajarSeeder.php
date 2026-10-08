<?php

namespace Database\Seeders;

use App\Models\{Guru, Mapel, Kelas, BebanMengajar};
use Illuminate\Database\Seeder;

class BebanMengajarSeeder extends Seeder
{
    public function run(): void
    {
        $guru  = Guru::pluck('id', 'kode');
        $mapel = Mapel::pluck('id', 'kode');
        $kelas = Kelas::pluck('id', 'nama');

        /**
         * DATA FINAL — Dari Excel Manual SMAN 1 Tungkal Ilir
         * Total JP = 585 ✅
         * Setiap kelas = 45 JP ✅
         */
        $data = [
            // ============ 1. Dwi Maryanto (TIDAK MENGAJAR) ============
            // Kepala Sekolah, tidak mengajar

            // ============ 2. Didik Nurkorolin (24 JP) ============
            ['2', 'L', ['XII.1'=>5, 'XII.2'=>5, 'XII.3'=>5, 'XII.4'=>5]],  // ⭐ FIX: Ekonomi XII (bukan XI)
            ['2', 'O', ['X.1'=>2, 'X.2'=>2]],

            // ============ 3. Ena Hastuty (25 JP) ============
            ['3', 'J', ['X.1'=>3, 'X.2'=>3, 'X.3'=>3, 'X.4'=>3, 'X.5'=>3,
                        'XI.1'=>5, 'XII.1'=>5]],

            // ============ 4. Fidro Susanto (26 JP) ============
            ['4', 'B', ['X.1'=>2, 'X.2'=>2, 'X.3'=>2, 'X.4'=>2, 'X.5'=>2,
                        'XI.1'=>2, 'XI.2'=>2, 'XI.3'=>2, 'XI.4'=>2,
                        'XII.1'=>2, 'XII.2'=>2, 'XII.3'=>2, 'XII.4'=>2]],

            // ============ 5. Wahyu Hidayat (24 JP) ============
            ['5', 'E', ['X.1'=>4, 'X.2'=>4, 'X.3'=>4, 'X.4'=>4, 'XII.1'=>4, 'XII.2'=>4]],

            // ============ 6. Edi Susanto (24 JP) ============
            ['6', 'M', ['X.1'=>3, 'X.2'=>3, 'X.3'=>3, 'X.4'=>3,
                        'XI.3'=>5, 'XII.1'=>3, 'XII.2'=>5]],

            // ============ 7. Desi Purwati (25 JP) ============
            ['7', 'O', ['XI.1'=>2, 'XI.2'=>2, 'XI.3'=>2, 'XI.4'=>2,
                        'XII.1'=>2, 'XII.2'=>2, 'XII.3'=>2, 'XII.4'=>2]],
            ['7', 'L', ['X.3'=>3, 'X.4'=>3, 'X.5'=>3]],

            // ============ 8. Jumin (27 JP) ============
            ['8', 'G', ['X.3'=>3, 'X.4'=>3, 'X.5'=>3]],
            ['8', 'S', ['XI.3'=>5, 'XI.4'=>5, 'XII.4'=>5]],
            ['8', 'M', ['XI.1'=>3]],

            // ============ 9. Siti Aminah (26 JP) ============
            ['9', 'G', ['X.1'=>3, 'X.2'=>3,
                        'XI.1'=>2, 'XI.2'=>2, 'XI.3'=>2, 'XI.4'=>2,
                        'XII.1'=>2, 'XII.2'=>2, 'XII.3'=>2, 'XII.4'=>2]],
            ['9', 'F', ['XII.1'=>2, 'XII.2'=>2]],

            // ============ 10. Raharjo (27 JP) ============
            ['10', 'H', ['X.1'=>3, 'X.2'=>3, 'X.3'=>3, 'X.4'=>3, 'X.5'=>3,
                         'XII.1'=>3, 'XII.2'=>3, 'XII.3'=>3, 'XII.4'=>3]],

            // ============ 11. Januar Andika (24 JP) ============
            ['11', 'A', ['XI.1'=>3, 'XI.2'=>3, 'XI.3'=>3, 'XI.4'=>3,
                         'XII.1'=>3, 'XII.2'=>3, 'XII.3'=>3, 'XII.4'=>3]],

            // ============ 12. Eny Rahayu (24 JP) ============
            ['12', 'C', ['XI.1'=>4, 'XI.2'=>4, 'XI.3'=>4, 'XI.4'=>4,
                         'XII.3'=>4, 'XII.4'=>4]],

            // ============ 13. Aditya Febrianto (25 JP) ============
            ['13', 'I', ['X.1'=>3, 'X.2'=>3, 'X.3'=>3, 'X.4'=>3, 'X.5'=>3,
                         'XI.2'=>5, 'XII.2'=>5]],

            // ============ 14. Asri Handayani (24 JP) ============
            ['14', 'E', ['XI.1'=>4, 'XI.2'=>4, 'XI.3'=>4, 'XI.4'=>4,
                         'XII.3'=>4, 'XII.4'=>4]],

            // ============ 15. Alfiansyah (24 JP) ============
            ['15', 'C', ['X.1'=>4, 'X.2'=>4, 'X.3'=>4, 'X.4'=>4,
                         'XII.1'=>4, 'XII.2'=>4]],

            // ============ 16. Dyah Putri Utari (25 JP) ============
            ['16', 'N', ['X.1'=>3, 'X.2'=>3, 'X.3'=>3, 'X.4'=>3, 'X.5'=>3,
                         'XI.4'=>5, 'XII.3'=>5]],

            // ============ 17. Ayis Susanto (39 JP) ============
            ['17', 'D', ['X.1'=>3, 'X.2'=>3, 'X.3'=>3, 'X.4'=>3, 'X.5'=>3,
                         'XI.1'=>3, 'XI.2'=>3, 'XI.3'=>3, 'XI.4'=>3,
                         'XII.1'=>3, 'XII.2'=>3, 'XII.3'=>3, 'XII.4'=>3]],

            // ============ 18. Yulparida (26 JP) ============
            ['18', 'L', ['XI.1'=>5, 'XI.2'=>5, 'XI.3'=>5, 'XI.4'=>5]],  // Ekonomi XI (BENAR)
            ['18', 'O', ['X.3'=>2, 'X.4'=>2, 'X.5'=>2]],                 // PKWU X.3-X.5 (BENAR)

            // ============ 19. Yayuk Setiarini (25 JP) ============
            ['19', 'A', ['X.1'=>3, 'X.2'=>3, 'X.3'=>3, 'X.4'=>3, 'X.5'=>3]],
            ['19', 'F', ['XII.1'=>2, 'XII.2'=>2]],
            ['19', 'H', ['XI.1'=>3, 'XI.2'=>3]],

            // ============ 20. M. Abdullah (26 JP) ============
            ['20', 'R', ['XI.1'=>5, 'XI.2'=>5, 'XII.1'=>5, 'XII.2'=>5]],
            ['20', 'H', ['XI.3'=>3, 'XI.4'=>3]],

            // ============ 21. I Gede Suastika (25 JP) ============
            ['21', 'K', ['X.1'=>3, 'X.2'=>3, 'X.3'=>3, 'X.4'=>3, 'X.5'=>3,
                         'XII.3'=>5, 'XII.4'=>5]],

            // ============ 22. Putri Indarti (25 JP) ============
            ['22', 'K', ['XI.1'=>5, 'XI.2'=>5, 'XI.3'=>5, 'XI.4'=>5]],
            ['22', 'Q', ['X.1'=>1, 'X.2'=>1, 'X.3'=>1, 'X.4'=>1, 'X.5'=>1]],

            // ============ 23. Rentina Olaria (26 JP) ============
            ['23', 'E', ['X.5'=>4]],
            ['23', 'P', ['X.4'=>2, 'X.5'=>2, 'XII.1'=>5, 'XII.2'=>5]],
            ['23', 'F', ['XI.1'=>2, 'XI.2'=>2, 'XI.3'=>2, 'XI.4'=>2]],

            // ============ 24. Haru Yuniana (10 JP) ============
            ['24', 'C', ['X.5'=>4]],
            ['24', 'P', ['X.1'=>2, 'X.2'=>2, 'X.3'=>2]],

            // ============ 25. Oktavia Widianingrum (6 JP) ============
            ['25', 'L', ['X.1'=>3, 'X.2'=>3]],

            // ============ 26 & 27. Guru BK (TIDAK MENGAJAR) ============
        ];

        $total = 0;
        foreach ($data as [$guruKode, $mapelKode, $kelasMap]) {
            foreach ($kelasMap as $namaKelas => $jumlahJp) {
                BebanMengajar::updateOrCreate(
                    [
                        'guru_id'  => $guru[$guruKode],
                        'mapel_id' => $mapel[$mapelKode],
                        'kelas_id' => $kelas[$namaKelas],
                    ],
                    ['jumlah_jp' => $jumlahJp]
                );
                $total += $jumlahJp;
            }
        }

        $this->command->info("✅ Beban Mengajar berhasil di-seed. Total: {$total} JP.");
    }
}