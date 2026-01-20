<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\DashboardController;
use App\Http\Controllers\Store\POSController;
use App\Http\Controllers\Store\InventoryController;
use App\Http\Controllers\Store\OrderController;
use App\Http\Controllers\Store\ExpenseController;
use App\Http\Controllers\Store\ReportController;

Route::prefix('store')->name('store.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // POS
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [POSController::class, 'index'])->name('index');
        Route::post('/order', [POSController::class, 'createOrder'])->name('order');
        Route::post('/hold', [POSController::class, 'hold'])->name('hold');
        Route::get('/holds', [POSController::class, 'holds'])->name('holds');
        Route::post('/resume/{id}', [POSController::class, 'resume'])->name('resume');
        Route::post('/payment', [POSController::class, 'payment'])->name('payment');
        Route::get('/invoice/{id}', [POSController::class, 'invoice'])->name('invoice');
    });
    
    // Inventory
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/stock-log', [InventoryController::class, 'stockLog'])->name('stock-log');
        Route::get('/alerts', [InventoryController::class, 'alerts'])->name('alerts');
    });
    
    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}/view', [OrderController::class, 'view'])->name('view');
        Route::post('/{id}/accept', [OrderController::class, 'accept'])->name('accept');
        Route::post('/{id}/reject', [OrderController::class, 'reject'])->name('reject');
        Route::get('/track', [OrderController::class, 'track'])->name('track');
        Route::get('/history', [OrderController::class, 'history'])->name('history');
    });
    
    // Expenses
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('index');
        Route::get('/create', [ExpenseController::class, 'create'])->name('create');
        Route::post('/', [ExpenseController::class, 'store'])->name('store');
        Route::get('/reports', [ExpenseController::class, 'reports'])->name('reports');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/daily-summary', [ReportController::class, 'dailySummary'])->name('daily-summary');
        Route::get('/employee-sales', [ReportController::class, 'employeeSales'])->name('employee-sales');
    });
});

