@extends('layouts.app')

@section('title', 'Perbandingan GA vs SA')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 font-medium">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-slate-800 font-semibold">Perbandingan GA vs SA</span>
</div>

{{-- Header --}}
<div class="relative bg-gradient-to-br from-purple-700 via-purple-600 to-indigo-700 rounded-3xl p-8 text-white mb-6 shadow-xl overflow-hidden">
    <div class="absolute top-0 right-0 text-[150px] opacity-10 -mr-8 -mt-8">
        <i class="fas fa-balance-scale"></i>
    </div>
    <div class="relative flex items-center gap-5">
        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
            <i class="fas fa-balance-scale text-3xl"></i>
        </div>
        <div>
            <h2 class="text-2xl lg:text-3xl font-bold">Perbandingan GA vs SA</h2>
            <p class="text-purple-100 text-sm mt-1">Analisis performa Algoritma Genetika vs Simulated Annealing</p>
        </div>
    </div>
</div>

@if($data->isEmpty())
    {{-- EMPTY STATE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-16 text-center">
        <div class="w-28 h-28 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-chart-bar text-5xl text-purple-500"></i>
        </div>
        <h3 class="text-2xl font-bold text-slate-800 mb-2">Belum Ada Data Perbandingan</h3>
        <p class="text-slate-500 mb-8 max-w-md mx-auto leading-relaxed">
            Untuk melihat perbandingan, Anda perlu menjalankan optimasi dengan memilih 
            <strong>Keduanya (GA & SA)</strong> agar sistem bisa menganalisis perbedaan performa.
        </p>
        
        <div class="grid grid-cols-3 gap-4 max-w-2xl mx-auto mb-8">
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                <i class="fas fa-dna text-blue-600 text-2xl mb-2"></i>
                <p class="font-bold text-slate-800 text-sm">GA</p>
                <p class="text-xs text-slate-500 mt-1">Optimasi evolusi</p>
            </div>
            <div class="bg-orange-50 rounded-xl p-4 border border-orange-100">
                <i class="fas fa-fire text-orange-600 text-2xl mb-2"></i>
                <p class="font-bold text-slate-800 text-sm">SA</p>
                <p class="text-xs text-slate-500 mt-1">Optimasi termal</p>
            </div>
            <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                <i class="fas fa-balance-scale text-purple-600 text-2xl mb-2"></i>
                <p class="font-bold text-slate-800 text-sm">BOTH</p>
                <p class="text-xs text-slate-500 mt-1">Bandingkan</p>
            </div>
        </div>

        <a href="{{ route('optimasi.index') }}" class="btn-primary text-base px-8 py-3">
            <i class="fas fa-rocket"></i> Mulai Optimasi Sekarang
        </a>
    </div>
@else
    {{-- TABEL PERBANDINGAN --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-table text-slate-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Tabel Perbandingan</h3>
                    <p class="text-xs text-slate-500">Hasil optimasi terbaru</p>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-slate-600 text-xs uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider">Algoritma</th>
                        <th class="px-6 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider">Fitness</th>
                        <th class="px-6 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider">Waktu (s)</th>
                        <th class="px-6 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider">Iterasi Konvergen</th>
                        <th class="px-6 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider">Pel. Hard</th>
                        <th class="px-6 py-4 text-center font-semibold text-slate-600 text-xs uppercase tracking-wider">Pel. Soft</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($data as $d)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-slate-600 text-xs font-medium">{{ $d->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold
                                {{ $d->algoritma === 'GA' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                                <i class="fas fa-{{ $d->algoritma === 'GA' ? 'dna' : 'fire' }}"></i>
                                {{ $d->algoritma }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-mono font-bold text-slate-800">
                            {{ number_format($d->fitness_terbaik, 0) }}
                        </td>
                        <td class="px-6 py-4 text-center text-slate-600">{{ number_format($d->waktu_komputasi, 2) }}s</td>
                        <td class="px-6 py-4 text-center text-slate-600">{{ $d->iterasi_konvergen }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-7 rounded-lg bg-red-50 text-red-600 font-bold text-xs">
                                {{ $d->pelanggaran_hard }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-7 rounded-lg bg-yellow-50 text-yellow-600 font-bold text-xs">
                                {{ $d->pelanggaran_soft }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- GRAFIK --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-slate-600"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Grafik Perbandingan</h3>
                <p class="text-xs text-slate-500">Visualisasi performa GA vs SA</p>
            </div>
        </div>
        <div style="height: 400px;">
            <canvas id="chart-banding"></canvas>
        </div>
    </div>
@endif

@endsection

@push('scripts')
@if(!$data->isEmpty())
<script>
const data = @json($data);
const labels = data.map(d => d.algoritma + ' #' + d.id);
const fitness = data.map(d => d.fitness_terbaik);
const waktu = data.map(d => d.waktu_komputasi);

new Chart(document.getElementById('chart-banding'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Fitness',
                data: fitness,
                backgroundColor: data.map(d => d.algoritma === 'GA' ? '#3b82f6' : '#f97316'),
                borderRadius: 8,
                yAxisID: 'y'
            },
            {
                label: 'Waktu (detik)',
                data: waktu,
                type: 'line',
                borderColor: '#16a34a',
                backgroundColor: '#16a34a',
                borderWidth: 3,
                tension: 0.3,
                pointRadius: 6,
                pointBackgroundColor: '#16a34a',
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { 
                position: 'top',
                labels: { font: { family: 'Inter', size: 12, weight: '600' }, usePointStyle: true }
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                padding: 12,
                titleFont: { family: 'Inter', size: 13 },
                bodyFont: { family: 'Inter', size: 12 }
            }
        },
        scales: {
            y: { 
                position: 'left', 
                title: { display: true, text: 'Fitness', font: { family: 'Inter', weight: '600' } },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            y1: { 
                position: 'right', 
                title: { display: true, text: 'Waktu (detik)', font: { family: 'Inter', weight: '600' } }, 
                grid: { drawOnChartArea: false }
            }
        }
    }
});
</script>
@endif
@endpush