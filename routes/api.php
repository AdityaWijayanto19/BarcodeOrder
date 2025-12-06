<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KitchenApiController; // Asumsikan Anda punya ini
use App\Http\Controllers\Api\MidtransController;  // Asumsikan Anda punya ini

// 1. RUTE WEBHOOK MIDTRANS (PENTING: Tidak boleh ada middleware CSRF)
// ----------------------------------------------------------------
Route::post('midtrans/webhook', [MidtransController::class, 'webhook']);


// 2. RUTE API DAPUR (Untuk memuat dan update status order)
// ----------------------------------------------------------------
Route::controller(KitchenApiController::class)->prefix('kitchen/orders')->group(function () {
    // GET /api/kitchen/orders (Untuk memuat daftar order)
    Route::get('/', 'index'); 
    
    // PUT /api/kitchen/orders/{order} (Untuk update status 'cooking', 'done', dll.)
    Route::put('/{order}', 'updateStatus'); 
});