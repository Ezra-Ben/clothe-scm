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

use App\Http\Controllers\VendorRegisterController;

Route::get('/vendor/register', [VendorRegisterController::class, 'showForm'])->name('vendor.form');
Route::post('/vendor/register', [VendorRegisterController::class, 'submit'])->name('vendor.submit');

use App\Http\Controllers\Admin\AdminSupplierDashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/suppliers/dashboard', [AdminSupplierDashboardController::class, 'index'])
        ->name('admin.supplier.dashboard');
});
