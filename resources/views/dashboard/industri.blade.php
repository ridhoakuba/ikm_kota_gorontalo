<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold text-gray-900 leading-tight">Dasbor Industri</h2>
                <p class="text-sm text-gray-500 mt-0.5">Selamat datang, <span class="font-semibold">{{ auth()->user()->name }}</span></p>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    Admin Industri
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-7">

            {{-- ===== FLASH MESSAGES ===== --}}
            @if (session('success'))
                <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-5 py-4">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-4">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9V5a1 1 0 112 0v4a1 1 0 01-2 0zm0 4a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-4">
                    <p class="font-semibold text-sm mb-2">Terdapat kesalahan:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ===== STATISTIK KARTU ===== --}}
            @php
                $totalIkm       = $dataIkms->count();
                $totalDitemukan = $dataIkms->where('status_verifikasi', 'ditemukan')->count();
                $totalTidak     = $dataIkms->where('status_verifikasi', 'tidak_ditemukan')->count();
                $totalMenunggu  = $dataIkms->where('status_verifikasi', 'menunggu')->count();
                $pctDitemukan   = $totalIkm > 0 ? round(($totalDitemukan / $totalIkm) * 100) : 0;
            @endphp

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total IKM</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 00-1-1h-2a1 1 0 00-1 1v5m4 0H9"/></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalIkm }}</p>
                    <p class="text-xs text-gray-400">Entri terdaftar</p>
                </div>
                {{-- Ditemukan --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ditemukan</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-emerald-600">{{ $totalDitemukan }}</p>
                    <p class="text-xs text-gray-400">{{ $pctDitemukan }}% dari total</p>
                </div>
                {{-- Tidak Ditemukan --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tidak Ditemukan</span>
                        <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-red-500">{{ $totalTidak }}</p>
                    <p class="text-xs text-gray-400">Perlu tindak lanjut</p>
                </div>
                {{-- Menunggu --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Menunggu</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-amber-500">{{ $totalMenunggu }}</p>
                    <p class="text-xs text-gray-400">Belum diverifikasi</p>
                </div>
            </div>

            {{-- ===== CARD UPLOAD EXCEL ===== --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                {{-- Header Card --}}
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Import Data IKM dari Excel</h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Kolom yang diperlukan:
                            <code class="font-mono text-blue-600 bg-blue-50 px-1 py-0.5 rounded">nib, nama_proyek, nama_perusahaan, alamat_usaha, kelurahan_usaha, judul_kbli</code>
                        </p>
                    </div>
                </div>

                {{-- Form Body --}}
                <div class="p-6">
                    <form
                        action="{{ route('ikm.import') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        x-data="{ fileName: '', dragging: false, loading: false }"
                        @submit="loading = true"
                    >
                        @csrf

                        {{-- Drop Zone --}}
                        <div
                            @dragover.prevent="dragging = true"
                            @dragleave.prevent="dragging = false"
                            @drop.prevent="
                                dragging = false;
                                const f = $event.dataTransfer.files[0];
                                if (f) { fileName = f.name; $refs.fileInput.files = $event.dataTransfer.files; }
                            "
                            :class="{
                                'border-blue-400 bg-blue-50 ring-2 ring-blue-200': dragging,
                                'border-gray-200 bg-gray-50 hover:border-blue-300 hover:bg-blue-50/50': !dragging
                            }"
                            class="border-2 border-dashed rounded-2xl p-8 text-center cursor-pointer transition-all duration-200"
                            @click="$refs.fileInput.click()"
                        >
                            <input
                                type="file"
                                id="file_excel"
                                name="file_excel"
                                accept=".xlsx,.xls"
                                class="hidden"
                                x-ref="fileInput"
                                @change="fileName = $event.target.files[0]?.name ?? ''"
                            >

                            <template x-if="!fileName">
                                <div class="space-y-2">
                                    <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-100 flex items-center justify-center">
                                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-700">Klik atau seret file Excel ke sini</p>
                                    <p class="text-xs text-gray-400">Format <span class="font-medium">.xlsx</span> atau <span class="font-medium">.xls</span> — Maks. 10 MB</p>
                                </div>
                            </template>

                            <template x-if="fileName">
                                <div class="space-y-2">
                                    <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-100 flex items-center justify-center">
                                        <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-700" x-text="fileName"></p>
                                    <p class="text-xs text-emerald-600 font-medium">File siap diupload — klik lagi untuk ganti</p>
                                </div>
                            </template>
                        </div>

                        {{-- Submit Button --}}
                        <div class="mt-4 flex justify-end">
                            <button
                                type="submit"
                                id="btn-import-excel"
                                :disabled="loading"
                                :class="loading ? 'opacity-70 cursor-not-allowed' : 'hover:bg-blue-700 active:scale-95'"
                                class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                <template x-if="!loading">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                </template>
                                <template x-if="loading">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                </template>
                                <span x-text="loading ? 'Mengimport...' : 'Import Sekarang'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ===== CARD TABEL REKAPITULASI ===== --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                {{-- Header Card --}}
                <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Rekapitulasi Seluruh Data IKM</h3>
                            <p class="text-xs text-gray-400">Menampilkan {{ $dataIkms->count() }} data dari semua kelurahan</p>
                        </div>
                    </div>
                </div>

                @if ($dataIkms->isEmpty())
                    {{-- Empty State --}}
                    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-500">Belum ada data IKM</p>
                        <p class="text-xs text-gray-400 mt-1">Import file Excel di atas untuk menambahkan data</p>
                    </div>
                @else
                    {{-- Tabel Responsif --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm min-w-[700px]">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-10">#</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">NIB</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Usaha</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Pemilik</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Kelurahan</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Jenis Usaha</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataIkms as $i => $ikm)
                                    <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/60' }} hover:bg-blue-50/40 transition-colors duration-100">
                                        <td class="px-5 py-3.5 text-xs text-gray-400 tabular-nums">{{ $i + 1 }}</td>
                                        <td class="px-5 py-3.5">
                                            <span class="font-mono text-xs text-gray-600 bg-gray-100 px-2 py-0.5 rounded">{{ $ikm->nib ?? '-' }}</span>
                                        </td>
                                        <td class="px-5 py-3.5 font-medium text-gray-800 max-w-[200px]">
                                            <span class="line-clamp-2">{{ $ikm->nama_usaha ?: '-' }}</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-600 max-w-[160px]">
                                            <span class="line-clamp-1">{{ $ikm->nama_pemilik ?: '-' }}</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-600 text-xs whitespace-nowrap">{{ $ikm->kelurahan?->nama_kelurahan ?? '-' }}</td>
                                        <td class="px-5 py-3.5 text-gray-500 text-xs max-w-[180px]">
                                            <span class="line-clamp-2">{{ $ikm->jenis_usaha ?: '-' }}</span>
                                        </td>
                                        <td class="px-5 py-3.5 whitespace-nowrap">
                                            @if ($ikm->status_verifikasi === 'ditemukan')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Ditemukan
                                                </span>
                                            @elseif ($ikm->status_verifikasi === 'tidak_ditemukan')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Tidak Ditemukan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Menunggu
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- ===== CARD TABEL MANAJEMEN AKUN KELURAHAN ===== --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                {{-- Header Card --}}
                <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-violet-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Manajemen Akun Petugas Kelurahan</h3>
                            <p class="text-xs text-gray-400">{{ $userKelurahan->count() }} akun terdaftar — klik "Reset Password" untuk mengatur ulang ke default</p>
                        </div>
                    </div>
                    <div class="self-start sm:self-auto">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-violet-100 text-violet-700 border border-violet-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            Reset = "password"
                        </span>
                    </div>
                </div>

                @if ($userKelurahan->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 px-6 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mb-3">
                            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-500">Belum ada akun kelurahan</p>
                        <p class="text-xs text-gray-400 mt-1">Jalankan seeder untuk membuat akun petugas kelurahan</p>
                    </div>
                @else
                    {{-- Tabel Responsif --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm min-w-[620px]">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-10">#</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Akun</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Kelurahan</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</th>
                                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userKelurahan as $i => $uk)
                                    <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/60' }} hover:bg-violet-50/30 transition-colors duration-100">
                                        <td class="px-5 py-3.5 text-xs text-gray-400 tabular-nums">{{ $i + 1 }}</td>
                                        <td class="px-5 py-3.5">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center flex-shrink-0">
                                                    <span class="text-white text-xs font-bold">{{ strtoupper(substr($uk->name, 0, 1)) }}</span>
                                                </div>
                                                <span class="font-medium text-gray-800 text-sm">{{ $uk->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            @if ($uk->kelurahan)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                                    {{ $uk->kelurahan->nama_kelurahan }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs italic">—</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-gray-500 font-mono">{{ $uk->email }}</td>
                                        <td class="px-5 py-3.5 text-center">
                                            <form
                                                action="{{ route('ikm.resetPasswordKelurahan', $uk) }}"
                                                method="POST"
                                                id="form-reset-pw-{{ $uk->id }}"
                                                x-data="{ loading: false }"
                                                @submit.prevent="
                                                    if (confirm('Reset password akun \"{{ addslashes($uk->name) }}\" ke default (\"password\")?')) {
                                                        loading = true;
                                                        $el.submit();
                                                    }
                                                "
                                            >
                                                @csrf
                                                <button
                                                    type="submit"
                                                    :disabled="loading"
                                                    :class="loading ? 'opacity-60 cursor-not-allowed' : 'hover:bg-violet-700 active:scale-95'"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-violet-600 text-white transition-all duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-1"
                                                >
                                                    <template x-if="!loading">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                    </template>
                                                    <template x-if="loading">
                                                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                                    </template>
                                                    <span x-text="loading ? 'Mereset...' : 'Reset Password'"></span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
