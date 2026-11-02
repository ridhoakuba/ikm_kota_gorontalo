<x-app-layout>
    {{-- ===== ALPINE.JS DATA: DASHBOARD STATE ===== --}}
    <div x-data="{
        modalGantiPassword: {{ $errors->hasAny(['password_lama','password_baru','password_baru_confirmation']) || session('modal_open') === 'ganti-password' ? 'true' : 'false' }},
        showLama: false,
        showBaru: false,
        showKonfirmasi: false,
        
        {{-- State Modal Verifikasi Lapangan --}}
        modalVerifikasi: false,
        activeIkm: null,
        formAction: '',
        selectedStatus: 'ditemukan',
        catatanPetugas: '',
        answers: {},
        geoLoading: false,

        openVerifikasi(ikm, defaultStatus = 'ditemukan') {
            this.activeIkm = ikm;
            this.formAction = '{{ url('/ikm') }}/' + ikm.id + '/status';
            this.selectedStatus = (ikm.status_verifikasi && ikm.status_verifikasi !== 'menunggu') ? ikm.status_verifikasi : defaultStatus;
            this.catatanPetugas = ikm.catatan_petugas || '';

            // Ekstrak data verifikasi tersimpan
            let rawData = ikm.data_verifikasi;
            this.answers = {};
            if (typeof rawData === 'string') {
                try { rawData = JSON.parse(rawData); } catch(e) {}
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

            this.modalVerifikasi = true;
        },

        getGPSLocation(slug) {
            if (!navigator.geolocation) {
                alert('Fitur GPS tidak didukung oleh browser Anda.');
                return;
            }
            this.geoLoading = true;
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const lat = pos.coords.latitude.toFixed(6);
                    const lng = pos.coords.longitude.toFixed(6);
                    const val = lat + ', ' + lng;
                    this.answers[slug] = val;
                    const inputEl = document.getElementById('input-coord-' + slug);
                    if (inputEl) inputEl.value = val;
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
        }
    }">

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold text-gray-900 leading-tight">Dasbor Verifikasi Lapangan</h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ auth()->user()->name }}
                    @if(auth()->user()->kelurahan)
                        <span class="mx-1 text-gray-300">·</span>
                        Kelurahan <span class="font-semibold text-gray-700">{{ auth()->user()->kelurahan->nama_kelurahan }}</span>
                        @if(auth()->user()->kelurahan->kecamatan)
                            <span class="text-xs text-gray-400">({{ auth()->user()->kelurahan->kecamatan }})</span>
                        @endif
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Petugas Kelurahan
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
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-4">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9V5a1 1 0 112 0v4a1 1 0 01-2 0zm0 4a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-semibold">{{ session('error') }}</p>
                </div>
            @endif

            {{-- ===== STATISTIK KARTU ===== --}}
            @php
                $totalIkm       = $dataIkms->count();
                $totalDitemukan = $dataIkms->where('status_verifikasi', 'ditemukan')->count();
                $totalTidak     = $dataIkms->where('status_verifikasi', 'tidak_ditemukan')->count();
                $totalMenunggu  = $dataIkms->where('status_verifikasi', 'menunggu')->count();
                $pctSelesai     = $totalIkm > 0 ? round((($totalDitemukan + $totalTidak) / $totalIkm) * 100) : 0;
            @endphp

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total IKM</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 00-1-1h-2a1 1 0 00-1 1v5m4 0H9"/></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalIkm }}</p>
                    <p class="text-xs text-gray-400">Di wilayah {{ auth()->user()->kelurahan?->nama_kelurahan ?? 'Anda' }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ditemukan</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-emerald-600">{{ $totalDitemukan }}</p>
                    <p class="text-xs text-gray-400">Terverifikasi ada & aktif</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tidak Ditemukan</span>
                        <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-red-500">{{ $totalTidak }}</p>
                    <p class="text-xs text-gray-400">Tutup / pindah alamat</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Progres</span>
                        <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-blue-600">{{ $pctSelesai }}<span class="text-lg">%</span></p>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1">
                        <div class="bg-blue-500 h-1.5 rounded-full transition-all" style="width: {{ $pctSelesai }}%"></div>
                    </div>
                </div>
            </div>

            {{-- ===== CARD TABEL DATA IKM ===== --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                {{-- Header Card --}}
                <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">
                                Daftar IKM — Kelurahan {{ auth()->user()->kelurahan?->nama_kelurahan ?? 'Anda' }}
                            </h3>
                            <p class="text-xs text-gray-400">{{ $dataIkms->count() }} data — klik tombol "Isi Verifikasi" untuk pengisian bukti lapangan</p>
                        </div>
                    </div>

                    @if ($totalMenunggu > 0)
                        <span class="self-start sm:self-auto inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            {{ $totalMenunggu }} perlu diverifikasi
                        </span>
                    @else
                        <span class="self-start sm:self-auto inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                            ✓ Seluruh data telah terverifikasi
                        </span>
                    @endif
                </div>

                @if ($dataIkms->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-500">Belum ada data IKM di kelurahan ini</p>
                        <p class="text-xs text-gray-400 mt-1">Data akan otomatis tampil setelah Admin Industri mengunggah file Excel master</p>
                    </div>
                @else
                    {{-- Tabel Data IKM --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm min-w-[750px]">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50/50">
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-10">#</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">NIB</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Usaha & Pemilik</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Alamat & Jenis Usaha</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataIkms as $i => $ikm)
                                    <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/60' }} hover:bg-emerald-50/20 transition-colors">
                                        <td class="px-5 py-4 text-xs text-gray-400 tabular-nums">{{ $i + 1 }}</td>
                                        
                                        {{-- NIB --}}
                                        <td class="px-5 py-4">
                                            <span class="font-mono text-xs text-gray-600 bg-gray-100 px-2 py-0.5 rounded border border-gray-200">
                                                {{ $ikm->nib ?? '—' }}
                                            </span>
                                        </td>

                                        {{-- Usaha & Pemilik --}}
                                        <td class="px-5 py-4 max-w-[200px]">
                                            <p class="font-bold text-gray-900 text-sm leading-snug">{{ $ikm->nama_usaha ?: '—' }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                {{ $ikm->nama_pemilik ?: '—' }}
                                            </p>
                                        </td>

                                        {{-- Alamat & Jenis --}}
                                        <td class="px-5 py-4 text-xs text-gray-600 max-w-[220px]">
                                            <p class="line-clamp-2 text-gray-700">{{ $ikm->alamat ?: '—' }}</p>
                                            <p class="text-[11px] text-gray-400 mt-1 line-clamp-1 italic">{{ $ikm->jenis_usaha ?: '—' }}</p>
                                        </td>

                                        {{-- Badge Status --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            @if ($ikm->status_verifikasi === 'ditemukan')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                    Ditemukan
                                                </span>
                                                @if($ikm->verified_at)
                                                    <span class="block text-[10px] text-gray-400 mt-1">
                                                        {{ $ikm->verified_at->format('d/m/Y H:i') }}
                                                    </span>
                                                @endif
                                            @elseif ($ikm->status_verifikasi === 'tidak_ditemukan')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                                    Tidak Ada
                                                </span>
                                                @if($ikm->verified_at)
                                                    <span class="block text-[10px] text-gray-400 mt-1">
                                                        {{ $ikm->verified_at->format('d/m/Y H:i') }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Menunggu
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Tombol Aksi Verifikasi Dinamis --}}
                                        <td class="px-5 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                @if ($ikm->status_verifikasi === 'menunggu')
                                                    {{-- Tombol Utama: Buka Modal Verifikasi Lapangan --}}
                                                    <button type="button"
                                                            @click="openVerifikasi({{ json_encode($ikm) }}, 'ditemukan')"
                                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white shadow-sm hover:shadow transition-all duration-150">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                                        </svg>
                                                        Verifikasi Lapangan
                                                    </button>
                                                @else
                                                    {{-- Sudah Diverifikasi: Tombol Edit & Reset --}}
                                                    <button type="button"
                                                            @click="openVerifikasi({{ json_encode($ikm) }})"
                                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 active:scale-95 transition-all">
                                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Ubah Data
                                                    </button>

                                                    <form action="{{ route('ikm.updateStatus', $ikm) }}" method="POST"
                                                          onsubmit="return confirm('Kembalikan status IKM ini ke \'Menunggu Verifikasi\'?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status_verifikasi" value="menunggu">
                                                        <button type="submit"
                                                                title="Reset ke Menunggu"
                                                                class="p-1.5 rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50 active:scale-95 transition-all">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
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

    {{-- ========================================================================= --}}
    {{-- ===== MODAL DYNAMIC FORM VERIFIKASI LAPANGAN (KELURAHAN) ===== --}}
    {{-- ========================================================================= --}}
    <div x-show="modalVerifikasi" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
         @keydown.escape.window="modalVerifikasi = false">
        
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalVerifikasi = false"></div>

        {{-- Modal Dialog --}}
        <div x-show="modalVerifikasi"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden my-8 z-10 border border-slate-100 flex flex-col max-h-[90vh]">

            {{-- Header Modal --}}
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-5 text-white flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold leading-tight">Formulir Verifikasi Lapangan</h3>
                            <p class="text-xs text-emerald-100 mt-0.5">Lengkapi instrumen bukti fisik sesuai temuan nyata di lokasi</p>
                        </div>
                    </div>
                    <button type="button" @click="modalVerifikasi = false" class="text-white/70 hover:text-white p-1.5 rounded-xl hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Body Scrollable --}}
            <form :action="formAction" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-y-auto">
                @csrf
                @method('PATCH')

                <div class="p-6 space-y-6 flex-1 overflow-y-auto">

                    {{-- Info Ringkas IKM Terpilih --}}
                    <template x-if="activeIkm">
                        <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div>
                                <span class="text-[10px] font-mono bg-white px-2 py-0.5 rounded border text-slate-500 font-semibold" x-text="'NIB: ' + (activeIkm.nib || '—')"></span>
                                <h4 class="text-sm font-bold text-slate-800 mt-1" x-text="activeIkm.nama_usaha"></h4>
                                <p class="text-slate-500 mt-0.5" x-text="'Pemilik: ' + (activeIkm.nama_pemilik || '—')"></p>
                                <p class="text-slate-400 mt-0.5 text-[11px]" x-text="'Alamat: ' + (activeIkm.alamat || '—')"></p>
                            </div>
                            <div class="sm:text-right">
                                <span class="text-[11px] font-semibold text-slate-600 block" x-text="'Jenis Usaha: ' + (activeIkm.jenis_usaha || '—')"></span>
                            </div>
                        </div>
                    </template>

                    {{-- 1. PILIHAN STATUS HASIL VERIFIKASI --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2.5">
                            Status Hasil Verifikasi <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            {{-- Pilihan Ditemukan --}}
                            <label class="relative flex items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all duration-150 select-none"
                                   :class="selectedStatus === 'ditemukan' ? 'border-emerald-500 bg-emerald-50/50 shadow-sm ring-2 ring-emerald-500/20' : 'border-gray-200 hover:border-gray-300 bg-white'">
                                <input type="radio" name="status_verifikasi" value="ditemukan" x-model="selectedStatus" class="sr-only">
                                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Ditemukan / Aktif</p>
                                    <p class="text-[11px] text-gray-500">Usaha beroperasi dan ada di lokasi</p>
                                </div>
                            </label>

                            {{-- Pilihan Tidak Ditemukan --}}
                            <label class="relative flex items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all duration-150 select-none"
                                   :class="selectedStatus === 'tidak_ditemukan' ? 'border-red-500 bg-red-50/50 shadow-sm ring-2 ring-red-500/20' : 'border-gray-200 hover:border-gray-300 bg-white'">
                                <input type="radio" name="status_verifikasi" value="tidak_ditemukan" x-model="selectedStatus" class="sr-only">
                                <div class="w-8 h-8 rounded-xl bg-red-100 text-red-700 flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Tidak Ditemukan</p>
                                    <p class="text-[11px] text-gray-500">Usaha tutup / pindah / fiktif</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- 2. DYNAMIC INSTRUMENTS RENDERING --}}
                    <div class="border-t border-gray-100 pt-5 space-y-4">
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Instrumen Verifikasi Lapangan
                            </h4>
                            <span class="text-[11px] text-gray-400">Dikonfigurasi oleh Bidang Industri</span>
                        </div>

                        @forelse ($instrumens as $item)
                            <div class="p-4 rounded-2xl border border-gray-100 bg-slate-50/50 space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-bold text-gray-800">
                                        {{ $item->nama_instrumen }}
                                        @if ($item->is_required)
                                            <span class="text-red-500 font-bold">*</span>
                                        @else
                                            <span class="text-gray-400 font-normal text-[11px] ml-1">(Opsional)</span>
                                        @endif
                                    </label>
                                    <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded bg-white border text-gray-400">
                                        {{ $item->tipe_input }}
                                    </span>
                                </div>

                                @if ($item->deskripsi)
                                    <p class="text-[11px] text-gray-500">{{ $item->deskripsi }}</p>
                                @endif

                                {{-- RENDER SESUAI TIPE INPUT --}}

                                {{-- Tipe: FILE / FOTO --}}
                                @if ($item->tipe_input === 'file')
                                    <div>
                                        <template x-if="answers['{{ $item->slug }}']">
                                            <div class="mb-2 p-2.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between text-xs text-emerald-800">
                                                <div class="flex items-center gap-2 truncate pr-2">
                                                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    <span class="truncate">Berkas tersimpan sebelumnya</span>
                                                </div>
                                                <a :href="'{{ asset('storage') }}/' + answers['{{ $item->slug }}']" target="_blank"
                                                   class="font-bold underline text-emerald-700 hover:text-emerald-900 flex-shrink-0">
                                                    Lihat Foto ↗
                                                </a>
                                            </div>
                                        </template>

                                        <input type="file" name="jawaban[{{ $item->slug }}]" accept="image/*,application/pdf"
                                               class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 border border-gray-200 rounded-xl cursor-pointer bg-white">
                                        <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG, atau PDF. Maksimal 10 MB.</p>
                                    </div>

                                {{-- Tipe: KOORDINAT GPS --}}
                                @elseif ($item->tipe_input === 'koordinat')
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <div class="relative flex-1">
                                                <input type="text" id="input-coord-{{ $item->slug }}" name="jawaban[{{ $item->slug }}]"
                                                       :value="answers['{{ $item->slug }}'] || ''"
                                                       @input="answers['{{ $item->slug }}'] = $event.target.value"
                                                       placeholder="cth: 0.540123, 123.059421"
                                                       class="w-full text-xs font-mono border border-gray-200 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none pr-10">
                                                
                                                <template x-if="answers['{{ $item->slug }}']">
                                                    <a :href="'https://www.google.com/maps?q=' + encodeURIComponent(answers['{{ $item->slug }}'])"
                                                       target="_blank" title="Buka di Google Maps"
                                                       class="absolute right-2.5 top-2.5 text-blue-600 hover:text-blue-800">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    </a>
                                                </template>
                                            </div>

                                            <button type="button" @click="getGPSLocation('{{ $item->slug }}')"
                                                    class="inline-flex items-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 active:scale-95 transition flex-shrink-0">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                <span>Ambil GPS</span>
                                            </button>
                                        </div>
                                    </div>

                                {{-- Tipe: SELECT / DROPDOWN --}}
                                @elseif ($item->tipe_input === 'select')
                                    <div>
                                        <select name="jawaban[{{ $item->slug }}]"
                                                :value="answers['{{ $item->slug }}'] || ''"
                                                @change="answers['{{ $item->slug }}'] = $event.target.value"
                                                class="w-full text-xs border border-gray-200 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                                            <option value="">-- Pilih Opsi --</option>
                                            @if (is_array($item->opsi_pilihan))
                                                @foreach ($item->opsi_pilihan as $opsi)
                                                    <option value="{{ $opsi }}" :selected="answers['{{ $item->slug }}'] === '{{ $opsi }}'">
                                                        {{ $opsi }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                {{-- Tipe: TEXT --}}
                                @else
                                    <div>
                                        <textarea name="jawaban[{{ $item->slug }}]" rows="2"
                                                  :value="answers['{{ $item->slug }}'] || ''"
                                                  @input="answers['{{ $item->slug }}'] = $event.target.value"
                                                  placeholder="Masukkan catatan / hasil pengecekan..."
                                                  class="w-full text-xs border border-gray-200 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-emerald-400 focus:outline-none resize-none"></textarea>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic py-2">Belum ada instrumen tambahan dari Bidang Industri.</p>
                        @endforelse
                    </div>

                    {{-- 3. CATATAN PETUGAS --}}
                    <div class="border-t border-gray-100 pt-5">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Catatan Tambahan Petugas Kelurahan
                        </label>
                        <textarea name="catatan_petugas" rows="2" x-model="catatanPetugas"
                                  placeholder="Tuliskan temuan penting lain di lapangan (opsional)"
                                  class="w-full text-xs border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none resize-none"></textarea>
                    </div>

                </div>

                {{-- Footer Modal --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 flex-shrink-0">
                    <button type="button" @click="modalVerifikasi = false"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-700 text-white text-xs font-bold hover:from-emerald-700 hover:to-teal-800 active:scale-95 transition shadow-sm">
                        Simpan Verifikasi Lapangan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== MODAL GANTI PASSWORD ===== --}}
    <div
        x-show="modalGantiPassword"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @keydown.escape.window="modalGantiPassword = false"
    >
        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="modalGantiPassword = false"></div>

        {{-- Panel Modal --}}
        <div
            x-show="modalGantiPassword"
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl ring-1 ring-black/5 overflow-hidden"
        >
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Ganti Password</h3>
                            <p class="text-xs text-emerald-100 mt-0.5">Perbarui kata sandi akun Anda</p>
                        </div>
                    </div>
                    <button type="button" @click="modalGantiPassword = false" class="text-white/70 hover:text-white transition p-1 rounded-lg hover:bg-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('kelurahan.gantiPassword') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="password_lama" class="block text-sm font-semibold text-gray-700 mb-1.5">Password Lama</label>
                    <input id="password_lama" name="password_lama" :type="showLama ? 'text' : 'password'" required
                           placeholder="Masukkan password saat ini"
                           class="block w-full py-2.5 px-3.5 rounded-xl border text-sm text-gray-900 border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="password_baru" class="block text-sm font-semibold text-gray-700 mb-1.5">Password Baru</label>
                    <input id="password_baru" name="password_baru" :type="showBaru ? 'text' : 'password'" required
                           placeholder="Minimal 8 karakter"
                           class="block w-full py-2.5 px-3.5 rounded-xl border text-sm text-gray-900 border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="password_baru_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                    <input id="password_baru_confirmation" name="password_baru_confirmation" :type="showKonfirmasi ? 'text' : 'password'" required
                           placeholder="Ulangi password baru"
                           class="block w-full py-2.5 px-3.5 rounded-xl border text-sm text-gray-900 border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 py-2.5 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm transition shadow-sm">
                        Simpan Password
                    </button>
                    <button type="button" @click="modalGantiPassword = false" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    </div>{{-- end x-data --}}
</x-app-layout>
