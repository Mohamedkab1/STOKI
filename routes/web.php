<?php
// routes/web.php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\AICategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ========== ROUTES D'AUTHENTIFICATION ==========
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ========== ROUTES PROTÉGÉES (nécessitent authentification) ==========
Route::middleware(['auth'])->group(function () {

    // ========== DASHBOARD ==========
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ========== IA - CATÉGORISATION AUTOMATIQUE ==========
    Route::post('/ai/suggest-category', [AICategoryController::class, 'suggestCategory'])
        ->name('ai.suggest-category');

    // ========== GESTION DES PRODUITS ==========
    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])
        ->name('products.adjust-stock');
    Route::post('/products/{product}/recalculate-stock', [ProductController::class, 'recalculateStock'])
        ->name('products.recalculate-stock');
    Route::get('/products/{product}/check-stock', function(\App\Models\Product $product) {
        return response()->json([
            'product_id' => $product->id,
            'name' => $product->name,
            'current_stock' => $product->quantity,
            'min_stock' => $product->min_stock,
            'is_low_stock' => $product->isLowStock(),
            'last_movements' => $product->stockMovements()->latest()->take(5)->get(['type', 'quantity', 'created_at'])
        ]);
    })->name('products.check-stock');
    Route::post('/products/{product}/add-stock', [ProductController::class, 'addStock'])
        ->name('products.add-stock');
    Route::get('/products/{product}/fifo-history', [ProductController::class, 'fifoHistory'])
        ->name('products.fifo-history');
    Route::get('/products/{product}/valuation', [ProductController::class, 'valuation'])
        ->name('products.valuation');
    Route::get('/products/{product}/batches', [ProductController::class, 'batches'])
        ->name('products.batches');

    // ========== GESTION DES MOUVEMENTS DE STOCK ==========
    Route::get('/stock-movements', [StockMovementController::class, 'index'])
        ->name('stock-movements.index');
    Route::post('/stock-movements', [StockMovementController::class, 'store'])
        ->name('stock-movements.store');

    // ========== GESTION DES CATÉGORIES ==========
    Route::resource('categories', CategoryController::class);

    // ========== GESTION DES FACTURES ==========
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'generatePDF'])
        ->name('invoices.pdf');
    Route::patch('/invoices/{invoice}/payment-status', [InvoiceController::class, 'updatePaymentStatus'])
        ->name('invoices.update-payment-status');

    // ========== GESTION DES NOTIFICATIONS ==========
    Route::prefix('notifications')->name('notifications.')->group(function () {
        // Routes JSON (API pour le dropdown de la cloche)
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::get('/category/{category}', [NotificationController::class, 'byCategory'])->name('category');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');

        // Routes avec vues Blade
        Route::get('/all', [NotificationController::class, 'showAll'])->name('all');
        Route::get('/{id}/show', [NotificationController::class, 'show'])->name('show');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/destroy-all', [NotificationController::class, 'destroyAll'])->name('destroy-all');
    });

    // ========== ROUTES API POUR LE DASHBOARD (AJAX) ==========
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/stock-stats', [DashboardController::class, 'getStockStats'])->name('stock-stats');
        Route::get('/recent-activities', [DashboardController::class, 'getRecentActivities'])->name('recent-activities');
    });

    // ========== ROUTES SUPER ADMIN ==========
    Route::middleware(['superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/admins', [\App\Http\Controllers\SuperAdmin\AdminManagementController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [\App\Http\Controllers\SuperAdmin\AdminManagementController::class, 'create'])->name('admins.create');
        Route::post('/admins', [\App\Http\Controllers\SuperAdmin\AdminManagementController::class, 'store'])->name('admins.store');
        Route::post('/admins/{admin}/approve', [\App\Http\Controllers\SuperAdmin\AdminManagementController::class, 'approve'])->name('admins.approve');
        Route::post('/admins/{admin}/reject', [\App\Http\Controllers\SuperAdmin\AdminManagementController::class, 'reject'])->name('admins.reject');
        Route::get('/admins/{admin}/edit', [\App\Http\Controllers\SuperAdmin\AdminManagementController::class, 'edit'])->name('admins.edit');
        Route::put('/admins/{admin}', [\App\Http\Controllers\SuperAdmin\AdminManagementController::class, 'update'])->name('admins.update');
        Route::delete('/admins/{admin}', [\App\Http\Controllers\SuperAdmin\AdminManagementController::class, 'destroy'])->name('admins.destroy');
    });

});

// ========== ROUTES BREEZE (Email Verification, Password Reset, Profile) ==========
Route::middleware('guest')->group(function () {
    // Email Verification
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Password Reset
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    // Profile Management
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile');
    Route::put('profile', [App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');
    Route::put('profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])
        ->name('profile.password');
});