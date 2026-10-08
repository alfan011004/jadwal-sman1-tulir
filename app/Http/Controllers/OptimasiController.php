<?php

namespace App\Http\Controllers;

use App\Models\HasilOptimasi;
use App\Services\GeneticAlgorithmService;
use App\Services\SimulatedAnnealingService;
use Illuminate\Http\Request;

class OptimasiController extends Controller
{
    public function index()
    {
        return view('optimasi.index');
    }

    public function jalankan(Request $request)
    {
        $request->validate([
            'algoritma' => 'required|in:GA,SA,BOTH',
        ]);

        set_time_limit(600);
        $hasil = [];

        if (in_array($request->algoritma, ['GA', 'BOTH'])) {
            $ga = new GeneticAlgorithmService(
                populasiSize:  (int) $request->input('ga_populasi', 50),
                generasi:      (int) $request->input('ga_generasi', 200),
                crossoverRate: (float) $request->input('ga_crossover', 0.85),
                mutationRate:  (float) $request->input('ga_mutation', 0.15),
            );
            $hasil['GA'] = $ga->jalankan();
            HasilOptimasi::create($hasil['GA']);
        }

        if (in_array($request->algoritma, ['SA', 'BOTH'])) {
            $sa = new SimulatedAnnealingService(
                suhuAwal:       (float) $request->input('sa_suhu', 1000.0),
                coolingRate:    (float) $request->input('sa_cooling', 0.95),
                iterasiPerSuhu: (int) $request->input('sa_iterasi', 50),
            );
            $hasil['SA'] = $sa->jalankan();
            HasilOptimasi::create($hasil['SA']);
        }

        return view('optimasi.hasil', compact('hasil'));
    }

    public function perbandingan()
    {
        $data = HasilOptimasi::orderByDesc('created_at')->limit(20)->get();
        return view('optimasi.perbandingan', compact('data'));
    }

    public function detail($id)
    {
        $hasil = HasilOptimasi::findOrFail($id);
        return view('optimasi.detail', compact('hasil'));
    }

    public function hapus($id)
    {
        $hasil = HasilOptimasi::findOrFail($id);
        $hasil->delete();
        return redirect()->route('dashboard')
            ->with('success', 'Riwayat optimasi berhasil dihapus.');
    }
}