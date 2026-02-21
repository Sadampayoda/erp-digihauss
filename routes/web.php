<?php

use App\Http\Controllers\AdvanceSaleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\SalesInvoiceController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SettingCoaController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function(){
    Route::get('/','index')->name('auth.view');
    Route::post('/','auth')->name('auth.login');
});


Route::controller(DashboardController::class)->group(function(){
    Route::get('/dashboard','index')->name('dashboard');
});


Route::resource('advance-sales', AdvanceSaleController::class)->names('advance-sales');
Route::resource('sales-invoices',SalesInvoiceController::class)->names('sales-invoices');


Route::resource('contacts', ContactController::class)->names('contacts');
Route::resource('items',ItemsController::class)->names('items');


Route::resource('brands',BrandController::class)->names('brands');
Route::resource('series',SeriesController::class)->names('series');
Route::resource('coas', CoaController::class)->names('coas');
Route::resource('payment-methods',PaymentMethodController::class)->names('payment-methods');
Route::resource('setting-coas',SettingCoaController::class)->names('setting-coas');
