<x-guest-layout>
    {{-- Wrapper Alpine.js untuk dua tab login --}}
    <div
        x-data="{
            tab: '{{ old('kelurahan_id') ? 'kelurahan' : 'industri' }}',
            switchTab(t) {
                this.tab = t;
            }
        }"
    >

        {{-- ===== SESSION STATUS ===== --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        @auth
            <div class="mb-5 p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <p class="font-medium">Anda sedang aktif login sebagai: <span class="font-bold">{{ auth()->user()->name }}</span></p>
                    <p class="mt-0.5 text-amber-700">Login dengan akun di bawah akan otomatis mengganti sesi akun Anda.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="font-semibold text-amber-900 underline hover:text-amber-700 whitespace-nowrap">
                    Ke Dasbor &rarr;
                </a>
            </div>
        @endauth

        {{-- ===== JUDUL & DESKRIPSI ===== --}}
        <div class="text-center mb-6">
            <p class="text-sm text-gray-500">Pilih tipe akun untuk masuk ke sistem</p>
        </div>

        {{-- ===== TAB SWITCHER ===== --}}
        <div class="flex rounded-xl bg-gray-100 p-1 gap-1 mb-7">
            {{-- Tab Industri --}}
            <button
                type="button"
                id="tab-industri"
                @click="switchTab('industri')"
                :class="tab === 'industri'
                    ? 'bg-white text-blue-700 shadow-sm font-semibold'
                    : 'text-gray-500 hover:text-gray-700 font-medium'"
                class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg text-sm transition-all duration-200"
            >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 00-1-1h-2a1 1 0 00-1 1v5m4 0H9"/>
                </svg>
                Login Industri
            </button>

            {{-- Tab Kelurahan --}}
            <button
                type="button"
                id="tab-kelurahan"
                @click="switchTab('kelurahan')"
                :class="tab === 'kelurahan'
                    ? 'bg-white text-emerald-700 shadow-sm font-semibold'
                    : 'text-gray-500 hover:text-gray-700 font-medium'"
                class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg text-sm transition-all duration-200"
            >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Login Kelurahan
            </button>
        </div>

        {{-- ===== FORM LOGIN INDUSTRI ===== --}}
        <div x-show="tab === 'industri'" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
        >
            <form method="POST" action="{{ route('login') }}" id="form-industri">
                @csrf

                {{-- Email --}}
                <div class="space-y-1.5 mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-700">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="username"
                            autofocus
                            placeholder="admin@industri.com"
                            class="block w-full pl-10 pr-4 py-2.5 rounded-xl border
                                   {{ $errors->has('email') ? 'border-red-400 bg-red-50 focus:ring-red-400' : 'border-gray-200 bg-white focus:border-blue-400 focus:ring-blue-200' }}
                                   text-sm text-gray-900 focus:outline-none focus:ring-2 transition-all duration-150"
                        >
                    </div>
                    @error('email')
                        <p class="text-xs text-red-600 flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password Industri --}}
                <div class="space-y-1.5 mb-5" x-data="{ show: false }">
                    <label for="password_industri" class="block text-sm font-semibold text-gray-700">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            id="password_industri"
                            :type="show ? 'text' : 'password'"
                            name="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="block w-full pl-10 pr-10 py-2.5 rounded-xl border
                                   {{ $errors->has('password') ? 'border-red-400 bg-red-50 focus:ring-red-400' : 'border-gray-200 bg-white focus:border-blue-400 focus:ring-blue-200' }}
                                   text-sm text-gray-900 focus:outline-none focus:ring-2 transition-all duration-150"
                        >
                        <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-600 flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember + Submit --}}
                <div class="flex items-center justify-between mb-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember_industri"
                               class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>
                </div>

                <button
                    type="submit"
                    id="btn-login-industri"
                    class="w-full flex items-center justify-center gap-2 py-3 px-6 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-semibold text-sm transition-all duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Masuk sebagai Admin Industri
                </button>
            </form>
        </div>

        {{-- ===== FORM LOGIN KELURAHAN ===== --}}
        <div x-show="tab === 'kelurahan'" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
        >
            <form method="POST" action="{{ route('login') }}" id="form-kelurahan">
                @csrf

                {{-- Pilih Kelurahan --}}
                <div class="space-y-1.5 mb-4">
                    <label for="kelurahan_id" class="block text-sm font-semibold text-gray-700">
                        Kelurahan Anda
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <select
                            id="kelurahan_id"
                            name="kelurahan_id"
                            class="block w-full pl-10 pr-10 py-2.5 rounded-xl border appearance-none
                                   {{ $errors->has('kelurahan_id') ? 'border-red-400 bg-red-50 focus:ring-red-400' : 'border-gray-200 bg-white focus:border-emerald-400 focus:ring-emerald-200' }}
                                   text-sm text-gray-900 focus:outline-none focus:ring-2 transition-all duration-150"
                        >
                            <option value="">— Pilih Kelurahan —</option>
                            @foreach ($kelurahans as $kel)
                                <option value="{{ $kel->id }}"
                                    {{ old('kelurahan_id') == $kel->id ? 'selected' : '' }}>
                                    {{ $kel->nama_kelurahan }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    @error('kelurahan_id')
                        <p class="text-xs text-red-600 flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password Kelurahan --}}
                <div class="space-y-1.5 mb-5" x-data="{ show: false }">
                    <label for="password_kelurahan" class="block text-sm font-semibold text-gray-700">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            id="password_kelurahan"
                            :type="show ? 'text' : 'password'"
                            name="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="block w-full pl-10 pr-10 py-2.5 rounded-xl border
                                   {{ $errors->has('password') ? 'border-red-400 bg-red-50 focus:ring-red-400' : 'border-gray-200 bg-white focus:border-emerald-400 focus:ring-emerald-200' }}
                                   text-sm text-gray-900 focus:outline-none focus:ring-2 transition-all duration-150"
                        >
                        <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-600 flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="flex items-center mb-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember_kelurahan"
                               class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>
                </div>

                <button
                    type="submit"
                    id="btn-login-kelurahan"
                    class="w-full flex items-center justify-center gap-2 py-3 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-semibold text-sm transition-all duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Masuk sebagai Petugas Kelurahan
                </button>
            </form>
        </div>

        {{-- ===== INFO AKUN DEMO ===== --}}
        <div class="mt-7 pt-5 border-t border-gray-100">
            <p class="text-center text-xs text-gray-400 mb-3 font-medium uppercase tracking-wide">Akun Demo</p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="bg-blue-50 rounded-xl p-3 border border-blue-100">
                    <p class="font-bold text-blue-700 mb-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                        Admin Industri
                    </p>
                    <p class="text-gray-600">📧 industri@ikm.test</p>
                    <p class="text-gray-600">🔑 password</p>
                </div>
                <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
                    <p class="font-bold text-emerald-700 mb-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        Petugas Kelurahan
                    </p>
                    <p class="text-gray-600">📍 Pilih kelurahan</p>
                    <p class="text-gray-600">🔑 password</p>
                </div>
            </div>
        </div>

    </div>
</x-guest-layout>
