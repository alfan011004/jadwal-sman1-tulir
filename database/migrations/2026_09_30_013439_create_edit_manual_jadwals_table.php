<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edit_manual_jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_optimasi_id')->constrained('hasil_optimasis')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('slot_id')->constrained('slot_waktus')->onDelete('cascade');
            $table->string('kode_manual', 20); // contoh: "5E"
            $table->foreignId('guru_id')->nullable()->constrained('gurus')->onDelete('set null');
            $table->foreignId('mapel_id')->nullable()->constrained('mapels')->onDelete('set null');
            $table->timestamps();
            
            // 1 kelas + 1 slot = 1 edit manual (unik)
            $table->unique(['hasil_optimasi_id', 'kelas_id', 'slot_id'], 'edit_manual_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edit_manual_jadwals');
    }
};