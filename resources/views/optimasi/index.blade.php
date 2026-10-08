@extends('layouts.app')

@section('title', 'Jalankan Optimasi')

@section('content')

{{-- ⭐ HERO HEADER ⭐ --}}
<div class="relative bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 rounded-3xl p-8 lg:p-10 text-white mb-8 shadow-xl overflow-hidden">
    <div class="absolute top-0 right-0 w-80 h-80 bg-white opacity-5 rounded-full -mr-20 -mt-20"></div>
    <div class="absolute bottom-0 right-40 w-40 h-40 bg-white opacity-5 rounded-full"></div>
    <div class="absolute top-4 right-8 text-[180px] opacity-10">
        <i class="fas fa-rocket"></i>
    </div>
    
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold mb-4">
            <i class="fas fa-play-circle"></i>
            Panel Optimasi
        </div>
        <h1 class="text-3xl lg:text-4xl font-bold mb-3 leading-tight">
            🚀 Jalankan Optimasi Penjadwalan
        </h1>
        <p class="text-blue-100 text-base lg:text-lg max-w-3xl leading-relaxed">
            Pilih algoritma metaheuristik dan atur parameter untuk menghasilkan jadwal pelajaran optimal 
            di SMAN 1 Tungkal Ilir.
        </p>
    </div>
</div>

