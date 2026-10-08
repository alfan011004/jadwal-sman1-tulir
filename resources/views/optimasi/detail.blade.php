@extends('layouts.app')

@section('title', 'Detail Optimasi #' . $hasil->id)

@section('content')

{{-- ⭐ BREADCRUMB & BACK ⭐ --}}
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-slate-800 font-semibold">Detail Optimasi #{{ $hasil->id }}</span>
    </div>
    <a href="{{ route('dashboard') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

{{-- ⭐ HEADER CARD ⭐ --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6">
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl text-white shadow-lg
                {{ $hasil->algoritma === 'GA' 
                    ? 'bg-gradient-to-br from-blue-500 to-blue-700' 
                    : 'bg-gradient-to-br from-orange-500 to-red-600' }}">
                <i class="fas fa-{{ $hasil->algoritma === 'GA' ? 'dna' : 'fire' }}"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">
                    Hasil Optimasi — {{ $hasil->algoritma }}
                </h2>
                <p class="text-sm text-slate-500">
                    <i class="far fa-clock mr-1"></i>
                    {{ $hasil->created_at->format('d F Y, H:i:s') }} WIB
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('cetak.jadwal', $hasil->id) }}" 
               target="_blank"
               class="btn-primary">
                <i class="fas fa-print"></i> Cetak Jadwal
            </a>
            <form action="{{ route('optimasi.hapus', $hasil->id) }}" 
                  method="POST" 
                  onsubmit="return confirm('Yakin hapus riwayat ini? Tindakan tidak bisa dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ⭐ METRIK CARDS ⭐ --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    
    <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                <i class="fas fa-star text-blue-600"></i>
            </div>
            <span class="text-xs font-semibold text-slate-500 uppercase">Fitness</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ number_format($hasil->fitness_terbaik, 0) }}</p>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center">
                <i class="fas fa-stopwatch text-green-600"></i>
            </div>
            <span class="text-xs font-semibold text-slate-500 uppercase">Waktu</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">
            {{ number_format($hasil->waktu_komputasi, 2) }}<span class="text-base text-slate-500">s</span>
        </p>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 rounded-lg bg-purple-100 flex items-center justify-center">
                <i class="fas fa-redo text-purple-600"></i>
            </div>
            <span class="text-xs font-semibold text-slate-500 uppercase">Konvergen</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $hasil->iterasi_konvergen }}</p>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <span class="text-xs font-semibold text-slate-500 uppercase">Hard</span>
        </div>
        <p class="text-2xl font-bold {{ $hasil->pelanggaran_hard > 0 ? 'text-red-600' : 'text-green-600' }}">
            {{ $hasil->pelanggaran_hard }}
        </p>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 rounded-lg bg-yellow-100 flex items-center justify-center">
                <i class="fas fa-info-circle text-yellow-600"></i>
            </div>
            <span class="text-xs font-semibold text-slate-500 uppercase">Soft</span>
        </div>
        <p class="text-2xl font-bold text-yellow-600">{{ $hasil->pelanggaran_soft }}</p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6 mb-6">
    
    {{-- ⭐ GRAFIK KONVERGENSI ⭐ --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-line text-blue-600"></i>
            Kurva Konvergensi
        </h3>
        <div style="height: 320px;">
            <canvas id="chart-konvergensi"></canvas>
        </div>
    </div>

    {{-- ⭐ PARAMETER ⭐ --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-sliders-h text-slate-600"></i>
            Parameter {{ $hasil->algoritma }}
        </h3>
        <div class="space-y-3">
            @foreach($hasil->parameter as $key => $value)
                <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0">
                    <span class="text-sm text-slate-500">
                        {{ ucwords(str_replace('_', ' ', $key)) }}
                    </span>
                    <span class="font-mono font-semibold text-slate-800">
                        {{ is_numeric($value) ? number_format($value, 2) : $value }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ⭐ PREVIEW JADWAL ⭐ --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-slate-800 flex items-center gap-2">
            <i class="fas fa-table text-slate-600"></i>
            Preview Jadwal
        </h3>
        <a href="{{ route('cetak.jadwal', $hasil->id) }}" 
           target="_blank"
           class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
            <i class="fas fa-external-link-alt"></i> Buka di tab baru
        </a>
    </div>
    
    <div class="bg-slate-50 rounded-lg p-8 text-center border-2 border-dashed border-slate-200">
        <i class="fas fa-file-pdf text-5xl text-slate-300 mb-3"></i>
        <p class="text-sm text-slate-500 mb-4">
            Klik tombol di bawah untuk melihat & mencetak jadwal lengkap dalam format A4
        </p>
        <a href="{{ route('cetak.jadwal', $hasil->id) }}" 
           target="_blank"
           class="btn-primary inline-flex">
            <i class="fas fa-print"></i> Buka Preview Cetak A4
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const log = @json($hasil->log_konvergensi);
    const isGA = '{{ $hasil->algoritma }}' === 'GA';
    
    const labels = log.map(l => l.generasi ?? l.iterasi);
    const data = log.map(l => l.fitness_terbaik);
    
    const warnaUtama = isGA ? '#2563eb' : '#ea580c';
    const warnaGradien = isGA 
        ? 'rgba(37, 99, 235, 0.15)' 
        : 'rgba(234, 88, 12, 0.15)';
    
    const ctx = document.getElementById('chart-konvergensi').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 320);
    gradient.addColorStop(0, warnaGradien);
    gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Fitness Terbaik — {{ $hasil->algoritma }}',
                data: data,
                borderColor: warnaUtama,
                backgroundColor: gradient,
                borderWidth: 2,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: warnaUtama,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        font: { family: 'Inter', weight: '600' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { family: 'Inter', size: 13, weight: '700' },
                    bodyFont: { family: 'Inter', size: 12 },
                    callbacks: {
                        label: function(context) {
                            return 'Fitness: ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: { 
                        display: true, 
                        text: isGA ? 'Generasi' : 'Iterasi',
                        font: { family: 'Inter', weight: '600' }
                    },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: { font: { family: 'Inter' }, maxTicksLimit: 10 }
                },
                y: {
                    title: { 
                        display: true, 
                        text: 'Nilai Fitness',
                        font: { family: 'Inter', weight: '600' }
                    },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: { 
                        font: { family: 'Inter' },
                        callback: function(value) {
                            return value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush