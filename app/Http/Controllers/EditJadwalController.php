<?php

namespace App\Http\Controllers;

use App\Models\{EditManualJadwal, HasilOptimasi, Guru, Mapel, Kelas, SlotWaktu};
use Illuminate\Http\Request;

class EditJadwalController extends Controller
{
    /**
     * Ambil semua edit manual untuk hasil optimasi tertentu
     */
    public function index($hasilId)
    {
        $edits = EditManualJadwal::where('hasil_optimasi_id', $hasilId)
            ->get()
            ->mapWithKeys(function ($e) {
                return ["k{$e->kelas_id}s{$e->slot_id}" => [
                    'kode' => $e->kode_manual,
                    'guru_id' => $e->guru_id,
                    'mapel_id' => $e->mapel_id,
                ]];
            });

        return response()->json($edits);
    }

    /**
     * Simpan / update edit manual
     */
    public function simpan(Request $request, $hasilId)
    {
        $request->validate([
            'kelas_id'   => 'required|exists:kelas,id',
            'slot_id'    => 'required|exists:slot_waktus,id',
            'kode_manual'=> 'required|string|max:20',
        ]);

        // Cek hasil optimasi ada
        $hasil = HasilOptimasi::findOrFail($hasilId);

        $kode = strtoupper(trim($request->kode_manual));

        // ⭐ VALIDASI: Parse kode
        // Format: angka + huruf (contoh: 5E, 17D, 25L)
        if (!preg_match('/^(\d+)([A-Z])$/', $kode, $matches)) {
            return response()->json([
                'success' => false,
                'message' => 'Format kode salah. Gunakan format: <nomor_guru><huruf_mapel> (contoh: 5E)',
            ], 422);
        }

        $kodeGuru  = $matches[1];
        $kodeMapel = $matches[2];

        // Cek guru ada
        $guru = Guru::where('kode', $kodeGuru)->first();
        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => "Kode guru '{$kodeGuru}' tidak ditemukan di database.",
            ], 422);
        }

        // Cek mapel ada
        $mapel = Mapel::where('kode', $kodeMapel)->first();
        if (!$mapel) {
            return response()->json([
                'success' => false,
                'message' => "Kode mapel '{$kodeMapel}' tidak ditemukan di database.",
            ], 422);
        }

        // ⭐ Simpan atau update
        $edit = EditManualJadwal::updateOrCreate(
            [
                'hasil_optimasi_id' => $hasilId,
                'kelas_id'          => $request->kelas_id,
                'slot_id'           => $request->slot_id,
            ],
            [
                'kode_manual' => $kode,
                'guru_id'     => $guru->id,
                'mapel_id'    => $mapel->id,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => "Berhasil disimpan: {$guru->nama} — {$mapel->nama}",
            'edit'    => [
                'kode'      => $kode,
                'guru_id'   => $guru->id,
                'guru_nama' => $guru->nama,
                'guru_kode' => $guru->kode,
                'mapel_id'  => $mapel->id,
                'mapel_nama'=> $mapel->nama,
                'mapel_kode'=> $mapel->kode,
            ],
        ]);
    }

    /**
     * Hapus edit manual (kembali ke hasil optimasi)
     */
    public function hapus(Request $request, $hasilId)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'slot_id'  => 'required|exists:slot_waktus,id',
        ]);

        EditManualJadwal::where('hasil_optimasi_id', $hasilId)
            ->where('kelas_id', $request->kelas_id)
            ->where('slot_id', $request->slot_id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Edit manual dihapus.',
        ]);
    }

    /**
     * Reset semua edit manual untuk 1 hasil optimasi
     */
    public function reset($hasilId)
    {
        $count = EditManualJadwal::where('hasil_optimasi_id', $hasilId)->delete();

        return response()->json([
            'success' => true,
            'message' => "Berhasil hapus {$count} edit manual.",
        ]);
    }

    /**
     * Ambil daftar guru untuk auto-suggest
     */
    public function daftarGuru()
    {
        $gurus = Guru::orderBy('kode', 'asc')
            ->get(['id', 'kode', 'nama'])
            ->map(fn($g) => [
                'id'   => $g->id,
                'kode' => $g->kode,
                'nama' => $g->nama,
            ]);

        return response()->json($gurus);
    }

    /**
     * Ambil daftar mapel untuk auto-suggest
     */
    public function daftarMapel()
    {
        $mapels = Mapel::orderBy('kode', 'asc')
            ->get(['id', 'kode', 'nama'])
            ->map(fn($m) => [
                'id'   => $m->id,
                'kode' => $m->kode,
                'nama' => $m->nama,
            ]);

        return response()->json($mapels);
    }
}