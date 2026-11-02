<x-guest-layout title="Beranda"
    description="Sistem Informasi Verifikasi dan Monitoring Industri Kecil Menengah Kota Gorontalo">

    <!-- ============================================================
         HERO SECTION
    ============================================================ -->
    <section class="relative min-h-[92vh] flex items-center overflow-hidden bg-gradient-to-br from-brand-950 via-brand-900 to-blue-900">

        <!-- Decorative background blobs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
            <div class="absolute -top-40 -right-40 w-[600px] h-[600px] rounded-full bg-brand-700/20 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] rounded-full bg-blue-600/20 blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] rounded-full bg-brand-800/10 blur-3xl"></div>

            <!-- Grid pattern overlay -->
            <div class="absolute inset-0 opacity-5"
                 style="background-image: linear-gradient(rgba(255,255,255,.15) 1px, transparent 1px),
                        linear-gradient(to right, rgba(255,255,255,.15) 1px, transparent 1px);
                        background-size: 64px 64px;">
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                <!-- Teks Utama -->
                <div class="space-y-8 animate-fade-in-up">
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-white/80 text-sm font-medium backdrop-blur-sm">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-400"></span>
                        </span>
                        Sistem Aktif — Kota Gorontalo
                    </div>

                    <!-- Judul Utama -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.1] tracking-tight">
                        Sistem Informasi
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-cyan-300">
                            Verifikasi & Monitoring
                        </span>
                        <span class="block text-white/90 text-3xl sm:text-4xl lg:text-5xl mt-1">
                            IKM Kota Gorontalo
                        </span>
                    </h1>

                    <!-- Deskripsi -->
                    <p class="text-base sm:text-lg text-blue-100/80 leading-relaxed max-w-xl">
                        Platform terpadu untuk memverifikasi keberadaan Industri Kecil Menengah secara
                        lapangan, melibatkan petugas kelurahan dan Bidang Industri Dinas Perindustrian
                        Kota Gorontalo.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <a href="{{ route('login') }}"
                           id="cta-mulai-verifikasi"
                           class="group inline-flex items-center gap-3 px-8 py-4 rounded-2xl
                                  bg-white text-brand-800 font-bold text-base
                                  hover:bg-blue-50 active:scale-95 transition-all duration-200
                                  shadow-2xl hover:shadow-white/20">
                            Mulai Verifikasi
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-200"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                      d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="{{ route('tentang') }}"
                           class="inline-flex items-center gap-2 px-6 py-4 rounded-2xl border border-white/30
                                  text-white font-semibold text-base hover:bg-white/10
                                  transition-all duration-200 backdrop-blur-sm">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>

                    <!-- Stats mini -->
                    <div class="flex flex-wrap items-center gap-6 pt-4 border-t border-white/10">
                        <div>
                            <p class="text-2xl font-bold text-white">{{ $totalKelurahan }}</p>
                            <p class="text-xs text-blue-200/70 uppercase tracking-wider font-medium">Kelurahan</p>
                        </div>
                        <div class="w-px h-8 bg-white/10"></div>
                        <div>
                            <p class="text-2xl font-bold text-white">{{ number_format($totalIkm) }}</p>
                            <p class="text-xs text-blue-200/70 uppercase tracking-wider font-medium">IKM Terdaftar</p>
                        </div>
                        <div class="w-px h-8 bg-white/10"></div>
                        <div>
                            <p class="text-2xl font-bold text-white">100%</p>
                            <p class="text-xs text-blue-200/70 uppercase tracking-wider font-medium">Digital</p>
                        </div>
                    </div>
                </div>

                <!-- Hero Illustration Card -->
                <div class="hidden lg:block animate-fade-in">
                    <div class="relative">
                        <!-- Main card -->
                        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 space-y-6 shadow-2xl">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-white/60 text-xs uppercase tracking-wider font-medium">Status Terkini</p>
                                    <p class="text-white font-bold text-lg mt-1">Monitoring IKM</p>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-green-400/20 border border-green-400/30 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Progress bars -->
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-white/70 font-medium">Ditemukan</span>
                                        <span class="text-green-300 font-bold">{{ $persenDitemukan }}%</span>
                                    </div>
                                    <div class="h-2.5 bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-green-400 to-emerald-400 rounded-full
                                                    transition-all duration-1000"
                                             style="width: {{ $persenDitemukan }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-white/70 font-medium">Menunggu Verifikasi</span>
                                        <span class="text-yellow-300 font-bold">{{ $persenMenunggu }}%</span>
                                    </div>
                                    <div class="h-2.5 bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-yellow-400 to-amber-400 rounded-full transition-all duration-1000"
                                             style="width: {{ $persenMenunggu }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-white/70 font-medium">Tidak Ditemukan</span>
                                        <span class="text-red-300 font-bold">{{ $persenTidakDitemukan }}%</span>
                                    </div>
                                    <div class="h-2.5 bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-red-400 to-rose-400 rounded-full transition-all duration-1000"
                                             style="width: {{ $persenTidakDitemukan }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent activity -->
                            <div class="pt-4 border-t border-white/10 space-y-3">
                                <p class="text-white/60 text-xs uppercase tracking-wider font-medium">Aktivitas Terbaru</p>
                                @forelse ($aktivitasTerbaru as $ikm)
                                    @php
                                        $status = $ikm->status_verifikasi;
                                        $isDitemukan = $status === \App\Models\DataIkm::STATUS_DITEMUKAN || $status === 'ditemukan';
                                        $isTidakDitemukan = $status === \App\Models\DataIkm::STATUS_TIDAK_DITEMUKAN || $status === 'tidak_ditemukan';
                                        
                                        $dotClass = $isDitemukan ? 'bg-green-400' : ($isTidakDitemukan ? 'bg-red-400' : 'bg-yellow-400');
                                        $badgeClass = $isDitemukan 
                                            ? 'bg-green-400/20 text-green-300' 
                                            : ($isTidakDitemukan ? 'bg-red-400/20 text-red-300' : 'bg-yellow-400/20 text-yellow-300');
                                        $statusLabel = $isDitemukan ? 'Ditemukan' : ($isTidakDitemukan ? 'Tidak Ditemukan' : 'Menunggu');
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full {{ $dotClass }} shrink-0"></div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-white text-sm font-medium truncate">{{ $ikm->nama_usaha }}</p>
                                            <p class="text-white/50 text-xs">{{ $ikm->kelurahan->nama_kelurahan ?? 'Kota Gorontalo' }}</p>
                                        </div>
                                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-white/50 text-xs py-2 text-center">Belum ada aktivitas verifikasi terbaru</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Floating badges -->
                        <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-2xl px-4 py-3 flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-brand-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-900 font-bold text-sm">Terverifikasi</p>
                                <p class="text-gray-500 text-xs">Real-time update</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave divider -->
        <div class="absolute bottom-0 inset-x-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M0 80L1440 80L1440 40C1200 80 960 0 720 20C480 40 240 80 0 40L0 80Z" fill="white"/>
            </svg>
        </div>
    </section>

    <!-- ============================================================
         3 PILAR FITUR
    ============================================================ -->
    <section class="py-20 sm:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-50 text-brand-700 text-sm font-semibold border border-brand-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Tiga Pilar Utama
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Infrastruktur Verifikasi yang Terintegrasi
                </h2>
                <p class="text-gray-500 text-lg leading-relaxed">
                    Sistem ini dirancang dengan tiga komponen utama yang saling mendukung untuk
                    memastikan data IKM yang akurat dan terbarui.
                </p>
            </div>

            <!-- Pilar Cards: grid responsif -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">

                <!-- Pilar 1: Distribusi Wilayah -->
                <div class="group relative bg-white rounded-3xl border border-gray-100 p-8 shadow-sm
                            hover:shadow-xl hover:border-brand-100 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-50/0 to-brand-50/60 opacity-0
                                group-hover:opacity-100 transition-opacity duration-300 rounded-3xl pointer-events-none"></div>
                    <div class="relative space-y-5">
                        <!-- Icon -->
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center shadow-lg shadow-brand-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </div>
                        <!-- Nomor -->
                        <div class="text-5xl font-black text-gray-100 select-none">01</div>
                        <div class="-mt-4 space-y-3">
                            <h3 class="text-xl font-bold text-gray-900">Distribusi Wilayah</h3>
                            <p class="text-gray-500 leading-relaxed text-sm">
                                Data IKM didistribusikan secara otomatis berdasarkan wilayah kelurahan.
                                Setiap petugas hanya mengakses dan memverifikasi data di wilayahnya,
                                memastikan kejelasan tanggung jawab dan efisiensi kerja.
                            </p>
                        </div>
                        <!-- Feature list -->
                        <ul class="space-y-2 text-sm">
                            @foreach(['Pembagian berdasarkan kelurahan', 'Akses data terbatas per wilayah', 'Rekap otomatis per kecamatan'] as $f)
                            <li class="flex items-center gap-2 text-gray-600">
                                <svg class="w-4 h-4 text-brand-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $f }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Pilar 2: Verifikasi Lapangan (highlighted) -->
                <div class="group relative bg-gradient-to-br from-brand-700 to-brand-900 rounded-3xl p-8 shadow-2xl shadow-brand-200/50 overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/5"></div>
                    <div class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-white/5"></div>
                    <div class="relative space-y-5">
                        <!-- Badge featured -->
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-white/90 text-xs font-semibold border border-white/20">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Komponen Inti
                        </div>
                        <!-- Icon -->
                        <div class="w-14 h-14 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="text-5xl font-black text-white/20 select-none">02</div>
                        <div class="-mt-4 space-y-3">
                            <h3 class="text-xl font-bold text-white">Verifikasi Lapangan</h3>
                            <p class="text-blue-100/80 leading-relaxed text-sm">
                                Petugas kelurahan melakukan survei langsung ke lokasi usaha dan memperbarui
                                status verifikasi secara real-time. Data langsung tersinkronisasi ke
                                sistem pusat Bidang Industri.
                            </p>
                        </div>
                        <ul class="space-y-2 text-sm">
                            @foreach(['Update status real-time', 'Tiga status: Menunggu / Ditemukan / Tidak Ditemukan', 'Riwayat verifikasi tercatat'] as $f)
                            <li class="flex items-center gap-2 text-blue-100/80">
                                <svg class="w-4 h-4 text-blue-300 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $f }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Pilar 3: Rekonsiliasi PTSP -->
                <div class="group relative bg-white rounded-3xl border border-gray-100 p-8 shadow-sm
                            hover:shadow-xl hover:border-brand-100 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-50/0 to-purple-50/60 opacity-0
                                group-hover:opacity-100 transition-opacity duration-300 rounded-3xl pointer-events-none"></div>
                    <div class="relative space-y-5">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center shadow-lg shadow-purple-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <div class="text-5xl font-black text-gray-100 select-none">03</div>
                        <div class="-mt-4 space-y-3">
                            <h3 class="text-xl font-bold text-gray-900">Rekonsiliasi PTSP</h3>
                            <p class="text-gray-500 leading-relaxed text-sm">
                                Hasil verifikasi lapangan direkonsiliasi dengan data izin usaha dari PTSP.
                                Bidang Industri dapat mengidentifikasi IKM aktif, tidak aktif, maupun
                                yang belum memiliki izin.
                            </p>
                        </div>
                        <ul class="space-y-2 text-sm">
                            @foreach(['Sinkronisasi data perizinan', 'Laporan rekapitulasi otomatis', 'Dashboard monitoring terpusat'] as $f)
                            <li class="flex items-center gap-2 text-gray-600">
                                <svg class="w-4 h-4 text-purple-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $f }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
         HOW IT WORKS (Alur Kerja Singkat)
    ============================================================ -->
    <section class="py-20 sm:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Cara Kerja Sistem
                </h2>
                <p class="text-gray-500">Proses verifikasi selesai dalam empat langkah sederhana.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['01', 'Import Data IKM', 'Bidang Industri mengimpor daftar IKM dari data PTSP ke sistem.', 'from-brand-500 to-brand-700', 'text-brand-600'],
                    ['02', 'Distribusi ke Kelurahan', 'Data IKM didistribusikan ke petugas kelurahan sesuai wilayah domisili usaha.', 'from-blue-500 to-blue-700', 'text-blue-600'],
                    ['03', 'Verifikasi Lapangan', 'Petugas kelurahan turun ke lapangan dan memperbarui status setiap IKM.', 'from-purple-500 to-purple-700', 'text-purple-600'],
                    ['04', 'Rekap & Monitoring', 'Bidang Industri memantau progress dan mengunduh laporan akhir.', 'from-green-500 to-green-700', 'text-green-600'],
                ] as [$num, $judul, $desc, $gradient, $textColor])
                <div class="relative bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <!-- Connector line (hidden on mobile) -->
                    @if(!$loop->last)
                    <div class="hidden lg:block absolute top-10 right-0 translate-x-1/2 w-6 h-0.5 bg-gray-200 z-10"></div>
                    @endif

                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center text-white font-black text-lg shadow-md">
                            {{ $num }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-base">{{ $judul }}</h3>
                            <p class="text-gray-500 text-sm mt-1.5 leading-relaxed">{{ $desc }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============================================================
         CTA SECTION
    ============================================================ -->
    <section class="py-20 sm:py-28 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="bg-gradient-to-br from-brand-900 via-brand-800 to-blue-900 rounded-3xl p-12 sm:p-16 shadow-2xl relative overflow-hidden">
                <!-- Decorative -->
                <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
                    <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-white/5 blur-2xl"></div>
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full bg-white/5 blur-2xl"></div>
                </div>

                <div class="relative space-y-6">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                        Siap Memulai Verifikasi?
                    </h2>
                    <p class="text-blue-100/80 text-lg max-w-xl mx-auto leading-relaxed">
                        Masuk dengan akun yang telah diberikan oleh Bidang Industri untuk mulai
                        melakukan verifikasi IKM di wilayah Anda.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                        <a href="{{ route('login') }}"
                           id="cta-bottom-login"
                           class="group inline-flex items-center gap-3 px-10 py-4 rounded-2xl bg-white
                                  text-brand-800 font-bold text-lg hover:bg-blue-50 active:scale-95
                                  transition-all duration-200 shadow-2xl w-full sm:w-auto justify-center">
                            Mulai Verifikasi Sekarang
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-200"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="{{ route('tentang') }}"
                           class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl border border-white/30
                                  text-white font-semibold text-base hover:bg-white/10 transition-all duration-200
                                  w-full sm:w-auto justify-center">
                            Tentang Sistem
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-guest-layout>
