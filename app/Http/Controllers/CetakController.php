<?php

namespace App\Http\Controllers;

use App\Models\{HasilOptimasi, Guru, Mapel, Kelas, SlotWaktu, EditManualJadwal, BebanMengajar};
use Illuminate\Http\Request;

class CetakController extends Controller
{
    /**
     * Tampilkan halaman cetak A4
     */
    public function jadwal($hasilId)
    {
        $hasil = HasilOptimasi::findOrFail($hasilId);

        // Guru urut natural (1, 2, 3, ..., 10, 11, ..., 27)
        $gurus = Guru::orderByRaw('CAST(kode AS UNSIGNED) ASC')->get();

        // Mapel urut alphabetic
        $mapels = Mapel::orderBy('kode')->get();

        // Kelas urut by tingkat & nama
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama')->get();

        // Slot waktu urut by hari & jam_ke
        $slots = SlotWaktu::orderBy('hari')->orderBy('jam_ke')->get();

        // Format jadwal: [kelas_id][slot_id] = ['guru_kode', 'mapel_kode']
        $jadwalMap = [];
        foreach ($hasil->jadwal as $item) {
            $guru  = $gurus->firstWhere('id', $item['guru_id']);
            $mapel = $mapels->firstWhere('id', $item['mapel_id']);
            $jadwalMap[$item['kelas_id']][$item['slot_id']] = [
                'guru'  => $guru->kode ?? '-',
                'mapel' => $mapel->kode ?? '-',
            ];
        }

        // Ambil edit manual dari database
        $editManual = EditManualJadwal::where('hasil_optimasi_id', $hasilId)
            ->get()
            ->mapWithKeys(function ($e) {
                return ["k{$e->kelas_id}s{$e->slot_id}" => [
                    'kode'      => $e->kode_manual,
                    'guru_id'   => $e->guru_id,
                    'mapel_id'  => $e->mapel_id,
                ]];
            });

        return view('cetak.jadwal-a4', compact(
            'hasil',
            'gurus',
            'mapels',
            'kelas',
            'slots',
            'jadwalMap',
            'editManual'
        ));
    }

    /**
     * ⭐ Tambah Guru Baru
     */
    public function tambahGuru(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:gurus,kode',
            'nama' => 'required|string|max:150',
        ], [
            'kode.required' => 'Kode guru wajib diisi.',
            'kode.unique'   => 'Kode guru sudah digunakan. Silakan pakai kode lain.',
            'nama.required' => 'Nama guru wajib diisi.',
        ]);

        $guru = Guru::create([
            'kode'  => strtoupper($validated['kode']),
            'nama'  => $validated['nama'],
            'is_bk' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Guru '{$guru->nama}' berhasil ditambahkan.",
            'guru'    => $guru,
        ]);
    }

    /**
     * ⭐ Tambah Mapel Baru
     */
    public function tambahMapel(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:mapels,kode',
            'nama' => 'required|string|max:150',
        ], [
            'kode.required' => 'Kode mapel wajib diisi.',
            'kode.unique'   => 'Kode mapel sudah digunakan. Silakan pakai kode lain.',
            'nama.required' => 'Nama mapel wajib diisi.',
        ]);

        $mapel = Mapel::create([
            'kode' => strtoupper($validated['kode']),
            'nama' => $validated['nama'],
        ]);

        return response()->json([
            'success' => true,
            'message' => "Mapel '{$mapel->nama}' berhasil ditambahkan.",
            'mapel'   => $mapel,
        ]);
    }

    /**
     * ⭐ Hapus Guru
     */
    public function hapusGuru($id)
    {
        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan.',
            ], 404);
        }

        $dipakaiBeban = BebanMengajar::where('guru_id', $id)->exists();
        if ($dipakaiBeban) {
            return response()->json([
                'success' => false,
                'message' => "Guru '{$guru->nama}' tidak bisa dihapus karena masih terdaftar di Beban Mengajar.",
            ], 422);
        }

        $dipakaiEdit = EditManualJadwal::where('guru_id', $id)->exists();
        if ($dipakaiEdit) {
            return response()->json([
                'success' => false,
                'message' => "Guru '{$guru->nama}' tidak bisa dihapus karena masih dipakai di edit manual jadwal. Reset edit manual dulu.",
            ], 422);
        }

        $nama = $guru->nama;
        $guru->delete();

        return response()->json([
            'success' => true,
            'message' => "Guru '{$nama}' berhasil dihapus.",
        ]);
    }

    /**
     * ⭐ Hapus Mapel
     */
    public function hapusMapel($id)
    {
        $mapel = Mapel::find($id);

        if (!$mapel) {
            return response()->json([
                'success' => false,
                'message' => 'Mapel tidak ditemukan.',
            ], 404);
        }

        $dipakaiBeban = BebanMengajar::where('mapel_id', $id)->exists();
        if ($dipakaiBeban) {
            return response()->json([
                'success' => false,
                'message' => "Mapel '{$mapel->nama}' tidak bisa dihapus karena masih terdaftar di Beban Mengajar.",
            ], 422);
        }

        $dipakaiEdit = EditManualJadwal::where('mapel_id', $id)->exists();
        if ($dipakaiEdit) {
            return response()->json([
                'success' => false,
                'message' => "Mapel '{$mapel->nama}' tidak bisa dihapus karena masih dipakai di edit manual jadwal.",
            ], 422);
        }

        $nama = $mapel->nama;
        $mapel->delete();

        return response()->json([
            'success' => true,
            'message' => "Mapel '{$nama}' berhasil dihapus.",
        ]);
    }

    /**
     * ⭐ Update Jam Istirahat
     */
    public function updateIstirahat(Request $request, $slotId)
    {
        $slot = SlotWaktu::find($slotId);

        if (!$slot) {
            return response()->json([
                'success' => false,
                'message' => 'Slot waktu tidak ditemukan.',
            ], 404);
        }

        if (!$slot->is_istirahat) {
            return response()->json([
                'success' => false,
                'message' => 'Slot ini bukan jam istirahat.',
            ], 422);
        }

        $validated = $request->validate([
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'jam_mulai.required'      => 'Jam mulai wajib diisi.',
            'jam_mulai.date_format'   => 'Format jam mulai harus HH:MM.',
            'jam_selesai.required'    => 'Jam selesai wajib diisi.',
            'jam_selesai.date_format' => 'Format jam selesai harus HH:MM.',
            'jam_selesai.after'       => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);

        $slot->update([
            'jam_mulai'   => $validated['jam_mulai'] . ':00',
            'jam_selesai' => $validated['jam_selesai'] . ':00',
        ]);

        return response()->json([
            'success'     => true,
            'message'     => 'Jam istirahat berhasil diperbarui.',
            'jam_mulai'   => \Carbon\Carbon::parse($slot->jam_mulai)->format('H.i'),
            'jam_selesai' => \Carbon\Carbon::parse($slot->jam_selesai)->format('H.i'),
        ]);
    }
}