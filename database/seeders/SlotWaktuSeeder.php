<?php

namespace Database\Seeders;

use App\Models\SlotWaktu;
use Illuminate\Database\Seeder;

class SlotWaktuSeeder extends Seeder
{
    public function run(): void
    {
        $pola = [
            'Senin'  => $this->polaHariPanjang(),
            'Selasa' => $this->polaHariPanjang(),
            'Rabu'   => $this->polaHariPanjang(),
            'Kamis'  => $this->polaHariPanjang(),
            'Jumat'  => $this->polaHariPendek(),
        ];

        foreach ($pola as $hari => $slots) {
            foreach ($slots as $s) {
                SlotWaktu::updateOrCreate(
                    ['hari' => $hari, 'jam_ke' => $s['jam_ke']],
                    [
                        'jam_mulai'    => $s['jam_mulai'],
                        'jam_selesai'  => $s['jam_selesai'],
                        'is_istirahat' => $s['is_istirahat'],
                    ]
                );
            }
        }

        $this->command->info('✅ Slot Waktu berhasil di-seed.');
    }

    private function polaHariPanjang(): array
    {
        return [
            ['jam_ke'=>1,  'jam_mulai'=>'07:30', 'jam_selesai'=>'08:15', 'is_istirahat'=>false],
            ['jam_ke'=>2,  'jam_mulai'=>'08:15', 'jam_selesai'=>'09:00', 'is_istirahat'=>false],
            ['jam_ke'=>3,  'jam_mulai'=>'09:00', 'jam_selesai'=>'09:45', 'is_istirahat'=>false],
            ['jam_ke'=>4,  'jam_mulai'=>'09:45', 'jam_selesai'=>'10:30', 'is_istirahat'=>false],
            ['jam_ke'=>45, 'jam_mulai'=>'10:30', 'jam_selesai'=>'10:45', 'is_istirahat'=>true],  // ⭐ 45 = istirahat setelah jam 4
            ['jam_ke'=>5,  'jam_mulai'=>'10:45', 'jam_selesai'=>'11:30', 'is_istirahat'=>false],
            ['jam_ke'=>6,  'jam_mulai'=>'11:30', 'jam_selesai'=>'12:15', 'is_istirahat'=>false],
            ['jam_ke'=>65, 'jam_mulai'=>'12:15', 'jam_selesai'=>'12:30', 'is_istirahat'=>true],  // ⭐ 65 = istirahat setelah jam 6
            ['jam_ke'=>7,  'jam_mulai'=>'12:30', 'jam_selesai'=>'13:15', 'is_istirahat'=>false],
            ['jam_ke'=>8,  'jam_mulai'=>'13:15', 'jam_selesai'=>'14:00', 'is_istirahat'=>false],
            ['jam_ke'=>9,  'jam_mulai'=>'14:00', 'jam_selesai'=>'14:45', 'is_istirahat'=>false],
            ['jam_ke'=>10, 'jam_mulai'=>'14:45', 'jam_selesai'=>'15:30', 'is_istirahat'=>false],
        ];
    }

    private function polaHariPendek(): array
    {
        return [
            ['jam_ke'=>1,  'jam_mulai'=>'07:30', 'jam_selesai'=>'08:15', 'is_istirahat'=>false],
            ['jam_ke'=>2,  'jam_mulai'=>'08:15', 'jam_selesai'=>'09:00', 'is_istirahat'=>false],
            ['jam_ke'=>3,  'jam_mulai'=>'09:00', 'jam_selesai'=>'09:45', 'is_istirahat'=>false],
            ['jam_ke'=>35, 'jam_mulai'=>'09:45', 'jam_selesai'=>'10:00', 'is_istirahat'=>true],  // ⭐ 35 = istirahat setelah jam 3
            ['jam_ke'=>4,  'jam_mulai'=>'10:00', 'jam_selesai'=>'10:45', 'is_istirahat'=>false],
            ['jam_ke'=>5,  'jam_mulai'=>'10:45', 'jam_selesai'=>'11:30', 'is_istirahat'=>false],
        ];
    }
}