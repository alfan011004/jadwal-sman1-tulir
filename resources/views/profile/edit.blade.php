@extends('layouts.app')

@section('title', 'Edit Profil')

@push('styles')
<style>
    .profile-card {
        background: white;
        border-radius: 1.25rem;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .profile-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        padding: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .profile-header::after {
        content: '';
        position: absolute;
        top: -60%; right: -5%;
        width: 320px; height: 320px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
        pointer-events: none;
    }
    .avatar-wrapper { position: relative; display: inline-block; }
    .avatar-img {
        width: 140px; height: 140px;
        object-fit: cover; border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        transition: transform 0.3s ease;
    }
    .avatar-img:hover { transform: scale(1.02); }
    .avatar-upload-btn {
        position: absolute;
        bottom: 5px; right: 5px;
        width: 42px; height: 42px;
        background: #3b82f6; color: white;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 3px solid white;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .avatar-upload-btn:hover { background: #2563eb; transform: scale(1.1); }
    .form-input {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.75rem;
        font-size: 0.95rem;
        color: #1e293b;
        transition: all 0.2s ease;
        background: #f8fafc;
    }
    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        background: white;
        box-shadow: 0 0 0 4px rgba(59,130,246,0.12);
    }
    .form-label {
        display: flex; align-items: center; gap: 0.5rem;
        font-weight: 600; font-size: 0.875rem;
        color: #334155; margin-bottom: 0.5rem;
    }
    .form-label i { color: #3b82f6; width: 18px; text-align: center; }
    .section-divider {
        display: flex; align-items: center; gap: 12px;
        margin: 32px 0 20px;
    }
    .section-divider .line { flex: 1; height: 1px; background: #e2e8f0; }
    .section-divider .label {
        font-size: 12px; font-weight: 700; color: #64748b;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .kepsek-avatar {
        width: 120px; height: 120px;
        object-fit: cover; border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto animate-fade-in">
    
    <nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition flex items-center gap-1.5">
            <i class="fas fa-home text-xs"></i> Dashboard
        </a>
        <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
        <span class="text-slate-800 font-semibold">Edit Profil</span>
    </nav>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 mb-6 flex items-center gap-3 shadow-sm">
            <i class="fas fa-check-circle text-green-600 text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="profile-card">
        <div class="profile-header">
            <div class="relative z-10 flex items-center gap-4">
                <div class="w-14 h-14 bg-white/15 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/20">
                    <i class="fas fa-user-edit text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Edit Profil</h1>
                    <p class="text-blue-100 text-sm mt-0.5">Kelola profil operator & foto Kepala Sekolah</p>
                </div>
            </div>
        </div>

        <div class="p-8 lg:p-10">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    
                    {{-- FOTO PROFIL OPERATOR --}}
                    <div class="lg:col-span-1 flex flex-col items-center">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Foto Profil Anda</p>
                        
                        <div class="avatar-wrapper">
                            @if($profile->fotoUrl())
                                <img src="{{ $profile->fotoUrl() }}" class="avatar-img" id="preview-image">
                                <div class="avatar-img bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-5xl font-bold hidden" id="preview-placeholder">
                                    {{ strtoupper(substr($profile->nama ?? 'A', 0, 1)) }}
                                </div>
                            @else
                                <div class="avatar-img bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-5xl font-bold" id="preview-placeholder">
                                    {{ strtoupper(substr($profile->nama ?? 'A', 0, 1)) }}
                                </div>
                                <img src="" class="avatar-img hidden" id="preview-image">
                            @endif
                            
                            <label for="foto" class="avatar-upload-btn" title="Ganti Foto">
                                <i class="fas fa-camera"></i>
                                <input type="file" name="foto" id="foto" class="hidden" accept="image/*" onchange="previewImage(this)">
                            </label>
                        </div>
                        
                        <p class="text-xs text-slate-500 mt-5 text-center">Klik ikon kamera untuk ganti foto</p>
                        
                        @if($profile->fotoUrl())
                            <button type="button" onclick="document.getElementById('delete-photo-form').submit();" class="text-xs text-red-500 font-semibold mt-3 flex items-center gap-1.5 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                                <i class="fas fa-trash-alt"></i> Hapus Foto
                            </button>
                        @endif
                    </div>

                    {{-- FORM NAMA & JABATAN --}}
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <label for="nama" class="form-label"><i class="fas fa-user"></i> Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-input" value="{{ old('nama', $profile->nama ?? '') }}" required>
                        </div>
                        <div>
                            <label for="jabatan" class="form-label"><i class="fas fa-briefcase"></i> Jabatan</label>
                            <input type="text" name="jabatan" id="jabatan" class="form-input" value="{{ old('jabatan', $profile->jabatan ?? '') }}" required>
                        </div>
                    </div>
                </div>

                {{-- ⭐⭐ SECTION FOTO KEPALA SEKOLAH ⭐⭐ --}}
                <div class="section-divider">
                    <div class="line"></div>
                    <span class="label"><i class="fas fa-user-tie mr-1"></i> Foto Kepala Sekolah</span>
                    <div class="line"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    <div class="lg:col-span-1 flex flex-col items-center">
                        <div class="avatar-wrapper">
                            @if($profile->fotoKepsekUrl())
                                <img src="{{ $profile->fotoKepsekUrl() }}" class="kepsek-avatar" id="preview-kepsek">
                                <div class="kepsek-avatar bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white hidden" id="preview-kepsek-placeholder">
                                    <i class="fas fa-user-tie text-4xl"></i>
                                </div>
                            @else
                                <div class="kepsek-avatar bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white" id="preview-kepsek-placeholder">
                                    <i class="fas fa-user-tie text-4xl"></i>
                                </div>
                                <img src="" class="kepsek-avatar hidden" id="preview-kepsek">
                            @endif
                            
                            <label for="foto_kepsek" class="avatar-upload-btn" style="background:#d97706;" title="Upload Foto Kepala Sekolah">
                                <i class="fas fa-camera"></i>
                                <input type="file" name="foto_kepsek" id="foto_kepsek" class="hidden" accept="image/*" onchange="previewKepsek(this)">
                            </label>
                        </div>
                        
                        <p class="text-xs text-slate-500 mt-5 text-center">Foto tampil di banner dashboard</p>
                        
                        @if($profile->fotoKepsekUrl())
                            <button type="button" onclick="if(confirm('Hapus foto Kepala Sekolah?')) document.getElementById('delete-kepsek-form').submit();" class="text-xs text-red-500 font-semibold mt-3 flex items-center gap-1.5 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                                <i class="fas fa-trash-alt"></i> Hapus Foto
                            </button>
                        @endif
                    </div>

                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-info-circle text-amber-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-amber-900 mb-1">Informasi</p>
                                    <p class="text-xs text-amber-800 leading-relaxed">
                                        Foto ini tampil di <strong>banner Dashboard</strong>. Gunakan foto persegi (1:1) agar tampil maksimal.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-8">
                    <a href="{{ route('dashboard') }}" class="btn-secondary w-full sm:w-auto justify-center">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn-primary w-full sm:w-auto justify-center">
                        <i class="fas fa-save"></i> Simpan Semua Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="delete-photo-form" action="{{ route('profile.hapusFoto') }}" method="POST" class="hidden">@csrf</form>
<form id="delete-kepsek-form" action="{{ route('profile.hapusFotoKepsek') }}" method="POST" class="hidden">@csrf</form>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        if (input.files[0].size > 2*1024*1024) { alert('Maks 2 MB!'); input.value=''; return; }
        var reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('preview-image');
            const ph = document.getElementById('preview-placeholder');
            if (img) { img.src = e.target.result; img.classList.remove('hidden'); }
            if (ph) ph.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function previewKepsek(input) {
    if (input.files && input.files[0]) {
        if (input.files[0].size > 2*1024*1024) { alert('Maks 2 MB!'); input.value=''; return; }
        var reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('preview-kepsek');
            const ph = document.getElementById('preview-kepsek-placeholder');
            if (img) { img.src = e.target.result; img.classList.remove('hidden'); }
            if (ph) ph.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush