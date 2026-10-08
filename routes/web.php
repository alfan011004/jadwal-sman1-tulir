<?php

use App\Http\Controllers\{
    DashboardController,
    OptimasiController,
    CetakController,
    EditJadwalController,
    DataController,
    BebanMengajarController,
    UserProfileController
};
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// ⭐ Profile
Route::get('/profile/edit',               [UserProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile/update',            [UserProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/hapus-foto',        [UserProfileController::class, 'hapusFoto'])->name('profile.hapusFoto');
Route::post('/profile/hapus-foto-kepsek', [UserProfileController::class, 'hapusFotoKepsek'])->name('profile.hapusFotoKepsek');

// ⭐ Data Master
Route::prefix('data')->name('data.')->group(function () {
    Route::get('/guru',             [DataController::class, 'guru'])->name('guru');
    Route::get('/guru/create',      [DataController::class, 'guruCreate'])->name('guru.create');
    Route::post('/guru',            [DataController::class, 'guruStore'])->name('guru.store');
    Route::get('/guru/{id}/edit',   [DataController::class, 'guruEdit'])->name('guru.edit');
    Route::put('/guru/{id}',        [DataController::class, 'guruUpdate'])->name('guru.update');
    Route::delete('/guru/{id}',     [DataController::class, 'guruDestroy'])->name('guru.destroy');
    
    Route::get('/mapel',            [DataController::class, 'mapel'])->name('mapel');
    Route::get('/mapel/create',     [DataController::class, 'mapelCreate'])->name('mapel.create');
    Route::post('/mapel',           [DataController::class, 'mapelStore'])->name('mapel.store');
    Route::get('/mapel/{id}/edit',  [DataController::class, 'mapelEdit'])->name('mapel.edit');
    Route::put('/mapel/{id}',       [DataController::class, 'mapelUpdate'])->name('mapel.update');
    Route::delete('/mapel/{id}',    [DataController::class, 'mapelDestroy'])->name('mapel.destroy');
    
    Route::get('/kelas',            [DataController::class, 'kelas'])->name('kelas');
    Route::get('/kelas/create',     [DataController::class, 'kelasCreate'])->name('kelas.create');
    Route::post('/kelas',           [DataController::class, 'kelasStore'])->name('kelas.store');
    Route::get('/kelas/{id}/edit',  [DataController::class, 'kelasEdit'])->name('kelas.edit');
    Route::put('/kelas/{id}',       [DataController::class, 'kelasUpdate'])->name('kelas.update');
    Route::delete('/kelas/{id}',    [DataController::class, 'kelasDestroy'])->name('kelas.destroy');
    
    Route::get('/beban-mengajar',   [DataController::class, 'bebanMengajar'])->name('beban');
});

// ⭐ Beban Mengajar
Route::prefix('beban')->name('beban.')->group(function () {
    Route::get('/',             [BebanMengajarController::class, 'index'])->name('index');
    Route::get('/create',       [BebanMengajarController::class, 'create'])->name('create');
    Route::post('/',            [BebanMengajarController::class, 'store'])->name('store');
    Route::get('/{id}/edit',    [BebanMengajarController::class, 'edit'])->name('edit');
    Route::put('/{id}',         [BebanMengajarController::class, 'update'])->name('update');
    Route::delete('/{id}',      [BebanMengajarController::class, 'destroy'])->name('destroy');
    Route::get('/cek-total',    [BebanMengajarController::class, 'cekTotal'])->name('cek-total');
    Route::post('/bulk-import', [BebanMengajarController::class, 'bulkImport'])->name('bulk-import');
    Route::post('/reset',       [BebanMengajarController::class, 'reset'])->name('reset');
});

// Optimasi
Route::prefix('optimasi')->name('optimasi.')->group(function () {
    Route::get('/',              [OptimasiController::class, 'index'])->name('index');
    Route::post('/jalankan',     [OptimasiController::class, 'jalankan'])->name('jalankan');
    Route::get('/perbandingan',  [OptimasiController::class, 'perbandingan'])->name('perbandingan');
    Route::get('/detail/{id}',   [OptimasiController::class, 'detail'])->name('detail');
    Route::delete('/hapus/{id}', [OptimasiController::class, 'hapus'])->name('hapus');
});

// ⭐ Cetak
Route::get('/cetak/{hasilId}', [CetakController::class, 'jadwal'])->name('cetak.jadwal');
Route::post('/cetak/tambah-guru',        [CetakController::class, 'tambahGuru'])->name('cetak.tambahGuru');
Route::post('/cetak/tambah-mapel',       [CetakController::class, 'tambahMapel'])->name('cetak.tambahMapel');
Route::delete('/cetak/hapus-guru/{id}',  [CetakController::class, 'hapusGuru'])->name('cetak.hapusGuru');
Route::delete('/cetak/hapus-mapel/{id}', [CetakController::class, 'hapusMapel'])->name('cetak.hapusMapel');
Route::put('/cetak/update-istirahat/{slotId}', [CetakController::class, 'updateIstirahat'])->name('cetak.updateIstirahat');

// Edit Manual Jadwal
Route::prefix('edit-jadwal/{hasilId}')->group(function () {
    Route::get('/',       [EditJadwalController::class, 'index'])->name('edit.index');
    Route::post('/',      [EditJadwalController::class, 'simpan'])->name('edit.simpan');
    Route::delete('/',    [EditJadwalController::class, 'hapus'])->name('edit.hapus');
    Route::post('/reset', [EditJadwalController::class, 'reset'])->name('edit.reset');
});

// API
Route::get('/api/daftar-guru',  [EditJadwalController::class, 'daftarGuru'])->name('api.guru');
Route::get('/api/daftar-mapel', [EditJadwalController::class, 'daftarMapel'])->name('api.mapel');