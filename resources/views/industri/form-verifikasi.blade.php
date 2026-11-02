<x-industri-layout activeModule="form-verifikasi" pageTitle="Form Verifikasi" breadcrumb="Konfigurasi Instrumen Verifikasi Lapangan Dinamis">

    <div x-data="{
        editModalOpen: false,
        editItem: {
            id: null,
            nama_instrumen: '',
            slug: '',
            deskripsi: '',
            tipe_input: 'text',
            opsi_pilihan: '',
            urutan: 1,
            is_required: true
        },
        openEdit(item) {
            this.editItem = {
                id: item.id,
                nama_instrumen: item.nama_instrumen,
                slug: item.slug,
                deskripsi: item.deskripsi || '',
                tipe_input: item.tipe_input,
                opsi_pilihan: Array.isArray(item.opsi_pilihan) ? item.opsi_pilihan.join('\n') : (item.opsi_pilihan || ''),
                urutan: item.urutan,
                is_required: item.is_required
            };
            this.editModalOpen = true;
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

        @if ($errors->any())
            <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-4">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9V5a1 1 0 112 0v4a1 1 0 01-2 0zm0 4a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold">Terdapat kesalahan input:</p>
                    <ul class="text-xs list-disc list-inside mt-1 space-y-0.5">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Kolom Kiri: Daftar Instrumen Dinamis (2 Kolom) --}}
            <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white shadow-sm shadow-teal-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Instrumen Verifikasi Lapangan</h3>
                                <p class="text-xs text-slate-400">Instrumen aktif otomatis ditampilkan pada form verifikasi petugas kelurahan</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-teal-50 text-teal-700 border border-teal-200">
                                {{ $instrumens->where('is_active', true)->count() }} Aktif
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                {{ $instrumens->count() }} Total
                            </span>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($instrumens as $instr)
                            <div class="p-5 flex flex-col sm:flex-row sm:items-start justify-between gap-4 hover:bg-slate-50/70 transition-colors {{ !$instr->is_active ? 'opacity-60 bg-slate-50/40' : '' }}">
                                
                                {{-- Info Instrumen --}}
                                <div class="flex items-start gap-3.5 flex-1 min-w-0">
                                    {{-- Icon Berdasarkan Tipe Input --}}
                                    <div class="mt-0.5 flex-shrink-0">
                                        @if ($instr->tipe_input === 'file')
                                            <div class="w-10 h-10 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center shadow-sm" title="Upload Berkas/Foto">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @elseif ($instr->tipe_input === 'koordinat')
                                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm" title="Titik Koordinat GPS">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                        @elseif ($instr->tipe_input === 'select')
                                            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shadow-sm" title="Pilihan Dropdown">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shadow-sm" title="Teks / Catatan">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-200 text-slate-700 text-[11px] font-bold">
                                                {{ $instr->urutan }}
                                            </span>
                                            <h4 class="text-sm font-bold text-slate-800 leading-snug">{{ $instr->nama_instrumen }}</h4>
                                            
                                            {{-- Slug Badge --}}
                                            <code class="text-[11px] font-mono bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200">
                                                {{ $instr->slug }}
                                            </code>

                                            {{-- Tipe Badge --}}
                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider
                                                {{ $instr->tipe_input === 'file' ? 'bg-pink-50 text-pink-700 border border-pink-200' : '' }}
                                                {{ $instr->tipe_input === 'koordinat' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                                {{ $instr->tipe_input === 'select' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                                {{ $instr->tipe_input === 'text' ? 'bg-slate-100 text-slate-700 border border-slate-200' : '' }}">
                                                {{ $instr->tipe_input }}
                                            </span>

                                            {{-- Wajib / Opsional Badge --}}
                                            @if ($instr->is_required)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200 uppercase">
                                                    Wajib
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-500 border border-slate-200 uppercase">
                                                    Opsional
                                                </span>
                                            @endif
                                        </div>

                                        @if ($instr->deskripsi)
                                            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">{{ $instr->deskripsi }}</p>
                                        @endif

                                        {{-- Render Opsi Dropdown jika tipe select --}}
                                        @if ($instr->tipe_input === 'select' && is_array($instr->opsi_pilihan) && count($instr->opsi_pilihan) > 0)
                                            <div class="mt-2.5 flex items-center gap-1.5 flex-wrap">
                                                <span class="text-[11px] text-slate-400 font-medium mr-1">Pilihan:</span>
                                                @foreach ($instr->opsi_pilihan as $opsi)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] bg-amber-50/80 text-amber-800 border border-amber-200">
                                                        {{ $opsi }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex items-center gap-2.5 flex-shrink-0 self-end sm:self-center">
                                    {{-- Toggle Aktif --}}
                                    <form action="{{ route('industri.form-verifikasi.update', $instr) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="toggle" value="is_active">
                                        <button type="submit"
                                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none {{ $instr->is_active ? 'bg-teal-600' : 'bg-slate-300' }}"
                                                title="{{ $instr->is_active ? 'Klik untuk menonaktifkan' : 'Klik untuk mengaktifkan' }}">
                                            <span class="inline-block w-4 h-4 transform rounded-full bg-white shadow transition-transform duration-200 {{ $instr->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                        </button>
                                    </form>

                                    {{-- Toggle Wajib --}}
                                    <form action="{{ route('industri.form-verifikasi.update', $instr) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="toggle" value="is_required">
                                        <button type="submit"
                                                class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all duration-150 {{ $instr->is_required ? 'bg-red-50 text-red-600 hover:bg-red-100 border border-red-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200 border border-slate-200' }}"
                                                title="Ubah status wajib atau opsional">
                                            {{ $instr->is_required ? '★ Wajib' : '☆ Opsional' }}
                                        </button>
                                    </form>

                                    {{-- Tombol Edit --}}
                                    <button type="button"
                                            @click="openEdit({{ json_encode($instr) }})"
                                            class="p-1.5 rounded-lg text-slate-500 hover:text-teal-600 hover:bg-teal-50 transition-colors"
                                            title="Edit Instrumen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('industri.form-verifikasi.destroy', $instr) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus instrumen \'{{ $instr->nama_instrumen }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors"
                                                title="Hapus Instrumen">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Belum ada instrumen verifikasi</p>
                                <p class="text-xs text-slate-400 mt-1">Tambahkan instrumen melalui formulir di samping kanan</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Petunjuk: Urutan instrumen menentukan posisi tampil di formulir kelurahan.</span>
                    <span class="font-medium text-slate-600">Dynamic Form Engine</span>
                </div>
            </div>

            {{-- Kolom Kanan: Form Tambah Instrumen Baru (1 Kolom) --}}
            <div class="xl:col-span-1" x-data="{ tipeInput: 'text' }">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden sticky top-24">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Tambah Instrumen</h3>
                            <p class="text-[11px] text-slate-400">Konfigurasi kolom pertanyaan baru</p>
                        </div>
                    </div>

                    <form action="{{ route('industri.form-verifikasi.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf

                        {{-- Nama Instrumen --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Nama Instrumen / Pertanyaan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_instrumen" required
                                   placeholder="cth: Upload Foto KTP Pemilik"
                                   class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 text-slate-800 placeholder-slate-400">
                            <p class="text-[11px] text-slate-400 mt-1">Slug kode unik dibuat secara otomatis dari nama ini.</p>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Deskripsi / Petunjuk Pengisian</label>
                            <textarea name="deskripsi" rows="2"
                                      placeholder="Penjelasan panduan untuk petugas lapangan (opsional)"
                                      class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 text-slate-800 placeholder-slate-400 resize-none"></textarea>
                        </div>

                        {{-- Tipe Input --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Tipe Input Lapangan <span class="text-red-500">*</span>
                            </label>
                            <select name="tipe_input" x-model="tipeInput" required
                                    class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 text-slate-800">
                                <option value="text">Teks Singkat / Catatan (text)</option>
                                <option value="file">Upload Berkas / Foto Kamera (file)</option>
                                <option value="koordinat">Titik Koordinat GPS (koordinat)</option>
                                <option value="select">Dropdown Pilihan (select)</option>
                            </select>
                        </div>

                        {{-- Dynamic Opsi Dropdown (hanya jika tipe select) --}}
                        <div x-show="tipeInput === 'select'" x-cloak x-transition class="p-3.5 bg-amber-50/60 rounded-xl border border-amber-200/70 space-y-1.5">
                            <label class="block text-xs font-bold text-amber-900">
                                Opsi Pilihan Dropdown <span class="text-red-500">*</span>
                            </label>
                            <p class="text-[11px] text-amber-700">Tuliskan satu opsi per baris:</p>
                            <textarea name="opsi_pilihan" rows="3"
                                      placeholder="Pilihan 1&#10;Pilihan 2&#10;Pilihan 3"
                                      class="w-full text-xs font-mono border border-amber-200 rounded-lg p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 text-slate-800"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3 items-center">
                            {{-- Urutan --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Urutan Tampil</label>
                                <input type="number" name="urutan" value="{{ $instrumens->count() + 1 }}" min="1"
                                       class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 text-slate-800">
                            </div>

                            {{-- Wajib Diisi --}}
                            <div class="pt-5">
                                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" name="is_required" value="1" checked
                                           class="w-4 h-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                    <span class="text-xs font-semibold text-slate-700">Wajib Diisi</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" id="btn-tambah-instrumen"
                                class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-teal-600 to-teal-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:from-teal-700 hover:to-teal-800 active:scale-95 transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Simpan Instrumen
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- ===== MODAL EDIT INSTRUMEN (ALPINE.JS) ===== --}}
        <div x-show="editModalOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4">
            
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="editModalOpen = false"></div>

            {{-- Modal Content --}}
            <div x-show="editModalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">
                
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Edit Instrumen Verifikasi</h3>
                    </div>
                    <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="'{{ url('/industri/form-verifikasi') }}/' + editItem.id" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Instrumen <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_instrumen" x-model="editItem.nama_instrumen" required
                               class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-teal-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" x-model="editItem.deskripsi" rows="2"
                                  class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-teal-500 focus:outline-none resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Tipe Input <span class="text-red-500">*</span></label>
                            <select name="tipe_input" x-model="editItem.tipe_input" required
                                    class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2.5 bg-white focus:ring-2 focus:ring-teal-500 focus:outline-none">
                                <option value="text">Teks Singkat / Catatan</option>
                                <option value="file">Upload Berkas / Foto</option>
                                <option value="koordinat">Titik Koordinat GPS</option>
                                <option value="select">Dropdown Pilihan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Urutan</label>
                            <input type="number" name="urutan" x-model="editItem.urutan" min="1"
                                   class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-teal-500 focus:outline-none">
                        </div>
                    </div>

                    {{-- Dynamic Opsi jika select --}}
                    <div x-show="editItem.tipe_input === 'select'" class="p-3 bg-amber-50 rounded-xl border border-amber-200 space-y-1">
                        <label class="block text-xs font-bold text-amber-900">Opsi Pilihan Dropdown (Satu per baris)</label>
                        <textarea name="opsi_pilihan" x-model="editItem.opsi_pilihan" rows="3"
                                  class="w-full text-xs font-mono border border-amber-200 rounded-lg p-2 bg-white focus:ring-2 focus:ring-amber-400 focus:outline-none"></textarea>
                    </div>

                    <div>
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="is_required" value="1" :checked="editItem.is_required"
                                   class="w-4 h-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                            <span class="text-xs font-semibold text-slate-700">Wajib Diisi oleh Petugas Lapangan</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="editModalOpen = false"
                                class="px-4 py-2 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-100 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 rounded-xl text-xs font-semibold bg-teal-600 text-white hover:bg-teal-700 active:scale-95 transition shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-industri-layout>
