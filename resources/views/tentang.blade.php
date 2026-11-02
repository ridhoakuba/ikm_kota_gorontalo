<x-guest-layout title="Tentang Sistem"
    description="Pelajari alur kerja verifikasi lapangan IKM Kota Gorontalo antara Bidang Industri dan Kelurahan">

    <!-- ============================================================
         PAGE HERO
    ============================================================ -->
    <section class="relative bg-gradient-to-br from-brand-950 via-brand-900 to-blue-900 py-24 sm:py-32 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
            <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-brand-700/20 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-blue-600/20 blur-3xl"></div>
            <div class="absolute inset-0 opacity-5"
                 style="background-image: linear-gradient(rgba(255,255,255,.15) 1px, transparent 1px),
                        linear-gradient(to right, rgba(255,255,255,.15) 1px, transparent 1px);
                        background-size: 64px 64px;">
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-white/80 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tentang Sistem
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight">
                Alur Kerja Verifikasi
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-cyan-300">
                    IKM Kota Gorontalo
                </span>
            </h1>
            <p class="text-blue-100/80 text-lg max-w-2xl mx-auto leading-relaxed">
                Memahami bagaimana Bidang Industri dan Kelurahan berkolaborasi untuk
                memastikan data IKM yang akurat dan dapat diandalkan.
            </p>
        </div>

        <div class="absolute bottom-0 inset-x-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M0 60L1440 60L1440 20C1200 60 960 0 720 15C480 30 240 60 0 20L0 60Z" fill="white"/>
            </svg>
        </div>
    </section>

    <!-- ============================================================
         LATAR BELAKANG
    ============================================================ -->
    <section class="py-20 sm:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                <!-- Teks -->
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-50 text-brand-700 text-sm font-semibold border border-brand-100">
                        Latar Belakang
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight">
                        Mengapa Sistem Ini Dibutuhkan?
                    </h2>
                    <div class="space-y-4 text-gray-600 leading-relaxed">
                        <p>
                            Data IKM yang dimiliki oleh Dinas Perindustrian Kota Gorontalo bersumber dari
                            Pelayanan Terpadu Satu Pintu (PTSP). Namun, data tersebut belum tentu mencerminkan
                            kondisi nyata di lapangan — banyak usaha yang sudah tidak aktif, pindah lokasi,
                            atau bahkan belum memiliki izin resmi.
                        </p>
                        <p>
                            Sistem Verifikasi IKM hadir sebagai solusi untuk menjembatani kesenjangan ini.
                            Dengan melibatkan petugas kelurahan sebagai ujung tombak verifikasi lapangan,
                            data IKM dapat diperbarui secara real-time dan akurat.
                        </p>
                        <p>
                            Hasilnya adalah basis data IKM yang valid, dapat digunakan untuk pengambilan
                            kebijakan, alokasi bantuan, maupun perencanaan pengembangan industri kecil
                            di Kota Gorontalo.
                        </p>
                    </div>
                </div>

                <!-- Stats cards -->
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['Kelurahan Terlibat', '2+', 'Wilayah aktif dalam sistem', 'from-brand-500 to-brand-700'],
                        ['Status Verifikasi', '3', 'Menunggu, Ditemukan, Tidak Ditemukan', 'from-blue-500 to-blue-700'],
                        ['Peran Pengguna', '2', 'Industri & Kelurahan', 'from-purple-500 to-purple-700'],
                        ['Pembaruan Data', 'Live', 'Real-time tanpa reload', 'from-green-500 to-green-700'],
                    ] as [$label, $val, $sub, $gradient])
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center mb-4">
                            <div class="w-4 h-4 rounded-full bg-white/40"></div>
                        </div>
                        <p class="text-3xl font-extrabold text-gray-900">{{ $val }}</p>
                        <p class="font-semibold text-gray-700 text-sm mt-1">{{ $label }}</p>
                        <p class="text-gray-400 text-xs mt-1 leading-relaxed">{{ $sub }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         ALUR KERJA DETAIL (Timeline)
    ============================================================ -->
    <section class="py-20 sm:py-24 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-50 text-brand-700 text-sm font-semibold border border-brand-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Alur Kerja Lengkap
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">
                    Dari Input Data Hingga Laporan Final
                </h2>
                <p class="text-gray-500 leading-relaxed">
                    Setiap tahap dirancang dengan pembagian peran yang jelas antara
                    <strong class="text-brand-700">Bidang Industri</strong> dan
                    <strong class="text-blue-700">Kelurahan</strong>.
                </p>
            </div>

            <!-- Timeline -->
            <div class="relative">
                <!-- Vertical line (desktop) -->
                <div class="hidden sm:block absolute left-1/2 top-0 bottom-0 w-0.5 bg-gradient-to-b from-brand-200 via-blue-200 to-transparent -translate-x-1/2"></div>

                <div class="space-y-8 sm:space-y-12">

                    @php
                    $steps = [
                        [
                            'peran'  => 'Bidang Industri',
                            'role'   => 'industri',
                            'num'    => '01',
                            'judul'  => 'Import & Persiapan Data IKM',
                            'desc'   => 'Admin Bidang Industri mengimpor data IKM yang bersumber dari sistem PTSP ke dalam platform. Data yang diimpor mencakup nama usaha, nama pemilik, alamat, dan jenis usaha.',
                            'icon'   => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12',
                            'color'  => 'brand',
                        ],
                        [
                            'peran'  => 'Bidang Industri',
                            'role'   => 'industri',
                            'num'    => '02',
                            'judul'  => 'Distribusi Berdasarkan Kelurahan',
                            'desc'   => 'Sistem secara otomatis mengelompokkan data IKM berdasarkan kelurahan domisili usaha. Setiap akun petugas kelurahan hanya dapat mengakses data yang berada di wilayahnya.',
                            'icon'   => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
                            'color'  => 'brand',
                        ],
                        [
                            'peran'  => 'Kelurahan',
                            'role'   => 'kelurahan',
                            'num'    => '03',
                            'judul'  => 'Login & Akses Data Wilayah',
                            'desc'   => 'Petugas kelurahan masuk ke sistem menggunakan akun yang telah dibuat oleh Bidang Industri. Mereka akan melihat daftar IKM yang berada di kelurahan mereka dengan status "Menunggu".',
                            'icon'   => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1',
                            'color'  => 'blue',
                        ],
                        [
                            'peran'  => 'Kelurahan',
                            'role'   => 'kelurahan',
                            'num'    => '04',
                            'judul'  => 'Verifikasi Lapangan',
                            'desc'   => 'Petugas turun ke lapangan untuk memverifikasi keberadaan dan kondisi setiap usaha. Mereka memperbarui status menjadi "Ditemukan" jika usaha aktif, atau "Tidak Ditemukan" jika usaha sudah tutup/tidak ada.',
                            'icon'   => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z',
                            'color'  => 'blue',
                        ],
                        [
                            'peran'  => 'Bidang Industri',
                            'role'   => 'industri',
                            'num'    => '05',
                            'judul'  => 'Monitoring Progress Real-time',
                            'desc'   => 'Bidang Industri dapat memantau progress verifikasi dari setiap kelurahan secara real-time melalui dashboard. Termasuk persentase penyelesaian dan distribusi status per kelurahan.',
                            'icon'   => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                            'color'  => 'brand',
                        ],
                        [
                            'peran'  => 'Bidang Industri',
                            'role'   => 'industri',
                            'num'    => '06',
                            'judul'  => 'Rekonsiliasi & Laporan Final',
                            'desc'   => 'Setelah verifikasi selesai, Bidang Industri melakukan rekonsiliasi data dengan database PTSP. Laporan final dapat diunduh dalam format yang siap digunakan untuk kebijakan dan perencanaan.',
                            'icon'   => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                            'color'  => 'brand',
                        ],
                    ];
                    @endphp

                    @foreach($steps as $step)
                    @php $isLeft = $loop->index % 2 === 0; @endphp
                    <div class="relative flex flex-col sm:flex-row {{ $isLeft ? '' : 'sm:flex-row-reverse' }} items-start sm:items-center gap-6 sm:gap-8">

                        <!-- Card -->
                        <div class="flex-1 {{ $isLeft ? 'sm:text-right' : 'sm:text-left' }}">
                            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow duration-200 group">
                                <!-- Role badge -->
                                <div class="flex items-center gap-2 mb-4 {{ $isLeft ? 'sm:justify-end' : 'sm:justify-start' }} justify-start">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $step['color'] === 'brand'
                                            ? 'bg-brand-50 text-brand-700 border border-brand-100'
                                            : 'bg-blue-50 text-blue-700 border border-blue-100' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $step['color'] === 'brand' ? 'bg-brand-500' : 'bg-blue-500' }}"></span>
                                        {{ $step['peran'] }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-gray-900 text-lg mb-2">{{ $step['judul'] }}</h3>
                                <p class="text-gray-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>
                            </div>
                        </div>

                        <!-- Center Node -->
                        <div class="hidden sm:flex shrink-0 w-14 h-14 rounded-2xl items-center justify-center z-10 shadow-lg font-black text-white text-base
                            {{ $step['color'] === 'brand'
                                ? 'bg-gradient-to-br from-brand-500 to-brand-700'
                                : 'bg-gradient-to-br from-blue-500 to-blue-700' }}">
                            {{ $step['num'] }}
                        </div>

                        <!-- Mobile node (left aligned) -->
                        <div class="sm:hidden flex items-center gap-3 order-first w-full">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-white text-sm shrink-0
                                {{ $step['color'] === 'brand'
                                    ? 'bg-gradient-to-br from-brand-500 to-brand-700'
                                    : 'bg-gradient-to-br from-blue-500 to-blue-700' }}">
                                {{ $step['num'] }}
                            </div>
                            <div class="h-0.5 flex-1 {{ $step['color'] === 'brand' ? 'bg-brand-100' : 'bg-blue-100' }}"></div>
                        </div>

                        <!-- Spacer (other side, desktop) -->
                        <div class="hidden sm:block flex-1"></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         PERAN & AKSES
    ============================================================ -->
    <section class="py-20 sm:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Peran dalam Sistem</h2>
                <p class="text-gray-500">Setiap pengguna memiliki hak akses dan tanggung jawab yang berbeda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">

                <!-- Bidang Industri -->
                <div class="relative bg-gradient-to-br from-brand-700 to-brand-900 rounded-3xl p-8 shadow-2xl shadow-brand-200/30 overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/5"></div>
                    <div class="space-y-6">
                        <div class="w-14 h-14 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-white/80 text-xs font-medium border border-white/20 mb-3">
                                Role: industri
                            </div>
                            <h3 class="text-2xl font-bold text-white">Bidang Industri</h3>
                            <p class="text-blue-100/70 text-sm mt-2 leading-relaxed">
                                Administrator sistem yang mengelola keseluruhan proses verifikasi.
                            </p>
                        </div>
                        <ul class="space-y-3">
                            @foreach([
                                'Import & kelola data IKM',
                                'Buat & kelola akun petugas kelurahan',
                                'Monitor progress verifikasi semua kelurahan',
                                'Unduh laporan rekap & rekonsiliasi',
                                'Akses dashboard statistik penuh',
                            ] as $a)
                            <li class="flex items-start gap-3 text-sm text-blue-100/80">
                                <svg class="w-4 h-4 text-green-300 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $a }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Kelurahan -->
                <div class="relative bg-gradient-to-br from-blue-700 to-blue-900 rounded-3xl p-8 shadow-2xl shadow-blue-200/30 overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/5"></div>
                    <div class="space-y-6">
                        <div class="w-14 h-14 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-white/80 text-xs font-medium border border-white/20 mb-3">
                                Role: kelurahan
                            </div>
                            <h3 class="text-2xl font-bold text-white">Petugas Kelurahan</h3>
                            <p class="text-blue-100/70 text-sm mt-2 leading-relaxed">
                                Verifikator lapangan yang bertugas mengonfirmasi keberadaan IKM.
                            </p>
                        </div>
                        <ul class="space-y-3">
                            @foreach([
                                'Lihat daftar IKM di wilayah kelurahan sendiri',
                                'Update status verifikasi per IKM',
                                'Catat keterangan tambahan hasil lapangan',
                                'Lihat ringkasan progress verifikasi wilayah',
                                'Hanya mengakses data kelurahan sendiri',
                            ] as $a)
                            <li class="flex items-start gap-3 text-sm text-blue-100/80">
                                <svg class="w-4 h-4 text-cyan-300 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $a }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         STATUS VERIFIKASI
    ============================================================ -->
    <section class="py-20 sm:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Status Verifikasi</h2>
                <p class="text-gray-500">Setiap IKM memiliki satu dari tiga status verifikasi berikut.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl mx-auto">
                @foreach([
                    [
                        'status' => 'Menunggu',
                        'value'  => 'menunggu',
                        'desc'   => 'IKM belum diverifikasi oleh petugas kelurahan. Status awal saat data pertama kali diimpor ke sistem.',
                        'color'  => 'yellow',
                        'icon'   => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                    [
                        'status' => 'Ditemukan',
                        'value'  => 'ditemukan',
                        'desc'   => 'Petugas kelurahan telah memverifikasi dan mengonfirmasi bahwa usaha ini aktif dan beroperasi di lokasi.',
                        'color'  => 'green',
                        'icon'   => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                    [
                        'status' => 'Tidak Ditemukan',
                        'value'  => 'tidak_ditemukan',
                        'desc'   => 'Petugas kelurahan tidak menemukan usaha di lokasi terdaftar. Usaha mungkin tutup, pindah, atau fiktif.',
                        'color'  => 'red',
                        'icon'   => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                ] as $s)
                <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm hover:shadow-md transition-shadow duration-200 text-center">
                    <div class="w-16 h-16 mx-auto rounded-2xl flex items-center justify-center mb-5
                        {{ $s['color'] === 'yellow' ? 'bg-yellow-50 border border-yellow-100'
                         : ($s['color'] === 'green' ? 'bg-green-50 border border-green-100'
                         : 'bg-red-50 border border-red-100') }}">
                        <svg class="w-8 h-8 {{ $s['color'] === 'yellow' ? 'text-yellow-500'
                                               : ($s['color'] === 'green' ? 'text-green-500'
                                               : 'text-red-500') }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $s['icon'] }}"/>
                        </svg>
                    </div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold mb-4
                        {{ $s['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-700'
                         : ($s['color'] === 'green' ? 'bg-green-100 text-green-700'
                         : 'bg-red-100 text-red-700') }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $s['color'] === 'yellow' ? 'bg-yellow-500'
                                                                : ($s['color'] === 'green' ? 'bg-green-500'
                                                                : 'bg-red-500') }}"></span>
                        {{ $s['status'] }}
                    </span>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $s['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============================================================
         CTA
    ============================================================ -->
    <section class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <h2 class="text-3xl font-extrabold text-gray-900">Siap untuk Memulai?</h2>
            <p class="text-gray-500 text-lg">Masuk ke sistem menggunakan akun yang telah diberikan.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}"
                   id="tentang-cta-login"
                   class="group inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-brand-600 text-white font-bold text-base
                          hover:bg-brand-700 active:scale-95 transition-all duration-200 shadow-xl shadow-brand-200 w-full sm:w-auto justify-center">
                    Masuk ke Sistem
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl border border-gray-200 text-gray-600
                          font-semibold text-base hover:bg-gray-50 transition-all duration-200 w-full sm:w-auto justify-center">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>

</x-guest-layout>
