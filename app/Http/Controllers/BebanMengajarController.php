<?php

namespace App\Http\Controllers;

use App\Models\{Guru, Mapel, Kelas, BebanMengajar};
use Illuminate\Http\Request;

class BebanMengajarController extends Controller
{
    public function index(Request $request)
    {
        $query = BebanMengajar::with(['guru', 'mapel', 'kelas']);

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->guru_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('guru', fn($q2) => $q2->where('nama', 'like', "%{$search}%"))
                  ->orWhereHas('mapel', fn($q2) => $q2->where('nama', 'like', "%{$search}%"))
                  ->orWhereHas('kelas', fn($q2) => $q2->where('nama', 'like', "%{$search}%"));
            });
        }

        $beban = $query->orderBy('kelas_id')->orderBy('guru_id')->paginate(50);

        // ⭐ FIX: Urutkan guru secara natural (1, 2, 3, ..., 10, 11)
        $gurus  = Guru::orderByRaw('CAST(kode AS UNSIGNED) ASC')->get();
        $mapels = Mapel::orderBy('kode')->get();
        $kelas  = Kelas::orderBy('tingkat')->orderBy('nama')->get();

        return view('beban.index', compact('beban', 'gurus', 'mapels', 'kelas'));
    }

    public function create()
    {
        // ⭐ FIX
        $gurus  = Guru::where('is_bk', false)->orderByRaw('CAST(kode AS UNSIGNED) ASC')->get();
        $mapels = Mapel::orderBy('kode')->get();
        $kelas  = Kelas::orderBy('tingkat')->orderBy('nama')->get();

        return view('beban.form', compact('gurus', 'mapels', 'kelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guru_id'   => 'required|exists:gurus,id',
            'mapel_id'  => 'required|exists:mapels,id',
            'kelas_id'  => 'required|exists:kelas,id',
            'jumlah_jp' => 'required|integer|min:1|max:10',
        ]);

        $exists = BebanMengajar::where('guru_id', $validated['guru_id'])
            ->where('mapel_id', $validated['mapel_id'])
            ->where('kelas_id', $validated['kelas_id'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Beban ini sudah ada! Silakan edit yang sudah ada.');
        }

        $totalJp = BebanMengajar::where('kelas_id', $validated['kelas_id'])->sum('jumlah_jp');
        $newTotal = $totalJp + $validated['jumlah_jp'];

        if ($newTotal > 45) {
            $sisa = 45 - $totalJp;
            return back()->withInput()->with('error', 
                "Total JP kelas ini akan menjadi {$newTotal} JP (lebih dari 45). Sisa slot: {$sisa} JP.");
        }

        BebanMengajar::create($validated);

        return redirect()->route('beban.index')
            ->with('success', 'Beban mengajar berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $beban  = BebanMengajar::findOrFail($id);
        // ⭐ FIX
        $gurus  = Guru::where('is_bk', false)->orderByRaw('CAST(kode AS UNSIGNED) ASC')->get();
        $mapels = Mapel::orderBy('kode')->get();
        $kelas  = Kelas::orderBy('tingkat')->orderBy('nama')->get();

        return view('beban.form', compact('beban', 'gurus', 'mapels', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $beban = BebanMengajar::findOrFail($id);

        $validated = $request->validate([
            'guru_id'   => 'required|exists:gurus,id',
            'mapel_id'  => 'required|exists:mapels,id',
            'kelas_id'  => 'required|exists:kelas,id',
            'jumlah_jp' => 'required|integer|min:1|max:10',
        ]);

        $totalJp = BebanMengajar::where('kelas_id', $validated['kelas_id'])
            ->where('id', '!=', $id)
            ->sum('jumlah_jp');
        $newTotal = $totalJp + $validated['jumlah_jp'];

        if ($newTotal > 45) {
            $sisa = 45 - $totalJp;
            return back()->withInput()->with('error', 
                "Total JP kelas ini akan menjadi {$newTotal} JP (lebih dari 45). Sisa slot: {$sisa} JP.");
        }

        $beban->update($validated);

        return redirect()->route('beban.index')
            ->with('success', 'Beban mengajar berhasil diupdate.');
    }

    public function destroy($id)
    {
        $beban = BebanMengajar::findOrFail($id);
        $beban->delete();

        return redirect()->route('beban.index')
            ->with('success', 'Beban mengajar berhasil dihapus.');
    }

    public function cekTotal()
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get()
            ->map(function ($k) {
                $jp = BebanMengajar::where('kelas_id', $k->id)->sum('jumlah_jp');
                return [
                    'id'      => $k->id,
                    'nama'    => $k->nama,
                    'tingkat' => $k->tingkat,
                    'jp'      => $jp,
                    'status'  => $jp == 45 ? 'ok' : ($jp > 45 ? 'lebih' : 'kurang'),
                    'selisih' => $jp - 45,
                ];
            });

        $totalKeseluruhan = $kelasList->sum('jp');
        $totalSeharusnya  = 13 * 45;

        return view('beban.cek-total', compact('kelasList', 'totalKeseluruhan', 'totalSeharusnya'));
    }

    public function bulkImport(Request $request)
    {
        $request->validate([
            'data' => 'required|string',
        ]);

        $lines = explode("\n", trim($request->data));
        $success = 0;
        $errors = [];

        foreach ($lines as $i => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = explode('|', $line);
            if (count($parts) !== 4) {
                $errors[] = "Baris " . ($i + 1) . ": Format salah (harus 4 kolom dipisah |)";
                continue;
            }

            [$kodeGuru, $kodeMapel, $namaKelas, $jumlahJp] = array_map('trim', $parts);

            $guru  = Guru::where('kode', $kodeGuru)->first();
            $mapel = Mapel::where('kode', $kodeMapel)->first();
            $kelas = Kelas::where('nama', $namaKelas)->first();

            if (!$guru)  { $errors[] = "Baris " . ($i + 1) . ": Guru '{$kodeGuru}' tidak ada"; continue; }
            if (!$mapel) { $errors[] = "Baris " . ($i + 1) . ": Mapel '{$kodeMapel}' tidak ada"; continue; }
            if (!$kelas) { $errors[] = "Baris " . ($i + 1) . ": Kelas '{$namaKelas}' tidak ada"; continue; }

            BebanMengajar::updateOrCreate(
                [
                    'guru_id'  => $guru->id,
                    'mapel_id' => $mapel->id,
                    'kelas_id' => $kelas->id,
                ],
                ['jumlah_jp' => (int) $jumlahJp]
            );
            $success++;
        }

        return redirect()->route('beban.index')->with('success', 
            "Bulk import selesai: {$success} baris berhasil." . 
            (count($errors) > 0 ? " " . count($errors) . " baris gagal." : "")
        )->with('bulk_errors', $errors);
    }

    public function reset()
    {
        $count = BebanMengajar::count();
        BebanMengajar::truncate();

        return redirect()->route('beban.index')->with('success', 
            "Berhasil hapus semua ({$count}) data beban mengajar."
        );
    }
}