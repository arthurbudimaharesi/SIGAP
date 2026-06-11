<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\PetugasController as AdminPetugasController;
use App\Http\Controllers\Admin\SlaController as AdminSlaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ZonaController;
use App\Http\Controllers\Admin\DaftarPengaduanController;
use App\Http\Controllers\Masyarakat\DashboardController as MasyarakatDashboardController;
use App\Http\Controllers\Masyarakat\PengaduanController;
use App\Http\Controllers\Masyarakat\RatingController;
use App\Http\Controllers\Masyarakat\RiwayatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Supervisor\AssignmentController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboardController;
use App\Http\Controllers\Supervisor\MonitorSlaController;
use App\Http\Controllers\Admin\LaporanKinerjaController;
use App\Http\Controllers\Supervisor\FilterPengaduanController;
use App\Http\Controllers\Supervisor\KinerjaPetugasController;
use App\Http\Controllers\Supervisor\LaporanController;
use App\Http\Controllers\Supervisor\ProfilController as SupervisorProfilController;
use App\Http\Controllers\Supervisor\VerifikasiController;
use App\Http\Controllers\Supervisor\ZonaController as SupervisorZonaController;
use App\Http\Controllers\Supervisor\ManajemenPetugasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ================= ADMIN =================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/pengaduan', [DaftarPengaduanController::class, 'index'])->name('pengaduan.index');

        // ✅ SLA (punyamu)
        Route::get('/sla', [AdminSlaController::class, 'index'])->name('sla.index');
        Route::get('/sla/{sla}/edit', [AdminSlaController::class, 'edit'])->name('sla.edit');
        Route::patch('/sla/{sla}', [AdminSlaController::class, 'update'])->name('sla.update');

        // ✅ USER ROLE (temenmu)
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // ✅ PETUGAS (FIX duplicate)
        Route::resource('petugas', AdminPetugasController::class);
        Route::patch('petugas/{petugas}/status', [PetugasController::class, 'updateStatus'])->name('petugas.update-status');
        Route::delete('petugas/{petugas}/hapus-permanen', [PetugasController::class, 'hapusPermanen'])->name('petugas.hapus-permanen');

        // Zona
        Route::resource('zona', ZonaController::class);
    });

});