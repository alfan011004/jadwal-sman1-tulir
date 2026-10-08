@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-slate-800 font-semibold">Data Guru</span>
    </div>
    <a href="{{ route('data.guru.create') }}" class="btn-primary text-sm">
        <i class="fas fa-plus"></i> Tambah Guru
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
<div class="bg-gradient-to-r from-blue-700 to-blue-600 rounded-2xl p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-chalkboard-teacher text-3xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold">Data Guru</h2>
                <p class="text-blue-100">Daftar seluruh guru pengajar & BK</p>
            </div>
        </div>
        <div class="flex gap-4">
            <div class="bg-white/10 rounded-lg px-4 py-2 text-center">
                <p class="text-2xl font-bold">{{ $totalPengajar }}</p>
                <p class="text-xs text-blue-100">Pengajar</p>
            </div>
            <div class="bg-white/10 rounded-lg px-4 py-2 text-center">
                <p class="text-2xl font-bold">{{ $totalBk }}</p>
                <p class="text-xs text-blue-100">Guru BK</p>
            </div>
            <div class="bg-white/10 rounded-lg px-4 py-2 text-center">
                <p class="text-2xl font-bold">{{ $totalJp }}</p>
                <p class="text-xs text-blue-100">Total JP</p>
            </div>
        </div>
    </div>
</div>

{{-- Search & Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-4">
    <div class="flex gap-3">
        <input type="text" id="search-input" placeholder="Cari nama, kode, atau NIP..." 
               class="flex-1 px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <select id="filter-status" class="px-4 py-2 border border-slate-200 rounded-lg">
            <option value="">Semua</option>
            <option value="pengajar">Pengajar</option>
            <option value="bk">Guru BK</option>
        </select>
    </div>
</div>

{{-- Tabel Guru --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Kode</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Nama</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">NIP</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Jabatan</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Mapel</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Total JP</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Status</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody id="guru-tbody" class="divide-y divide-slate-100">
                @forelse($gurus as $g)
                <tr class="hover:bg-slate-50 transition guru-row" 
                    data-nama="{{ strtolower($g['nama']) }}"
                    data-kode="{{ strtolower($g['kode']) }}"
                    data-nip="{{ strtolower($g['nip'] ?? '') }}"
                    data-status="{{ $g['is_bk'] ? 'bk' : 'pengajar' }}">
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 rounded-lg font-bold text-xs">
                            {{ $g['kode'] }}
                        </span>
                    </td>
                    <td class="px-4 py-3 font-semibold text-slate-800">{{ $g['nama'] }}</td>
                    <td class="px-4 py-3 text-slate-600 font-mono text-xs">{{ $g['nip'] ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($g['jabatan'])
                            <span class="text-xs bg-purple-50 text-purple-700 px-2 py-1 rounded-full">
                                {{ $g['jabatan'] }}
                            </span>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-xs text-slate-600">
                            {{ $g['jumlah_mapel'] }} mapel · {{ $g['jumlah_kelas'] }} kelas
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-bold text-lg text-orange-600">{{ $g['total_jp'] }}</span>
                        <span class="text-xs text-slate-500">JP</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($g['is_bk'])
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">
                                <i class="fas fa-hands-helping"></i> BK
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                <i class="fas fa-check"></i> Pengajar
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('data.guru.edit', $g['id']) }}" 
                               class="text-blue-600 hover:text-blue-800 p-2" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('data.guru.destroy', $g['id']) }}" method="POST" 
                                  onsubmit="return confirm('Yakin hapus guru: {{ $g['nama'] }}?');" class="inline">
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
                    <td colspan="8" class="px-4 py-12 text-center text-slate-400">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>Belum ada data guru</p>
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
    const filterStatus = document.getElementById('filter-status');
    const rows = document.querySelectorAll('.guru-row');

    function filterTable() {
        const search = searchInput.value.toLowerCase().trim();
        const status = filterStatus.value;

        rows.forEach(row => {
            const nama = row.dataset.nama;
            const kode = row.dataset.kode;
            const nip = row.dataset.nip;
            const rowStatus = row.dataset.status;

            const matchSearch = !search || 
                nama.includes(search) || 
                kode.includes(search) || 
                nip.includes(search);

            const matchStatus = !status || rowStatus === status;

            row.style.display = (matchSearch && matchStatus) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterStatus.addEventListener('change', filterTable);
</script>
@endpush

@endsection