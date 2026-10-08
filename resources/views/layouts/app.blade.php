<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Penjadwalan SMAN 1 Tungkal Ilir')</title>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    
    body { 
        background: #f1f5f9;
        background-image: 
            radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.08) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(139, 92, 246, 0.06) 0px, transparent 50%);
        background-attachment: fixed;
        min-height: 100vh;
    }

    .glass-nav {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 
                    0 10px 10px -5px rgba(0, 0, 0, 0.03);
    }

    .nav-link {
        position: relative;
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .nav-link:hover {
        background: #f1f5f9;
        color: #1e40af;
    }
    .nav-link.active {
        background: linear-gradient(135deg, #1e40af, #3b82f6);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.35);
    }
    .nav-link.active i { color: white; }

    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.4s ease-out; }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, #1e40af, #3b82f6);
        color: white;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        border: none;
        cursor: pointer;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: white;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        cursor: pointer;
    }
    .btn-secondary:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .logo-footer-sman {
        width: 80px;
        height: 80px;
        object-fit: contain;
        flex-shrink: 0;
    }
    .logo-footer-ubd {
        width: 100px;
        height: 100px;
        object-fit: contain;
        flex-shrink: 0;
    }
</style>

@stack('styles')
</head>
<body>

{{-- ⭐ NAVBAR MODERN ⭐ --}}
<nav class="glass-nav sticky top-0 z-50">
    <div class="max-w-[1400px] mx-auto px-4 lg:px-6">
        <div class="flex items-center justify-between h-16">
            
            {{-- Logo & Brand --}}
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition">
                @php
                    $logoPath = public_path('assets/logo-sman1.png');
                    $logoBase64 = file_exists($logoPath) 
                        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
                        : '';
                @endphp
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo" class="w-10 h-10 object-contain">
                @else
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center text-white font-bold shadow-lg">
                        S1
                    </div>
                @endif
                <div class="hidden md:block">
                    <h1 class="text-base font-bold text-slate-800 leading-tight">
                        SMAN 1 Tungkal Ilir
                    </h1>
                    <p class="text-xs text-slate-500 font-medium">
                        Sistem Optimasi Penjadwalan
                    </p>
                </div>
            </a>

            {{-- Menu --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('dashboard') }}" 
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line text-sm"></i> Dashboard
                </a>
                <a href="{{ route('beban.index') }}" 
                   class="nav-link {{ request()->routeIs('beban.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list text-sm"></i> Beban
                </a>
                <a href="{{ route('optimasi.index') }}" 
                   class="nav-link {{ request()->routeIs('optimasi.index') ? 'active' : '' }}">
                    <i class="fas fa-play-circle text-sm"></i> Optimasi
                </a>
                <a href="{{ route('optimasi.perbandingan') }}" 
                   class="nav-link {{ request()->routeIs('optimasi.perbandingan') ? 'active' : '' }}">
                    <i class="fas fa-balance-scale text-sm"></i> Perbandingan
                </a>
            </div>

            {{-- ⭐ USER PROFILE (DIPERBAIKI) ⭐ --}}
            @php
                $profileData = null;
                try {
                    $profileData = \App\Models\UserProfile::ambil();
                } catch (\Exception $e) {
                    $profileData = null;
                }
            @endphp
            <div class="flex items-center gap-3">
                <a href="{{ route('profile.edit') }}" 
                   class="hidden lg:block text-right hover:opacity-80 transition cursor-pointer group">
                    <p class="text-sm font-semibold text-slate-700 group-hover:text-blue-600 transition">
                        {{ $profileData->nama ?? 'Administrator' }}
                    </p>
                    <p class="text-xs text-slate-500 group-hover:text-blue-500 transition">
                        {{ $profileData->jabatan ?? 'Operator Sistem' }}
                    </p>
                </a>
                <a href="{{ route('profile.edit') }}" 
                   class="w-10 h-10 rounded-full overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg hover:ring-2 hover:ring-blue-300 transition cursor-pointer"
                   title="Edit Profil">
                    @if($profileData && $profileData->fotoUrl())
                        <img src="{{ $profileData->fotoUrl() }}" 
                             alt="Foto Profil" 
                             class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($profileData->nama ?? 'A', 0, 1)) }}
                    @endif
                </a>
                <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" 
                        class="md:hidden text-slate-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="hidden md:hidden py-3 border-t border-slate-200">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 nav-link mb-1">
                <i class="fas fa-chart-line mr-2"></i> Dashboard
            </a>
            <a href="{{ route('beban.index') }}" class="block px-4 py-2 nav-link mb-1">
                <i class="fas fa-clipboard-list mr-2"></i> Beban
            </a>
            <a href="{{ route('optimasi.index') }}" class="block px-4 py-2 nav-link mb-1">
                <i class="fas fa-play-circle mr-2"></i> Optimasi
            </a>
            <a href="{{ route('optimasi.perbandingan') }}" class="block px-4 py-2 nav-link">
                <i class="fas fa-balance-scale mr-2"></i> Perbandingan
            </a>
        </div>
    </div>
