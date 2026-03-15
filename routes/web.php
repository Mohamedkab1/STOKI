<?php
// routes/web.php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController; // Nouveau
use Illuminate\Support\Facades\Route;

// ========== ROUTES D'AUTHENTIFICATION ==========
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== ROUTES PROTÉGÉES (nécessitent auth) ==========
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Produits
    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])
        ->name('products.adjust-stock');
    Route::get('/products/{product}/fifo-history', [ProductController::class, 'fifoHistory'])
        ->name('products.fifo-history');
    
    // Catégories
    Route::resource('categories', CategoryController::class);
    
    // Factures
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'generatePDF'])
        ->name('invoices.pdf');
    Route::patch('/invoices/{invoice}/payment-status', [InvoiceController::class, 'updatePaymentStatus'])
        ->name('invoices.update-payment-status');
    
    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/latest', [NotificationController::class, 'getLatest'])->name('latest');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/destroy-all', [NotificationController::class, 'destroyAll'])->name('destroy-all');
    });
});