<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Jadwal - SMAN 1 Tungkal Ilir</title>
<style>
  @page { size: A4 landscape; margin: 8mm 6mm; }
  body { font-family: 'Times New Roman', serif; font-size: 9pt; margin: 0; color: #000; }

  /* ===== KOP SURAT ===== */
  .kop {
    display: flex; align-items: center; gap: 10px;
    padding-bottom: 6px; border-bottom: 3px double #000; margin-bottom: 8px;
  }
  .kop .logo-kiri  { width: 75px; height: 75px; object-fit: contain; flex-shrink: 0; }
  .kop .logo-kanan { width: 70px; height: 70px; object-fit: contain; flex-shrink: 0; }
  .kop-teks { flex: 1; text-align: center; line-height: 1.2; }
  .kop-teks .pemprov { font-size: 11.5pt; font-weight: bold; letter-spacing: .5px; }
  .kop-teks .dinas   { font-size: 12.5pt; font-weight: bold; }
  .kop-teks .sekolah { font-size: 16pt; font-weight: bold; letter-spacing: 1px; margin: 2px 0; }
  .kop-teks .alamat, 
  .kop-teks .kontak { font-size: 8pt; font-style: italic; }

  /* ===== JUDUL ===== */
  .judul { text-align: center; margin: 8px 0 6px; }
  .judul h3 { margin: 0; font-size: 11.5pt; letter-spacing: 1px; }
  .judul h4 { margin: 2px 0 0; font-size: 10.5pt; font-weight: normal; }

  /* ===== LAYOUT ===== */
  .container { display: flex; gap: 8px; }
  .jadwal-utama { flex: 3; }
  .panel-kode { flex: 1; }

  /* ===== TABEL JADWAL ===== */
  table.jadwal { width: 100%; border-collapse: collapse; table-layout: fixed; }
  table.jadwal thead { display: table-header-group; }
  table.jadwal tr { page-break-inside: avoid; }
  table.jadwal th, table.jadwal td {
    border: 1px solid #000; padding: 0 2px;
    text-align: center; font-size: 7pt; height: 14px; line-height: 1.2;
  }
  table.jadwal th { background: #ddd; font-weight: bold; }
  .kolom-jam   { width: 6%; background: #f0f0f0; }
  .kolom-hari  { width: 6%; background: #f0f0f0; }
  .kolom-waktu { width: 10%; background: #f8f8f8; font-size: 6.5pt; }

  /* ===== BARIS ISTIRAHAT (DIPERBESAR) ===== */
  .baris-istirahat td {
    background: #f5f5f5 !important;
    height: 18px !important;
    padding: 3px 8px !important;
    font-style: italic;
    font-size: 9pt !important;
  }
  .baris-istirahat .istirahat-label { 
    font-weight: bold; 
    font-size: 9.5pt; 
    letter-spacing: 0.5px; 
    color: #333; 
  }
  .baris-istirahat .istirahat-waktu { 
    font-weight: normal; 
    font-size: 9pt; 
    color: #555; 
    margin-left: 10px; 
  }
  .baris-istirahat .edit-icon { 
    font-size: 10pt; 
  }

  /* ===== SEL YANG BISA DIEDIT ===== */
  .sel-edit { cursor: pointer; position: relative; transition: background 0.15s; }
  .sel-edit:hover { background: #fef3c7; }
  .sel-kosong { color: #9ca3af; background: #fef3c7; font-weight: bold; }
  .sel-edit-terisi { background: #dbeafe; color: #1e40af; font-weight: 600; }
  .sel-edit input {
    width: 100%; border: 2px solid #1e40af; padding: 0;
    font-size: 7pt; text-align: center; font-family: inherit;
    outline: none; background: white; text-transform: uppercase;
  }

  /* ===== EDITOR ISTIRAHAT ===== */
  .baris-istirahat .istirahat-edit-trigger {
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  .baris-istirahat .istirahat-edit-trigger:hover {
    color: #1e40af;
    text-decoration: underline;
  }
  .baris-istirahat .edit-icon {
    opacity: 0;
    font-size: 10pt;
    color: #1e40af;
    transition: opacity 0.2s;
  }
  .baris-istirahat:hover .edit-icon {
    opacity: 1;
  }
  .istirahat-inline-form {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: white;
    padding: 3px 10px;
    border-radius: 4px;
    border: 1.5px solid #1e40af;
    font-style: normal;
  }
  .istirahat-inline-form label {
    font-size: 8pt;
    color: #666;
    font-weight: normal;
    font-style: normal;
  }
  .istirahat-inline-form input[type="time"] {
    font-size: 9pt;
    padding: 2px 4px;
    border: 1px solid #cbd5e1;
    border-radius: 3px;
    font-family: 'Times New Roman', serif;
    width: 75px;
  }
  .istirahat-inline-form input[type="time"]:focus {
    outline: none;
    border-color: #1e40af;
    box-shadow: 0 0 0 2px rgba(30, 64, 175, 0.15);
  }
  .istirahat-inline-form .btn-save-mini,
  .istirahat-inline-form .btn-cancel-mini {
    padding: 3px 8px;
    font-size: 8.5pt;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-family: sans-serif;
    font-weight: 600;
    transition: all 0.2s;
  }
  .istirahat-inline-form .btn-save-mini { background: #1e40af; color: white; }
  .istirahat-inline-form .btn-save-mini:hover { background: #1e3a8a; }
  .istirahat-inline-form .btn-cancel-mini { background: #f1f5f9; color: #475569; }
  .istirahat-inline-form .btn-cancel-mini:hover { background: #e2e8f0; }

  /* ===== AUTO-SUGGEST DROPDOWN ===== */
  .autocomplete-box {
    position: absolute; z-index: 9999; background: white;
    border: 2px solid #1e40af; border-radius: 6px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    max-height: 250px; overflow-y: auto; min-width: 220px;
    font-family: sans-serif; font-size: 12px;
  }
  .autocomplete-item {
    padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #f0f0f0;
    display: flex; justify-content: space-between; gap: 10px;
  }
  .autocomplete-item:hover, .autocomplete-item.active { background: #dbeafe; }
  .autocomplete-item .kode { font-weight: bold; color: #1e40af; min-width: 30px; }
  .autocomplete-item .nama { flex: 1; color: #374151; }
  .autocomplete-section {
    padding: 4px 12px; background: #f9fafb; font-size: 10px;
    font-weight: bold; color: #6b7280; text-transform: uppercase;
    border-bottom: 1px solid #e5e7eb;
  }

  /* ===== NOTIFIKASI ===== */
  .notif {
    position: fixed; top: 20px; right: 20px; padding: 12px 20px;
    border-radius: 8px; color: white; font-family: sans-serif;
    font-size: 13px; z-index: 9999;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    animation: slideIn 0.3s ease-out; max-width: 350px;
  }
  .notif-success { background: #16a34a; }
  .notif-error   { background: #dc2626; }
  .notif-info    { background: #2563eb; }
  @keyframes slideIn {
    from { transform: translateX(400px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
  }

  /* ===== PANEL KODE ===== */
  table.kode { width: 100%; border-collapse: collapse; margin-bottom: 5px; font-size: 6.5pt; }
  table.kode th, table.kode td { border: 1px solid #000; padding: 1px 3px; line-height: 1.1; }
  table.kode th { background: #ddd; text-align: center; }

  /* ===== PANEL HEADER + TOMBOL TAMBAH ===== */
  .panel-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 4px; margin-top: 8px;
  }
  .panel-header h4 { margin: 0; font-size: 10px; font-weight: bold; }
  .btn-tambah {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; background: #1e40af; color: white;
    border: none; border-radius: 5px; cursor: pointer;
    font-size: 10px; font-weight: 600; font-family: sans-serif;
    transition: background 0.2s;
  }
  .btn-tambah:hover { background: #1e3a8a; }

  /* ===== TOMBOL HAPUS ===== */
  .btn-hapus {
    display: inline-flex; align-items: center; justify-content: center;
    width: 18px; height: 18px;
    background: #fee2e2; color: #dc2626;
    border: none; border-radius: 4px; cursor: pointer;
    font-size: 9px; transition: all 0.2s;
    padding: 0; font-family: sans-serif;
  }
  .btn-hapus:hover {
    background: #dc2626; color: white; transform: scale(1.1);
  }
  .kolom-aksi { width: 24px; text-align: center; padding: 0 !important; }

  /* ===== TTD ===== */
  .ttd { margin-top: 10px; display: flex; justify-content: flex-end; font-size: 9pt; }
  .ttd-box { text-align: center; width: 40%; }
  .ttd-nama { margin-top: 40px; font-weight: bold; text-decoration: underline; }

  /* ===== FORM CONTROL ===== */
  .no-print { 
    background: #f0f4ff; border: 2px dashed #1e40af; border-radius: 8px; 
    padding: 15px 20px; margin: 15px auto; max-width: 900px; font-family: sans-serif;
  }
  .no-print h3 { margin: 0 0 12px; color: #1e40af; font-size: 14px; }
  .form-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); 
    gap: 12px; margin-bottom: 12px;
  }
  .form-item label { display: block; font-size: 11px; color: #555; margin-bottom: 3px; font-weight: 600; }
  .form-item input, .form-item select {
    width: 100%; padding: 6px 8px; border: 1px solid #ccc; 
    border-radius: 4px; font-size: 13px;
  }
  .btn-row { text-align: center; }
  .btn {
    padding: 10px 25px; background: #1e40af; color: white;
    border: none; border-radius: 6px; cursor: pointer;
    font-size: 14px; margin: 0 5px; font-family: sans-serif;
  }
  .btn:hover { background: #1e3a8a; }
  .btn-secondary { background: #6b7280; }
  .btn-secondary:hover { background: #4b5563; }
  .btn-warning { background: #d97706; }
  .btn-warning:hover { background: #b45309; }

  .info-box {
    background: #fef3c7; border-left: 4px solid #d97706;
    padding: 10px 15px; margin-bottom: 12px; border-radius: 4px;
    font-size: 12px; color: #78350f;
  }
  .stats-box {
    background: #dbeafe; border-left: 4px solid #1e40af;
    padding: 10px 15px; margin-bottom: 12px; border-radius: 4px;
    font-size: 12px; color: #1e3a8a;
    display: flex; justify-content: space-between; align-items: center;
  }

  /* ===== MODAL ===== */
  .modal-overlay {
    position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5);
    z-index: 10000; display: none; align-items: center; justify-content: center;
    font-family: sans-serif;
  }
  .modal-overlay.active { display: flex; }
  .modal-box {
    background: white; border-radius: 10px; padding: 24px;
    width: 90%; max-width: 420px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    animation: modalIn 0.2s ease-out;
  }
  @keyframes modalIn {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
  }
  .modal-box h3 {
    margin: 0 0 16px; font-size: 16px; color: #1e40af;
    display: flex; align-items: center; gap: 8px;
  }
  .modal-box .form-group { margin-bottom: 14px; }
  .modal-box label {
    display: block; font-size: 12px; font-weight: 600;
    color: #374151; margin-bottom: 5px;
  }
  .modal-box input {
    width: 100%; padding: 8px 12px; border: 1.5px solid #d1d5db;
    border-radius: 6px; font-size: 13px; box-sizing: border-box;
    transition: border-color 0.2s;
  }
  .modal-box input:focus {
    outline: none; border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
  }
  .modal-actions {
    display: flex; gap: 8px; justify-content: flex-end; margin-top: 20px;
  }
  .modal-actions button {
    padding: 8px 18px; border: none; border-radius: 6px;
    font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;
  }
  .modal-actions .btn-cancel { background: #f1f5f9; color: #475569; }
  .modal-actions .btn-cancel:hover { background: #e2e8f0; }
  .modal-actions .btn-save { background: #1e40af; color: white; }
  .modal-actions .btn-save:hover { background: #1e3a8a; }
  .modal-actions .btn-save:disabled { background: #94a3b8; cursor: not-allowed; }
  .modal-error {
    background: #fef2f2; border-left: 3px solid #dc2626;
    color: #991b1b; padding: 8px 12px; border-radius: 4px;
    font-size: 12px; margin-bottom: 12px; display: none;
  }
  .modal-error.active { display: block; }

  /* ===== PRINT ===== */
  @media print { 
    .no-print, .autocomplete-box, .notif, .modal-overlay, .btn-tambah, .btn-hapus, .edit-icon { display: none !important; }
    body { font-size: 8pt; }
    table.jadwal th, table.jadwal td { font-size: 6.5pt; height: 13px; }
    .sel-edit:hover { background: transparent; }
    .sel-kosong { background: transparent !important; color: #000 !important; }
    .sel-edit-terisi { background: transparent !important; color: #000 !important; font-weight: normal !important; }
    .baris-istirahat td {
      background: #f5f5f5 !important;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
      height: 16px !important;
      font-size: 8.5pt !important;
    }
    .baris-istirahat .istirahat-label { font-size: 8.5pt !important; }
    .baris-istirahat .istirahat-waktu { font-size: 8pt !important; }
  }
</style>
</head>
<body>

{{-- ⭐ BASE64 LOGO ⭐ --}}
@php
    $logo1Path = public_path('assets/logo-sman1.png');
    $logo2Path = public_path('assets/logo-sumasel.png');
    $logo1Base64 = file_exists($logo1Path) 
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logo1Path))
        : '';
    $logo2Base64 = file_exists($logo2Path) 
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logo2Path))
        : '';
@endphp

{{-- ⭐ FORM PENGATURAN ⭐ --}}
<div class="no-print">
  <h3>⚙️ Pengaturan Cetak</h3>
  
  <div class="info-box">
    💡 <b>Cara Edit Manual:</b> Klik sel jadwal manapun → ketik kode → pilih dari dropdown → Enter.
    Format kode: <code>&lt;nomor_guru&gt;&lt;huruf_mapel&gt;</code> (contoh: <code>5E</code> = Guru 5, Mapel E).
    Data otomatis tersimpan ke server. Tekan <b>Esc</b> untuk batal.<br>
    ⏰ <b>Ubah Jam Istirahat:</b> Klik teks <b>ISTIRAHAT</b> di tabel jadwal → ubah jam → Simpan.
  </div>

  <div class="stats-box">
    <span>📊 <b id="jumlah-edit">0</b> edit manual tersimpan di server</span>
    <span>💾 <b id="status-server">✓ Server Terhubung</b></span>
  </div>
  
  <div class="form-grid">
    <div class="form-item">
      <label>Semester</label>
      <select id="input-semester">
        <option value="GANJIL" selected>Ganjil</option>
        <option value="GENAP">Genap</option>
      </select>
    </div>
    <div class="form-item">
      <label>Tahun Ajaran</label>
      <input type="text" id="input-tahun" value="2026/2027">
    </div>
    <div class="form-item">
      <label>Tanggal Tanda Tangan</label>
      <input type="date" id="input-tanggal" value="2026-09-30">
    </div>
    <div class="form-item">
      <label>Nama Kepala Sekolah</label>
      <input type="text" id="input-kepsek" value="Dwi Maryanto, S.Pd.T., M.Pd">
    </div>
    <div class="form-item">
      <label>NIP Kepala Sekolah</label>
      <input type="text" id="input-nip" value="198503112014031001">
    </div>
  </div>
  <div class="btn-row">
    <button class="btn" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    <button class="btn btn-warning" onclick="resetEditManual()">🗑️ Reset Semua Edit</button>
    <button class="btn btn-secondary" onclick="resetPengaturan()">🔄 Reset Pengaturan</button>
    <button class="btn btn-secondary" onclick="history.back()">← Kembali</button>
  </div>
</div>

{{-- ===== KOP SURAT ===== --}}
<div class="kop">
  <img src="{{ $logo2Base64 }}" class="logo-kiri" alt="Logo Provinsi Sumatera Selatan">
  
  <div class="kop-teks">
    <div class="pemprov">PEMERINTAH PROVINSI SUMATERA SELATAN</div>
    <div class="dinas">DINAS PENDIDIKAN</div>
    <div class="sekolah">SMA NEGERI 1 TUNGKAL ILIR</div>
    <div class="alamat">
      Jalan Komplek Pendidikan Desa Sidomulyo Kecamatan Tungkal Ilir Kabupaten Banyuasin K.P 30956
    </div>
    <div class="kontak">
      NSS : 301110711326 &nbsp;|&nbsp; NPSN : 10646154 &nbsp;|&nbsp;
      Website : sman1tulir.sch.id &nbsp;|&nbsp; Email : smanegeri1tungkalilir@yahoo.co.id
    </div>
  </div>
  
  <img src="{{ $logo1Base64 }}" class="logo-kanan" alt="Logo SMAN 1 Tungkal Ilir">
</div>

{{-- ===== JUDUL ===== --}}
<div class="judul">
  <h3>JADWAL TUGAS MENGAJAR</h3>
  <h4>
    SEMESTER <span id="display-semester">GANJIL</span> 
    TAHUN AJARAN <span id="display-tahun">2026/2027</span>
  </h4>
</div>

{{-- ===== KONTEN ===== --}}
<div class="container">
  <div class="jadwal-utama">
    <table class="jadwal">
      <thead>
        <tr>
          <th class="kolom-hari" rowspan="2">Hari</th>
          <th class="kolom-jam" rowspan="2">Jam</th>
          <th class="kolom-waktu" rowspan="2">Waktu</th>
          @php $groupedKelas = $kelas->groupBy('tingkat'); @endphp
          @foreach($groupedKelas as $tingkat => $items)
            <th colspan="{{ $items->count() }}">Kelas {{ $tingkat }}</th>
          @endforeach
        </tr>
        <tr>
          @foreach($kelas as $k)
            <th>{{ $k->nama }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @php $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat']; @endphp
        @foreach($hariList as $hari)
          @php 
            $slotHari = $slots->where('hari', $hari); 
            $totalRowspan = $slotHari->count();
            $first = true;
          @endphp
          @foreach($slotHari as $slot)
            <tr @if($slot->is_istirahat) class="baris-istirahat" @endif>
              
              @if($slot->is_istirahat)
                @if($first)
                  <td class="kolom-hari" rowspan="{{ $totalRowspan }}" style="background:#f0f0f0;">{{ strtoupper($hari) }}</td>
                  @php $first = false; @endphp
                @endif
                <td class="kolom-jam" style="background:#f5f5f5;"></td>
                <td class="kolom-waktu" style="background:#f5f5f5;"></td>
                <td colspan="{{ $kelas->count() }}" 
                    style="background:#f5f5f5; text-align:center; font-style:italic; font-size:8pt;"
                    data-istirahat-id="{{ $slot->id }}"
                    data-jam-mulai="{{ \Carbon\Carbon::parse($slot->jam_mulai)->format('H:i') }}"
                    data-jam-selesai="{{ \Carbon\Carbon::parse($slot->jam_selesai)->format('H:i') }}"
                    class="istirahat-cell">
                  <span class="istirahat-edit-trigger" onclick="editIstirahat(this)" title="Klik untuk ubah jam istirahat">
                    <span class="istirahat-label" style="color:#333;">ISTIRAHAT</span>
                    <span class="istirahat-waktu">
                      · {{ \Carbon\Carbon::parse($slot->jam_mulai)->format('H.i') }} - 
                      {{ \Carbon\Carbon::parse($slot->jam_selesai)->format('H.i') }} WIB
                    </span>
                    <span class="edit-icon">✎</span>
                  </span>
                </td>
              @else
                @if($first)
                  <td class="kolom-hari" rowspan="{{ $totalRowspan }}">{{ strtoupper($hari) }}</td>
                  @php $first = false; @endphp
                @endif
                
                <td class="kolom-jam">{{ $slot->jam_ke }}</td>
                <td class="kolom-waktu">
                  {{ \Carbon\Carbon::parse($slot->jam_mulai)->format('H.i') }}-{{ \Carbon\Carbon::parse($slot->jam_selesai)->format('H.i') }}
                </td>
                
                @foreach($kelas as $k)
                  @php 
                    $cellKey = "k{$k->id}s{$slot->id}";
                    $cell = $jadwalMap[$k->id][$slot->id] ?? null;
                    $text = $cell ? $cell['guru'] . $cell['mapel'] : '-';
                    
                    $edit = $editManual[$cellKey] ?? null;
                    if ($edit) {
                        $text = $edit['kode'];
                    }
                    
                    $isKosong = $text === '-';
                    $isEdit = $edit !== null;
                  @endphp
                  <td class="sel-edit {{ $isKosong ? 'sel-kosong' : ($isEdit ? 'sel-edit-terisi' : '') }}" 
                      data-key="{{ $cellKey }}"
                      data-kelas-id="{{ $k->id }}"
                      data-slot-id="{{ $slot->id }}"
                      onclick="startEdit(this)">{{ $text }}</td>
                @endforeach
              @endif
            </tr>
          @endforeach
        @endforeach
      </tbody>
    </table>
  </div>

  {{-- ⭐⭐ PANEL KODE + TOMBOL TAMBAH + HAPUS ⭐⭐ --}}
  <div class="panel-kode">
    
    {{-- Kode Guru --}}
    <div class="panel-header">
      <h4>Kode Guru</h4>
      <button class="btn-tambah" onclick="openModal('guru')" title="Tambah Guru Baru">
        + Tambah
      </button>
    </div>
    <table class="kode">
      <thead>
        <tr>
          <th style="width: 30px;">Kode</th>
          <th>Nama</th>
          <th class="kolom-aksi"></th>
        </tr>
      </thead>
      <tbody id="tabel-guru-body">
        @foreach($gurus as $g)
          <tr data-guru-id="{{ $g->id }}">
            <td>{{ $g->kode }}</td>
            <td style="text-align:left">{{ $g->nama }}</td>
            <td class="kolom-aksi">
              <button class="btn-hapus" 
                      onclick="hapusGuru({{ $g->id }}, '{{ addslashes($g->nama) }}')" 
                      title="Hapus guru">✕</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    {{-- Kode Mapel --}}
    <div class="panel-header">
      <h4>Kode Mata Pelajaran</h4>
      <button class="btn-tambah" onclick="openModal('mapel')" title="Tambah Mapel Baru">
        + Tambah
      </button>
    </div>
    <table class="kode">
      <thead>
        <tr>
          <th style="width: 30px;">Kode</th>
          <th>Mapel</th>
          <th class="kolom-aksi"></th>
        </tr>
      </thead>
      <tbody id="tabel-mapel-body">
        @foreach($mapels as $m)
          <tr data-mapel-id="{{ $m->id }}">
            <td>{{ $m->kode }}</td>
            <td style="text-align:left">{{ $m->nama }}</td>
            <td class="kolom-aksi">
              <button class="btn-hapus" 
                      onclick="hapusMapel({{ $m->id }}, '{{ addslashes($m->nama) }}')" 
                      title="Hapus mapel">✕</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

{{-- ===== TTD ===== --}}
<div class="ttd">
  <div class="ttd-box">
    <div>Tungkal Ilir, <span id="display-tanggal">30 September 2026</span></div>
    <div>Kepala SMA Negeri 1 Tungkal Ilir</div>
    <div class="ttd-nama" id="display-kepsek">Dwi Maryanto, S.Pd.T., M.Pd</div>
    <div>NIP. <span id="display-nip">198503112014031001</span></div>
  </div>
</div>

{{-- ⭐⭐ MODAL TAMBAH GURU / MAPEL ⭐⭐ --}}
<div class="modal-overlay" id="modal-overlay">
  <div class="modal-box">
    <h3 id="modal-title">+ Tambah Guru Baru</h3>
    
    <div class="modal-error" id="modal-error"></div>
    
    <div class="form-group">
      <label for="modal-kode">Kode</label>
      <input type="text" id="modal-kode" placeholder="Contoh: 28" maxlength="10" autocomplete="off">
    </div>
    
    <div class="form-group">
      <label for="modal-nama">Nama Lengkap</label>
      <input type="text" id="modal-nama" placeholder="Contoh: Ahmad Fauzi, S.Pd" maxlength="150" autocomplete="off">
    </div>
    
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal()">Batal</button>
      <button class="btn-save" id="modal-save-btn" onclick="submitModal()">Simpan</button>
    </div>
  </div>
</div>

{{-- ⭐ SCRIPT ⭐ --}}
<script>
// ⭐⭐ KONFIGURASI ⭐⭐
const HASIL_ID = {{ $hasil->id }};
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
const URL_EDIT = `/edit-jadwal/${HASIL_ID}`;
const URL_GURU = `/api/daftar-guru`;
const URL_MAPEL = `/api/daftar-mapel`;
const URL_TAMBAH_GURU  = `/cetak/tambah-guru`;
const URL_TAMBAH_MAPEL = `/cetak/tambah-mapel`;
const URL_HAPUS_GURU   = `/cetak/hapus-guru`;
const URL_HAPUS_MAPEL  = `/cetak/hapus-mapel`;
const URL_UPDATE_ISTIRAHAT = `/cetak/update-istirahat`;

let DAFTAR_GURU = [];
let DAFTAR_MAPEL = [];
let jumlahEdit = {{ count($editManual) }};
let modalType = 'guru';

// ============== NOTIFIKASI ==============
function showNotif(message, type = 'info') {
    document.querySelectorAll('.notif').forEach(n => n.remove());
    const notif = document.createElement('div');
    notif.className = `notif notif-${type}`;
    notif.textContent = message;
    document.body.appendChild(notif);
    setTimeout(() => {
        notif.style.opacity = '0';
        notif.style.transition = 'opacity 0.3s';
        setTimeout(() => notif.remove(), 300);
    }, 3000);
}

function updateStats(delta = 0) {
    jumlahEdit += delta;
    if (jumlahEdit < 0) jumlahEdit = 0;
    document.getElementById('jumlah-edit').textContent = jumlahEdit;
}

// ============== AUTO-SUGGEST ==============
let autocompleteBox = null;

function closeAutocomplete() {
    if (autocompleteBox) {
        autocompleteBox.remove();
        autocompleteBox = null;
    }
}

function showAutocomplete(input, td) {
    closeAutocomplete();
    const value = input.value.toUpperCase().trim();
    if (!value) return;
    
    let guruMatches = [];
    let mapelMatches = [];
    const match = value.match(/^(\d+)([A-Z]?)$/);
    
    if (match) {
        const [, nomorGuru, hurufMapel] = match;
        guruMatches = DAFTAR_GURU.filter(g => g.kode.startsWith(nomorGuru));
        if (hurufMapel) {
            mapelMatches = DAFTAR_MAPEL.filter(m => m.kode.startsWith(hurufMapel));
        }
    } else {
        guruMatches = DAFTAR_GURU.filter(g => 
            g.nama.toLowerCase().includes(value.toLowerCase())
        ).slice(0, 5);
        mapelMatches = DAFTAR_MAPEL.filter(m => 
            m.nama.toLowerCase().includes(value.toLowerCase())
        ).slice(0, 5);
    }
    
    if (guruMatches.length === 0 && mapelMatches.length === 0) return;
    
    autocompleteBox = document.createElement('div');
    autocompleteBox.className = 'autocomplete-box';
    
    if (guruMatches.length > 0) {
        const section = document.createElement('div');
        section.className = 'autocomplete-section';
        section.textContent = 'Guru';
        autocompleteBox.appendChild(section);
        
        guruMatches.slice(0, 8).forEach(g => {
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            item.innerHTML = `<span class="kode">${g.kode}</span><span class="nama">${g.nama}</span>`;
            item.onclick = (e) => {
                e.stopPropagation();
                input.value = g.kode;
                input.focus();
                showAutocomplete(input, td);
            };
            autocompleteBox.appendChild(item);
        });
    }
    
    if (mapelMatches.length > 0) {
        const section = document.createElement('div');
        section.className = 'autocomplete-section';
        section.textContent = 'Mapel';
        autocompleteBox.appendChild(section);
        
        mapelMatches.slice(0, 10).forEach(m => {
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            item.innerHTML = `<span class="kode">${m.kode}</span><span class="nama">${m.nama}</span>`;
            item.onclick = (e) => {
                e.stopPropagation();
                const current = input.value.toUpperCase();
                const guruKode = current.match(/^\d+/);
                input.value = (guruKode ? guruKode[0] : '') + m.kode;
                input.focus();
                showAutocomplete(input, td);
            };
            autocompleteBox.appendChild(item);
        });
    }
    
    const rect = td.getBoundingClientRect();
    autocompleteBox.style.left = rect.left + 'px';
    autocompleteBox.style.top = (rect.bottom + 2) + 'px';
    document.body.appendChild(autocompleteBox);
}

// ============== EDIT MANUAL ==============
function startEdit(td) {
    if (td.querySelector('input')) return;
    
    const currentText = td.textContent.trim();
    const key = td.dataset.key;
    const kelasId = td.dataset.kelasId;
    const slotId = td.dataset.slotId;
    const originalHTML = td.innerHTML;
    
    td.innerHTML = `<input type="text" value="${currentText === '-' ? '' : currentText}" 
                           placeholder="5E" maxlength="10" autocomplete="off">`;
    
    const input = td.querySelector('input');
    input.focus();
    input.select();
    
    input.addEventListener('input', () => showAutocomplete(input, td));
    input.addEventListener('focus', () => showAutocomplete(input, td));
    
    input.addEventListener('keydown', async function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            closeAutocomplete();
            await simpanEdit(td, input.value.trim().toUpperCase(), key, kelasId, slotId, originalHTML);
        } else if (e.key === 'Escape') {
            closeAutocomplete();
            td.innerHTML = originalHTML;
        }
    });
    
    input.addEventListener('blur', function() {
        setTimeout(() => {
            closeAutocomplete();
            if (td.querySelector('input')) {
                td.innerHTML = originalHTML;
            }
        }, 200);
    });
}

async function simpanEdit(td, newValue, key, kelasId, slotId, originalHTML) {
    if (newValue === '-' || newValue === '') {
        await hapusEdit(td, kelasId, slotId, originalHTML);
        return;
    }
    
    try {
        const res = await fetch(URL_EDIT, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                kelas_id: parseInt(kelasId),
                slot_id: parseInt(slotId),
                kode_manual: newValue,
            }),
        });
        
        const data = await res.json();
        
        if (data.success) {
            td.textContent = newValue;
            td.classList.remove('sel-kosong');
            td.classList.add('sel-edit-terisi');
            td.title = `${data.edit.guru_nama} — ${data.edit.mapel_nama}`;
            
            if (!originalHTML.includes(newValue)) {
                updateStats(1);
            }
            showNotif(`${data.message}`, 'success');
        } else {
            td.innerHTML = originalHTML;
            showNotif(`${data.message}`, 'error');
        }
    } catch (err) {
        td.innerHTML = originalHTML;
        showNotif(`Error: ${err.message}`, 'error');
    }
}

async function hapusEdit(td, kelasId, slotId, originalHTML) {
    try {
        const res = await fetch(URL_EDIT, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                kelas_id: parseInt(kelasId),
                slot_id: parseInt(slotId),
            }),
        });
        
        const data = await res.json();
        
        if (data.success) {
            td.innerHTML = originalHTML;
            td.classList.add('sel-kosong');
            td.classList.remove('sel-edit-terisi');
            td.removeAttribute('title');
            updateStats(-1);
            showNotif('Edit manual dihapus', 'info');
        }
    } catch (err) {
        showNotif(`Error: ${err.message}`, 'error');
    }
}

async function resetEditManual() {
    if (!confirm('Hapus SEMUA edit manual? Jadwal kembali ke hasil optimasi semula.')) return;
    
    try {
        const res = await fetch(`${URL_EDIT}/reset`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
        });
        
        const data = await res.json();
        
        if (data.success) {
            showNotif(`${data.message}`, 'success');
            setTimeout(() => location.reload(), 800);
        }
    } catch (err) {
        showNotif(`Error: ${err.message}`, 'error');
    }
}

// ============== EDIT JAM ISTIRAHAT ==============
function editIstirahat(triggerEl) {
    const td = triggerEl.closest('td');
    if (td.querySelector('.istirahat-inline-form')) return;
    
    const slotId = td.dataset.istirahatId;
    const jamMulai = td.dataset.jamMulai;
    const jamSelesai = td.dataset.jamSelesai;
    const originalHTML = td.innerHTML;
    
    td.innerHTML = `
        <div class="istirahat-inline-form">
            <label>Mulai:</label>
            <input type="time" class="input-jam-mulai" value="${jamMulai}">
            <label>Selesai:</label>
            <input type="time" class="input-jam-selesai" value="${jamSelesai}">
            <button type="button" class="btn-save-mini" onclick="simpanIstirahat(${slotId}, this)">✓ Simpan</button>
            <button type="button" class="btn-cancel-mini" onclick="batalIstirahat(this)">✕ Batal</button>
        </div>
    `;
    
    td.dataset.originalHtml = originalHTML;
    
    setTimeout(() => {
        td.querySelector('.input-jam-mulai').focus();
    }, 50);
    
    td.querySelectorAll('input[type="time"]').forEach(input => {
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                simpanIstirahat(slotId, td.querySelector('.btn-save-mini'));
            } else if (e.key === 'Escape') {
                batalIstirahat(td.querySelector('.btn-cancel-mini'));
            }
        });
    });
}

async function simpanIstirahat(slotId, btnEl) {
    const form = btnEl.closest('.istirahat-inline-form');
    const td = btnEl.closest('td');
    const jamMulai = form.querySelector('.input-jam-mulai').value;
    const jamSelesai = form.querySelector('.input-jam-selesai').value;
    
    if (!jamMulai || !jamSelesai) {
        showNotif('Jam mulai & selesai wajib diisi.', 'error');
        return;
    }
    
    if (jamSelesai <= jamMulai) {
        showNotif('Jam selesai harus lebih besar dari jam mulai.', 'error');
        return;
    }
    
    btnEl.disabled = true;
    btnEl.textContent = '...';
    
    try {
        const res = await fetch(`${URL_UPDATE_ISTIRAHAT}/${slotId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                jam_mulai: jamMulai,
                jam_selesai: jamSelesai,
            }),
        });
        
        const data = await res.json();
        
        if (data.success) {
            td.innerHTML = `
                <span class="istirahat-edit-trigger" onclick="editIstirahat(this)" title="Klik untuk ubah jam istirahat">
                    <span class="istirahat-label" style="color:#333;">ISTIRAHAT</span>
                    <span class="istirahat-waktu">
                        · ${data.jam_mulai} - ${data.jam_selesai} WIB
                    </span>
                    <span class="edit-icon">✎</span>
                </span>
            `;
            td.dataset.jamMulai = jamMulai;
            td.dataset.jamSelesai = jamSelesai;
            
            showNotif(data.message, 'success');
        } else {
            showNotif(data.message || 'Gagal menyimpan.', 'error');
            btnEl.disabled = false;
            btnEl.textContent = '✓ Simpan';
        }
    } catch (err) {
        showNotif(`Error: ${err.message}`, 'error');
        btnEl.disabled = false;
        btnEl.textContent = '✓ Simpan';
    }
}

function batalIstirahat(btnEl) {
    const td = btnEl.closest('td');
    if (td.dataset.originalHtml) {
        td.innerHTML = td.dataset.originalHtml;
        delete td.dataset.originalHtml;
    }
}

// ============== MODAL TAMBAH GURU/MAPEL ==============
function openModal(type) {
    modalType = type;
    const overlay = document.getElementById('modal-overlay');
    const title   = document.getElementById('modal-title');
    const kodeInput = document.getElementById('modal-kode');
    const namaInput = document.getElementById('modal-nama');
    const errorBox  = document.getElementById('modal-error');
    
    kodeInput.value = '';
    namaInput.value = '';
    errorBox.classList.remove('active');
    errorBox.textContent = '';
    
    if (type === 'guru') {
        title.innerHTML = '+ Tambah Guru Baru';
        kodeInput.placeholder = 'Contoh: 28';
        namaInput.placeholder = 'Contoh: Ahmad Fauzi, S.Pd';
    } else {
        title.innerHTML = '+ Tambah Mapel Baru';
        kodeInput.placeholder = 'Contoh: T';
        namaInput.placeholder = 'Contoh: Teknologi Informasi';
    }
    
    overlay.classList.add('active');
    setTimeout(() => kodeInput.focus(), 100);
}

function closeModal() {
    document.getElementById('modal-overlay').classList.remove('active');
}

async function submitModal() {
    const kode = document.getElementById('modal-kode').value.trim().toUpperCase();
    const nama = document.getElementById('modal-nama').value.trim();
    const saveBtn = document.getElementById('modal-save-btn');
    
    if (!kode) { showModalError('Kode wajib diisi.'); return; }
    if (!nama) { showModalError('Nama wajib diisi.'); return; }
    
    document.getElementById('modal-error').classList.remove('active');
    saveBtn.disabled = true;
    saveBtn.textContent = 'Menyimpan...';
    
    const url = modalType === 'guru' ? URL_TAMBAH_GURU : URL_TAMBAH_MAPEL;
    
    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ kode, nama }),
        });
        
        const data = await res.json();
        
        if (data.success) {
            if (modalType === 'guru') {
                DAFTAR_GURU.push(data.guru);
                tambahBarisTabel('tabel-guru-body', data.guru.kode, data.guru.nama, data.guru.id);
            } else {
                DAFTAR_MAPEL.push(data.mapel);
                tambahBarisTabel('tabel-mapel-body', data.mapel.kode, data.mapel.nama, data.mapel.id);
            }
            
            showNotif(`${data.message}`, 'success');
            closeModal();
        } else {
            showModalError(data.message || 'Gagal menyimpan data.');
        }
    } catch (err) {
        showModalError('Terjadi kesalahan pada server.');
    } finally {
        saveBtn.disabled = false;
        saveBtn.textContent = 'Simpan';
    }
}

function showModalError(msg) {
    const errorBox = document.getElementById('modal-error');
    errorBox.textContent = msg;
    errorBox.classList.add('active');
}

function tambahBarisTabel(tbodyId, kode, nama, id) {
    const tbody = document.getElementById(tbodyId);
    const tr = document.createElement('tr');
    
    if (tbodyId === 'tabel-guru-body') {
        tr.setAttribute('data-guru-id', id);
        tr.innerHTML = `
            <td>${kode}</td>
            <td style="text-align:left">${nama}</td>
            <td class="kolom-aksi">
                <button class="btn-hapus" onclick="hapusGuru(${id}, '${nama.replace(/'/g, "\\'")}')" title="Hapus guru">✕</button>
            </td>
        `;
    } else {
        tr.setAttribute('data-mapel-id', id);
        tr.innerHTML = `
            <td>${kode}</td>
            <td style="text-align:left">${nama}</td>
            <td class="kolom-aksi">
                <button class="btn-hapus" onclick="hapusMapel(${id}, '${nama.replace(/'/g, "\\'")}')" title="Hapus mapel">✕</button>
            </td>
        `;
    }
    
    tbody.appendChild(tr);
}

// ============== HAPUS GURU/MAPEL ==============
async function hapusGuru(id, nama) {
    if (!confirm(`Yakin ingin menghapus guru "${nama}"?\n\nData yang sudah dihapus tidak bisa dikembalikan.`)) {
        return;
    }
    
    try {
        const res = await fetch(`${URL_HAPUS_GURU}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
        });
        
        const data = await res.json();
        
        if (data.success) {
            DAFTAR_GURU = DAFTAR_GURU.filter(g => g.id !== id);
            
            const row = document.querySelector(`tr[data-guru-id="${id}"]`);
            if (row) {
                row.style.transition = 'opacity 0.3s';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 300);
            }
            
            showNotif(data.message, 'success');
        } else {
            showNotif(data.message || 'Gagal menghapus guru.', 'error');
        }
    } catch (err) {
        showNotif(`Error: ${err.message}`, 'error');
    }
}

async function hapusMapel(id, nama) {
    if (!confirm(`Yakin ingin menghapus mapel "${nama}"?\n\nData yang sudah dihapus tidak bisa dikembalikan.`)) {
        return;
    }
    
    try {
        const res = await fetch(`${URL_HAPUS_MAPEL}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
        });
        
        const data = await res.json();
        
        if (data.success) {
            DAFTAR_MAPEL = DAFTAR_MAPEL.filter(m => m.id !== id);
            
            const row = document.querySelector(`tr[data-mapel-id="${id}"]`);
            if (row) {
                row.style.transition = 'opacity 0.3s';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 300);
            }
            
            showNotif(data.message, 'success');
        } else {
            showNotif(data.message || 'Gagal menghapus mapel.', 'error');
        }
    } catch (err) {
        showNotif(`Error: ${err.message}`, 'error');
    }
}

// Tutup modal jika klik overlay
document.getElementById('modal-overlay').addEventListener('click', (e) => {
    if (e.target.id === 'modal-overlay') closeModal();
});

// Tutup modal dengan Esc
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && document.getElementById('modal-overlay').classList.contains('active')) {
        closeModal();
    }
});

// ============== PENGATURAN ==============
const DEFAULTS = {
    semester: 'GANJIL',
    tahun: '2026/2027',
    tanggal: '2026-09-30',
    kepsek: 'Dwi Maryanto, S.Pd.T., M.Pd',
    nip: '198503112014031001',
};

const BULAN = ['Januari','Februari','Maret','April','Mei','Juni',
               'Juli','Agustus','September','Oktober','November','Desember'];

function formatTanggal(isoDate) {
    if (!isoDate) return '';
    const d = new Date(isoDate);
    return `${d.getDate()} ${BULAN[d.getMonth()]} ${d.getFullYear()}`;
}

function updateDisplay() {
    const semester = document.getElementById('input-semester').value;
    const tahun    = document.getElementById('input-tahun').value;
    const tanggal  = document.getElementById('input-tanggal').value;
    const kepsek   = document.getElementById('input-kepsek').value;
    const nip      = document.getElementById('input-nip').value;

    document.getElementById('display-semester').textContent = semester;
    document.getElementById('display-tahun').textContent    = tahun;
    document.getElementById('display-tanggal').textContent  = formatTanggal(tanggal);
    document.getElementById('display-kepsek').textContent   = kepsek;
    document.getElementById('display-nip').textContent      = nip;

    localStorage.setItem('cetak_setting', JSON.stringify({
        semester, tahun, tanggal, kepsek, nip
    }));
}

function loadSettings() {
    const saved = localStorage.getItem('cetak_setting');
    if (saved) {
        const s = JSON.parse(saved);
        document.getElementById('input-semester').value = s.semester || DEFAULTS.semester;
        document.getElementById('input-tahun').value    = s.tahun    || DEFAULTS.tahun;
        document.getElementById('input-tanggal').value  = s.tanggal  || DEFAULTS.tanggal;
        document.getElementById('input-kepsek').value   = s.kepsek   || DEFAULTS.kepsek;
        document.getElementById('input-nip').value      = s.nip      || DEFAULTS.nip;
    }
}

function resetPengaturan() {
    if (!confirm('Reset pengaturan ke default?')) return;
    localStorage.removeItem('cetak_setting');
    document.getElementById('input-semester').value = DEFAULTS.semester;
    document.getElementById('input-tahun').value    = DEFAULTS.tahun;
    document.getElementById('input-tanggal').value  = DEFAULTS.tanggal;
    document.getElementById('input-kepsek').value   = DEFAULTS.kepsek;
    document.getElementById('input-nip').value      = DEFAULTS.nip;
    updateDisplay();
}

// ============== INIT ==============
document.addEventListener('DOMContentLoaded', async () => {
    loadSettings();
    updateDisplay();
    updateStats(0);
    
    try {
        const [resGuru, resMapel] = await Promise.all([
            fetch(URL_GURU),
            fetch(URL_MAPEL),
        ]);
        DAFTAR_GURU = await resGuru.json();
        DAFTAR_MAPEL = await resMapel.json();
        console.log('Loaded:', DAFTAR_GURU.length, 'guru,', DAFTAR_MAPEL.length, 'mapel');
    } catch (err) {
        console.error('Gagal load daftar:', err);
        document.getElementById('status-server').textContent = 'Server Error';
    }
    
    ['input-semester','input-tahun','input-tanggal','input-kepsek','input-nip']
        .forEach(id => {
            document.getElementById(id).addEventListener('input', updateDisplay);
            document.getElementById(id).addEventListener('change', updateDisplay);
        });
    
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.autocomplete-box') && !e.target.closest('.sel-edit') 
            && !e.target.closest('.istirahat-inline-form')) {
            closeAutocomplete();
        }
    });
});
</script>

</body>
</html>