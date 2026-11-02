@props(['activeModule' => 'beranda', 'pageTitle' => 'Dasbor', 'breadcrumb' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }} — {{ config('app.name', 'IKM Gorontalo') }}</title>
    <meta name="description" content="Sistem Manajemen Data IKM Kota Gorontalo — Bidang Perindustrian">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .slim-scroll::-webkit-scrollbar       { width: 4px; }
        .slim-scroll::-webkit-scrollbar-track { background: transparent; }
        .slim-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        .nav-active { box-shadow: 0 0 0 1px rgba(29,78,216,.35), inset 0 0 14px rgba(29,78,216,.12); }
        @keyframes badge-pulse { 0%,100% { opacity:1; } 50% { opacity:.55; } }
        .badge-pulse { animation: badge-pulse 2s ease-in-out infinite; }
        @media print {
            aside, header.topbar-header, footer { display: none !important; }
            .no-print { display: none !important; }
            main { padding: 0 !important; }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-slate-800">

    <!-- Elemen Wrapper Terluar -->
    <div class="flex h-screen bg-gray-50 overflow-hidden" x-data="{ sidebarOpen: true }">

        <!-- Mobile Overlay / Backdrop -->
        <div x-show="sidebarOpen"
             x-cloak
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 z-40 md:hidden"
             x-transition:enter="transition-opacity duration-300 ease-in-out"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-300 ease-in-out"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Elemen Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-0 -translate-x-full md:translate-x-0 overflow-hidden'"
               class="h-screen sticky top-0 flex flex-col justify-between overflow-y-auto transition-all duration-300 ease-in-out shrink-0 bg-slate-900 z-30 fixed inset-y-0 left-0 md:sticky md:top-0 border-r border-slate-800">

            <div class="w-64 flex flex-col flex-1 h-full justify-between">
                <!-- Brand -->
                <div class="flex items-center gap-3 px-5 py-5 border-b border-white/8 flex-shrink-0">
                    <img src="{{ asset('images/logo-gorontalo.png') }}"
                         onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/2/28/LOGO_KOTA_GORONTALO.png';"
                         alt="Logo Gorontalo"
                         class="h-10 w-auto object-contain">
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-bold text-sm truncate">Verifikasi IKM</p>
                        <p class="text-slate-400 text-xs truncate">Kota Gorontalo</p>
                    </div>
                    <button @click="sidebarOpen = false" class="md:hidden text-slate-500 hover:text-white transition-colors flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Nav Menu -->
                <nav class="flex-1 px-3 py-4 overflow-y-auto slim-scroll space-y-0.5">
                    <p class="px-3 text-[10px] font-semibold text-slate-500 uppercase tracking-widest mb-3">Menu Utama</p>

                    @php
                        $navItems = [
                            ['key' => 'beranda',         'label' => 'Beranda',         'sub' => 'Statistik',   'route' => 'industri.beranda',       'icon' => 'home'],
                            ['key' => 'data-ikm',        'label' => 'Data IKM',        'sub' => 'Impor & Master', 'route' => 'industri.data-ikm',   'icon' => 'document'],
                            ['key' => 'akun-kelurahan',  'label' => 'Akun Kelurahan',  'sub' => 'Manajemen',   'route' => 'industri.akun-kelurahan','icon' => 'users'],
                            ['key' => 'form-verifikasi', 'label' => 'Form Verifikasi', 'sub' => 'Instrumen',   'route' => 'industri.form-verifikasi','icon' => 'checklist'],
                            ['key' => 'laporan',         'label' => 'Laporan',         'sub' => 'Rekonsiliasi','route' => 'industri.laporan',        'icon' => 'chart'],
                        ];
                    @endphp

                    @foreach ($navItems as $item)
                        @php $isActive = $activeModule === $item['key']; @endphp
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 group
                                  {{ $isActive ? 'bg-blue-600 text-white nav-active' : 'text-slate-400 hover:bg-white/8 hover:text-white' }}">

                            {{-- Icons --}}
                            @if($item['icon'] === 'home')
                                <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            @elseif($item['icon'] === 'document')
                                <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @elseif($item['icon'] === 'users')
                                <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            @elseif($item['icon'] === 'checklist')
                                <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            @else
                                <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @endif

                            <span class="flex-1">{{ $item['label'] }}</span>
                            <span class="text-[10px] {{ $isActive ? 'text-blue-200' : 'text-slate-500 group-hover:text-slate-300' }}">{{ $item['sub'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <!-- User Info + Logout -->
                <div class="mt-auto border-t border-white/8 flex-shrink-0">
                    <div class="px-4 py-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow">
                                <span class="text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                                <p class="text-slate-400 text-[11px] truncate">{{ auth()->user()->email ?? '-' }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-slate-400 text-xs font-medium hover:bg-red-600/15 hover:text-red-400 transition-all duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar dari Sistem
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Elemen Konten Utama (Wrapper Header + Main Content) -->
        <div class="flex-1 min-w-0 flex flex-col transition-all duration-300 ease-in-out h-screen overflow-y-auto">

            <!-- Topbar -->
            <header class="topbar-header sticky top-0 z-20 bg-white/85 backdrop-blur-md border-b border-slate-200 flex items-center gap-4 px-4 sm:px-6 py-3.5 shadow-sm">
                <button @click="sidebarOpen = !sidebarOpen"
                        type="button"
                        aria-label="Toggle Sidebar"
                        class="flex items-center justify-center w-9 h-9 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="flex-1 min-w-0">
                    <h1 class="text-sm font-bold text-slate-800 truncate">{{ $pageTitle }}</h1>
                    @if($breadcrumb)
                        <p class="text-xs text-slate-400 truncate">{{ $breadcrumb }}</p>
                    @endif
                </div>
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100 flex-shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 badge-pulse"></span>
                    Admin Industri
                </span>
            </header>

            <!-- Flash Messages -->
            <div class="px-4 sm:px-6 lg:px-8 pt-6">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition
                         class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-5 py-4 mb-0 shadow-sm">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium flex-1">{{ session('success') }}</p>
                        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition
                         class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-4 mb-0 shadow-sm">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9V5a1 1 0 112 0v4a1 1 0 01-2 0zm0 4a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium flex-1">{{ session('error') }}</p>
                        <button @click="show = false" class="text-red-400 hover:text-red-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif
                @if (isset($errors) && $errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-4 mb-0">
                        <p class="font-semibold text-sm mb-2">Terdapat kesalahan:</p>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="border-t border-slate-200 px-6 py-4 text-center">
                <p class="text-xs text-slate-400">© {{ date('Y') }} Bidang Perindustrian — Dinas Perindag Kota Gorontalo</p>
            </footer>
        </div>

    </div>

</body>
</html>
