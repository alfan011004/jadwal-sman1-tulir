<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_optimasis', function (Blueprint $table) {
            $table->id();
            $table->enum('algoritma', ['GA', 'SA']);
            $table->json('parameter');
            $table->double('fitness_terbaik');
            $table->double('waktu_komputasi');
            $table->integer('iterasi_konvergen');
            $table->integer('pelanggaran_hard');
            $table->integer('pelanggaran_soft');
            $table->json('jadwal');
            $table->json('log_konvergensi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_optimasis');
    }
};