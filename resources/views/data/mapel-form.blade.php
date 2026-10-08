@extends('layouts.app')

@section('title', isset($mapel) ? 'Edit Mapel' : 'Tambah Mapel')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <i class="fas fa-chevron-right text-xs"></i>
    <a href="{{ route('data.mapel') }}" class="hover:text-blue-600">
        Data Mata Pelajaran
    </a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-slate-800 font-semibold">{{ isset($mapel) ? 'Edit' : 'Tambah' }}</span>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-{{ isset($mapel) ? 'edit' : 'book-medical' }} text-green-600 text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    {{ isset($mapel) ? 'Edit Mata Pelajaran' : 'Tambah Mapel Baru' }}
                </h2>
                <p class="text-sm text-slate-500">
                    {{ isset($mapel) ? 'Ubah data mapel' : 'Masukkan data mapel baru' }}
                </p>
            </div>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 mb-4">
                <p class="font-bold mb-1"><i class="fas fa-exclamation-circle"></i> Ada kesalahan:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($mapel) ? route('data.mapel.update', $mapel->id) : route('data.mapel.store') }}" 
              method="POST" class="space-y-4">
            @csrf
            @if(isset($mapel))
                @method('PUT')
            @endif

            {{-- Kode Mapel --}}
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    <i class="fas fa-hashtag text-green-500 mr-1"></i> Kode Mapel
                </label>
                <input type="text" name="kode" 
                       value="{{ old('kode', $mapel->kode ?? $nextKode) }}" 
                       required maxlength="5"
                       style="text-transform: uppercase;"
                       class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-green-500 font-mono text-lg">
                <p class="text-xs text-slate-500 mt-1">
                    Kode unik untuk mapel (contoh: A, B, C, ..., S)
                    @if(!isset($mapel))
                        · <strong>Saran: {{ $nextKode }}</strong>
                    @endif
                </p>
                @error('kode')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama Mapel --}}
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    <i class="fas fa-book text-green-500 mr-1"></i> Nama Mata Pelajaran
                </label>
                <input type="text" name="nama" 
                       value="{{ old('nama', $mapel->nama ?? '') }}" 
                       required maxlength="100"
                       placeholder="Contoh: Matematika, Bahasa Indonesia, Fisika"
                       class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-green-500">
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kategori Mapel --}}
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    <i class="fas fa-fire text-red-500 mr-1"></i> Kategori Mapel
                </label>
                <label class="flex items-center gap-3 p-4 border-2 border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition">
                    <input type="checkbox" name="is_mapel_berat" value="1" 
                           {{ old('is_mapel_berat', $mapel->is_mapel_berat ?? false) ? 'checked' : '' }}
                           class="w-5 h-5 text-red-600 rounded">
                    <div>
                        <p class="font-semibold text-slate-800">Mapel Berat</p>
                        <p class="text-xs text-slate-500">
                            Centang untuk mapel berat (Matematika, Fisika, Kimia, Biologi).
                            Mapel berat akan diprioritaskan di jam pagi saat optimasi.
                        </p>
                    </div>
                </label>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 pt-4">
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <i class="fas fa-save"></i> 
                    {{ isset($mapel) ? 'Update Mapel' : 'Simpan Mapel Baru' }}
                </button>
                <a href="{{ route('data.mapel') }}" class="btn-secondary flex-1 justify-center">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection