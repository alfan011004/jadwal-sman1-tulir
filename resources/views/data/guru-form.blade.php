@extends('layouts.app')

@section('title', isset($guru) ? 'Edit Guru' : 'Tambah Guru')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <i class="fas fa-chevron-right text-xs"></i>
    <a href="{{ route('data.guru') }}" class="hover:text-blue-600">
        Data Guru
    </a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-slate-800 font-semibold">{{ isset($guru) ? 'Edit' : 'Tambah' }}</span>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-{{ isset($guru) ? 'edit' : 'user-plus' }} text-blue-600 text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    {{ isset($guru) ? 'Edit Data Guru' : 'Tambah Guru Baru' }}
                </h2>
                <p class="text-sm text-slate-500">
                    {{ isset($guru) ? 'Ubah data guru yang sudah ada' : 'Masukkan data guru baru' }}
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

        <form action="{{ isset($guru) ? route('data.guru.update', $guru->id) : route('data.guru.store') }}" 
              method="POST" class="space-y-4">
            @csrf
            @if(isset($guru))
                @method('PUT')
            @endif

            {{-- Kode Guru --}}
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    <i class="fas fa-hashtag text-blue-500 mr-1"></i> Kode Guru
                </label>
                <input type="text" name="kode" 
                       value="{{ old('kode', $guru->kode ?? $nextKode) }}" 
                       required maxlength="5"
                       class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-lg">
                <p class="text-xs text-slate-500 mt-1">
                    Kode unik untuk guru (contoh: 1, 2, 3, ..., 27)
                    @if(!isset($guru))
                        · <strong>Saran: {{ $nextKode }}</strong>
                    @endif
                </p>
                @error('kode')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama Guru --}}
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    <i class="fas fa-user text-blue-500 mr-1"></i> Nama Lengkap
                </label>
                <input type="text" name="nama" 
                       value="{{ old('nama', $guru->nama ?? '') }}" 
                       required maxlength="100"
                       placeholder="Contoh: Dwi Maryanto, S.Pd.T., M.Pd"
                       class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NIP --}}
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    <i class="fas fa-id-card text-blue-500 mr-1"></i> NIP
                    <span class="text-xs text-slate-400 font-normal">(opsional)</span>
                </label>
                <input type="text" name="nip" 
                       value="{{ old('nip', $guru->nip ?? '') }}" 
                       maxlength="30"
                       placeholder="Contoh: 198503112014031001"
                       class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 font-mono">
                @error('nip')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jabatan Tambahan --}}
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    <i class="fas fa-briefcase text-blue-500 mr-1"></i> Jabatan Tambahan
                    <span class="text-xs text-slate-400 font-normal">(opsional)</span>
                </label>
                <input type="text" name="jabatan_tambahan" 
                       value="{{ old('jabatan_tambahan', $guru->jabatan_tambahan ?? '') }}" 
                       maxlength="100"
                       placeholder="Contoh: Wakasek Kurikulum, Wali X.1, Kepala Lab, dll"
                       class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-slate-500 mt-1">
                    Kosongkan jika tidak ada jabatan tambahan
                </p>
                @error('jabatan_tambahan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status BK --}}
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    <i class="fas fa-hands-helping text-purple-500 mr-1"></i> Status Guru
                </label>
                <label class="flex items-center gap-3 p-4 border-2 border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition">
                    <input type="checkbox" name="is_bk" value="1" 
                           {{ old('is_bk', $guru->is_bk ?? false) ? 'checked' : '' }}
                           class="w-5 h-5 text-purple-600 rounded">
                    <div>
                        <p class="font-semibold text-slate-800">Guru BK (Bimbingan Konseling)</p>
                        <p class="text-xs text-slate-500">Centang jika guru ini hanya sebagai pendamping BK (tidak mengajar)</p>
                    </div>
                </label>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 pt-4">
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <i class="fas fa-save"></i> 
                    {{ isset($guru) ? 'Update Data Guru' : 'Simpan Guru Baru' }}
                </button>
                <a href="{{ route('data.guru') }}" class="btn-secondary flex-1 justify-center">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection