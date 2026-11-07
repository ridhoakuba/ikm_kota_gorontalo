{{-- ===================================================================== --}}
{{-- ===== MODAL DETAIL / EDIT VERIFIKASI & LEGALITAS (BIDANG INDUSTRI) ===== --}}
{{-- Scope Alpine: ikmPage. Memakai form & instrumen dinamis yang sama dengan Kelurahan --}}
{{-- ===================================================================== --}}
<div x-show="editOpen" x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
     @keydown.escape.window="editOpen = false">

    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editOpen = false"></div>

    <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden my-8 z-10 border border-slate-100 flex flex-col max-h-[90vh]">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5 text-white flex-shrink-0">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold leading-tight">Detail / Edit Verifikasi</h3>
                        <p class="text-xs text-blue-100 mt-0.5">Pembaruan langsung oleh Bidang Industri — tanpa menunggu verifikasi kelurahan</p>
                    </div>
                </div>
                <button type="button" @click="editOpen = false" class="text-white/70 hover:text-white p-1.5 rounded-xl hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <form :action="formAction" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-y-auto">
            @csrf
            @method('PATCH')

            <div class="p-6 space-y-6 flex-1 overflow-y-auto">

                {{-- Info Ringkas IKM --}}
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
                            <span class="text-[11px] text-slate-400 block" x-text="'Kel. ' + (activeIkm.kelurahan?.nama_kelurahan || '—') + ' · Kec. ' + (activeIkm.kelurahan?.kecamatan || '—')"></span>
                            <span class="text-[10px] text-blue-500 font-medium block mt-1"
                                  x-show="activeIkm.updated_by_role"
                                  x-text="'Terakhir diperbarui oleh ' + (activeIkm.updated_by_role === 'industri' ? 'Bidang Industri' : 'Kelurahan')"></span>
                        </div>
                    </div>
                </template>

                {{-- 1. STATUS VERIFIKASI --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2.5">
                        Status Verifikasi <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="relative flex items-center p-3 rounded-2xl border-2 cursor-pointer transition-all select-none"
                               :class="selectedStatus === 'menunggu' ? 'border-amber-500 bg-amber-50/50 ring-2 ring-amber-500/20' : 'border-gray-200 hover:border-gray-300 bg-white'">
                            <input type="radio" name="status_verifikasi" value="menunggu" x-model="selectedStatus" class="sr-only">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Menunggu</p>
                                <p class="text-[11px] text-gray-500">Belum terverifikasi</p>
                            </div>
                        </label>
                        <label class="relative flex items-center p-3 rounded-2xl border-2 cursor-pointer transition-all select-none"
                               :class="selectedStatus === 'ditemukan' ? 'border-emerald-500 bg-emerald-50/50 ring-2 ring-emerald-500/20' : 'border-gray-200 hover:border-gray-300 bg-white'">
                            <input type="radio" name="status_verifikasi" value="ditemukan" x-model="selectedStatus" class="sr-only">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Ditemukan</p>
                                <p class="text-[11px] text-gray-500">Aktif beroperasi</p>
                            </div>
                        </label>
                        <label class="relative flex items-center p-3 rounded-2xl border-2 cursor-pointer transition-all select-none"
                               :class="selectedStatus === 'tidak_ditemukan' ? 'border-red-500 bg-red-50/50 ring-2 ring-red-500/20' : 'border-gray-200 hover:border-gray-300 bg-white'">
                            <input type="radio" name="status_verifikasi" value="tidak_ditemukan" x-model="selectedStatus" class="sr-only">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Tidak Ditemukan</p>
                                <p class="text-[11px] text-gray-500">Tutup / pindah / fiktif</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- 2. KBLI & LEGALITAS USAHA --}}
                <div class="border-t border-gray-100 pt-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">KBLI & Jenis Usaha</h4>
                        <span class="text-[11px] text-gray-400">Sesuai klasifikasi usaha</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label for="edit-kode-kbli" class="block text-[11px] font-semibold text-gray-600 mb-1">Kode / No. KBLI</label>
                            <input type="text" id="edit-kode-kbli" name="kode_kbli" x-model="editKbli.kode_kbli" maxlength="20"
                                   placeholder="cth: 10710"
                                   class="w-full text-xs font-mono border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="edit-jenis-usaha" class="block text-[11px] font-semibold text-gray-600 mb-1">Judul KBLI / Jenis Usaha</label>
                            <input type="text" id="edit-jenis-usaha" name="jenis_usaha" x-model="editKbli.jenis_usaha" maxlength="255"
                                   placeholder="cth: Industri Produk Roti dan Kue"
                                   class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>
                    </div>
                </div>

                {{-- 3. LEGALITAS USAHA --}}
                <div class="border-t border-gray-100 pt-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Legalitas Usaha</h4>
                        <span class="text-[11px] text-gray-400">Kosongkan jika belum ada</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label for="edit-halal" class="block text-[11px] font-semibold text-gray-600 mb-1">No. Sertifikat Halal</label>
                            <input type="text" id="edit-halal" name="sertifikat_halal" x-model="legalitas.sertifikat_halal" maxlength="100"
                                   placeholder="cth: ID73110000012345"
                                   class="w-full text-xs font-mono border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>
                        <div>
                            <label for="edit-bpom" class="block text-[11px] font-semibold text-gray-600 mb-1">No. Izin BPOM</label>
                            <input type="text" id="edit-bpom" name="izin_bpom" x-model="legalitas.izin_bpom" maxlength="100"
                                   placeholder="cth: MD 123456789012"
                                   class="w-full text-xs font-mono border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>
                        <div>
                            <label for="edit-pirt" class="block text-[11px] font-semibold text-gray-600 mb-1">No. PIRT</label>
                            <input type="text" id="edit-pirt" name="pirt" x-model="legalitas.pirt" maxlength="100"
                                   placeholder="cth: 2057571010123-23"
                                   class="w-full text-xs font-mono border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>
                    </div>
                </div>

                {{-- 3. INSTRUMEN DINAMIS (sama dengan Kelurahan, tidak wajib bagi Bidang Industri) --}}
                <div class="border-t border-gray-100 pt-5 space-y-4">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Instrumen Verifikasi</h4>
                        <span class="text-[11px] text-gray-400">Seluruh isian opsional bagi Bidang Industri</span>
                    </div>

                    @forelse ($instrumens as $item)
                        <div class="p-4 rounded-2xl border border-gray-100 bg-slate-50/50 space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-gray-800">{{ $item->nama_instrumen }}</label>
                                <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded bg-white border text-gray-400">{{ $item->tipe_input }}</span>
                            </div>

                            @if ($item->deskripsi)
                                <p class="text-[11px] text-gray-500">{{ $item->deskripsi }}</p>
                            @endif

                            @if ($item->tipe_input === 'file')
                                <div>
                                    <template x-if="answers['{{ $item->slug }}']">
                                        <div class="mb-2 p-2.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between text-xs text-emerald-800">
                                            <span class="truncate pr-2">Berkas tersimpan sebelumnya</span>
                                            <a :href="'{{ asset('storage') }}/' + answers['{{ $item->slug }}']" target="_blank"
                                               class="font-bold underline text-emerald-700 hover:text-emerald-900 flex-shrink-0">Lihat Berkas ↗</a>
                                        </div>
                                    </template>
                                    <input type="file" name="jawaban[{{ $item->slug }}]" accept="image/*,application/pdf"
                                           class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-800 hover:file:bg-blue-200 border border-gray-200 rounded-xl cursor-pointer bg-white">
                                    <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG, atau PDF. Kosongkan untuk mempertahankan berkas lama.</p>
                                </div>

                            @elseif ($item->tipe_input === 'koordinat')
                                <div class="flex items-center gap-2">
                                    <input type="text" name="jawaban[{{ $item->slug }}]"
                                           x-model="answers['{{ $item->slug }}']"
                                           placeholder="cth: 0.540123, 123.059421"
                                           class="flex-1 text-xs font-mono border border-gray-200 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                    <button type="button" @click="getGPSLocation('{{ $item->slug }}')" :disabled="geoLoading"
                                            class="inline-flex items-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 active:scale-95 transition flex-shrink-0 disabled:opacity-60">
                                        <span x-text="geoLoading ? 'Mencari...' : 'Ambil GPS'"></span>
                                    </button>
                                </div>

                            @elseif ($item->tipe_input === 'select')
                                <select name="jawaban[{{ $item->slug }}]" x-model="answers['{{ $item->slug }}']"
                                        class="w-full text-xs border border-gray-200 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                    <option value="">-- Pilih Opsi --</option>
                                    @if (is_array($item->opsi_pilihan))
                                        @foreach ($item->opsi_pilihan as $opsi)
                                            <option value="{{ $opsi }}">{{ $opsi }}</option>
                                        @endforeach
                                    @endif
                                </select>

                            @else
                                <textarea name="jawaban[{{ $item->slug }}]" rows="2"
                                          x-model="answers['{{ $item->slug }}']"
                                          placeholder="Masukkan catatan / hasil pengecekan..."
                                          class="w-full text-xs border border-gray-200 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none resize-none"></textarea>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic py-2">Belum ada instrumen verifikasi yang dikonfigurasi.</p>
                    @endforelse
                </div>

                {{-- 4. CATATAN --}}
                <div class="border-t border-gray-100 pt-5">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Catatan Bidang Industri</label>
                    <textarea name="catatan_petugas" rows="2" x-model="catatanPetugas"
                              placeholder="Catatan pembaruan (opsional)"
                              class="w-full text-xs border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-400 focus:outline-none resize-none"></textarea>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 flex-shrink-0">
                <button type="button" @click="editOpen = false"
                        class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" id="btn-simpan-verifikasi-industri"
                        class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white text-xs font-bold hover:from-blue-700 hover:to-indigo-800 active:scale-95 transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

