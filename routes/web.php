<?php

use App\Http\Controllers\FormVerifikasiController;
use App\Http\Controllers\IkmController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $totalIkm = \App\Models\DataIkm::count();
    $totalKelurahan = \App\Models\Kelurahan::count();
    $ditemukan = \App\Models\DataIkm::where('status_verifikasi', 'ditemukan')->count();
    $menunggu = \App\Models\DataIkm::where('status_verifikasi', 'menunggu')->count();
    $tidakDitemukan = \App\Models\DataIkm::where('status_verifikasi', 'tidak_ditemukan')->count();

    // Hitung Persentase (cegah division by zero)
    $persenDitemukan = $totalIkm > 0 ? round(($ditemukan / $totalIkm) * 100) : 0;
    $persenMenunggu = $totalIkm > 0 ? round(($menunggu / $totalIkm) * 100) : 0;
    $persenTidakDitemukan = $totalIkm > 0 ? round(($tidakDitemukan / $totalIkm) * 100) : 0;

    // Ambil 3-5 Aktivitas/Verifikasi Terbaru
    $aktivitasTerbaru = \App\Models\DataIkm::with('kelurahan')
                        ->latest('updated_at')
                        ->take(3)
                        ->get();

    return view('welcome', compact(
        'totalIkm', 
        'totalKelurahan', 
        'ditemukan', 
        'menunggu', 
        'tidakDitemukan',
        'persenDitemukan', 
        'persenMenunggu', 
        'persenTidakDitemukan',
        'aktivitasTerbaru'
    ));
})->name('home');

Route::get('/tentang', function () {
    return view('tentang');
})->name('tentang');

// Dashboard berbasis role — diarahkan ke view yang sesuai oleh IkmController
Route::get('/dashboard', [IkmController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // ─── Profile ──────────────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── Kelurahan — Ganti password sendiri ───────────────────────────────
    Route::post('/kelurahan/ganti-password', [IkmController::class, 'gantiPassword'])
        ->name('kelurahan.gantiPassword');

    // ─── IKM — Update status verifikasi (role kelurahan) ──────────────────
    Route::patch('/ikm/{dataIkm}/status', [IkmController::class, 'updateStatus'])
        ->name('ikm.updateStatus');

    // ─── Legacy route — reset password (backward compat) ──────────────────
    Route::post('/ikm/user/{user}/reset-password', [IkmController::class, 'resetPasswordKelurahan'])
        ->name('ikm.resetPasswordKelurahan');

    // =========================================================================
    // MODUL BIDANG INDUSTRI — Sidebar Multi-Modul
    // Prefix: /industri    |    Name prefix: industri.
    // =========================================================================
    Route::prefix('industri')->name('industri.')->group(function () {

        // Modul 1: Beranda / Statistik
        Route::get('/beranda', [IkmController::class, 'beranda'])
            ->name('beranda');

        // Modul 2: Data IKM — Master Tabel & Import
        Route::get('/data-ikm', [IkmController::class, 'dataIkm'])
            ->name('data-ikm');
        Route::post('/data-ikm/import', [IkmController::class, 'import'])
            ->name('import');
        Route::post('/data-ikm/manual', [IkmController::class, 'storeManual'])
            ->name('store-manual');

        // Modul 3: Akun Kelurahan — Manajemen & Reset Password
        Route::get('/akun-kelurahan', [IkmController::class, 'akunKelurahan'])
            ->name('akun-kelurahan');
        Route::post('/akun-kelurahan/{user}/reset', [IkmController::class, 'resetPasswordKelurahan'])
            ->name('reset-password');

        // Modul 4: Form Verifikasi — Instrumen (Dynamic Form Engine)
        Route::get('/form-verifikasi', [FormVerifikasiController::class, 'index'])
            ->name('form-verifikasi');
        Route::post('/form-verifikasi', [FormVerifikasiController::class, 'store'])
            ->name('form-verifikasi.store');
        Route::patch('/form-verifikasi/{formVerifikasi}', [FormVerifikasiController::class, 'update'])
            ->name('form-verifikasi.update');
        Route::delete('/form-verifikasi/{formVerifikasi}', [FormVerifikasiController::class, 'destroy'])
            ->name('form-verifikasi.destroy');

        // Aliases untuk kompatibilitas
        Route::post('/instrumen', [FormVerifikasiController::class, 'store'])
            ->name('instrumen.tambah');
        Route::patch('/instrumen/{formVerifikasi}', [FormVerifikasiController::class, 'update'])
            ->name('instrumen.update');
        Route::delete('/instrumen/{formVerifikasi}', [FormVerifikasiController::class, 'destroy'])
            ->name('instrumen.hapus');

        // Modul 5: Laporan & Tindak Lanjut
        Route::get('/laporan', [IkmController::class, 'laporan'])
            ->name('laporan');
        Route::patch('/laporan/{dataIkm}/tindaklanjut', [IkmController::class, 'tandaiDitindaklanjuti'])
            ->name('tindaklanjut');
        Route::get('/laporan/ekspor', [IkmController::class, 'eksporLaporan'])
            ->name('ekspor');
    });
});

require __DIR__ . '/auth.php';
