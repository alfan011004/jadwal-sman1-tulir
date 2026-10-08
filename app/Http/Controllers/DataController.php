<?php

namespace App\Http\Controllers;

use App\Models\{Guru, Mapel, Kelas, BebanMengajar, SlotWaktu};
use Illuminate\Http\Request;

class DataController extends Controller
{
    // =====================================================
    // GURU
    // =====================================================

    public function guru()
    {
        $gurus = Guru::with(['bebanMengajars.mapel', 'bebanMengajars.kelas'])
            ->orderByRaw('CAST(kode AS UNSIGNED) ASC')
            ->get()
            ->map(function ($guru) {
                return [
                    'id'           => $guru->id,
                    'kode'         => $guru->kode,
                    'nama'         => $guru->nama,
                    'nip'          => $guru->nip,
                    'is_bk'        => $guru->is_bk,
                    'jabatan'      => $guru->jabatan_tambahan,
                    'total_jp'     => $guru->bebanMengajars->sum('jumlah_jp'),
                    'jumlah_mapel' => $guru->bebanMengajars->unique('mapel_id')->count(),
                    'jumlah_kelas' => $guru->bebanMengajars->unique('kelas_id')->count(),
                    'mapel_list'   => $guru->bebanMengajars->pluck('mapel.nama')->unique()->values(),
                ];
            });

        $totalPengajar = $gurus->where('is_bk', false)->count();
        $totalBk       = $gurus->where('is_bk', true)->count();
        $totalJp       = $gurus->sum('total_jp');

        return view('data.guru', compact('gurus', 'totalPengajar', 'totalBk', 'totalJp'));
    }

    public function guruCreate()
    {
        $lastKode = Guru::orderByRaw('CAST(kode AS UNSIGNED) DESC')->first();
        $nextKode = $lastKode ? (int) $lastKode->kode + 1 : 1;

        return view('data.guru-form', ['guru' => null, 'nextKode' => $nextKode]);
    }

    public function guruStore(Request $request)
    {
        $validated = $request->validate([
            'kode'             => 'required|string|max:5|unique:gurus,kode',
            'nama'             => 'required|string|max:100',
            'nip'              => 'nullable|string|max:30',
            'jabatan_tambahan' => 'nullable|string|max:100',
            'is_bk'            => 'boolean',
        ]);
        $validated['is_bk'] = $request->has('is_bk');
        Guru::create($validated);

        return redirect()->route('data.guru')
            ->with('success', "Guru '{$validated['nama']}' berhasil ditambahkan.");
    }

    public function guruEdit($id)
    {
        $guru = Guru::findOrFail($id);
        return view('data.guru-form', ['guru' => $guru, 'nextKode' => null]);
    }

    public function guruUpdate(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        $validated = $request->validate([
            'kode'             => 'required|string|max:5|unique:gurus,kode,' . $id,
            'nama'             => 'required|string|max:100',
            'nip'              => 'nullable|string|max:30',
            'jabatan_tambahan' => 'nullable|string|max:100',
            'is_bk'            => 'boolean',
        ]);
        $validated['is_bk'] = $request->has('is_bk');
        $guru->update($validated);

        return redirect()->route('data.guru')
            ->with('success', "Guru '{$guru->nama}' berhasil diupdate.");
    }

    public function guruDestroy($id)
    {
        $guru = Guru::findOrFail($id);
        $bebanCount = BebanMengajar::where('guru_id', $id)->count();
        if ($bebanCount > 0) {
            return redirect()->route('data.guru')
                ->with('error', "Guru '{$guru->nama}' tidak bisa dihapus karena memiliki {$bebanCount} beban mengajar.");
        }
        $nama = $guru->nama;
        $guru->delete();
        return redirect()->route('data.guru')
            ->with('success', "Guru '{$nama}' berhasil dihapus.");
    }

    // =====================================================
    // MAPEL
    // =====================================================

    public function mapel()
    {
        $mapels = Mapel::with(['bebanMengajars.guru', 'bebanMengajars.kelas'])
            ->orderBy('kode')
            ->get()
            ->map(function ($mapel) {
                $gurusPengajar = $mapel->bebanMengajars->pluck('guru')->unique('id')->filter();
                $kelasList     = $mapel->bebanMengajars->pluck('kelas')->unique('id')->filter();
                return [
                    'id'             => $mapel->id,
                    'kode'           => $mapel->kode,
                    'nama'           => $mapel->nama,
                    'is_mapel_berat' => $mapel->is_mapel_berat,
                    'total_jp'       => $mapel->bebanMengajars->sum('jumlah_jp'),
                    'jumlah_guru'    => $gurusPengajar->count(),
                    'jumlah_kelas'   => $kelasList->count(),
                ];
            });

        $totalMapelBerat  = $mapels->where('is_mapel_berat', true)->count();
        $totalMapelRingan = $mapels->where('is_mapel_berat', false)->count();

        return view('data.mapel', compact('mapels', 'totalMapelBerat', 'totalMapelRingan'));
    }

    public function mapelCreate()
    {
        $usedKodes = Mapel::pluck('kode')->toArray();
        $alphabet = range('A', 'Z');
        $nextKode = 'A';
        foreach ($alphabet as $letter) {
            if (!in_array($letter, $usedKodes)) {
                $nextKode = $letter;
                break;
            }
        }
        return view('data.mapel-form', ['mapel' => null, 'nextKode' => $nextKode]);
    }

    public function mapelStore(Request $request)
    {
        $validated = $request->validate([
            'kode'           => 'required|string|max:5|unique:mapels,kode',
            'nama'           => 'required|string|max:100',
            'is_mapel_berat' => 'boolean',
        ]);
        $validated['kode'] = strtoupper($validated['kode']);
        $validated['is_mapel_berat'] = $request->has('is_mapel_berat');
        Mapel::create($validated);
        return redirect()->route('data.mapel')
            ->with('success', "Mapel '{$validated['nama']}' berhasil ditambahkan.");
    }

    public function mapelEdit($id)
    {
        $mapel = Mapel::findOrFail($id);
        return view('data.mapel-form', ['mapel' => $mapel, 'nextKode' => null]);
    }

    public function mapelUpdate(Request $request, $id)
    {
        $mapel = Mapel::findOrFail($id);
        $validated = $request->validate([
            'kode'           => 'required|string|max:5|unique:mapels,kode,' . $id,
            'nama'           => 'required|string|max:100',
            'is_mapel_berat' => 'boolean',
        ]);
        $validated['kode'] = strtoupper($validated['kode']);
        $validated['is_mapel_berat'] = $request->has('is_mapel_berat');
        $mapel->update($validated);
        return redirect()->route('data.mapel')
            ->with('success', "Mapel '{$mapel->nama}' berhasil diupdate.");
    }

    public function mapelDestroy($id)
    {
        $mapel = Mapel::findOrFail($id);
        $bebanCount = BebanMengajar::where('mapel_id', $id)->count();
        if ($bebanCount > 0) {
            return redirect()->route('data.mapel')
                ->with('error', "Mapel '{$mapel->nama}' tidak bisa dihapus karena memiliki {$bebanCount} beban mengajar.");
        }
        $nama = $mapel->nama;
        $mapel->delete();
        return redirect()->route('data.mapel')
            ->with('success', "Mapel '{$nama}' berhasil dihapus.");
    }

    // =====================================================
    // KELAS
    // =====================================================

    public function kelas()
    {
        $totalSlot = SlotWaktu::where('is_istirahat', false)->count();

        $kelasList = Kelas::with(['bebanMengajars.guru', 'bebanMengajars.mapel'])
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get()
            ->map(function ($k) use ($totalSlot) {
                return [
                    'id'          => $k->id,
                    'nama'        => $k->nama,
                    'tingkat'     => $k->tingkat,
                    'total_jp'    => $k->bebanMengajars->sum('jumlah_jp'),
                    'total_slot'  => $totalSlot,
                    'jumlah_guru' => $k->bebanMengajars->unique('guru_id')->count(),
                    'jumlah_mapel'=> $k->bebanMengajars->unique('mapel_id')->count(),
                ];
            });

        $groupedKelas = $kelasList->groupBy('tingkat');

        return view('data.kelas', compact('kelasList', 'groupedKelas'));
    }

    /**
     * Form tambah kelas
     */
    public function kelasCreate()
    {
        // Auto-suggest nama berikutnya per tingkat
        $tingkatNext = [];
        foreach (['X', 'XI', 'XII'] as $tingkat) {
            $kelasTingkat = Kelas::where('tingkat', $tingkat)->get();
            $maxNum = 0;
            foreach ($kelasTingkat as $k) {
                // Extract number dari nama (X.1 → 1)
                if (preg_match('/\.(\d+)$/', $k->nama, $m)) {
                    $maxNum = max($maxNum, (int) $m[1]);
                }
            }
            $tingkatNext[$tingkat] = $tingkat . '.' . ($maxNum + 1);
        }

        return view('data.kelas-form', [
            'kelas' => null,
            'tingkatNext' => $tingkatNext,
        ]);
    }

    /**
     * Simpan kelas baru
     */
    public function kelasStore(Request $request)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:10|unique:kelas,nama',
            'tingkat' => 'required|in:X,XI,XII',
        ]);

        Kelas::create($validated);

        return redirect()->route('data.kelas')
            ->with('success', "Kelas '{$validated['nama']}' berhasil ditambahkan.");
    }

    /**
     * Form edit kelas
     */
    public function kelasEdit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('data.kelas-form', [
            'kelas' => $kelas,
            'tingkatNext' => null,
        ]);
    }

    /**
     * Update kelas
     */
    public function kelasUpdate(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $validated = $request->validate([
            'nama'    => 'required|string|max:10|unique:kelas,nama,' . $id,
            'tingkat' => 'required|in:X,XI,XII',
        ]);

        $kelas->update($validated);

        return redirect()->route('data.kelas')
            ->with('success', "Kelas '{$kelas->nama}' berhasil diupdate.");
    }

    /**
     * Hapus kelas
     */
    public function kelasDestroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        $bebanCount = BebanMengajar::where('kelas_id', $id)->count();
        if ($bebanCount > 0) {
            return redirect()->route('data.kelas')
                ->with('error', "Kelas '{$kelas->nama}' tidak bisa dihapus karena memiliki {$bebanCount} beban mengajar.");
        }

        $nama = $kelas->nama;
        $kelas->delete();

        return redirect()->route('data.kelas')
            ->with('success', "Kelas '{$nama}' berhasil dihapus.");
    }

    // =====================================================
    // BEBAN MENGAJAR
    // =====================================================

    public function bebanMengajar()
    {
        $beban = BebanMengajar::with(['guru', 'mapel', 'kelas'])
            ->join('gurus', 'beban_mengajars.guru_id', '=', 'gurus.id')
            ->orderByRaw('CAST(gurus.kode AS UNSIGNED) ASC')
            ->orderBy('beban_mengajars.kelas_id')
            ->select('beban_mengajars.*')
            ->get()
            ->map(function ($b) {
                return [
                    'id'        => $b->id,
                    'guru_kode' => $b->guru->kode ?? '-',
                    'guru_nama' => $b->guru->nama ?? '-',
                    'mapel_kode'=> $b->mapel->kode ?? '-',
                    'mapel_nama'=> $b->mapel->nama ?? '-',
                    'kelas_nama'=> $b->kelas->nama ?? '-',
                    'jumlah_jp' => $b->jumlah_jp,
                ];
            });

        $groupedByGuru = $beban->groupBy('guru_kode');
        $totalJp     = $beban->sum('jumlah_jp');
        $totalGuru   = $beban->unique('guru_kode')->count();
        $totalMapel  = $beban->unique('mapel_kode')->count();

        return view('data.beban-mengajar', compact(
            'beban', 'groupedByGuru', 'totalJp', 'totalGuru', 'totalMapel'
        ));
    }
}