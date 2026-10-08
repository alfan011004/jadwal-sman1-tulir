@extends('layouts.app')

@section('title', 'Data Mata Pelajaran')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-slate-800 font-semibold">Data Mata Pelajaran</span>
    </div>
    <a href="{{ route('data.mapel.create') }}" class="btn-primary text-sm">
        <i class="fas fa-plus"></i> Tambah Mapel
    </a>
</div>

{{-- Flash Message --}}
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 mb-4 flex items-center gap-3">
        <i class="fas fa-check-circle text-green-600 text-xl"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 mb-4 flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
        {{ session('error') }}
    </div>
@endif

{{-- Header --}}
<div class="bg-gradient-to-r from-green-700 to-green-600 rounded-2xl p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-book text-3xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold">Data Mata Pelajaran</h2>
                <p class="text-green-100">Daftar seluruh mapel Kurikulum Merdeka</p>
            </div>
        </div>
        <div class="flex gap-4">
            <div class="bg-white/10 rounded-lg px-4 py-2 text-center">
                <p class="text-2xl font-bold">{{ $totalMapelRingan }}</p>
                <p class="text-xs text-green-100">Mapel Ringan</p>
            </div>
            <div class="bg-white/10 rounded-lg px-4 py-2 text-center">
                <p class="text-2xl font-bold">{{ $totalMapelBerat }}</p>
                <p class="text-xs text-green-100">Mapel Berat</p>
            </div>
        </div>
    </div>
</div>

{{-- Search & Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-4">
    <div class="flex gap-3">
        <input type="text" id="search-input" placeholder="Cari kode atau nama mapel..." 
               class="flex-1 px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
        <select id="filter-berat" class="px-4 py-2 border border-slate-200 rounded-lg">
            <option value="">Semua Mapel</option>
            <option value="berat">Mapel Berat</option>
            <option value="ringan">Mapel Ringan</option>
        </select>
    </div>
</div>

{{-- Tabel Mapel --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Kode</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Nama Mapel</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Jumlah Guru</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Jumlah Kelas</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Total JP</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Kategori</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody id="mapel-tbody" class="divide-y divide-slate-100">
                @forelse($mapels as $m)
                <tr class="hover:bg-slate-50 transition mapel-row"
                    data-nama="{{ strtolower($m['nama']) }}"
                    data-kode="{{ strtolower($m['kode']) }}"
                    data-berat="{{ $m['is_mapel_berat'] ? 'berat' : 'ringan' }}">
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-700 rounded-lg font-bold text-xs">
                            {{ $m['kode'] }}
                        </span>
                    </td>
                    <td class="px-4 py-3 font-semibold text-slate-800">{{ $m['nama'] }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-slate-700 font-semibold">{{ $m['jumlah_guru'] }}</span>
                        <span class="text-xs text-slate-500"> guru</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-slate-700 font-semibold">{{ $m['jumlah_kelas'] }}</span>
                        <span class="text-xs text-slate-500"> kelas</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-bold text-lg text-orange-600">{{ $m['total_jp'] }}</span>
                        <span class="text-xs text-slate-500">JP</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($m['is_mapel_berat'])
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                <i class="fas fa-fire"></i> Berat
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-semibold">
                                Ringan
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('data.mapel.edit', $m['id']) }}" 
                               class="text-blue-600 hover:text-blue-800 p-2" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('data.mapel.destroy', $m['id']) }}" method="POST" 
                                  onsubmit="return confirm('Yakin hapus mapel: {{ $m['nama'] }}?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 p-2" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>Belum ada data mapel</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Back Button --}}
<div class="mt-6">
    <a href="{{ route('dashboard') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

@push('scripts')
<script>
    const searchInput = document.getElementById('search-input');
    const filterBerat = document.getElementById('filter-berat');
    const rows = document.querySelectorAll('.mapel-row');

    function filterTable() {
        const search = searchInput.value.toLowerCase().trim();
        const berat = filterBerat.value;

        rows.forEach(row => {
            const nama = row.dataset.nama;
            const kode = row.dataset.kode;
            const rowBerat = row.dataset.berat;

            const matchSearch = !search || nama.includes(search) || kode.includes(search);
            const matchBerat = !berat || rowBerat === berat;

            row.style.display = (matchSearch && matchBerat) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterBerat.addEventListener('change', filterTable);
</script>
@endpush

@endsection