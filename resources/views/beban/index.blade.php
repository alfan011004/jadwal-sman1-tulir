@extends('layouts.app')

@section('title', 'Beban Mengajar')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600 font-medium">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-slate-800 font-semibold">Beban Mengajar</span>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('beban.cek-total') }}" class="btn-secondary text-sm">
            <i class="fas fa-clipboard-check"></i> Cek Total JP
        </a>
        <a href="{{ route('beban.create') }}" class="btn-primary text-sm">
            <i class="fas fa-plus"></i> Tambah Beban
        </a>
    </div>
</div>

{{-- Flash Message --}}
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 mb-4 flex items-center gap-3 shadow-sm">
        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check text-white text-sm"></i>
        </div>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-4 flex items-center gap-3 shadow-sm">
        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-exclamation text-white text-sm"></i>
        </div>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
@endif

{{-- Header --}}
<div class="relative bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 rounded-3xl p-8 text-white mb-6 shadow-xl overflow-hidden">
    <div class="absolute top-0 right-0 text-[150px] opacity-10 -mr-8 -mt-8">
        <i class="fas fa-clipboard-list"></i>
    </div>
    <div class="relative flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-clipboard-list text-3xl"></i>
            </div>
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold">Pembagian Tugas Guru Mengajar</h2>
                <p class="text-blue-100 text-sm mt-1">Kelola data beban mengajar per guru × mapel × kelas</p>
            </div>
        </div>
        <div class="text-right bg-white/10 backdrop-blur rounded-xl px-6 py-3">
            <p class="text-4xl font-bold">{{ $beban->total() }}</p>
            <p class="text-xs text-blue-100 font-medium uppercase tracking-wide">Total Data</p>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3">
        <div class="md:col-span-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="🔍 Cari nama guru/mapel/kelas..." 
                   class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
        </div>
        <div class="md:col-span-3">
            <select name="kelas_id" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                <option value="">📚 Semua Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-3">
            <select name="guru_id" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                <option value="">👨‍🏫 Semua Guru</option>
                @foreach($gurus as $g)
                    <option value="{{ $g->id }}" {{ request('guru_id') == $g->id ? 'selected' : '' }}>
                        {{ $g->kode }} - {{ $g->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2 flex gap-2">
            <button type="submit" class="btn-primary flex-1 justify-center text-sm">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('beban.index') }}" class="btn-secondary px-3" title="Reset">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider w-16">No</th>
                    <th class="px-4 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider w-16">Kode</th>
                    <th class="px-4 py-4 text-left font-semibold text-slate-600 text-xs uppercase tracking-wider">Nama Guru</th>
                    <th class="px-4 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider">Mapel</th>
                    <th class="px-4 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider">Kelas</th>
                    <th class="px-4 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider">JP</th>
                    <th class="px-4 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($beban as $index => $b)
                <tr class="hover:bg-blue-50/40 transition">
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 text-slate-600 rounded-full font-bold text-xs">
                            {{ $beban->firstItem() + $index }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-700 rounded-lg font-bold text-xs">
                            {{ $b->guru->kode }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <p class="font-semibold text-slate-800">{{ $b->guru->nama }}</p>
                        @if($b->guru->jabatan_tambahan)
                            <p class="text-xs text-purple-600 mt-0.5">{{ $b->guru->jabatan_tambahan }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs font-medium">
                            <span class="font-bold">{{ $b->mapel->kode }}</span>
                            <span>{{ $b->mapel->nama }}</span>
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center justify-center px-3 py-1.5 bg-purple-100 text-purple-700 rounded-lg font-bold text-xs">
                            {{ $b->kelas->nama }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="inline-flex items-baseline gap-1">
                            <span class="font-bold text-xl text-orange-600">{{ $b->jumlah_jp }}</span>
                            <span class="text-xs text-slate-500 font-medium">JP</span>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('beban.edit', $b->id) }}" 
                               class="w-8 h-8 flex items-center justify-center text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                               title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <form action="{{ route('beban.destroy', $b->id) }}" method="POST" 
                                  onsubmit="return confirm('Hapus beban ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-8 h-8 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-lg transition" 
                                        title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-16 text-center">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-inbox text-3xl text-slate-400"></i>
                        </div>
                        <p class="font-semibold text-slate-700 mb-1">Belum ada data beban mengajar</p>
                        <p class="text-sm text-slate-500 mb-4">Mulai dengan menambahkan beban mengajar</p>
                        <a href="{{ route('beban.create') }}" class="btn-primary">
                            <i class="fas fa-plus"></i> Tambah Data Pertama
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($beban->hasPages())
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        {{ $beban->appends(request()->query())->links() }}
    </div>
    @endif
</div>

{{-- Bulk Actions --}}
<div class="mt-4 flex gap-2 flex-wrap">
    <button onclick="showBulkImport()" class="btn-secondary text-sm">
        <i class="fas fa-file-import"></i> Bulk Import
    </button>
    <form action="{{ route('beban.reset') }}" method="POST" 
          onsubmit="return confirm('YAKIN hapus SEMUA data beban mengajar? Tindakan tidak bisa dibatalkan!');">
        @csrf
        <button type="submit" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition shadow-sm">
            <i class="fas fa-trash-alt"></i> Reset Semua
        </button>
    </form>
</div>

{{-- Modal Bulk Import --}}
<div id="modal-bulk" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-import text-blue-600"></i>
                </div>
                <h3 class="text-lg font-bold">Bulk Import Beban Mengajar</h3>
            </div>
            <button onclick="hideBulkImport()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-sm">
            <p class="font-semibold text-blue-800 mb-1">📌 Format Import:</p>
            <code class="text-xs bg-white px-2 py-1 rounded border border-blue-200 block mb-2">
                kode_guru|kode_mapel|kelas|jp
            </code>
            <p class="text-xs text-blue-700">Contoh:</p>
            <code class="text-xs bg-white px-2 py-1 rounded border border-blue-200 block">
                3|J|X.1|3<br>
                4|B|X.1|2<br>
                5|E|X.1|4
            </code>
        </div>
        
        <form action="{{ route('beban.bulk-import') }}" method="POST">
            @csrf
            <textarea name="data" rows="10" 
                      class="w-full px-4 py-3 border border-slate-200 rounded-lg font-mono text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="3|J|X.1|3&#10;4|B|X.1|2&#10;5|E|X.1|4"></textarea>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="hideBulkImport()" class="btn-secondary">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-upload"></i> Import Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showBulkImport() {
    document.getElementById('modal-bulk').classList.remove('hidden');
}
function hideBulkImport() {
    document.getElementById('modal-bulk').classList.add('hidden');
}
</script>

@endsection