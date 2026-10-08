@extends('layouts.app')

@section('title', 'Data Beban Mengajar')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-slate-800 font-semibold">Data Beban Mengajar</span>
</div>

{{-- Header --}}
<div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-2xl p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-3xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold">Beban Mengajar</h2>
                <p class="text-orange-100">Total JP per guru × kelas × mapel</p>
            </div>
        </div>
        <div class="flex gap-4">
            <div class="bg-white/10 rounded-lg px-4 py-2 text-center">
                <p class="text-2xl font-bold">{{ $totalGuru }}</p>
                <p class="text-xs text-orange-100">Guru</p>
            </div>
            <div class="bg-white/10 rounded-lg px-4 py-2 text-center">
                <p class="text-2xl font-bold">{{ $totalMapel }}</p>
                <p class="text-xs text-orange-100">Mapel</p>
            </div>
            <div class="bg-white/10 rounded-lg px-4 py-2 text-center">
                <p class="text-2xl font-bold">{{ $totalJp }}</p>
                <p class="text-xs text-orange-100">Total JP</p>
            </div>
        </div>
    </div>
</div>

{{-- Search --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-4">
    <input type="text" id="search-input" placeholder="Cari nama guru, mapel, atau kelas..." 
           class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
</div>

{{-- Tabel Beban --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Kode Guru</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Nama Guru</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Mapel</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Kelas</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Jumlah JP</th>
                </tr>
            </thead>
            <tbody id="beban-tbody" class="divide-y divide-slate-100">
                @foreach($beban as $b)
                <tr class="hover:bg-slate-50 transition beban-row"
                    data-search="{{ strtolower($b['guru_nama'] . ' ' . $b['mapel_nama'] . ' ' . $b['kelas_nama']) }}">
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 text-orange-700 rounded-lg font-bold text-xs">
                            {{ $b['guru_kode'] }}
                        </span>
                    </td>
                    <td class="px-4 py-3 font-semibold text-slate-800">{{ $b['guru_nama'] }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 text-green-700 rounded text-xs">
                            <span class="font-bold">{{ $b['mapel_kode'] }}</span>
                            <span>{{ $b['mapel_nama'] }}</span>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center justify-center w-12 h-7 bg-purple-100 text-purple-700 rounded font-bold text-xs">
                            {{ $b['kelas_nama'] }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-bold text-lg text-orange-600">{{ $b['jumlah_jp'] }}</span>
                        <span class="text-xs text-slate-500">JP</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('dashboard') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

@push('scripts')
<script>
    const searchInput = document.getElementById('search-input');
    const rows = document.querySelectorAll('.beban-row');

    searchInput.addEventListener('input', function() {
        const search = this.value.toLowerCase().trim();
        rows.forEach(row => {
            const data = row.dataset.search;
            row.style.display = !search || data.includes(search) ? '' : 'none';
        });
    });
</script>
@endpush

@endsection