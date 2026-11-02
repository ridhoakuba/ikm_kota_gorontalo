<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kelurahan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login dengan data kelurahan untuk dropdown.
     */
    public function create(): View
    {
        $kelurahans = Kelurahan::orderBy('nama_kelurahan')->get();

        return view('auth.login', compact('kelurahans'));
    }

    /**
     * Proses autentikasi berdasarkan tipe login (industri atau kelurahan).
     */
    public function store(Request $request): RedirectResponse
    {
        // ── CEK SESI SEBELUMNYA ──────────────────────────────────────────────
        // Jika user sebelumnya masih dalam keadaan login, panggil logout terlebih dahulu
        // agar sesi lama tidak menimpa atau mengunci akun baru.
        if (Auth::check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // ── LOGIN KELURAHAN ──────────────────────────────────────────────────
        if ($request->filled('kelurahan_id')) {
            $request->validate([
                'kelurahan_id' => ['required', 'integer', 'exists:kelurahans,id'],
                'password'     => ['required', 'string'],
            ], [
                'kelurahan_id.required' => 'Silakan pilih kelurahan.',
                'kelurahan_id.exists'   => 'Kelurahan tidak ditemukan.',
                'password.required'     => 'Password wajib diisi.',
            ]);

            // Cari user dengan role 'kelurahan' yang terkait kelurahan ini
            $userKelurahan = User::where('role', 'kelurahan')
                ->where('kelurahan_id', $request->kelurahan_id)
                ->first();

            if (! $userKelurahan) {
                return back()
                    ->withInput($request->only('kelurahan_id'))
                    ->withErrors(['kelurahan_id' => 'Tidak ada akun petugas untuk kelurahan ini.']);
            }

            // Autentikasi menggunakan email user kelurahan yang ditemukan
            if (! Auth::attempt(['email' => $userKelurahan->email, 'password' => $request->password], $request->boolean('remember'))) {
                return back()
                    ->withInput($request->only('kelurahan_id'))
                    ->withErrors(['password' => 'Password yang Anda masukkan salah.']);
            }

            $request->session()->regenerate();

            return redirect('/dashboard');
        }

        // ── LOGIN INDUSTRI ───────────────────────────────────────────────────
        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (! Auth::attempt(
            ['email' => $request->email, 'password' => $request->password],
            $request->boolean('remember')
        )) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password yang Anda masukkan salah.']);
        }

        $request->session()->regenerate();

        return redirect('/dashboard');
    }

    /**
     * Hapus sesi autentikasi yang aktif.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
