<?php

use App\Http\Controllers\AdvancePaymentController;
use App\Http\Controllers\AdvanceSaleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\ItemStockController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ReceiptInvoiceController;
use App\Http\Controllers\SalesInvoiceController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SettingCoaController;
use App\Http\Controllers\UserController;
use App\Models\AdvancePayment;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->name('login');
    Route::post('/', 'auth')->name('login.auth');
});


Route::middleware('auth')->group(function () {

    Route::prefix('dashboard')->group(function () {
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/', 'index')->name('dashboard');
        });
    });

    Route::resource('advance-sales', AdvanceSaleController::class)->names('advance-sales');
    Route::resource('sales-invoices', SalesInvoiceController::class)->names('sales-invoices');
    Route::resource('sales-returns', SalesReturnController::class)->names('sales-returns');

    Route::resource('advance-payments',AdvancePaymentController::class)->names('advance-payments');
    Route::resource('receipt-invoices',ReceiptInvoiceController::class)->names('receipt-invoices');


    Route::get('items/stock', ItemStockController::class)->name('items.stock');
    Route::resource('items', ItemsController::class)->names('items');
    Route::resource('contacts', ContactController::class)->names('contacts');


    Route::resource('brands', BrandController::class)->names('brands');
    Route::resource('series', SeriesController::class)->names('series');
    Route::resource('coas', CoaController::class)->names('coas');
    Route::resource('payment-methods', PaymentMethodController::class)->names('payment-methods');
    Route::resource('setting-coas', SettingCoaController::class)->names('setting-coas');
    Route::resource('users', UserController::class)->names('users');
    Route::resource('permissions',PermissionController::class)->names('permissions');


    Route::resource('journals', JournalController::class)->names('journals');
});
