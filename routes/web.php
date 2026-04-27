<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductWebController;

Route::get('/', [ProductWebController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductWebController::class, 'create'])->name('products.create');
Route::post('/products', [ProductWebController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductWebController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductWebController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductWebController::class, 'destroy'])->name('products.destroy');
Route::post('/products/{product}/stock-in', [ProductWebController::class, 'stockIn'])->name('products.stockIn');
Route::post('/products/{product}/stock-out', [ProductWebController::class, 'stockOut'])->name('products.stockOut');
Route::get('/products/report', [ProductWebController::class, 'report'])->name('products.report');
Route::get('/products/export', [ProductWebController::class, 'export'])->name('products.export');
