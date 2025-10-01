<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LansiaController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\AjaxController;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('auth')->group(function () {

    // Alias Breeze setelah login
    Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))
        ->name('dashboard');

    // Semua route ADMIN: prefix /admin dan nama admin.*
    Route::prefix('admin')->name('admin.')->group(function () {

        // Dasbor
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        // CRUD Lansia
        // TAMBAH: route create supaya ada halaman "Tambah Data Baru"
        Route::resource('lansia', LansiaController::class)
            ->only(['index','create','store','update','destroy']);

        // Manajemen staf (khusus admin)
        Route::middleware('isAdmin')->group(function () {
            Route::get('staff',   [StaffController::class,'index'])->name('staff.index');
            Route::post('staff',  [StaffController::class,'store'])->name('staff.store');
            Route::patch('staff/{user}', [StaffController::class,'update'])->name('staff.update');
            Route::delete('staff/{user}',[StaffController::class,'destroy'])->name('staff.destroy');
        });

        // AJAX desa
        Route::get('/ajax/desa', [AjaxController::class,'desaByKecamatan'])->name('ajax.desa');
    });
});

require __DIR__.'/auth.php';
