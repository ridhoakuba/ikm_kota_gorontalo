<x-industri-layout activeModule="beranda" pageTitle="Beranda" breadcrumb="Statistik & Rekapitulasi Data IKM">

    @php
        $totalIkm       = $dataIkms->count();
        $totalDitemukan = $dataIkms->where('status_verifikasi', 'ditemukan')->count();
        $totalTidak     = $dataIkms->where('status_verifikasi', 'tidak_ditemukan')->count();
        $totalMenunggu  = $dataIkms->where('status_verifikasi', 'menunggu')->count();
        $pctDitemukan   = $totalIkm > 0 ? round(($totalDitemukan / $totalIkm) * 100) : 0;
        $pctTidak       = $totalIkm > 0 ? round(($totalTidak / $totalIkm) * 100) : 0;
        $pctMenunggu    = $totalIkm > 0 ? round(($totalMenunggu / $totalIkm) * 100) : 0;
    @endphp

    {{-- ===== KPI CARDS ===== --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-7">

        {{-- Total --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col gap-3 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total IKM</span>
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 00-1-1h-2a1 1 0 00-1 1v5m4 0H9"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-4xl font-extrabold text-slate-800 tabular-nums">{{ number_format($totalIkm) }}</p>
                <p class="text-xs text-slate-400 mt-1">Entri terdaftar dari PTSP</p>
            </div>
            <div class="w-full h-1 rounded-full bg-slate-100"></div>
        </div>

        {{-- Menunggu --}}
        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-5 flex flex-col gap-3 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-amber-500 uppercase tracking-wider">Menunggu</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-4xl font-extrabold text-amber-500 tabular-nums">{{ number_format($totalMenunggu) }}</p>
                <p class="text-xs text-slate-400 mt-1">Belum diverifikasi</p>
            </div>
            <div class="w-full h-1 rounded-full bg-amber-100">
                <div class="h-1 rounded-full bg-amber-400 transition-all duration-500" style="width: {{ $pctMenunggu }}%"></div>
            </div>
        </div>

        {{-- Ditemukan --}}
        <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm p-5 flex flex-col gap-3 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Ditemukan / Aktif</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-4xl font-extrabold text-emerald-600 tabular-nums">{{ number_format($totalDitemukan) }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $pctDitemukan }}% dari total IKM</p>
            </div>
            <div class="w-full h-1 rounded-full bg-emerald-100">
                <div class="h-1 rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $pctDitemukan }}%"></div>
            </div>
        </div>

        {{-- Tidak Ditemukan --}}
        <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-5 flex flex-col gap-3 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-red-500 uppercase tracking-wider">Tidak Ditemukan</span>
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-4xl font-extrabold text-red-500 tabular-nums">{{ number_format($totalTidak) }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $pctTidak }}% — Perlu tindak lanjut</p>
            </div>
            <div class="w-full h-1 rounded-full bg-red-100">
                <div class="h-1 rounded-full bg-red-500 transition-all duration-500" style="width: {{ $pctTidak }}%"></div>
            </div>
        </div>
    </div>

    {{-- ===== GRID: PROGRESS CHART + REKAP KECAMATAN ===== --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Progress Overview --}}
        <div class="xl:col-span-1 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-sm font-bold text-slate-800 mb-1">Progres Verifikasi</h3>
            <p class="text-xs text-slate-400 mb-6">Keseluruhan capaian saat ini</p>

            {{-- Donut Chart (Pure CSS) --}}
            @php
                $circumference = 2 * M_PI * 38; // r=38
                $dashDitemukan = $totalIkm > 0 ? ($totalDitemukan / $totalIkm) * $circumference : 0;
                $dashMenunggu  = $totalIkm > 0 ? ($totalMenunggu  / $totalIkm) * $circumference : 0;
                $dashTidak     = $totalIkm > 0 ? ($totalTidak     / $totalIkm) * $circumference : 0;
                $offsetDitemukan = 0;
                $offsetMenunggu  = -$dashDitemukan;
                $offsetTidak     = -$dashDitemukan - $dashMenunggu;
            @endphp

            <div class="flex items-center justify-center mb-6">
                <div class="relative">
                    <svg viewBox="0 0 100 100" class="w-40 h-40 -rotate-90">
                        <!-- Background -->
                        <circle cx="50" cy="50" r="38" fill="none" stroke="#f1f5f9" stroke-width="14"/>
                        @if($totalIkm > 0)
                        <!-- Ditemukan (emerald) -->
                        <circle cx="50" cy="50" r="38" fill="none" stroke="#10b981" stroke-width="14"
                            stroke-dasharray="{{ $dashDitemukan }} {{ $circumference - $dashDitemukan }}"
                            stroke-dashoffset="{{ $offsetDitemukan }}" stroke-linecap="butt"/>
                        <!-- Menunggu (amber) -->
                        <circle cx="50" cy="50" r="38" fill="none" stroke="#f59e0b" stroke-width="14"
                            stroke-dasharray="{{ $dashMenunggu }} {{ $circumference - $dashMenunggu }}"
                            stroke-dashoffset="{{ $offsetMenunggu }}" stroke-linecap="butt"/>
                        <!-- Tidak Ditemukan (red) -->
                        <circle cx="50" cy="50" r="38" fill="none" stroke="#ef4444" stroke-width="14"
                            stroke-dasharray="{{ $dashTidak }} {{ $circumference - $dashTidak }}"
                            stroke-dashoffset="{{ $offsetTidak }}" stroke-linecap="butt"/>
                        @endif
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-extrabold text-slate-800">{{ $pctDitemukan }}%</span>
                        <span class="text-[11px] text-slate-400">Ditemukan</span>
                    </div>
                </div>
            </div>

            <div class="space-y-2.5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span class="text-xs text-slate-600">Ditemukan / Aktif</span>
                    </div>
                    <span class="text-xs font-bold text-slate-700">{{ number_format($totalDitemukan) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="text-xs text-slate-600">Menunggu Verifikasi</span>
                    </div>
                    <span class="text-xs font-bold text-slate-700">{{ number_format($totalMenunggu) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                        <span class="text-xs text-slate-600">Tidak Ditemukan</span>
                    </div>
                    <span class="text-xs font-bold text-slate-700">{{ number_format($totalTidak) }}</span>
                </div>
            </div>
        </div>

        {{-- Rekap Per Kecamatan --}}
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Rekap per Kecamatan</h3>
                    <p class="text-xs text-slate-400">Progres verifikasi 9 kecamatan Kota Gorontalo</p>
                </div>
            </div>

            @if ($rekap->isEmpty())
                <div class="flex flex-col items-center justify-center py-14 text-center">
                    <svg class="w-10 h-10 text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm text-slate-400">Belum ada data untuk ditampilkan</p>
                </div>
            @else
                <div class="divide-y divide-slate-50">
                    @foreach ($rekap as $row)
                        @php
                            $pct = $row->total > 0 ? round(($row->ditemukan / $row->total) * 100) : 0;
                        @endphp
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">{{ $row->kecamatan ?? 'Tidak Diketahui' }}</p>
                                    <p class="text-xs text-slate-400">{{ $row->total }} IKM terdaftar</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-blue-600">{{ $pct }}%</p>
                                    <p class="text-xs text-slate-400">selesai</p>
                                </div>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-100">
                                <div class="h-2 rounded-full transition-all duration-700"
                                     style="width: {{ $pct }}%; background: linear-gradient(90deg, #2563eb, #06b6d4)"></div>
                            </div>
                            <div class="flex items-center gap-4 mt-2">
                                <span class="flex items-center gap-1 text-[11px] text-emerald-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ $row->ditemukan }} Aktif
                                </span>
                                <span class="flex items-center gap-1 text-[11px] text-red-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    {{ $row->tidak_ditemukan }} Tdk Aktif
                                </span>
                                <span class="flex items-center gap-1 text-[11px] text-amber-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                    {{ $row->menunggu }} Menunggu
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ===== QUICK LINKS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
        <a href="{{ route('industri.data-ikm') }}"
           class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200 group">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-blue-600 transition-colors duration-200">
                <svg class="w-5 h-5 text-blue-600 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-700">Import Data Excel</p>
                <p class="text-xs text-slate-400">Upload file PTSP terbaru</p>
            </div>
        </a>
        <a href="{{ route('industri.akun-kelurahan') }}"
           class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-violet-200 transition-all duration-200 group">
            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center group-hover:bg-violet-600 transition-colors duration-200">
                <svg class="w-5 h-5 text-violet-600 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-700">Reset Password</p>
                <p class="text-xs text-slate-400">Manajemen akun kelurahan</p>
            </div>
        </a>
        <a href="{{ route('industri.laporan') }}"
           class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-red-200 transition-all duration-200 group">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center group-hover:bg-red-500 transition-colors duration-200">
                <svg class="w-5 h-5 text-red-500 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-700">Laporan Tindak Lanjut</p>
                <p class="text-xs text-slate-400">IKM tidak ditemukan / tutup</p>
            </div>
        </a>
    </div>

</x-industri-layout>
