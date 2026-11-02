<x-industri-layout activeModule="akun-kelurahan" pageTitle="Akun Kelurahan" breadcrumb="Manajemen & Reset Password Petugas Kelurahan">

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-violet-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Manajemen Akun Petugas Kelurahan</h3>
                    <p class="text-xs text-slate-400">{{ $userKelurahan->count() }} akun terdaftar — tombol Reset mengatur ulang password ke <code class="bg-slate-100 px-1 rounded">"password"</code></p>
                </div>
            </div>
            <span class="self-start sm:self-auto inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-violet-100 text-violet-700 border border-violet-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                Reset Default = "password"
            </span>
        </div>

        {{-- Pencarian --}}
        <div class="px-6 py-3 border-b border-slate-50 bg-slate-50/60">
            <input type="text" id="search-kelurahan" placeholder="🔍  Cari nama atau kelurahan..."
                   class="w-full sm:w-80 text-sm border border-slate-200 rounded-xl px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-violet-500 text-slate-700"
                   x-data x-on:input="
                       const q = $el.value.toLowerCase();
                       document.querySelectorAll('#tabel-kelurahan tbody tr').forEach(row => {
                           row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
                       });
                   ">
        </div>

        @if ($userKelurahan->isEmpty())
            <div class="flex flex-col items-center justify-center py-14 text-center">
                <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-sm font-semibold text-slate-500">Belum ada akun kelurahan</p>
                <p class="text-xs text-slate-400 mt-1">Jalankan <code class="bg-slate-100 px-1 rounded">php artisan db:seed --class=KelurahanUserSeeder</code></p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[640px]" id="tabel-kelurahan">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider w-10">#</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Akun</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kelurahan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kecamatan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Email / Username</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($userKelurahan as $i => $uk)
                            <tr class="hover:bg-violet-50/30">
                                <td class="px-5 py-3.5 text-xs text-slate-400 tabular-nums">{{ $i + 1 }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center flex-shrink-0">
                                            <span class="text-white text-xs font-bold">{{ strtoupper(substr($uk->name, 0, 1)) }}</span>
                                        </div>
                                        <span class="font-medium text-slate-800">{{ $uk->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if ($uk->kelurahan)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                            {{ $uk->kelurahan->nama_kelurahan }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs italic">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500">
                                    {{ $uk->kelurahan?->kecamatan ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500 font-mono">{{ $uk->email }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <form action="{{ route('industri.reset-password', $uk) }}" method="POST"
                                          x-data="{ loading: false }"
                                          @submit.prevent="
                                              if (confirm('Reset password akun \"{{ addslashes($uk->name) }}\" ke default (\"password\")?')) {
                                                  loading = true;
                                                  $el.submit();
                                              }
                                          ">
                                        @csrf
                                        <button type="submit"
                                                :disabled="loading"
                                                :class="loading ? 'opacity-60 cursor-not-allowed' : 'hover:bg-violet-700 active:scale-95'"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-violet-600 text-white transition-all duration-150 shadow-sm">
                                            <template x-if="!loading">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                            </template>
                                            <template x-if="loading">
                                                <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                                </svg>
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

</x-industri-layout>
