<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GuruSeeder::class,
            MapelSeeder::class,
            KelasSeeder::class,
            SlotWaktuSeeder::class,
            BebanMengajarSeeder::class,
        ]);

        $this->command?->info('Seeder selesai. Database siap untuk optimasi GA & SA.');
    }
}
