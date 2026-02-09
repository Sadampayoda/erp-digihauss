<?php

use App\Http\Controllers\AdvanceSaleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemsController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function(){
    Route::get('/','index')->name('auth.view');
    Route::post('/','auth')->name('auth.login');
});


Route::controller(DashboardController::class)->group(function(){
    Route::get('/dashboard','index')->name('dashboard');
});


Route::resource('advance-sales', AdvanceSaleController::class)->names('advance-sales');
Route::resource('contacts', ContactController::class)->names('contacts');
Route::resource('items',ItemsController::class)->names('items');
