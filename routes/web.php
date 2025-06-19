<?php

use App\Http\Controllers\Vendor\VendorController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('vendor/register', [VendorController::class, 'showForm'])->name('vendor.form');
Route::post('vendor/register', [VendorController::class, 'submitForm'])->name('vendor.register');

require __DIR__.'/auth.php';

use App\Http\Controllers\Vendor\VendorRegisterController;

Route::get('/vendor/register', [VendorRegisterController::class, 'showForm'])->name('vendor.form');
Route::post('/vendor/register', [VendorRegisterController::class, 'submit'])->name('vendor.submit');

use App\Http\Controllers\Supplier\SupplierController;

Route::middleware(['auth'])->group(function () {
    Route::get('/supplier/{id}/profile', [SupplierController::class, 'profile'])->name('supplier.profile');
    Route::get('/supplier/{id}/dashboard', [SupplierController::class, 'dashboard'])->name('supplier.dashboard');
});
Route::put('/supplier/{id}/update', [SupplierController::class, 'update'])->name('supplier.update');