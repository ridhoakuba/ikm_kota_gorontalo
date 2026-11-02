<x-industri-layout activeModule="laporan" pageTitle="Laporan & Tindak Lanjut" breadcrumb="Rekonsiliasi Data IKM untuk Pelaporan ke PTSP">

    <div x-data="{
        modalBuktiOpen: false,
        selectedIkm: null,
        openBukti(ikm) {
            this.selectedIkm = ikm;
            this.modalBuktiOpen = true;
        }
    }">

        {{-- ===== FLASH MESSAGES ===== --}}
        @if (session('success'))
            <div class="mb-6 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-5 py-4">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- ===== SUMMARY CARDS ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-red-500 uppercase tracking-wider mb-2">Tidak Ditemukan</p>
                <p class="text-3xl font-extrabold text-red-500 tabular-nums">{{ $totalTidakDitemukan }}</p>
                <p class="text-xs text-slate-400 mt-1">IKM tutup / pindah / tidak aktif</p>
            </div>
            <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-amber-500 uppercase tracking-wider mb-2">Belum Ditindaklanjuti</p>
                <p class="text-3xl font-extrabold text-amber-500 tabular-nums">{{ $totalBelumTindak }}</p>
                <p class="text-xs text-slate-400 mt-1">Masih menunggu penanganan</p>
            </div>
            <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-2">Sudah Ditindaklanjuti</p>
                <p class="text-3xl font-extrabold text-emerald-600 tabular-nums">{{ $totalSudahTindak }}</p>
                <p class="text-xs text-slate-400 mt-1">Selesai direkonsiliasi</p>
            </div>
        </div>

        {{-- ===== TABEL LAPORAN ===== --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            {{-- Header + Actions --}}
            <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-red-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Data IKM — Tidak Ditemukan / Tidak Aktif</h3>
                        <p class="text-xs text-slate-400">{{ $laporans->total() }} entri perlu rekonsiliasi</p>
                    </div>
                </div>

                {{-- Ekspor Buttons --}}
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <a href="{{ route('industri.ekspor') }}"
                       id="btn-ekspor-excel"
                       class="inline-flex items-center gap-2 bg-emerald-600 text-white text-xs font-semibold px-4 py-2 rounded-xl hover:bg-emerald-700 active:scale-95 transition-all duration-150 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Ekspor Excel
                    </a>
                    <button onclick="window.print()"
                            id="btn-print"
                            class="inline-flex items-center gap-2 bg-slate-700 text-white text-xs font-semibold px-4 py-2 rounded-xl hover:bg-slate-800 active:scale-95 transition-all duration-150 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </button>
                </div>
            </div>

            {{-- Filter --}}
            <div class="px-6 py-3 border-b border-slate-50 bg-slate-50/60">
                <form method="GET" action="{{ route('industri.laporan') }}" class="flex flex-wrap items-center gap-3">
                    <select name="kecamatan" id="laporan-kecamatan"
                            class="text-sm border border-slate-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-red-500 text-slate-700">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($kecamatans as $kec)
                            <option value="{{ $kec }}" {{ request('kecamatan') === $kec ? 'selected' : '' }}>{{ $kec }}</option>
                        @endforeach
                    </select>
                    <select name="tindak" id="laporan-tindak"
                            class="text-sm border border-slate-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-red-500 text-slate-700">
                        <option value="">Semua Status Tindak Lanjut</option>
                        <option value="0" {{ request('tindak') === '0' ? 'selected' : '' }}>⏳ Belum Ditindaklanjuti</option>
                        <option value="1" {{ request('tindak') === '1' ? 'selected' : '' }}>✅ Sudah Ditindaklanjuti</option>
                    </select>
                    <button type="submit" class="inline-flex items-center gap-1.5 bg-red-600 text-white text-xs font-semibold px-4 py-2 rounded-xl hover:bg-red-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>
                    @if(request()->hasAny(['kecamatan','tindak']))
                        <a href="{{ route('industri.laporan') }}" class="text-xs text-slate-400 hover:text-red-500 transition-colors">Reset</a>
                    @endif
                </form>
            </div>

            @if ($laporans->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <svg class="w-12 h-12 text-emerald-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-semibold text-slate-500">Tidak ada data yang perlu ditindaklanjuti</p>
                    <p class="text-xs text-slate-400 mt-1">Semua IKM sudah diverifikasi dengan baik 🎉</p>
                </div>
            @else
                <div class="overflow-x-auto" id="print-area">
                    <table class="w-full text-sm min-w-[850px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider w-10">#</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">NIB</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Usaha & Pemilik</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kelurahan</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Petugas Verifikasi</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Tindak</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider no-print">Aksi Rekonsiliasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($laporans as $i => $ikm)
                                <tr class="hover:bg-red-50/20 {{ $ikm->ditindaklanjuti ? 'opacity-70' : '' }}">
                                    <td class="px-5 py-3.5 text-xs text-slate-400 tabular-nums">{{ $laporans->firstItem() + $i }}</td>
                                    
                                    {{-- NIB --}}
                                    <td class="px-5 py-3.5">
                                        <span class="font-mono text-xs text-slate-600 bg-slate-100 px-2 py-0.5 rounded border">{{ $ikm->nib ?? '—' }}</span>
                                    </td>

                                    {{-- Nama Usaha & Pemilik --}}
                                    <td class="px-5 py-3.5 font-medium text-slate-800 max-w-[200px]">
                                        <p class="font-bold text-slate-900 text-sm line-clamp-1">{{ $ikm->nama_usaha ?: '—' }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $ikm->nama_pemilik ?: '—' }}</p>
                                    </td>

                                    {{-- Kelurahan & Kecamatan --}}
                                    <td class="px-5 py-3.5 text-xs text-slate-500">
                                        <span class="font-semibold text-slate-700">{{ $ikm->kelurahan?->nama_kelurahan ?? '—' }}</span>
                                        @if($ikm->kelurahan?->kecamatan)
                                            <span class="block text-[10px] text-slate-400">{{ $ikm->kelurahan->kecamatan }}</span>
                                        @endif
                                    </td>

                                    {{-- Petugas & Tanggal --}}
                                    <td class="px-5 py-3.5 text-xs text-slate-600">
                                        @if ($ikm->verifiedBy)
                                            <span class="font-semibold text-slate-800 block">{{ $ikm->verifiedBy->name }}</span>
                                        @else
                                            <span class="text-slate-400 italic">Petugas Lapangan</span>
                                        @endif
                                        @if ($ikm->verified_at)
                                            <span class="text-[10px] text-slate-400">{{ $ikm->verified_at->format('d M Y, H:i') }}</span>
                                        @endif
                                    </td>

                                    {{-- Status Tindak Lanjut --}}
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        @if ($ikm->ditindaklanjuti)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Menunggu
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-5 py-3.5 text-center no-print">
                                        <div class="flex items-center justify-center gap-2">
                                            
                                            {{-- Tombol Lihat Bukti Verifikasi --}}
                                            <button type="button"
                                                    @click="openBukti({{ json_encode($ikm) }})"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 active:scale-95 transition shadow-sm"
                                                    title="Lihat Bukti Foto & Koordinat Lapangan">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Bukti Lapangan
                                            </button>

                                            {{-- Tombol Tandai Selesai Tindak Lanjut --}}
                                            @unless ($ikm->ditindaklanjuti)
                                                <form action="{{ route('industri.tindaklanjut', $ikm) }}" method="POST"
                                                      onsubmit="return confirm('Tandai IKM \'{{ $ikm->nama_usaha }}\' sebagai sudah selesai ditindaklanjuti/direkonsiliasi?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" id="btn-tindak-{{ $ikm->id }}"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 active:scale-95 transition-all duration-150 shadow-sm">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Selesai
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-slate-300 italic px-2">Terdata</span>
                                            @endunless
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 no-print">
                    <p class="text-xs text-slate-400">
                        Menampilkan {{ $laporans->firstItem() }}–{{ $laporans->lastItem() }} dari {{ $laporans->total() }} entri
                    </p>
                    <div class="flex items-center gap-1">
                        @if ($laporans->onFirstPage())
                            <span class="px-3 py-1.5 text-xs font-medium text-slate-300 bg-slate-50 rounded-lg border border-slate-100 cursor-not-allowed">← Prev</span>
                        @else
                            <a href="{{ $laporans->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                               class="px-3 py-1.5 text-xs font-medium text-slate-600 bg-white rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">← Prev</a>
                        @endif
                        <span class="px-3 py-1.5 text-xs font-bold text-white bg-red-500 rounded-lg">{{ $laporans->currentPage() }}</span>
                        @if ($laporans->hasMorePages())
                            <a href="{{ $laporans->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                               class="px-3 py-1.5 text-xs font-medium text-slate-600 bg-white rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">Next →</a>
                        @else
                            <span class="px-3 py-1.5 text-xs font-medium text-slate-300 bg-slate-50 rounded-lg border border-slate-100 cursor-not-allowed">Next →</span>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        {{-- ===================================================================== --}}
        {{-- ===== MODAL DETAIL BUKTI VERIFIKASI LAPANGAN (INDUSTRI) ===== --}}
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

            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalBuktiOpen = false"></div>

            {{-- Modal Content --}}
            <div x-show="modalBuktiOpen"
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden my-8 z-10 border border-slate-100 flex flex-col max-h-[90vh]">
                
                {{-- Header Modal --}}
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-6 py-5 text-white flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold leading-tight">Bukti & Hasil Verifikasi Lapangan</h3>
                                <p class="text-xs text-slate-300 mt-0.5">Rekonsiliasi data faktual dari petugas kelurahan</p>
                            </div>
                        </div>
                        <button type="button" @click="modalBuktiOpen = false" class="text-slate-400 hover:text-white p-1.5 rounded-xl hover:bg-white/10 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Body Modal --}}
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

                    {{-- HASIL DYNAMIC FORM ENGINE --}}
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

                                            {{-- Jika tipe FILE --}}
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

                                            {{-- Jika tipe KOORDINAT --}}
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

                                            {{-- Jika tipe SELECT / TEXT --}}
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

                {{-- Footer Modal --}}
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

</x-industri-layout>
