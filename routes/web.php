<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/katalog', [BookController::class, 'index'])->name('books.index');
Route::get('/katalog/{book}', [BookController::class, 'show'])->name('books.show');
Route::post('/katalog/{book}/minta-lapak', [BookController::class, 'requestLapak'])->name('books.request-lapak');

Route::get('/kegiatan', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/kegiatan/{activity:slug}', [ActivityController::class, 'show'])->name('activities.show');

Route::get('/cron/sync-fines', [CronController::class, 'syncFines'])->name('cron.sync-fines');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/katalog/{book}/pinjam', [BookController::class, 'borrow'])->name('books.borrow');
    Route::get('/pinjaman/{loan}/konfirmasi', [LoanController::class, 'confirmation'])->name('loans.confirmation');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
