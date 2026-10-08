@extends('layouts.app')

@section('title', 'Hasil Optimasi')

@section('content')
<h2 class="text-2xl font-bold mb-6 text-gray-800">📊 Hasil Optimasi</h2>

@foreach ($hasil as $kode => $h)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold">
                <span class="px-3 py-1 rounded {{ $kode === 'GA' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">{{ $kode }}</span>
            </h3>
            @if (isset($hasilIds[$kode]))
                <a href="{{ route('cetak.jadwal', $hasilIds[$kode]) }}" target="_blank" rel="noopener" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold">
                    🖨️ Cetak A4
                </a>
            @endif
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-500">Fitness</p>
                <p class="text-xl font-bold">{{ number_format($h['fitness_terbaik'], 0) }}</p>
            </div>
            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-500">Waktu (s)</p>
                <p class="text-xl font-bold">{{ number_format($h['waktu_komputasi'], 2) }}</p>
            </div>
            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-500">Iterasi Konvergen</p>
                <p class="text-xl font-bold">{{ $h['iterasi_konvergen'] }}</p>
            </div>
            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-500">Pelanggaran Hard</p>
                <p class="text-xl font-bold text-red-600">{{ $h['pelanggaran_hard'] }}</p>
            </div>
            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-500">Pelanggaran Soft</p>
                <p class="text-xl font-bold text-yellow-600">{{ $h['pelanggaran_soft'] }}</p>
            </div>
        </div>

        <canvas id="chart-{{ $kode }}" height="80"></canvas>
    </div>
@endforeach

<div class="text-center">
    <a href="{{ route('optimasi.index') }}" class="text-blue-600 hover:underline">← Kembali</a>
</div>
@endsection

@push('scripts')
<script>
@foreach ($hasil as $kode => $h)
{
    const log = @json($h['log_konvergensi']);
    const labels = log.map((item) => item.generasi ?? item.iterasi);
    const data = log.map((item) => item.fitness_terbaik);

    new Chart(document.getElementById('chart-{{ $kode }}'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Fitness Terbaik - {{ $kode }}',
                data: data,
                borderColor: '{{ $kode === "GA" ? "#2563eb" : "#ea580c" }}',
                backgroundColor: 'rgba(0,0,0,0)',
                tension: 0.3,
                pointRadius: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Kurva Konvergensi {{ $kode }}' }
            },
            scales: {
                x: { title: { display: true, text: 'Iterasi/Generasi' } },
                y: { title: { display: true, text: 'Fitness' } }
            }
        }
    });
}
@endforeach
</script>
@endpush
