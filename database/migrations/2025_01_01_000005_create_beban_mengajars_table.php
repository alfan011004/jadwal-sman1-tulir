<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beban_mengajars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->tinyInteger('jumlah_jp');
            $table->timestamps();

            $table->unique(['guru_id', 'mapel_id', 'kelas_id'], 'unique_beban');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beban_mengajars');
    }
};