<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuControllers;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EbookControllers;
use App\Http\Controllers\EbookReadingControllers;
use App\Http\Controllers\KatalogBukuControllers;
use App\Http\Controllers\KatalogEbookControllers;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanControllers;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\ProfileControllers;
use App\Http\Controllers\UsersControllers;
use Illuminate\Support\Facades\Route;

Route::get('/',[AuthController::class,'showLoginForm']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('loginProses');

Route::middleware(['auth'])->group(function() {
    
    
    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileControllers::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileControllers::class, 'edit'])->name('profile.edit');
        Route::put('/', [ProfileControllers::class, 'update'])->name('profile.update');
    });
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/reading/start', [EbookReadingControllers::class, 'startReading'])->name('reading.start');
    
    Route::prefix('Katalog')->group(function(){
        Route::resource('KatalogEbook', KatalogEbookControllers::class);
        Route::resource('KatalogBuku', KatalogBukuControllers::class);
    });

    Route::prefix('MasterData')->group(function(){
        Route::resource('ebook', EbookControllers::class);
    });
    
    Route::middleware('admin')->group(function() {
        Route::prefix('pengaturan')->group(function(){
            Route::resource('users', UsersControllers::class);
            Route::resource('prodi', ProdiController::class);
        });
        
        Route::prefix('MasterData')->group(function(){
            Route::resource('buku', BukuControllers::class);
            Route::resource('kategori', KategoriController::class);
        });
    });

    // Route untuk peminjaman
    Route::post('/peminjaman', [PeminjamanControllers::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman', [PeminjamanControllers::class, 'index'])->name('peminjaman.index');
    Route::delete('/peminjaman/{id}/cancel', [PeminjamanControllers::class, 'cancel'])->name('peminjaman.cancel');
    Route::get('/peminjaman/{id}/show', [PeminjamanControllers::class, 'show'])->name('peminjaman.show');
    

    // Untuk admin
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::put('/peminjaman/{id}/approve', [PeminjamanControllers::class, 'approve'])->name('admin.peminjaman.approve');
        Route::put('/peminjaman/{id}/reject', [PeminjamanControllers::class, 'reject'])->name('admin.peminjaman.reject');
        Route::put('/peminjaman/{id}/return', [PeminjamanControllers::class, 'returnBook'])->name('admin.peminjaman.return');
    });



    
    
});


