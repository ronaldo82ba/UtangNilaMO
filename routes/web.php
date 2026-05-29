<?php

use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\BorrowerStatementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UtangEntryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('borrowers', BorrowerController::class);

    Route::get('/borrowers/{borrower}/utang', [UtangEntryController::class, 'index'])->name('borrowers.utang');
    Route::post('/borrowers/{borrower}/utang', [UtangEntryController::class, 'store'])->name('borrowers.utang.store');
    Route::delete('/borrowers/{borrower}/utang/{utang}', [UtangEntryController::class, 'destroy'])->name('borrowers.utang.destroy');

    Route::get('/borrowers/{borrower}/payments', [PaymentController::class, 'index'])->name('borrowers.payments');
    Route::post('/borrowers/{borrower}/payments', [PaymentController::class, 'store'])->name('borrowers.payments.store');
    Route::delete('/borrowers/{borrower}/payments/{payment}', [PaymentController::class, 'destroy'])->name('borrowers.payments.destroy');

    Route::get('/borrowers/{borrower}/statement', [BorrowerStatementController::class, 'show'])->name('borrowers.statement');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
