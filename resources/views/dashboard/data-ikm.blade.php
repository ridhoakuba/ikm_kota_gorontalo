<x-industri-layout activeModule="data-ikm" pageTitle="Data IKM" breadcrumb="Impor & Master Data IKM Seluruh Kelurahan">

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
            </div>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('industri.data-ikm') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Kecamatan</label>
                    <select name="kecamatan" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 w-full bg-white">
                        <option value="">Semua Kecamatan</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>
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
                        @foreach(($daftarKbli ?? collect()) as $item)
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
                <table class="w-full text-sm min-w-[780px]">
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
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($dataIkms as $i => $ikm)
                            <tr class="hover:bg-blue-50/40">
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
                                <td class="px-5 py-3.5 text-slate-500 text-xs max-w-[180px]">
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
    </div>

</x-industri-layout>
