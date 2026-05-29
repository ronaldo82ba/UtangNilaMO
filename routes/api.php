<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\MeController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\BorrowerController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\StatementController;
use App\Http\Controllers\Api\V1\UtangEntryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public auth routes
    Route::post('/register', RegisterController::class)->name('api.register');
    Route::post('/login', LoginController::class)->name('api.login');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/logout', LogoutController::class)->name('api.logout');
        Route::get('/me', MeController::class)->name('api.me');

        // Dashboard
        Route::get('/dashboard', DashboardController::class)->name('api.dashboard');

        // Borrowers CRUD
        Route::apiResource('borrowers', BorrowerController::class)->names([
            'index' => 'api.borrowers.index',
            'store' => 'api.borrowers.store',
            'show' => 'api.borrowers.show',
            'update' => 'api.borrowers.update',
            'destroy' => 'api.borrowers.destroy',
        ]);

        // Utang entries (nested under borrowers)
        Route::apiResource('borrowers.utang', UtangEntryController::class)
            ->parameters(['utang' => 'utang'])
            ->names([
                'index' => 'api.borrowers.utang.index',
                'store' => 'api.borrowers.utang.store',
                'show' => 'api.borrowers.utang.show',
                'update' => 'api.borrowers.utang.update',
                'destroy' => 'api.borrowers.utang.destroy',
            ]);

        // Payments (nested under borrowers)
        Route::apiResource('borrowers.payments', PaymentController::class)
            ->parameters(['payments' => 'payment'])
            ->names([
                'index' => 'api.borrowers.payments.index',
                'store' => 'api.borrowers.payments.store',
                'show' => 'api.borrowers.payments.show',
                'update' => 'api.borrowers.payments.update',
                'destroy' => 'api.borrowers.payments.destroy',
            ]);

        // Statement
        Route::get('/borrowers/{borrower}/statement', [StatementController::class, 'show'])
            ->name('api.borrowers.statement');
        Route::get('/borrowers/{borrower}/statement/text', [StatementController::class, 'text'])
            ->name('api.borrowers.statement.text');
        Route::get('/borrowers/{borrower}/statement/escpos', [StatementController::class, 'escpos'])
            ->name('api.borrowers.statement.escpos');
    });
});
