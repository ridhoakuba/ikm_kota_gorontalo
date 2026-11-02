<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $description ?? 'Sistem Informasi Verifikasi dan Monitoring Industri Kecil Menengah Kota Gorontalo' }}">

    <title>{{ $title ?? 'Beranda' }} — Verifikasi IKM Kota Gorontalo</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#172554',
                        },
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out both',
                        'fade-in':    'fadeIn 0.5s ease-out both',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%':   { opacity: '0', transform: 'translateY(24px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%':   { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                    },
                },
            },
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="font-sans antialiased bg-white text-gray-900">

    <!-- ===================== NAVBAR ===================== -->
    <header x-data="{ open: false }"
            class="fixed inset-x-0 top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm transition-all duration-300">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo & Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 group">
                    <img src="{{ asset('images/logo-gorontalo.png') }}"
                         onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/2/28/LOGO_KOTA_GORONTALO.png';"
                         alt="Logo Gorontalo"
                         class="h-10 w-auto object-contain">
                    <div class="flex flex-col justify-center">
                        <p class="text-xs sm:text-sm font-700 text-gray-900 leading-tight font-bold">Verifikasi IKM</p>
                        <p class="text-[11px] sm:text-xs text-brand-600 font-medium leading-tight">Kota Gorontalo</p>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        Beranda
                    </a>
                    <a href="{{ route('tentang') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('tentang') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        Tentang Sistem
                    </a>
                </div>

                <!-- Desktop CTA -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 text-white text-sm font-semibold
                              hover:bg-brand-700 active:scale-95 transition-all duration-200 shadow-md hover:shadow-brand-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Masuk / Login
                    </a>
                </div>

                <!-- Mobile: Hamburger -->
                <button @click="open = !open" id="mobile-menu-toggle"
                        class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors duration-200"
                        :aria-expanded="open" aria-label="Toggle menu">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div x-show="open" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="md:hidden border-t border-gray-100 py-3 space-y-1">
                <a href="{{ route('home') }}"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
                          {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Beranda
                </a>
                <a href="{{ route('tentang') }}"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
                          {{ request()->routeIs('tentang') ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tentang Sistem
                </a>
                <div class="pt-2 px-4">
                    <a href="{{ route('login') }}"
                       class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Masuk / Login
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- ===================== MAIN CONTENT ===================== -->
    <main class="pt-16">
        {{ $slot }}
    </main>

    <!-- ===================== FOOTER ===================== -->
    <footer class="bg-gray-950 text-gray-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Brand -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-gorontalo.png') }}"
                             onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/2/28/LOGO_KOTA_GORONTALO.png';"
                             alt="Logo Gorontalo"
                             class="h-10 w-auto object-contain">
                        <div>
                            <p class="text-white font-bold text-sm">Verifikasi IKM</p>
                            <p class="text-brand-400 text-xs">Kota Gorontalo</p>
                        </div>
                    </div>
                    <p class="text-sm leading-relaxed">
                        Platform digital untuk verifikasi dan monitoring Industri Kecil Menengah di Kota Gorontalo.
                    </p>
                </div>

                <!-- Navigasi -->
                <div>
                    <h3 class="text-white font-semibold text-sm mb-4">Navigasi</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ route('tentang') }}" class="hover:text-white transition-colors">Tentang Sistem</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk Sistem</a></li>
                    </ul>
                </div>

                <!-- Kontak -->
                <div>
                    <h3 class="text-white font-semibold text-sm mb-4">Instansi</h3>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Dinas Perindustrian & Perdagangan Kota Gorontalo
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                <p>© {{ date('Y') }} Sistem Verifikasi IKM Kota Gorontalo. Hak cipta dilindungi.</p>
                <p>Dikembangkan oleh <span class="text-brand-400 font-medium">Bidang Industri</span></p>
            </div>
        </div>
    </footer>

</body>
</html>
