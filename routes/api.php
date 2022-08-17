<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CloseSaleController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventDateController;
use App\Http\Controllers\Api\EventStockController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductSaleController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\StockController;
use Illuminate\Support\Facades\Route;

// Auth
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
    Route::post('/reset-password', [AuthController::class, 'forgotPassword'])->name('password.reset');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'getUser'])->name('user');
        Route::post('/register/employee', [AuthController::class, 'registerEmployee'])->name('register.employee');
        Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

// Produtos
Route::prefix('product')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index.product');
    Route::post('/', [ProductController::class, 'store'])->name('store.product');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show.product');
    Route::post('/{id}', [ProductController::class, 'update'])->name('update.product');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy.product');
});

// Eventos
Route::prefix('event')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index.event');
    Route::post('/', [EventController::class, 'store'])->name('store.event');
    Route::get('/{id}', [EventController::class, 'show'])->name('show.event');
    Route::put('/{id}', [EventController::class, 'update'])->name('update.event');
    Route::delete('/{id}', [EventController::class, 'destroy'])->name('destroy.event');

    // Date
    Route::post('/date', [EventDateController::class, 'store'])->name('store.event.date');
    Route::delete('/date/{id}', [EventDateController::class, 'destroy'])->name('destroy.event.date');

    //Stock
    Route::post('/stock', [EventStockController::class, 'store'])->name('store.event.stock');
    Route::delete('/stock/{id}', [EventStockController::class, 'destroy'])->name('destroy.event.stock');
});

// Stock
Route::prefix('stock')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('index.stock');
    Route::post('/', [StockController::class, 'store'])->name('store.stock');
    Route::delete('/{id}', [StockController::class, 'destroy'])->name('destroy.stock');
});

// Sale
Route::prefix('sale')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [SaleController::class, 'store'])->name('store.sale');
    Route::get('/{id}', [SaleController::class, 'show'])->name('show.sale');
    Route::delete('/{id}', [SaleController::class, 'destroy'])->name('destroy.sale');

    // ProductSale
    Route::post('/', [ProductSaleController::class, 'store'])->name('store.sale.product');
    Route::put('/', [ProductSaleController::class, 'update'])->name('update.sale.product');
    Route::delete('/{id}', [ProductSaleController::class, 'destroy'])->name('destroy.sale.product');

    // CloseSale
    Route::post('/close', [CloseSaleController::class, 'store'])->name('store.sale.close');
});
