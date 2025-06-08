<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuControllers;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EbookControllers;
use App\Http\Controllers\KatalogEbookControllers;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\ProfileControllers;
use App\Http\Controllers\UsersControllers;
use Illuminate\Support\Facades\Route;

Route::get('/',[AuthController::class,'showLoginForm']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('loginProses');

Route::middleware(['auth'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('users', UsersControllers::class);

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileControllers::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileControllers::class, 'edit'])->name('profile.edit');
        Route::put('/', [ProfileControllers::class, 'update'])->name('profile.update');
    });

    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuControllers::class);
    Route::resource('ebook', EbookControllers::class);
    Route::resource('KatalogEbook', KatalogEbookControllers::class);
    
});


Route::middleware(['auth', 'admin'])->group(function() {
    Route::resource('prodi', ProdiController::class);
});



