<x-industri-layout activeModule="data-ikm" pageTitle="Data IKM" breadcrumb="Impor & Master Data IKM Seluruh Kelurahan">

<div x-data="ikmPage">

    {{-- ===== AKSI UTAMA: TAMBAH IKM MANUAL ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <p class="text-sm text-slate-500">
            Kelola Master Data IKM: impor massal dari Excel (PTSP) atau tambahkan satu data usaha secara manual.
        </p>
        <button type="button" id="btn-tambah-ikm-manual" @click="tambahOpen = true"
                class="inline-flex items-center justify-center gap-2 bg-emerald-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-emerald-700 active:scale-95 transition-all duration-150 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah IKM Manual
        </button>
    </div>

    {{-- ===== UPLOAD EXCEL ===== --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Import Data IKM dari Excel (PTSP)</h3>
                <p class="text-xs text-slate-400 mt-0.5">
                    Kolom yang diperlukan:
                    <code class="font-mono text-blue-600 bg-blue-50 px-1 py-0.5 rounded text-[11px]">nib, nama_proyek, nama_perusahaan, alamat_usaha, kelurahan_usaha, kbli, judul_kbli</code>
                </p>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('industri.import') }}" method="POST" enctype="multipart/form-data"
                  x-data="{ fileName: '', dragging: false, loading: false }"
                  @submit="loading = true">
                @csrf
                <div
                    @dragover.prevent="dragging = true"
                    @dragleave.prevent="dragging = false"
                    @drop.prevent="dragging = false; const f = $event.dataTransfer.files[0]; if (f) { fileName = f.name; $refs.fileInput.files = $event.dataTransfer.files; }"
                    :class="{ 'border-blue-400 bg-blue-50 ring-2 ring-blue-200': dragging, 'border-slate-200 bg-slate-50 hover:border-blue-300 hover:bg-blue-50/50': !dragging }"
                    class="border-2 border-dashed rounded-2xl p-8 text-center cursor-pointer transition-all duration-200"
                    @click="$refs.fileInput.click()"
                >
                    <input type="file" id="file_excel" name="file_excel" accept=".xlsx,.xls" class="hidden" x-ref="fileInput"
                           @change="fileName = $event.target.files[0]?.name ?? ''">

                    <template x-if="!fileName">
                        <div class="space-y-2">
                            <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-100 flex items-center justify-center">
                                <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">Klik atau seret file Excel ke sini</p>
                            <p class="text-xs text-slate-400">Format <span class="font-medium">.xlsx</span> atau <span class="font-medium">.xls</span> — Maks. 10 MB</p>
                        </div>
                    </template>
                    <template x-if="fileName">
                        <div class="space-y-2">
                            <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-100 flex items-center justify-center">
                                <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-700" x-text="fileName"></p>
                            <p class="text-xs text-emerald-600 font-medium">File siap diupload — klik lagi untuk ganti</p>
                        </div>
                    </template>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" id="btn-import-excel"
                            :disabled="loading"
                            :class="loading ? 'opacity-70 cursor-not-allowed' : 'hover:bg-blue-700 active:scale-95'"
                            class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all duration-150 shadow-sm">
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

    {{-- ===== FILTER + TABEL MASTER IKM ===== --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        {{-- Header + Filter --}}
        <div class="px-6 py-4 border-b border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Master Data IKM</h3>
                        <p class="text-xs text-slate-400">{{ $dataIkms->total() }} data — halaman {{ $dataIkms->currentPage() }} dari {{ $dataIkms->lastPage() }}</p>
                    </div>
                </div>
                <div class="text-xs text-slate-400">
                    Menampilkan <span class="font-semibold text-slate-600">{{ $dataIkms->count() }}</span> data per halaman
                </div>
            </div>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('industri.data-ikm') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Kecamatan</label>
                    <select name="kecamatan" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 w-full bg-white">
                        <option value="">Semua Kecamatan</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec }}" {{ request('kecamatan') === $kec ? 'selected' : '' }}>
                                {{ $kec }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Kelurahan</label>
                    <select name="kelurahan_id" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 w-full bg-white">
                        <option value="">Semua Kelurahan</option>
                        @foreach ($kelurahans as $kel)
                            <option value="{{ $kel->id }}" {{ request('kelurahan_id') == $kel->id ? 'selected' : '' }}>{{ $kel->nama_kelurahan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">KBLI / Jenis Usaha</label>
                    <select name="kbli" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 w-full bg-white">
                        <option value="">Semua KBLI</option>
                        @foreach($daftarKbli as $item)
                            @php
                                $val = $item->kode_kbli ?: $item->jenis_usaha;
                                $label = $item->kode_kbli ? ($item->kode_kbli . ' : ' . $item->jenis_usaha) : $item->jenis_usaha;
                            @endphp
                            <option value="{{ $val }}" {{ request('kbli') == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 w-full bg-white">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="ditemukan" {{ request('status') === 'ditemukan' ? 'selected' : '' }}>Ditemukan</option>
                        <option value="tidak_ditemukan" {{ request('status') === 'tidak_ditemukan' ? 'selected' : '' }}>Tidak Ditemukan</option>
                    </select>
                </div>
                <div class="sm:col-span-2 lg:col-span-4 flex items-center gap-2">
                    <button type="submit" id="btn-filter"
                            class="inline-flex items-center gap-2 bg-blue-600 text-white text-xs font-semibold px-5 py-2 rounded-xl hover:bg-blue-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Terapkan Filter
                    </button>
                    @if(request()->hasAny(['kecamatan', 'kelurahan_id', 'kbli', 'status']))
                        <a href="{{ route('industri.data-ikm') }}"
                           class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-red-500 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel --}}
        @if ($dataIkms->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-sm font-semibold text-slate-500">Tidak ada data yang cocok</p>
                <p class="text-xs text-slate-400 mt-1">Coba ubah filter atau import data Excel terlebih dahulu</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[950px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider w-10">#</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">NIB</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Usaha</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Pemilik</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kelurahan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">No KBLI</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Jenis Usaha</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Bukti / Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($dataIkms as $i => $ikm)
                            <tr class="hover:bg-blue-50/40 transition-colors">
                                <td class="px-5 py-3.5 text-xs text-slate-400 tabular-nums">{{ $dataIkms->firstItem() + $i }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="font-mono text-xs text-slate-600 bg-slate-100 px-2 py-0.5 rounded">{{ $ikm->nib ?? '—' }}</span>
                                </td>
                                <td class="px-5 py-3.5 font-medium text-slate-800 max-w-[200px]">
                                    <span class="line-clamp-2">{{ $ikm->nama_usaha ?: '—' }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 max-w-[160px]">
                                    <span class="line-clamp-1">{{ $ikm->nama_pemilik ?: '—' }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 text-xs whitespace-nowrap">
                                    {{ $ikm->kelurahan?->nama_kelurahan ?? '—' }}
                                    @if($ikm->kelurahan?->kecamatan)
                                        <span class="block text-[10px] text-slate-400">{{ $ikm->kelurahan->kecamatan }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($ikm->kode_kbli)
                                        <span class="px-2.5 py-1 text-xs font-mono font-semibold bg-slate-100 text-slate-700 rounded-md border border-slate-200">
                                            {{ $ikm->kode_kbli }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 text-xs max-w-[200px]">
                                    <span class="line-clamp-2">{{ $ikm->jenis_usaha ?: '—' }}</span>
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
                                    @if ($ikm->updated_by_role === 'industri')
                                        <span class="block text-[10px] text-blue-500 font-medium mt-1" title="Diperbarui langsung oleh Bidang Industri">
                                            ✎ Oleh Bidang Industri
                                        </span>
                                    @elseif ($ikm->updated_by_role === 'kelurahan')
                                        <span class="block text-[10px] text-slate-400 mt-1" title="Diperbarui oleh Petugas Kelurahan">
                                            ✎ Oleh Kelurahan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                    @if ($ikm->data_verifikasi)
                                        <button type="button" @click="openBukti({{ json_encode($ikm) }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 active:scale-95 transition shadow-sm"
                                                title="Lihat Bukti Foto & Koordinat Lapangan">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Bukti
                                        </button>
                                    @endif
                                        <button type="button" @click="openEdit({{ json_encode($ikm) }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 active:scale-95 transition shadow-sm"
                                                title="Detail / Edit Verifikasi & Legalitas">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Detail / Edit Verifikasi
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-slate-400">
                    Menampilkan {{ $dataIkms->firstItem() }}–{{ $dataIkms->lastItem() }} dari {{ $dataIkms->total() }} entri
                </p>
                <div class="flex items-center gap-1">
                    {{-- Previous --}}
                    @if ($dataIkms->onFirstPage())
                        <span class="px-3 py-1.5 text-xs font-medium text-slate-300 bg-slate-50 rounded-lg border border-slate-100 cursor-not-allowed">← Prev</span>
                    @else
                        <a href="{{ $dataIkms->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                           class="px-3 py-1.5 text-xs font-medium text-slate-600 bg-white rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">← Prev</a>
                    @endif

                    <span class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 rounded-lg">{{ $dataIkms->currentPage() }}</span>

                    {{-- Next --}}
                    @if ($dataIkms->hasMorePages())
                        <a href="{{ $dataIkms->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                           class="px-3 py-1.5 text-xs font-medium text-slate-600 bg-white rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">Next →</a>
                    @else
                        <span class="px-3 py-1.5 text-xs font-medium text-slate-300 bg-slate-50 rounded-lg border border-slate-100 cursor-not-allowed">Next →</span>
                    @endif
                </div>
            </div>
        @endif

        {{-- ===================================================================== --}}
        {{-- ===== MODAL DETAIL BUKTI VERIFIKASI LAPANGAN ===== --}}
        {{-- ===================================================================== --}}
        <div x-show="modalBuktiOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
             @keydown.escape.window="modalBuktiOpen = false">

            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalBuktiOpen = false"></div>

            <div x-show="modalBuktiOpen"
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden my-8 z-10 border border-slate-100 flex flex-col max-h-[90vh]">
                
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-6 py-5 text-white flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold leading-tight">Bukti Verifikasi Lapangan</h3>
                                <p class="text-xs text-slate-300 mt-0.5">Hasil pengecekan faktual petugas kelurahan</p>
                            </div>
                        </div>
                        <button type="button" @click="modalBuktiOpen = false" class="text-slate-400 hover:text-white p-1.5 rounded-xl hover:bg-white/10 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-6 flex-1 overflow-y-auto" x-show="selectedIkm">
                    {{-- Detail Identitas IKM --}}
                    <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-[11px] font-bold bg-white px-2 py-0.5 rounded border text-slate-600" x-text="'NIB: ' + (selectedIkm?.nib || '—')"></span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase"
                                      :class="selectedIkm?.status_verifikasi === 'ditemukan' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'"
                                      x-text="selectedIkm?.status_verifikasi === 'ditemukan' ? 'Ditemukan' : 'Tidak Ditemukan'"></span>
                            </div>
                            <h4 class="text-base font-bold text-slate-900 mt-1.5" x-text="selectedIkm?.nama_usaha"></h4>
                            <p class="text-slate-600 mt-0.5" x-text="'Pemilik: ' + (selectedIkm?.nama_pemilik || '—')"></p>
                            <p class="text-slate-400 text-[11px] mt-0.5" x-text="'Alamat: ' + (selectedIkm?.alamat || '—')"></p>
                        </div>
                        <div class="sm:text-right border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-200">
                            <p class="text-slate-500 font-semibold" x-text="'Kelurahan ' + (selectedIkm?.kelurahan?.nama_kelurahan || '—')"></p>
                            <p class="text-slate-400 text-[11px]" x-text="'Kec. ' + (selectedIkm?.kelurahan?.kecamatan || '—')"></p>
                            <p class="text-slate-500 text-[11px] mt-1" x-text="'Jenis: ' + (selectedIkm?.jenis_usaha || '—')"></p>
                        </div>
                    </div>

                    {{-- Informasi Petugas & Waktu --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3.5 bg-blue-50/60 rounded-2xl border border-blue-100">
                            <p class="text-[10px] uppercase font-bold text-blue-500 tracking-wider">Petugas Verifikasi</p>
                            <p class="text-sm font-bold text-blue-950 mt-0.5" x-text="selectedIkm?.verified_by?.name || (selectedIkm?.verified_by ? 'Petugas Kelurahan' : '—')"></p>
                        </div>
                        <div class="p-3.5 bg-purple-50/60 rounded-2xl border border-purple-100">
                            <p class="text-[10px] uppercase font-bold text-purple-500 tracking-wider">Waktu Verifikasi</p>
                            <p class="text-sm font-bold text-purple-950 mt-0.5" x-text="selectedIkm?.verified_at ? new Date(selectedIkm.verified_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) : '—'"></p>
                        </div>
                    </div>

                    {{-- Bukti Fisik Lapangan --}}
                    <div>
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <span>Instrumen & Bukti Lapangan</span>
                            <span class="h-px bg-slate-200 flex-1"></span>
                        </h4>

                        <div class="space-y-3">
                            <template x-if="!selectedIkm?.data_verifikasi || Object.keys(selectedIkm.data_verifikasi).length === 0">
                                <p class="text-xs text-slate-400 italic py-4 text-center">Belum ada data instrumen dinamis yang disimpan.</p>
                            </template>

                            <template x-if="selectedIkm?.data_verifikasi">
                                <div class="space-y-3">
                                    <template x-for="(item, key) in selectedIkm.data_verifikasi" :key="key">
                                        <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/70 space-y-2">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-bold text-slate-800" x-text="item.nama || key"></span>
                                                <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-white border text-slate-400 uppercase" x-text="item.tipe || 'text'"></span>
                                            </div>

                                            {{-- FILE --}}
                                            <template x-if="item.tipe === 'file'">
                                                <div class="mt-2">
                                                    <template x-if="item.nilai">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-20 h-20 rounded-xl overflow-hidden border border-slate-200 bg-white flex-shrink-0 shadow-sm">
                                                                <img :src="'{{ asset('storage') }}/' + item.nilai"
                                                                     alt="Bukti Foto"
                                                                     class="w-full h-full object-cover cursor-pointer hover:scale-105 transition duration-200"
                                                                     @click="window.open('{{ asset('storage') }}/' + item.nilai, '_blank')">
                                                            </div>
                                                            <div class="text-xs space-y-1">
                                                                <p class="font-semibold text-slate-700">Foto Bukti Fisik Lapangan</p>
                                                                <a :href="'{{ asset('storage') }}/' + item.nilai" target="_blank"
                                                                   class="inline-flex items-center gap-1 text-teal-600 hover:text-teal-700 font-bold underline">
                                                                    <span>Buka Foto Ukuran Penuh</span>
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <template x-if="!item.nilai">
                                                        <span class="text-xs text-slate-400 italic">Tidak ada file yang diunggah</span>
                                                    </template>
                                                </div>
                                            </template>

                                            {{-- KOORDINAT --}}
                                            <template x-if="item.tipe === 'koordinat'">
                                                <div class="flex items-center gap-2 mt-1">
                                                    <code class="text-xs font-mono bg-white px-3 py-1.5 rounded-xl border border-slate-200 text-blue-700 font-bold" x-text="item.nilai || 'Belum diisi'"></code>
                                                    <template x-if="item.nilai">
                                                        <a :href="'https://www.google.com/maps?q=' + encodeURIComponent(item.nilai)" target="_blank"
                                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition shadow-sm">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                            Buka di Google Maps ↗
                                                        </a>
                                                    </template>
                                                </div>
                                            </template>

                                            {{-- SELECT / TEXT --}}
                                            <template x-if="item.tipe !== 'file' && item.tipe !== 'koordinat'">
                                                <div class="bg-white p-3 rounded-xl border border-slate-200/80 text-xs text-slate-800">
                                                    <span class="font-medium" x-text="item.nilai || '—'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Catatan Petugas Lapangan --}}
                    <div class="border-t border-slate-100 pt-4">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan Petugas Lapangan</label>
                        <div class="p-3 bg-amber-50/70 border border-amber-200/80 rounded-2xl text-xs text-amber-900 leading-relaxed italic"
                             x-text="selectedIkm?.catatan_petugas || 'Tidak ada catatan tambahan.'"></div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between flex-shrink-0">
                    <span class="text-xs text-slate-400">Verifikasi Terintegrasi Dinas Perindag Kota Gorontalo</span>
                    <button type="button" @click="modalBuktiOpen = false"
                            class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-sm transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL TAMBAH IKM MANUAL & MODAL DETAIL / EDIT VERIFIKASI ===== --}}
    @include('industri.partials.modal-tambah-ikm')
    @include('industri.partials.modal-edit-verifikasi')

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('ikmPage', () => ({
            // ===== Modal Bukti (read-only) =====
            modalBuktiOpen: false,
            selectedIkm: null,
            openBukti(ikm) {
                this.selectedIkm = ikm;
                this.modalBuktiOpen = true;
            },

            // ===== Modal Tambah IKM Manual =====
            tambahOpen: {{ old('_form') === 'tambah-manual' ? 'true' : 'false' }},
            kelurahans: @js($kelurahans->map(fn ($k) => ['id' => $k->id, 'nama' => $k->nama_kelurahan, 'kecamatan' => $k->kecamatan])->values()),
            daftarKbli: @js($daftarKbli->map(fn ($k) => ['kode' => $k->kode_kbli ?? '', 'judul' => $k->jenis_usaha])->values()),
            tambah: {
                kecamatan: @js(old('_form') === 'tambah-manual' ? old('kecamatan', '') : ''),
                kelurahan_id: @js(old('_form') === 'tambah-manual' ? (string) old('kelurahan_id', '') : ''),
                kode_kbli: @js(old('_form') === 'tambah-manual' ? old('kode_kbli', '') : ''),
                jenis_usaha: @js(old('_form') === 'tambah-manual' ? old('jenis_usaha', old('judul_kbli', '')) : ''),
            },
            get kelurahanOptions() {
                if (!this.tambah.kecamatan) return this.kelurahans;
                return this.kelurahans.filter(k => k.kecamatan === this.tambah.kecamatan);
            },
            onKecamatanChange() {
                if (!this.kelurahanOptions.some(k => String(k.id) === String(this.tambah.kelurahan_id))) {
                    this.tambah.kelurahan_id = '';
                }
            },
            onSelectKbliPreset(idx) {
                if (idx === '' || !this.daftarKbli[idx]) return;
                this.tambah.kode_kbli = this.daftarKbli[idx].kode || '';
                this.tambah.jenis_usaha = this.daftarKbli[idx].judul || '';
            },

            // ===== Modal Detail / Edit Verifikasi (Bidang Industri) =====
            editOpen: false,
            activeIkm: null,
            formAction: '',
            selectedStatus: 'menunggu',
            catatanPetugas: '',
            editKbli: { kode_kbli: '', jenis_usaha: '' },
            legalitas: { sertifikat_halal: '', izin_bpom: '', pirt: '' },
            answers: {},
            geoLoading: false,

            openEdit(ikm) {
                this.activeIkm = ikm;
                this.formAction = '{{ url('/ikm') }}/' + ikm.id + '/status';
                this.selectedStatus = ikm.status_verifikasi || 'menunggu';
                this.catatanPetugas = ikm.catatan_petugas || '';
                this.editKbli = {
                    kode_kbli: ikm.kode_kbli || '',
                    jenis_usaha: ikm.jenis_usaha || '',
                };
                this.legalitas = {
                    sertifikat_halal: ikm.sertifikat_halal || '',
                    izin_bpom: ikm.izin_bpom || '',
                    pirt: ikm.pirt || '',
                };

                let rawData = ikm.data_verifikasi;
                this.answers = {};
                if (typeof rawData === 'string') {
                    try { rawData = JSON.parse(rawData); } catch (e) {}
                }
                if (rawData && typeof rawData === 'object') {
                    for (let k in rawData) {
                        if (rawData[k] && typeof rawData[k] === 'object' && 'nilai' in rawData[k]) {
                            this.answers[k] = rawData[k].nilai;
                        } else {
                            this.answers[k] = rawData[k];
                        }
                    }
                }

                this.editOpen = true;
            },

            getGPSLocation(slug) {
                if (!navigator.geolocation) {
                    alert('Fitur GPS tidak didukung oleh browser Anda.');
                    return;
                }
                this.geoLoading = true;
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        this.answers[slug] = pos.coords.latitude.toFixed(6) + ', ' + pos.coords.longitude.toFixed(6);
                        this.geoLoading = false;
                    },
                    (err) => {
                        this.geoLoading = false;
                        let msg = 'Gagal mendeteksi lokasi GPS: ';
                        if (err.code === 1) msg += 'Izin akses lokasi ditolak oleh pengguna.';
                        else if (err.code === 2) msg += 'Titik lokasi tidak tersedia.';
                        else if (err.code === 3) msg += 'Waktu permintaan habis.';
                        else msg += err.message;
                        alert(msg);
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            },
        }));
    });
</script>

</x-industri-layout>
