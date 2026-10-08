@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- ⭐ WELCOME HERO + FOTO KEPSEK ⭐ --}}
<div class="mb-8">
    <div class="relative bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 rounded-3xl p-8 lg:p-10 text-white shadow-2xl overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 right-40 w-48 h-48 bg-white opacity-5 rounded-full"></div>
        <div class="absolute top-20 right-20 text-[180px] opacity-10 pointer-events-none">
            <i class="fas fa-calendar-alt"></i>
        </div>
        
        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
            
            {{-- KIRI: Teks --}}
            <div class="flex-1 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold mb-4">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    Sistem Aktif
                </div>
                <h2 class="text-3xl lg:text-4xl font-bold mb-3 leading-tight">
                    Selamat Datang! 👋
                </h2>
                <p class="text-blue-100 mb-6 text-base lg:text-lg leading-relaxed max-w-2xl">
                    Sistem optimasi penjadwalan mata pelajaran dengan 
                    <strong class="text-white">Perbandingan Algoritma Genetika (GA)</strong> dan 
                    <strong class="text-white">Simulated Annealing (SA)</strong> untuk SMAN 1 Tungkal Ilir.
                </p>
                <a href="{{ route('optimasi.index') }}" 
                   class="inline-flex items-center gap-2 bg-white text-blue-700 px-6 py-3 rounded-xl font-bold hover:bg-blue-50 transition shadow-xl hover:shadow-2xl hover:-translate-y-0.5">
                    <i class="fas fa-rocket"></i>
                    Mulai Optimasi
                </a>
            </div>

            {{-- ⭐ KANAN: Foto Kepala Sekolah (dari DATABASE) ⭐ --}}
            @php
                $profileKepsek = \App\Models\UserProfile::ambil();
                $fotoKepsek = $profileKepsek->fotoKepsekUrl();
            @endphp
            
            <div class="flex-shrink-0">
                @if($fotoKepsek)
                    {{-- Foto Kepsek dari database --}}
                    <div class="relative">
                        <div class="absolute inset-0 bg-white/20 rounded-full blur-2xl scale-110"></div>
                        <div class="relative w-40 h-40 lg:w-52 lg:h-52 rounded-full overflow-hidden border-4 border-white/40 shadow-2xl backdrop-blur-sm bg-white/10">
                            <img src="{{ $fotoKepsek }}" 
                                 alt="Kepala Sekolah" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-white rounded-full px-4 py-1.5 shadow-lg whitespace-nowrap">
                            <p class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                <i class="fas fa-user-tie text-blue-600"></i>
                                Kepala Sekolah
                            </p>
                        </div>
                    </div>
                @else
                    {{-- Placeholder kalau belum upload --}}
                    <div class="relative">
                        <div class="w-40 h-40 lg:w-52 lg:h-52 rounded-full overflow-hidden border-4 border-white/40 shadow-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                            <div class="text-center px-4">
                                <i class="fas fa-user-tie text-white/60 text-5xl mb-2"></i>
                                <p class="text-[10px] text-white/70">Belum ada foto</p>
                                <a href="{{ route('profile.edit') }}" class="text-[10px] text-white/90 underline mt-1 inline-block hover:text-white">Upload sekarang</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- ⭐ STATISTIK ⭐ --}}
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <i class="fas fa-chart-pie text-blue-600"></i>
            Statistik Data
        </h3>
        <span class="text-xs text-slate-400 font-medium">
            <i class="fas fa-info-circle mr-1"></i> Klik kartu untuk detail
        </span>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Total Guru --}}
        <a href="{{ route('data.guru') }}" 
           class="group bg-white rounded-2xl p-5 shadow-sm border border-slate-100 card-hover cursor-pointer block">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-blue-600 transition-colors duration-300">
                    <i class="fas fa-chalkboard-teacher text-blue-600 text-xl group-hover:text-white transition-colors"></i>
                </div>
                <i class="fas fa-arrow-right text-slate-300 group-hover:text-blue-600 group-hover:translate-x-1 transition-all"></i>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-1">{{ $stats['total_guru'] }}</p>
            <p class="text-sm font-medium text-slate-500 mb-3">Total Guru</p>
            <div class="flex items-center gap-3 text-xs pt-3 border-t border-slate-100">
                <span class="text-blue-600 font-semibold">
                    <i class="fas fa-user-tie mr-1"></i>{{ $stats['guru_pengajar'] }} Pengajar
                </span>
                <span class="text-purple-600 font-semibold">
                    <i class="fas fa-hands-helping mr-1"></i>{{ $stats['guru_bk'] }} BK
                </span>
            </div>
        </a>

        {{-- Mapel --}}
        <a href="{{ route('data.mapel') }}" 
           class="group bg-white rounded-2xl p-5 shadow-sm border border-slate-100 card-hover cursor-pointer block">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center group-hover:bg-green-600 transition-colors duration-300">
                    <i class="fas fa-book text-green-600 text-xl group-hover:text-white transition-colors"></i>
                </div>
                <i class="fas fa-arrow-right text-slate-300 group-hover:text-green-600 group-hover:translate-x-1 transition-all"></i>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-1">{{ $stats['total_mapel'] }}</p>
            <p class="text-sm font-medium text-slate-500 mb-3">Mata Pelajaran</p>
            <div class="pt-3 border-t border-slate-100 text-xs text-green-600 font-semibold">
                <i class="fas fa-check-circle mr-1"></i>
                Kurikulum Merdeka
            </div>
        </a>

        {{-- Kelas --}}
        <a href="{{ route('data.kelas') }}" 
           class="group bg-white rounded-2xl p-5 shadow-sm border border-slate-100 card-hover cursor-pointer block">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center group-hover:bg-purple-600 transition-colors duration-300">
                    <i class="fas fa-users text-purple-600 text-xl group-hover:text-white transition-colors"></i>
                </div>
                <i class="fas fa-arrow-right text-slate-300 group-hover:text-purple-600 group-hover:translate-x-1 transition-all"></i>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-1">{{ $stats['total_kelas'] }}</p>
            <p class="text-sm font-medium text-slate-500 mb-3">Total Kelas</p>
            <div class="flex items-center gap-2 text-xs pt-3 border-t border-slate-100">
                <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded font-semibold">X</span>
                <span class="px-2 py-0.5 bg-green-50 text-green-600 rounded font-semibold">XI</span>
                <span class="px-2 py-0.5 bg-purple-50 text-purple-600 rounded font-semibold">XII</span>
            </div>
        </a>

        {{-- Total JP --}}
        <a href="{{ route('beban.index') }}" 
           class="group bg-white rounded-2xl p-5 shadow-sm border border-slate-100 card-hover cursor-pointer block">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center group-hover:bg-orange-600 transition-colors duration-300">
                    <i class="fas fa-clock text-orange-600 text-xl group-hover:text-white transition-colors"></i>
                </div>
                <i class="fas fa-arrow-right text-slate-300 group-hover:text-orange-600 group-hover:translate-x-1 transition-all"></i>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-1">{{ $stats['total_jp'] }}</p>
            <p class="text-sm font-medium text-slate-500 mb-3">Total JP</p>
            <div class="pt-3 border-t border-slate-100 text-xs text-orange-600 font-semibold">
                <i class="fas fa-layer-group mr-1"></i>
                {{ $stats['total_slot'] }} slot per kelas
            </div>
        </a>
    </div>
</div>

{{-- ⭐ QUICK ACTIONS ⭐ --}}
<div class="mb-8">
    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
        <i class="fas fa-bolt text-yellow-500"></i>
        Aksi Cepat
    </h3>
    
    <div class="grid lg:grid-cols-3 gap-4">
        <a href="{{ route('optimasi.index') }}" 
           class="group relative bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white card-hover overflow-hidden">
            <div class="absolute top-0 right-0 text-9xl opacity-10 -mr-4 -mt-4">
                <i class="fas fa-dna"></i>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-dna text-2xl"></i>
                </div>
                <h4 class="font-bold text-xl mb-1">Algoritma Genetika</h4>
                <p class="text-blue-100 text-sm mb-4">Optimasi berbasis evolusi</p>
                <div class="flex items-center gap-2 text-sm font-semibold">
                    Jalankan <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('optimasi.index') }}" 
           class="group relative bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl p-6 text-white card-hover overflow-hidden">
            <div class="absolute top-0 right-0 text-9xl opacity-10 -mr-4 -mt-4">
                <i class="fas fa-fire"></i>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-fire text-2xl"></i>
                </div>
                <h4 class="font-bold text-xl mb-1">Simulated Annealing</h4>
                <p class="text-orange-100 text-sm mb-4">Optimasi berbasis termal</p>
                <div class="flex items-center gap-2 text-sm font-semibold">
                    Jalankan <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('optimasi.perbandingan') }}" 
           class="group relative bg-gradient-to-br from-purple-500 to-indigo-700 rounded-2xl p-6 text-white card-hover overflow-hidden">
            <div class="absolute top-0 right-0 text-9xl opacity-10 -mr-4 -mt-4">
                <i class="fas fa-balance-scale"></i>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-balance-scale text-2xl"></i>
                </div>
                <h4 class="font-bold text-xl mb-1">Perbandingan</h4>
                <p class="text-purple-100 text-sm mb-4">Analisis GA vs SA</p>
                <div class="flex items-center gap-2 text-sm font-semibold">
                    Lihat Hasil <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- ⭐ RIWAYAT ⭐ --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-history text-slate-600"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Riwayat Optimasi Terakhir</h3>
                <p class="text-xs text-slate-500">Hasil optimasi yang pernah dijalankan</p>
            </div>
        </div>
        <span class="text-xs text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full font-semibold">
            {{ $riwayat->count() }} data
        </span>
    </div>

    @if($riwayat->isEmpty())
        <div class="p-16 text-center">
            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-inbox text-4xl text-slate-400"></i>
            </div>
            <h4 class="font-semibold text-slate-700 mb-2 text-lg">Belum Ada Riwayat Optimasi</h4>
            <p class="text-sm text-slate-500 mb-6 max-w-md mx-auto">
                Mulai optimasi pertama Anda untuk melihat riwayat hasil di sini
            </p>
            <a href="{{ route('optimasi.index') }}" class="btn-primary">
                <i class="fas fa-rocket"></i> Mulai Optimasi Sekarang
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-slate-600 text-xs uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider">Algoritma</th>
                        <th class="px-6 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider">Fitness</th>
                        <th class="px-6 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider">Pelanggaran</th>
                        <th class="px-6 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($riwayat as $r)
                    <tr class="hover:bg-blue-50/50 transition cursor-pointer"
                        onclick="window.location='{{ route('optimasi.detail', $r->id) }}'">
                        <td class="px-6 py-4 text-slate-600 font-medium">
                            <i class="far fa-clock text-slate-400 mr-2"></i>
                            {{ $r->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold
                                {{ $r->algoritma === 'GA' 
                                    ? 'bg-blue-100 text-blue-700' 
                                    : 'bg-orange-100 text-orange-700' }}">
                                <i class="fas fa-{{ $r->algoritma === 'GA' ? 'dna' : 'fire' }}"></i>
                                {{ $r->algoritma }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-mono font-bold text-slate-800">
                            {{ number_format($r->fitness_terbaik, 0) }}
                        </td>
                        <td class="px-6 py-4 text-center text-slate-600 font-medium">
                            {{ number_format($r->waktu_komputasi, 2) }}s
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex gap-1.5 text-xs">
                                <span class="px-2 py-1 rounded bg-red-50 text-red-600 font-semibold">
                                    H: {{ $r->pelanggaran_hard }}
                                </span>
                                <span class="px-2 py-1 rounded bg-yellow-50 text-yellow-600 font-semibold">
                                    S: {{ $r->pelanggaran_soft }}
                                </span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('optimasi.detail', $r->id) }}" 
                                   onclick="event.stopPropagation()"
                                   class="text-blue-600 hover:text-blue-800 font-semibold inline-flex items-center gap-1">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="{{ route('cetak.jadwal', $r->id) }}" 
                                   target="_blank"
                                   onclick="event.stopPropagation()"
                                   class="text-green-600 hover:text-green-800 font-semibold inline-flex items-center gap-1">
                                    <i class="fas fa-print"></i> Cetak
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection