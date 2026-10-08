<?php

namespace App\Http\Controllers;

use App\Models\BebanMengajar;
use App\Models\Guru;
use App\Models\HasilOptimasi;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\SlotWaktu;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_guru' => Guru::count(),
            'guru_pengajar' => Guru::where('is_bk', false)->count(),
            'guru_bk' => Guru::where('is_bk', true)->count(),
            'total_mapel' => Mapel::count(),
            'total_kelas' => Kelas::count(),
            'total_jp' => BebanMengajar::sum('jumlah_jp'),
            'total_slot' => SlotWaktu::where('is_istirahat', false)->count(),
        ];

        $riwayat = HasilOptimasi::orderByDesc('created_at')->limit(10)->get();

        return view('dashboard', compact('stats', 'riwayat'));
    }
}
