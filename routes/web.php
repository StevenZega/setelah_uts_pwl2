<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\TransaksiPenjualanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
    Route::resource('categories', App\Http\Controllers\CategoryProductController::class);
    Route::resource('transaksis', App\Http\Controllers\TransaksiPenjualanController::class);
});

Route::get('/send-email/{to}/{Id}', [\App\Http\Controllers\TransaksiPenjualanController::class, 'sendEmail']);
