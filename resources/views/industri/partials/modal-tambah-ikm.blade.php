{{-- ===================================================================== --}}
{{-- ===== MODAL TAMBAH IKM MANUAL (scope Alpine: ikmPage) ===== --}}
{{-- ===================================================================== --}}
<div x-show="tambahOpen" x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
     @keydown.escape.window="tambahOpen = false">

    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="tambahOpen = false"></div>

    <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden my-8 z-10 border border-slate-100 flex flex-col max-h-[90vh]">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-5 text-white flex-shrink-0">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold leading-tight">Tambah IKM Manual</h3>
                        <p class="text-xs text-emerald-100 mt-0.5">Input satu data usaha langsung ke Master Data IKM</p>
                    </div>
                </div>
                <button type="button" @click="tambahOpen = false" class="text-white/70 hover:text-white p-1.5 rounded-xl hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <form action="{{ route('industri.store-manual') }}" method="POST" class="flex flex-col flex-1 overflow-y-auto">
            @csrf
            <input type="hidden" name="_form" value="tambah-manual">

            <div class="p-6 space-y-4 flex-1 overflow-y-auto">

                @if (old('_form') === 'tambah-manual' && $errors->any())
                    <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700">
                        <ul class="list-disc pl-4 space-y-0.5">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="tambah-nib" class="block text-xs font-bold text-gray-700 mb-1.5">NIB <span class="text-red-500">*</span></label>
                        <input type="text" id="tambah-nib" name="nib" value="{{ old('nib') }}" required maxlength="50"
                               placeholder="Nomor Induk Berusaha"
                               class="w-full text-sm font-mono border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                    </div>
                    <div>
                        <label for="tambah-nama-usaha" class="block text-xs font-bold text-gray-700 mb-1.5">Nama Usaha <span class="text-red-500">*</span></label>
                        <input type="text" id="tambah-nama-usaha" name="nama_usaha" value="{{ old('nama_usaha') }}" required maxlength="255"
                               class="w-full text-sm border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="tambah-nama-pemilik" class="block text-xs font-bold text-gray-700 mb-1.5">Nama Pemilik <span class="text-red-500">*</span></label>
                        <input type="text" id="tambah-nama-pemilik" name="nama_pemilik" value="{{ old('nama_pemilik') }}" required maxlength="255"
                               class="w-full text-sm border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="tambah-alamat" class="block text-xs font-bold text-gray-700 mb-1.5">Alamat Usaha <span class="text-red-500">*</span></label>
                        <textarea id="tambah-alamat" name="alamat" rows="2" required maxlength="1000"
                                  class="w-full text-sm border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none resize-none">{{ old('alamat') }}</textarea>
                    </div>

                    {{-- Dropdown dinamis Kecamatan → Kelurahan --}}
                    <div>
                        <label for="tambah-kecamatan" class="block text-xs font-bold text-gray-700 mb-1.5">Kecamatan</label>
                        <select id="tambah-kecamatan" name="kecamatan" x-model="tambah.kecamatan" @change="onKecamatanChange()"
                                class="w-full text-sm border border-gray-200 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                            <option value="">-- Semua Kecamatan --</option>
                            @foreach ($kecamatans as $kec)
                                <option value="{{ $kec }}">{{ $kec }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tambah-kelurahan" class="block text-xs font-bold text-gray-700 mb-1.5">Kelurahan <span class="text-red-500">*</span></label>
                        <select id="tambah-kelurahan" name="kelurahan_id" x-model="tambah.kelurahan_id" required
                                class="w-full text-sm border border-gray-200 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                            <option value="">-- Pilih Kelurahan --</option>
                            <template x-for="kel in kelurahanOptions" :key="kel.id">
                                <option :value="kel.id" x-text="kel.nama" :selected="String(kel.id) === String(tambah.kelurahan_id)"></option>
                            </template>
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1" x-show="kelurahanOptions.length === 0">Tidak ada kelurahan pada kecamatan ini.</p>
                    </div>

                    {{-- Pilihan cepat dari Daftar KBLI yang sudah ada --}}
                    <div class="sm:col-span-2">
                        <label for="tambah-pilih-kbli" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Pilih dari Daftar KBLI Tersimpan <span class="text-gray-400 font-normal">(Opsional — mengisi otomatis Kode & Judul KBLI)</span>
                        </label>
                        <select id="tambah-pilih-kbli" @change="onSelectKbliPreset($event.target.value)"
                                class="w-full text-sm border border-gray-200 rounded-xl px-3.5 py-2.5 bg-slate-50 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                            <option value="">-- Ketik Manual atau Pilih KBLI yang Sudah Ada --</option>
                            @foreach($daftarKbli as $idx => $item)
                                @php
                                    $label = $item->kode_kbli ? ($item->kode_kbli . ' : ' . $item->jenis_usaha) : $item->jenis_usaha;
                                @endphp
                                <option value="{{ $idx }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tambah-kode-kbli" class="block text-xs font-bold text-gray-700 mb-1.5">Kode KBLI</label>
                        <input type="text" id="tambah-kode-kbli" name="kode_kbli" x-model="tambah.kode_kbli" maxlength="20"
                               placeholder="cth: 10710"
                               class="w-full text-sm font-mono border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                    </div>
                    <div>
                        <label for="tambah-jenis" class="block text-xs font-bold text-gray-700 mb-1.5">Judul KBLI / Jenis Usaha <span class="text-red-500">*</span></label>
                        <input type="text" id="tambah-jenis" name="jenis_usaha" x-model="tambah.jenis_usaha" required maxlength="255"
                               placeholder="cth: Industri Produk Roti dan Kue"
                               class="w-full text-sm border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="tambah-status" class="block text-xs font-bold text-gray-700 mb-1.5">Status Awal</label>
                        <select id="tambah-status" name="status_verifikasi"
                                class="w-full text-sm border border-gray-200 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                            <option value="menunggu" @selected(old('status_verifikasi', 'menunggu') === 'menunggu')>Menunggu Verifikasi</option>
                            <option value="ditemukan" @selected(old('status_verifikasi') === 'ditemukan')>Ditemukan / Aktif</option>
                            <option value="tidak_ditemukan" @selected(old('status_verifikasi') === 'tidak_ditemukan')>Tidak Ditemukan / Tutup</option>
                        </select>
                    </div>
                </div>

                <p class="text-[11px] text-gray-400">
                    Nomor Halal, BPOM, dan PIRT dapat dilengkapi setelah data tersimpan melalui tombol
                    <span class="font-semibold">Detail / Edit Verifikasi</span>.
                </p>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 flex-shrink-0">
                <button type="button" @click="tambahOpen = false"
                        class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" id="btn-simpan-ikm-manual"
                        class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-700 text-white text-xs font-bold hover:from-emerald-700 hover:to-teal-800 active:scale-95 transition shadow-sm">
                    Simpan Data IKM
                </button>
            </div>
        </form>
    </div>
</div>

