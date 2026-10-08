@extends('layouts.app')

@section('title', 'Data Kelas')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-slate-800 font-semibold">Data Kelas</span>
    </div>
    <a href="{{ route('data.kelas.create') }}" class="btn-primary text-sm">
        <i class="fas fa-plus"></i> Tambah Kelas
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
<div class="bg-gradient-to-r from-purple-700 to-purple-600 rounded-2xl p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-3xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold">Data Kelas</h2>
                <p class="text-purple-100">Daftar seluruh rombongan belajar</p>
            </div>
        </div>
        <div class="flex gap-4">
            @foreach($groupedKelas as $tingkat => $items)
                <div class="bg-white/10 rounded-lg px-4 py-2 text-center">
                    <p class="text-2xl font-bold">{{ $items->count() }}</p>
                    <p class="text-xs text-purple-100">Kelas {{ $tingkat }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-4">
    <div class="flex gap-3">
        <input type="text" id="search-input" placeholder="Cari nama kelas..." 
               class="flex-1 px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-purple-500">
        <select id="filter-tingkat" class="px-4 py-2 border border-slate-200 rounded-lg">
            <option value="">Semua Tingkat</option>
            <option value="X">Kelas X</option>
            <option value="XI">Kelas XI</option>
            <option value="XII">Kelas XII</option>
        </select>
    </div>
</div>

{{-- Grid Kelas --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6" id="kelas-grid">
    @forelse($kelasList as $k)
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 card-hover kelas-card"
         data-nama="{{ strtolower($k['nama']) }}"
         data-tingkat="{{ $k['tingkat'] }}">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                    {{ $k['nama'] }}
                </div>
                <div>
                    <p class="text-xs text-slate-500">Tingkat</p>
                    <p class="font-bold text-slate-800">Kelas {{ $k['tingkat'] }}</p>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <span class="text-xs bg-purple-50 text-purple-700 px-3 py-1 rounded-full font-semibold">
                    {{ $k['total_jp'] }} JP
                </span>
                <a href="{{ route('data.kelas.edit', $k['id']) }}" 
                   class="text-blue-600 hover:text-blue-800 p-1" title="Edit">
                    <i class="fas fa-edit text-sm"></i>
                </a>
                <form action="{{ route('data.kelas.destroy', $k['id']) }}" method="POST" 
                      onsubmit="return confirm('Yakin hapus kelas: {{ $k['nama'] }}?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Hapus">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2 text-center">
            <div class="bg-slate-50 rounded-lg p-2">
                <p class="text-lg font-bold text-slate-800">{{ $k['jumlah_guru'] }}</p>
                <p class="text-xs text-slate-500">Guru</p>
            </div>
            <div class="bg-slate-50 rounded-lg p-2">
                <p class="text-lg font-bold text-slate-800">{{ $k['jumlah_mapel'] }}</p>
                <p class="text-xs text-slate-500">Mapel</p>
            </div>
            <div class="bg-slate-50 rounded-lg p-2">
                <p class="text-lg font-bold text-slate-800">{{ $k['total_slot'] }}</p>
                <p class="text-xs text-slate-500">Slot</p>
            </div>
        </div>

        @if($k['total_jp'] < $k['total_slot'])
            <div class="mt-3 text-xs text-orange-600 bg-orange-50 px-3 py-1 rounded">
                <i class="fas fa-exclamation-triangle"></i>
                Kurang {{ $k['total_slot'] - $k['total_jp'] }} JP dari {{ $k['total_slot'] }} slot
            </div>
        @elseif($k['total_jp'] > $k['total_slot'])
            <div class="mt-3 text-xs text-red-600 bg-red-50 px-3 py-1 rounded">
                <i class="fas fa-exclamation-circle"></i>
                Kelebihan {{ $k['total_jp'] - $k['total_slot'] }} JP
            </div>
        @else
            <div class="mt-3 text-xs text-green-600 bg-green-50 px-3 py-1 rounded">
                <i class="fas fa-check-circle"></i>
                JP Lengkap
            </div>
        @endif
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl p-12 text-center text-slate-400">
        <i class="fas fa-inbox text-4xl mb-2"></i>
        <p>Belum ada data kelas</p>
        <a href="{{ route('data.kelas.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
            + Tambah kelas pertama
        </a>
    </div>
    @endforelse
</div>

<div class="mt-6">
    <a href="{{ route('dashboard') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

@push('scripts')
<script>
    const searchInput = document.getElementById('search-input');
    const filterTingkat = document.getElementById('filter-tingkat');
    const cards = document.querySelectorAll('.kelas-card');

    function filterCards() {
        const search = searchInput.value.toLowerCase().trim();
        const tingkat = filterTingkat.value;

        cards.forEach(card => {
            const nama = card.dataset.nama;
            const rowTingkat = card.dataset.tingkat;

            const matchSearch = !search || nama.includes(search);
            const matchTingkat = !tingkat || rowTingkat === tingkat;

            card.style.display = (matchSearch && matchTingkat) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterCards);
    filterTingkat.addEventListener('change', filterCards);
</script>
@endpush

@endsection