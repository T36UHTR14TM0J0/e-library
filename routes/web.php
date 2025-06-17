<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuControllers;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EbookControllers;
use App\Http\Controllers\EbookReadingControllers;
use App\Http\Controllers\KatalogBukuControllers;
use App\Http\Controllers\KatalogEbookControllers;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LaporanAnggotaController;
use App\Http\Controllers\LaporanBukuController;
use App\Http\Controllers\LaporanPeminjamanController;
use App\Http\Controllers\PeminjamanControllers;
use App\Http\Controllers\PenerbitControllers;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\ProfileControllers;
use App\Http\Controllers\UsersControllers;
use Illuminate\Support\Facades\Route;

// ###### LANDING PAGE ##### //
Route::get('/',[LandingController::class,'index'])->name('home');
Route::get('/viewBukuFisik',[LandingController::class,'viewBukuFisik'])->name('buku-fisik');
Route::get('/viewEbook',[LandingController::class,'viewEbook'])->name('ebook');
Route::get('/detailEbook/{id}', [LandingController::class, 'detailEbook'])->name('detail_ebook');
Route::get('/detailBuku/{id}', [LandingController::class, 'detailBuku'])->name('detail_buku');
Route::get('/prosedur', [LandingController::class, 'prosedur'])->name('prosedur');
Route::get('/jam-pelayanan', [LandingController::class, 'jamLayanan'])->name('jamLayanan');
Route::get('/layanan', [LandingController::class, 'layanan'])->name('layanan');
Route::get('/tentang', [LandingController::class, 'about'])->name('about');
// ##### END LANDING PAGE ##### //

// ##### LOGIN ##### //
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('loginProses');
// ##### END LOGIN ##### //

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
            Route::resource('penerbit', PenerbitControllers::class);
            Route::resource('prodi', ProdiController::class);
        });
        
        Route::prefix('MasterData')->group(function(){
            Route::resource('buku', BukuControllers::class);
            Route::resource('kategori', KategoriController::class);
        });

        Route::prefix('laporan')->group(function() {
            Route::get('/peminjaman', [LaporanPeminjamanController::class, 'index'])->name('laporan.peminjaman');
            Route::get('/peminjaman/export/pdf', [LaporanPeminjamanController::class, 'exportPDF'])->name('laporan.peminjaman.export.pdf');
            Route::get('/peminjaman/export/excel', [LaporanPeminjamanController::class, 'exportExcel'])->name('laporan.peminjaman.export.excel');
           

            Route::prefix('anggota')->group(function() {
                Route::get('/', [LaporanAnggotaController::class, 'index'])->name('laporan.anggota.index');
                Route::get('/pdf', [LaporanAnggotaController::class, 'exportPDF'])->name('laporan.anggota.export.pdf');
                Route::get('/excel', [LaporanAnggotaController::class, 'exportExcel'])->name('laporan.anggota.export.excel');
            });
            Route::prefix('buku')->group(function() {
                Route::get('/', [LaporanBukuController::class, 'index'])->name('laporan.buku.index');
                Route::get('/pdf', [LaporanBukuController::class, 'exportPDF'])->name('laporan.buku.export.pdf');
                Route::get('/excel', [LaporanBukuController::class, 'exportExcel'])->name('laporan.buku.export.excel');
            });
        
        });
        
    });

    // Route untuk peminjaman
    Route::post('/peminjaman', [PeminjamanControllers::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman', [PeminjamanControllers::class, 'index'])->name('peminjaman.index');
    Route::delete('/peminjaman/{id}/cancel', [PeminjamanControllers::class, 'cancel'])->name('peminjaman.cancel');
    Route::get('/peminjaman/{id}/show', [PeminjamanControllers::class, 'show'])->name('peminjaman.show');
    Route::get('/peminjaman/{id}/cetak', [PeminjamanControllers::class, 'cetakPDF'])->name('peminjaman.cetak');
    

    // Untuk admin
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::put('/peminjaman/{id}/approve', [PeminjamanControllers::class, 'approve'])->name('admin.peminjaman.approve');
        Route::put('/peminjaman/{id}/reject', [PeminjamanControllers::class, 'reject'])->name('admin.peminjaman.reject');
        Route::put('/peminjaman/{id}/return', [PeminjamanControllers::class, 'returnBook'])->name('admin.peminjaman.return');
    });



    
    
});