</nav>

{{-- ⭐ MAIN ⭐ --}}
<main class="max-w-[1400px] mx-auto px-4 lg:px-6 py-8 animate-fade-in">
    @yield('content')
</main>

{{-- ⭐ FOOTER DENGAN LOGO BESAR ⭐ --}}
<footer class="mt-16 border-t border-slate-200 bg-white">
    <div class="max-w-[1400px] mx-auto px-4 lg:px-6 py-10">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            
            {{-- KIRI: Logo SMAN 1 Tungkal Ilir --}}
            <div class="flex items-center gap-5">
                @php
                    $logoFooterPath = public_path('assets/logo-sman1.png');
                    $logoFooterBase64 = file_exists($logoFooterPath) 
                        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoFooterPath))
                        : '';
                @endphp
                @if($logoFooterBase64)
                    <img src="{{ $logoFooterBase64 }}" 
                         alt="Logo SMAN 1 Tungkal Ilir" 
                         class="logo-footer-sman">
                @else
                    <div style="width: 80px; height: 80px;" class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center text-white text-base font-bold shadow-lg">
                        S1
                    </div>
                @endif
                <div>
                    <p class="text-base font-bold text-slate-800 leading-tight">
                        SMA Negeri 1 Tungkal Ilir
                    </p>
                    <p class="text-xs text-slate-500 mt-1">
                        Jl. Komplek Pendidikan Desa Sidomulyo
                    </p>
                    <p class="text-xs text-slate-500">
                        Kec. Tungkal Ilir, Banyuasin, Sumatera Selatan
                    </p>
                </div>
            </div>

            {{-- TENGAH: Copyright --}}
            <div class="text-center order-last md:order-none">
                <p class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} —Perbandingan GA vs SA
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    Dikembangkan untuk Optimasi Penjadwalan
                </p>
            </div>

            {{-- KANAN: Logo Universitas Bina Darma --}}
            <div class="flex items-center gap-5">
                <div class="text-right">
                    <p class="text-base font-bold text-slate-800 leading-tight">
                        Universitas Bina Darma
                    </p>
                    <p class="text-xs text-slate-500 mt-1">
                        Program Studi Teknik Informatika
                    </p>
                    <p class="text-xs text-slate-500">
                        Fakultas Sains dan Teknologi
                    </p>
                </div>
                @php
                    $logoUnivPath = public_path('assets/logo-bina-darma.png');
                    $logoUnivBase64 = file_exists($logoUnivPath) 
                        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoUnivPath))
                        : '';
                @endphp
                @if($logoUnivBase64)
                    <img src="{{ $logoUnivBase64 }}" 
                         alt="Logo Universitas Bina Darma" 
                         class="logo-footer-ubd">
                @else
                    <div style="width: 100px; height: 100px;" class="bg-gradient-to-br from-red-500 to-red-700 rounded-xl flex items-center justify-center text-white text-base font-bold shadow-lg">
                        UBD
                    </div>
                @endif
            </div>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>