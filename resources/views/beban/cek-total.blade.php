@extends('layouts.app')

@section('title', 'Cek Total JP')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <i class="fas fa-chevron-right text-xs"></i>
    <a href="{{ route('beban.index') }}" class="hover:text-blue-600">Beban Mengajar</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-slate-800 font-semibold">Cek Total JP</span>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                <i class="fas fa-clipboard-check text-blue-600 mr-2"></i>
                Cek Total JP per Kelas
            </h2>
            <p class="text-slate-500">Validasi setiap kelas memiliki tepat 45 JP</p>
        </div>
        <div class="text-right">
            <p class="text-3xl font-bold {{ $totalKeseluruhan == $totalSeharusnya ? 'text-green-600' : 'text-red-600' }}">
                {{ $totalKeseluruhan }} / {{ $totalSeharusnya }}
            </p>
            <p class="text-xs text-slate-500">Total JP</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($kelasList as $k)
        <div class="border-2 rounded-xl p-4 
            {{ $k['status'] === 'ok' ? 'border-green-200 bg-green-50' : '' }}
            {{ $k['status'] === 'lebih' ? 'border-red-200 bg-red-50' : '' }}
            {{ $k['status'] === 'kurang' ? 'border-orange-200 bg-orange-50' : '' }}">
            
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center font-bold text-slate-700 shadow-sm">
                        {{ $k['nama'] }}
                    </div>
                    <span class="text-xs font-semibold px-2 py-1 rounded-full
                        {{ $k['status'] === 'ok' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $k['status'] === 'lebih' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $k['status'] === 'kurang' ? 'bg-orange-100 text-orange-700' : '' }}">
                        Kelas {{ $k['tingkat'] }}
                    </span>
                </div>
                @if($k['status'] === 'ok')
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                @elseif($k['status'] === 'lebih')
                    <i class="fas fa-exclamation-circle text-red-500 text-2xl"></i>
                @else
                    <i class="fas fa-exclamation-triangle text-orange-500 text-2xl"></i>
                @endif
            </div>
            
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-bold 
                    {{ $k['status'] === 'ok' ? 'text-green-700' : '' }}
                    {{ $k['status'] === 'lebih' ? 'text-red-700' : '' }}
                    {{ $k['status'] === 'kurang' ? 'text-orange-700' : '' }}">
                    {{ $k['jp'] }}
                </span>
                <span class="text-sm text-slate-500">/ 45 JP</span>
            </div>
            
            <div class="mt-2 text-xs">
                @if($k['status'] === 'ok')
                    <span class="text-green-700">✓ Total JP sudah tepat</span>
                @elseif($k['status'] === 'lebih')
                    <span class="text-red-700 font-semibold">✗ Kelebihan {{ $k['selisih'] }} JP</span>
                @else
                    <span class="text-orange-700 font-semibold">⚠ Kurang {{ abs($k['selisih']) }} JP</span>
                @endif
            </div>

            <a href="{{ route('beban.index', ['kelas_id' => $k['id']]) }}" 
               class="mt-3 inline-block text-xs text-blue-600 hover:underline">
                Lihat detail →
            </a>
        </div>
        @endforeach
    </div>
</div>

{{-- Rekomendasi --}}
@if($totalKeseluruhan != $totalSeharusnya)
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
    <p class="font-semibold text-yellow-800">
        <i class="fas fa-exclamation-triangle"></i> Rekomendasi Perbaikan:
    </p>
    <ul class="text-sm text-yellow-700 mt-2 list-disc list-inside">
        @foreach($kelasList as $k)
            @if($k['status'] === 'lebih')
                <li>Kelas <strong>{{ $k['nama'] }}</strong> kelebihan <strong>{{ $k['selisih'] }} JP</strong> — hapus atau kurangi JP mapel tertentu</li>
            @elseif($k['status'] === 'kurang')
                <li>Kelas <strong>{{ $k['nama'] }}</strong> kurang <strong>{{ abs($k['selisih']) }} JP</strong> — tambah mapel atau JP</li>
            @endif
        @endforeach
    </ul>
</div>
@endif

{{-- Back --}}
<div class="mt-6">
    <a href="{{ route('beban.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Beban Mengajar
    </a>
</div>

@endsection