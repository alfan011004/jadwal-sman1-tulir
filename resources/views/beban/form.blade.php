@extends('layouts.app')

@section('title', isset($beban) ? 'Edit Beban' : 'Tambah Beban')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <i class="fas fa-chevron-right text-xs"></i>
    <a href="{{ route('beban.index') }}" class="hover:text-blue-600">
        Beban Mengajar
    </a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-slate-800 font-semibold">{{ isset($beban) ? 'Edit' : 'Tambah' }}</span>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-{{ isset($beban) ? 'edit' : 'plus' }} text-blue-600 text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    {{ isset($beban) ? 'Edit Beban Mengajar' : 'Tambah Beban Mengajar' }}
                </h2>
                <p class="text-sm text-slate-500">Isi data pembagian tugas guru mengajar</p>
            </div>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ isset($beban) ? route('beban.update', $beban->id) : route('beban.store') }}" 
              method="POST" class="space-y-4">
            @csrf
            @if(isset($beban))
                @method('PUT')
            @endif

            {{-- Guru --}}
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    <i class="fas fa-user-tie text-blue-500 mr-1"></i> Guru Pengajar
                </label>
                <select name="guru_id" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($gurus as $g)
                        <option value="{{ $g->id }}" 
                                {{ old('guru_id', $beban->guru_id ?? '') == $g->id ? 'selected' : '' }}>
                            {{ $g->kode }} - {{ $g->nama }}
                        </option>
                    @endforeach
                </select>
                @error('guru_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Mapel --}}
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    <i class="fas fa-book text-green-500 mr-1"></i> Mata Pelajaran
                </label>
                <select name="mapel_id" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mapels as $m)
                        <option value="{{ $m->id }}" 
                                {{ old('mapel_id', $beban->mapel_id ?? '') == $m->id ? 'selected' : '' }}>
                            {{ $m->kode }} - {{ $m->nama }}
                        </option>
                    @endforeach
                </select>
                @error('mapel_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kelas --}}
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    <i class="fas fa-users text-purple-500 mr-1"></i> Kelas
                </label>
                <select name="kelas_id" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" 
                                {{ old('kelas_id', $beban->kelas_id ?? '') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
                @error('kelas_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- JP --}}
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    <i class="fas fa-clock text-orange-500 mr-1"></i> Jumlah JP per Minggu
                </label>
                <input type="number" name="jumlah_jp" value="{{ old('jumlah_jp', $beban->jumlah_jp ?? 2) }}" 
                       min="1" max="10" required
                       class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-slate-500 mt-1">Contoh: 2 (untuk mapel 2 JP/minggu)</p>
                @error('jumlah_jp')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 pt-4">
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <i class="fas fa-save"></i> 
                    {{ isset($beban) ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('beban.index') }}" class="btn-secondary flex-1 justify-center">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection