<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    
    // GESTION DES PRODUITS
    Route::apiResource('products', ProductApiController::class);


    // GESTION DES MOUVEMENTS DE STOCK
    Route::get('/stock-movements', [\App\Http\Controllers\Api\StockMovementApiController::class, 'index']);
    Route::post('/stock-movements', [\App\Http\Controllers\Api\StockMovementApiController::class, 'store']);
    
});