{{-- ⭐ FORM ⭐ --}}
<form action="{{ route('optimasi.jalankan') }}" method="POST" class="space-y-6">
    @csrf

    {{-- ⭐ PILIH ALGORITMA ⭐ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 lg:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-chess text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Pilih Algoritma</h2>
                <p class="text-sm text-slate-500">Pilih satu atau bandingkan keduanya</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- GA --}}
            <label class="relative cursor-pointer group">
                <input type="radio" name="algoritma" value="GA" checked 
                       onchange="toggleParameter()"
                       class="peer sr-only">
                <div class="border-2 border-slate-200 rounded-2xl p-5 transition-all duration-300 
                            peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-lg
                            hover:border-blue-300 hover:shadow-md">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition">
                            <i class="fas fa-dna text-blue-600 text-xl"></i>
                        </div>
                        <div class="w-6 h-6 rounded-full border-2 border-slate-300 
                                    peer-checked:border-blue-500 peer-checked:bg-blue-500 
                                    flex items-center justify-center transition">
                            <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition"></i>
                        </div>
                    </div>
                    <p class="font-bold text-slate-800 mb-1">Algoritma Genetika</p>
                    <p class="text-xs text-slate-500">Optimasi berbasis evolusi</p>
                </div>
            </label>

            {{-- SA --}}
            <label class="relative cursor-pointer group">
                <input type="radio" name="algoritma" value="SA" 
                       onchange="toggleParameter()"
                       class="peer sr-only">
                <div class="border-2 border-slate-200 rounded-2xl p-5 transition-all duration-300 
                            peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:shadow-lg
                            hover:border-orange-300 hover:shadow-md">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center group-hover:bg-orange-200 transition">
                            <i class="fas fa-fire text-orange-600 text-xl"></i>
                        </div>
                        <div class="w-6 h-6 rounded-full border-2 border-slate-300 
                                    peer-checked:border-orange-500 peer-checked:bg-orange-500 
                                    flex items-center justify-center transition">
                            <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition"></i>
                        </div>
                    </div>
                    <p class="font-bold text-slate-800 mb-1">Simulated Annealing</p>
                    <p class="text-xs text-slate-500">Optimasi berbasis termal</p>
                </div>
            </label>

            {{-- BOTH --}}
            <label class="relative cursor-pointer group">
                <input type="radio" name="algoritma" value="BOTH" 
                       onchange="toggleParameter()"
                       class="peer sr-only">
                <div class="border-2 border-slate-200 rounded-2xl p-5 transition-all duration-300 
                            peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:shadow-lg
                            hover:border-purple-300 hover:shadow-md">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-200 transition">
                            <i class="fas fa-balance-scale text-purple-600 text-xl"></i>
                        </div>
                        <div class="w-6 h-6 rounded-full border-2 border-slate-300 
                                    peer-checked:border-purple-500 peer-checked:bg-purple-500 
                                    flex items-center justify-center transition">
                            <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition"></i>
                        </div>
                    </div>
                    <p class="font-bold text-slate-800 mb-1">Keduanya (Bandingkan)</p>
                    <p class="text-xs text-slate-500">Bandingkan GA & SA</p>
                </div>
            </label>
        </div>
    </div>

    {{-- ⭐ PARAMETER ⭐ --}}
    <div id="params-container" class="grid lg:grid-cols-2 gap-6">
        
        {{-- GA PARAMS --}}
        <div id="params-ga" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                        <i class="fas fa-dna text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-lg">Parameter GA</h3>
                        <p class="text-xs text-blue-100">Algoritma Genetika</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 border-l-4 border-blue-500 px-6 py-4">
                <p class="text-sm text-blue-900 font-medium mb-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Apa itu Algoritma Genetika?
                </p>
                <p class="text-xs text-blue-800 leading-relaxed">
                    Cara kerja mirip proses evolusi di alam. Sistem membuat banyak 
                    <strong>kandidat jadwal</strong>, lalu memilih yang terbaik secara bertahap.
                </p>
            </div>
            
            <div class="p-6 space-y-5">
                {{-- Populasi --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-users text-blue-500"></i> Ukuran Populasi
                    </label>
                    <input type="number" name="ga_populasi" value="50" min="10" max="500"
                        class="w-full border-2 border-slate-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-medium">
                    <div class="mt-2 bg-slate-50 rounded-lg p-3 border-l-2 border-blue-400">
                        <p class="text-xs text-slate-700 leading-relaxed">
                            <strong>💡 Arti:</strong> Berapa banyak "kandidat jadwal" yang dibuat sekaligus.
                        </p>
                        <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                            <strong>📊 Rekomendasi:</strong> <code class="bg-blue-100 px-1.5 rounded text-blue-700 font-bold">50</code> (standar)<br>
                            Isi 20 untuk cepat, 100 untuk hasil lebih bagus.
                        </p>
                    </div>
                </div>
                
                {{-- Generasi --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-redo text-blue-500"></i> Jumlah Generasi
                    </label>
                    <input type="number" name="ga_generasi" value="200" min="10" max="2000"
                        class="w-full border-2 border-slate-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-medium">
                    <div class="mt-2 bg-slate-50 rounded-lg p-3 border-l-2 border-blue-400">
                        <p class="text-xs text-slate-700 leading-relaxed">
                            <strong>💡 Arti:</strong> Berapa kali sistem memperbaiki jadwal secara bertahap.
                        </p>
                        <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                            <strong>📊 Rekomendasi:</strong> <code class="bg-blue-100 px-1.5 rounded text-blue-700 font-bold">200</code> (seimbang)<br>
                            Isi 50 cepat, 500 optimal (3-5 menit).
                        </p>
                    </div>
                </div>
                
                {{-- Crossover & Mutation --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="flex items-center gap-1.5 text-sm font-semibold text-slate-700 mb-2">
                            <i class="fas fa-code-branch text-blue-500 text-xs"></i> Crossover
                        </label>
                        <input type="number" name="ga_crossover" value="0.85" step="0.01" min="0" max="1"
                            class="w-full border-2 border-slate-200 rounded-lg px-3 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-medium">
                        <div class="mt-2 bg-slate-50 rounded-lg p-2 border-l-2 border-blue-400">
                            <p class="text-[11px] text-slate-600 leading-relaxed">
                                <strong>💡 Arti:</strong> Peluang 2 jadwal "menikah" buat jadwal baru.
                            </p>
                            <p class="text-[11px] text-slate-500 mt-1">
                                <strong>📊 Rekomendasi:</strong> <code class="bg-blue-100 px-1 rounded text-blue-700">0.85</code>
                            </p>
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center gap-1.5 text-sm font-semibold text-slate-700 mb-2">
                            <i class="fas fa-random text-blue-500 text-xs"></i> Mutation
                        </label>
                        <input type="number" name="ga_mutation" value="0.15" step="0.01" min="0" max="1"
                            class="w-full border-2 border-slate-200 rounded-lg px-3 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-medium">
                        <div class="mt-2 bg-slate-50 rounded-lg p-2 border-l-2 border-blue-400">
                            <p class="text-[11px] text-slate-600 leading-relaxed">
                                <strong>💡 Arti:</strong> Peluang perubahan acak biar tidak stuck.
                            </p>
                            <p class="text-[11px] text-slate-500 mt-1">
                                <strong>📊 Rekomendasi:</strong> <code class="bg-blue-100 px-1 rounded text-blue-700">0.15</code>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SA PARAMS --}}
        <div id="params-sa" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-red-600 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                        <i class="fas fa-fire text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-lg">Parameter SA</h3>
                        <p class="text-xs text-orange-100">Simulated Annealing</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-orange-50 border-l-4 border-orange-500 px-6 py-4">
                <p class="text-sm text-orange-900 font-medium mb-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Apa itu Simulated Annealing?
                </p>
                <p class="text-xs text-orange-800 leading-relaxed">
                    Mirip <strong>pendinginan logam panas</strong>. Awalnya acak, lalu makin fokus.
                </p>
            </div>
            
            <div class="p-6 space-y-5">
                {{-- Suhu Awal --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-thermometer-full text-orange-500"></i> Suhu Awal
                    </label>
                    <input type="number" name="sa_suhu" value="1000" step="10" min="10"
                        class="w-full border-2 border-slate-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition font-medium">
                    <div class="mt-2 bg-slate-50 rounded-lg p-3 border-l-2 border-orange-400">
                        <p class="text-xs text-slate-700 leading-relaxed">
                            <strong>💡 Arti:</strong> Seberapa "acak" sistem di awal.
                        </p>
                        <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                            <strong>📊 Rekomendasi:</strong> <code class="bg-orange-100 px-1.5 rounded text-orange-700 font-bold">1000</code> (standar)
                        </p>
                    </div>
                </div>
                
                {{-- Cooling Rate --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-snowflake text-orange-500"></i> Cooling Rate
                    </label>
                    <input type="number" name="sa_cooling" value="0.95" step="0.01" min="0.5" max="0.999"
                        class="w-full border-2 border-slate-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition font-medium">
                    <div class="mt-2 bg-slate-50 rounded-lg p-3 border-l-2 border-orange-400">
                        <p class="text-xs text-slate-700 leading-relaxed">
                            <strong>💡 Arti:</strong> Seberapa cepat "suhu turun". Makin lambat makin teliti.
                        </p>
                        <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                            <strong>📊 Rekomendasi:</strong> <code class="bg-orange-100 px-1.5 rounded text-orange-700 font-bold">0.95</code>
                        </p>
                    </div>
                </div>
                
                {{-- Iterasi per Suhu --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-repeat text-orange-500"></i> Iterasi per Suhu
                    </label>
                    <input type="number" name="sa_iterasi" value="50" min="10" max="500"
                        class="w-full border-2 border-slate-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition font-medium">
                    <div class="mt-2 bg-slate-50 rounded-lg p-3 border-l-2 border-orange-400">
                        <p class="text-xs text-slate-700 leading-relaxed">
                            <strong>💡 Arti:</strong> Percobaan per tiap level suhu sebelum turun.
                        </p>
                        <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                            <strong>📊 Rekomendasi:</strong> <code class="bg-orange-100 px-1.5 rounded text-orange-700 font-bold">50</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ⭐ TOMBOL JALANKAN ⭐ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 text-center">
        <div class="max-w-md mx-auto">
            <div class="flex items-center justify-center gap-2 text-slate-600 mb-4 text-sm">
                <i class="fas fa-info-circle text-blue-500"></i>
                <span>Proses optimasi akan berjalan di server. Mohon tunggu sampai selesai.</span>
            </div>
            <button type="submit" 
                    class="inline-flex items-center gap-3 bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 
                           text-white px-10 py-4 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl transition-all duration-300 
                           hover:-translate-y-1">
                <i class="fas fa-rocket text-xl"></i>
                Jalankan Optimasi
            </button>
            <p class="text-xs text-slate-500 mt-3">
                <i class="fas fa-clock mr-1"></i>
                Estimasi: 30 detik – 2 menit tergantung parameter
            </p>
        </div>
    </div>

</form>

{{-- ⭐ PANDUAN CEPAT (CONDITIONAL) ⭐ --}}
<div id="panduan-container" class="mt-8">
    
    {{-- ⭐ PANDUAN GA ⭐ --}}
    <div id="panduan-ga" class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border-2 border-blue-200 p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-dna text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-blue-900">📖 Panduan Cepat — Algoritma Genetika</h3>
                <p class="text-sm text-blue-700">Kalau tidak yakin mau isi berapa, ikuti panduan ini:</p>
            </div>
        </div>
        
        <div class="grid md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl p-4 border border-blue-200">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-xs">1</div>
                    <p class="font-bold text-slate-800 text-sm">Coba Cepat</p>
                </div>
                <p class="text-xs text-slate-600 mb-2">
                    Untuk test apakah sistem bekerja, isi angka kecil dulu:
                </p>
                <code class="text-[10px] bg-slate-100 p-2 rounded block">
                    Populasi: <strong>20</strong><br>
                    Generasi: <strong>50</strong><br>
                    Crossover: <strong>0.85</strong><br>
                    Mutation: <strong>0.15</strong>
                </code>
                <p class="text-[11px] text-green-700 mt-2 font-semibold">
                    <i class="fas fa-clock"></i> Selesai ~5-10 detik
                </p>
            </div>

            <div class="bg-white rounded-xl p-4 border border-blue-200">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xs">2</div>
                    <p class="font-bold text-slate-800 text-sm">Setting Standar</p>
                </div>
                <p class="text-xs text-slate-600 mb-2">
                    Untuk penggunaan normal sekolah, pakai setting ini:
                </p>
                <code class="text-[10px] bg-blue-50 p-2 rounded block text-blue-800">
                    Populasi: <strong>50</strong><br>
                    Generasi: <strong>200</strong><br>
                    Crossover: <strong>0.85</strong><br>
                    Mutation: <strong>0.15</strong>
                </code>
                <p class="text-[11px] text-blue-700 mt-2 font-semibold">
                    <i class="fas fa-clock"></i> Selesai ~30-60 detik
                </p>
            </div>

            <div class="bg-white rounded-xl p-4 border border-blue-200">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xs">3</div>
                    <p class="font-bold text-slate-800 text-sm">Hasil Terbaik</p>
                </div>
                <p class="text-xs text-slate-600 mb-2">
                    Untuk hasil jadwal paling optimal, naikkan:
                </p>
                <code class="text-[10px] bg-purple-50 p-2 rounded block text-purple-800">
                    Populasi: <strong>100</strong><br>
                    Generasi: <strong>500</strong><br>
                    Crossover: <strong>0.85</strong><br>
                    Mutation: <strong>0.20</strong>
                </code>
                <p class="text-[11px] text-purple-700 mt-2 font-semibold">
                    <i class="fas fa-clock"></i> Selesai ~2-3 menit
                </p>
            </div>
        </div>

        <div class="mt-4 bg-blue-100 border border-blue-300 rounded-lg p-3">
            <p class="text-xs text-blue-900">
                <i class="fas fa-check-circle mr-1"></i>
                <strong>Tips GA:</strong> Kalau hasil jadwal masih ada yang kosong/dobel, coba naikkan
                <strong>Jumlah Generasi</strong> (misal jadi 500) lalu jalankan ulang.
            </p>
        </div>
    </div>

    {{-- ⭐ PANDUAN SA ⭐ --}}
    <div id="panduan-sa" class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl border-2 border-orange-200 p-6 shadow-sm" style="display: none;">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-fire text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-orange-900">📖 Panduan Cepat — Simulated Annealing</h3>
                <p class="text-sm text-orange-700">Kalau tidak yakin mau isi berapa, ikuti panduan ini:</p>
            </div>
        </div>
        
        <div class="grid md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl p-4 border border-orange-200">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-xs">1</div>
                    <p class="font-bold text-slate-800 text-sm">Coba Cepat</p>
                </div>
                <p class="text-xs text-slate-600 mb-2">
                    Untuk test apakah sistem bekerja, isi angka kecil dulu:
                </p>
                <code class="text-[10px] bg-slate-100 p-2 rounded block">
                    Suhu: <strong>500</strong><br>
                    Cooling: <strong>0.90</strong><br>
                    Iterasi: <strong>20</strong>
                </code>
                <p class="text-[11px] text-green-700 mt-2 font-semibold">
                    <i class="fas fa-clock"></i> Selesai ~5-10 detik
                </p>
            </div>

            <div class="bg-white rounded-xl p-4 border border-orange-200">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xs">2</div>
                    <p class="font-bold text-slate-800 text-sm">Setting Standar</p>
                </div>
                <p class="text-xs text-slate-600 mb-2">
                    Untuk penggunaan normal sekolah, pakai setting ini:
                </p>
                <code class="text-[10px] bg-orange-50 p-2 rounded block text-orange-800">
                    Suhu: <strong>1000</strong><br>
                    Cooling: <strong>0.95</strong><br>
                    Iterasi: <strong>50</strong>
                </code>
                <p class="text-[11px] text-blue-700 mt-2 font-semibold">
                    <i class="fas fa-clock"></i> Selesai ~20-40 detik
                </p>
            </div>

            <div class="bg-white rounded-xl p-4 border border-orange-200">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xs">3</div>
                    <p class="font-bold text-slate-800 text-sm">Hasil Terbaik</p>
                </div>
                <p class="text-xs text-slate-600 mb-2">
                    Untuk hasil jadwal paling optimal, naikkan:
                </p>
                <code class="text-[10px] bg-purple-50 p-2 rounded block text-purple-800">
                    Suhu: <strong>2000</strong><br>
                    Cooling: <strong>0.98</strong><br>
                    Iterasi: <strong>100</strong>
                </code>
                <p class="text-[11px] text-purple-700 mt-2 font-semibold">
                    <i class="fas fa-clock"></i> Selesai ~2-3 menit
                </p>
            </div>
        </div>

        <div class="mt-4 bg-orange-100 border border-orange-300 rounded-lg p-3">
            <p class="text-xs text-orange-900">
                <i class="fas fa-check-circle mr-1"></i>
                <strong>Tips SA:</strong> Kalau hasil jadwal masih ada yang kosong/dobel, coba naikkan
                <strong>Cooling Rate</strong> (misal jadi 0.98) atau <strong>Iterasi per Suhu</strong> (jadi 100) lalu jalankan ulang.
            </p>
        </div>
    </div>

    {{-- ⭐ PANDUAN BOTH ⭐ --}}
    <div id="panduan-both" class="space-y-4" style="display: none;">
        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl border-2 border-purple-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-balance-scale text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-purple-900">📖 Panduan Cepat — Mode Bandingkan</h3>
                    <p class="text-sm text-purple-700">Anda akan menjalankan kedua algoritma sekaligus. Berikut rekomendasi parameternya:</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                {{-- GA untuk BOTH --}}
                <div class="bg-white rounded-xl p-4 border border-blue-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dna text-blue-600"></i>
                        </div>
                        <p class="font-bold text-slate-800">Parameter GA</p>
                    </div>
                    <code class="text-[10px] bg-blue-50 p-2 rounded block text-blue-800 mb-2">
                        Populasi: <strong>50</strong><br>
                        Generasi: <strong>200</strong><br>
                        Crossover: <strong>0.85</strong><br>
                        Mutation: <strong>0.15</strong>
                    </code>
                    <p class="text-[11px] text-blue-700 font-semibold">
                        <i class="fas fa-clock"></i> ~30-60 detik
                    </p>
                </div>

                {{-- SA untuk BOTH --}}
                <div class="bg-white rounded-xl p-4 border border-orange-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-fire text-orange-600"></i>
                        </div>
                        <p class="font-bold text-slate-800">Parameter SA</p>
                    </div>
                    <code class="text-[10px] bg-orange-50 p-2 rounded block text-orange-800 mb-2">
                        Suhu: <strong>1000</strong><br>
                        Cooling: <strong>0.95</strong><br>
                        Iterasi: <strong>50</strong>
                    </code>
                    <p class="text-[11px] text-orange-700 font-semibold">
                        <i class="fas fa-clock"></i> ~20-40 detik
                    </p>
                </div>
            </div>

            <div class="mt-4 bg-purple-100 border border-purple-300 rounded-lg p-3">
                <p class="text-xs text-purple-900">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>Estimasi total:</strong> ~1-2 menit untuk kedua algoritma. 
                    Hasil akan ditampilkan di halaman <strong>Perbandingan</strong> untuk analisis.
                </p>
            </div>
        </div>
    </div>

</div>

{{-- ⭐ SCRIPT TOGGLE ⭐ --}}
@push('scripts')
<script>
function toggleParameter() {
    const algoritma = document.querySelector('input[name="algoritma"]:checked').value;
    
    // PARAMETER
    const container = document.getElementById('params-container');
    const paramsGa = document.getElementById('params-ga');
    const paramsSa = document.getElementById('params-sa');
    
    container.className = 'grid gap-6';
    paramsGa.style.display = 'none';
    paramsSa.style.display = 'none';
    paramsGa.classList.remove('lg:max-w-2xl', 'lg:mx-auto');
    paramsSa.classList.remove('lg:max-w-2xl', 'lg:mx-auto');
    
    // PANDUAN
    const panduanGa = document.getElementById('panduan-ga');
    const panduanSa = document.getElementById('panduan-sa');
    const panduanBoth = document.getElementById('panduan-both');
    
    panduanGa.style.display = 'none';
    panduanSa.style.display = 'none';
    panduanBoth.style.display = 'none';
    
    if (algoritma === 'GA') {
        // Params
        container.classList.add('lg:grid-cols-1');
        paramsGa.style.display = 'block';
        paramsGa.classList.add('lg:max-w-2xl', 'lg:mx-auto');
        // Panduan
        panduanGa.style.display = 'block';
    } else if (algoritma === 'SA') {
        // Params
        container.classList.add('lg:grid-cols-1');
        paramsSa.style.display = 'block';
        paramsSa.classList.add('lg:max-w-2xl', 'lg:mx-auto');
        // Panduan
        panduanSa.style.display = 'block';
    } else if (algoritma === 'BOTH') {
        // Params
        container.classList.add('lg:grid-cols-2');
        paramsGa.style.display = 'block';
        paramsSa.style.display = 'block';
        // Panduan
        panduanBoth.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleParameter();
});
</script>
@endpush

@endsection