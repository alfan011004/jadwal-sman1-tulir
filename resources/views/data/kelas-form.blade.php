@extends('layouts.app')

@section('title', isset($kelas) ? 'Edit Kelas' : 'Tambah Kelas')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <i class="fas fa-chevron-right text-xs"></i>
    <a href="{{ route('data.kelas') }}" class="hover:text-blue-600">
        Data Kelas
    </a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-slate-800 font-semibold">{{ isset($kelas) ? 'Edit' : 'Tambah' }}</span>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-{{ isset($kelas) ? 'edit' : 'users' }} text-purple-600 text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    {{ isset($kelas) ? 'Edit Data Kelas' : 'Tambah Kelas Baru' }}
                </h2>
                <p class="text-sm text-slate-500">
                    {{ isset($kelas) ? 'Ubah data kelas' : 'Masukkan data kelas baru' }}
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

        <form action="{{ isset($kelas) ? route('data.kelas.update', $kelas->id) : route('data.kelas.store') }}" 
              method="POST" class="space-y-4">
            @csrf
            @if(isset($kelas))
                @method('PUT')
            @endif

            {{-- Nama Kelas --}}
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    <i class="fas fa-school text-purple-500 mr-1"></i> Nama Kelas
                </label>
                <input type="text" name="nama" 
                       value="{{ old('nama', $kelas->nama ?? '') }}" 
                       required maxlength="10"
                       placeholder="Contoh: X.1, XI.3, XII.4"
                       class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-purple-500 font-mono text-lg">
                <p class="text-xs text-slate-500 mt-1">
                    Format: tingkat.nomor (contoh: X.1, XI.2, XII.3)
                </p>
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tingkat --}}
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    <i class="fas fa-layer-group text-purple-500 mr-1"></i> Tingkat
                </label>
                <select name="tingkat" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">-- Pilih Tingkat --</option>
                    <option value="X"   {{ old('tingkat', $kelas->tingkat ?? '') == 'X' ? 'selected' : '' }}>Kelas X</option>
                    <option value="XI"  {{ old('tingkat', $kelas->tingkat ?? '') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                    <option value="XII" {{ old('tingkat', $kelas->tingkat ?? '') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                </select>
                @error('tingkat')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if(!isset($kelas) && $tingkatNext)
            <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-lightbulb mr-1"></i>
                    <strong>Saran nama kelas berikutnya:</strong>
                </p>
                <ul class="text-xs text-blue-700 mt-1 list-disc list-inside">
                    <li>Kelas X: <code class="bg-white px-1 rounded">{{ $tingkatNext['X'] }}</code></li>
                    <li>Kelas XI: <code class="bg-white px-1 rounded">{{ $tingkatNext['XI'] }}</code></li>
                    <li>Kelas XII: <code class="bg-white px-1 rounded">{{ $tingkatNext['XII'] }}</code></li>
                </ul>
            </div>
            @endif

            {{-- Buttons --}}
            <div class="flex gap-3 pt-4">
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <i class="fas fa-save"></i> 
                    {{ isset($kelas) ? 'Update Kelas' : 'Simpan Kelas Baru' }}
                </button>
                <a href="{{ route('data.kelas') }}" class="btn-secondary flex-1 justify-center">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection