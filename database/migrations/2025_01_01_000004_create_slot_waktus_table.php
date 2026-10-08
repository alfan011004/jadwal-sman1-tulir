<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slot_waktus', function (Blueprint $table) {
            $table->id();
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']);
            $table->smallInteger('jam_ke'); // 1-10 untuk JP, 97-99 untuk istirahat
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->boolean('is_istirahat')->default(false);
            $table->timestamps();

            $table->index(['hari', 'jam_ke']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slot_waktus');
    }
};