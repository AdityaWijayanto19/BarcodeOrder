<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicOrderController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminMenuController;
use App\Http\Controllers\TableController;
use App\Models\Table;

Route::get('/', fn() => redirect('/menus'));

// Rute pemesanan baru:
Route::get('order/form', [PublicOrderController::class, 'showOrderForm'])->name('order.form');
Route::post('order', [PublicOrderController::class, 'storeOrder'])->name('order.store');
Route::get('/order/success/{invoice}', [PublicOrderController::class, 'showOrderSuccess'])->name('order.success');

// Kitchen
Route::get('kitchen', [KitchenController::class, 'index'])->name('kitchen.index');

// Admin Dashboard
Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('admin/barcodes', [AdminController::class, 'barcodes'])->name('admin.barcodes');
Route::get('admin/barcodes/print', [AdminController::class, 'printBarcodes'])->name('admin.print-barcodes');

// ===== BARCODE ROUTE (harus duluan) =====
Route::get('admin/tables/barcode/{id}', function ($id) {
    $table = Table::findOrFail($id);
    $url = url('/order/form?table=' . $table->id);
    return view('admin.tables.barcode', compact('table', 'url'));
})->name('table.barcode');

// Resource
Route::resource('menus', AdminMenuController::class);
Route::resource('admin/tables', TableController::class);

