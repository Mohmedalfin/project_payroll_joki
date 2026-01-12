<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\JabatanController; 
use App\Http\Controllers\KaryawanController; 
use App\Http\Controllers\PenggunaController; 
use App\Http\Controllers\GajiController; 
use App\Http\Controllers\KehadiranController; 
use App\Http\Controllers\LaporanController; 

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\LaporanUserController;

Route::get('/', [AuthController::class, 'index'])->name('root');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth']) 
    ->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/data-pengguna', PenggunaController::class)
        ->parameters(['data-pengguna' => 'pengguna']) 
        ->names('pengguna');

    Route::resource('/data-karyawan', KaryawanController::class)
        ->parameters(['data-karyawan' => 'karyawan']) 
        ->names('karyawan');

    Route::resource('/data-jabatan', JabatanController::class)
        ->parameters(['data-jabatan' => 'jabatan']) 
        ->names('jabatan');

    Route::get('/data-kehadiran', [KehadiranController::class, 'adminIndex'])->name('data-kehadiran');

    Route::resource('/gaji-karyawan', GajiController::class)
        ->parameters(['gaji-karyawan' => 'gaji']) 
        ->names('gaji');

    /** Laporan gaji */
    Route::get('/laporan-gaji', [LaporanController::class, 'index'])
        ->name('laporan.gaji');

    /** Cetak semua payroll */
    Route::get('/laporan-gaji/cetak-semua', [LaporanController::class, 'cetakSemua'])
        ->name('laporan.cetak-semua');

    /** Cetak slip 1 karyawan */
    Route::get('/laporan-gaji/cetak/{karyawan}', [LaporanController::class, 'cetak'])
        ->name('laporan.cetak');
});



// USER AREA
Route::prefix('user')
    ->name('user.')
    ->middleware(['auth'])
    ->group(function () {
        
        Route::get('/dashboard', function () {
            return redirect()->route('user.absensi');
        })->name('dashboard');

        Route::get('/presensi', [AbsensiController::class, 'index'])->name('absensi');
        Route::post('/presensi', [AbsensiController::class, 'store'])->name('absensi.store');

       // 1. Halaman List Gaji
        Route::get('/gaji', [LaporanUserController::class, 'index'])->name('gaji.index');

        // 2. Proses Hitung Gaji (Route yang Error Tadi)
        Route::post('/gaji/hitung', [LaporanUserController::class, 'store'])->name('gaji.store');

        // 3. Download PDF
        Route::get('/gaji/{id}/download', [LaporanUserController::class, 'download'])->name('gaji.download');}
);