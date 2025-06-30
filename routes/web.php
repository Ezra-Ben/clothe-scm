<?php

use App\Http\Controllers\Admin\AdminSupplierController;
use App\Http\Controllers\Supplier\PerformanceController;
use App\Http\Controllers\Supplier\ContractController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');

    Route::view('dashboard', 'dashboard')->middleware('verified')->name('dashboard');
    
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Supplier  views (for supplier)
    Route::middleware(['can:view-readonly'])->group(function () {
        Route::get('/supplier/profile', [SupplierController::class, 'profile'])->name('supplier.profile');
        Route::patch('/supplier/update', [SupplierController::class, 'update'])->name('supplier.update');
        Route::get('/supplier/dashboard', [SupplierController::class, 'dashboard'])->name('supplier.dashboard');
        Route::get('/supplier/performance', [PerformanceController::class, 'index'])->name('supplier.performance');
        Route::get('/supplier/contracts', [ContractController::class, 'index'])->name('supplier.contracts.index');
	Route::get('/supplier/contracts/{contractId}', [ContractController::class, 'show'])->name('supplier.contracts.show');
    });

    // Editable views (admin or supplier_manager)
    Route::middleware(['can:manage-suppliers'])->group(function () {
	Route::get('/supplier/select', [AdminSupplierController::class, 'select'])->name('admin.select.supplier');
  	Route::get('/manage/supplier/contracts', [ContractController::class, 'index'])->name('manage.supplier.contracts.index');
	Route::get('/supplier/{id}/contracts/create', [ContractController::class, 'create'])->name('manage.supplier.contracts.create');
        Route::get('/supplier/{contractId}/contracts/{id}', [ContractController::class, 'show'])->name('manage.supplier.contracts.show');
	Route::post('/supplier/{id}/contracts', [ContractController::class, 'store'])->name('manage.supplier.contracts.store');
 	Route::put('/supplier/{id}/contracts/{contractId}', [ContractController::class, 'update'])->name('manage.supplier.contracts.update');
        Route::get('/supplier/{id}/performance', [PerformanceController::class, 'index'])->name('manage.supplier.performance');
 	Route::post('/supplier/{id}/performance', [PerformanceController::class, 'store'])->name('manage.supplier.performance.store');
    });


        Route::get('vendor/register', [VendorController::class, 'showForm'])->name('vendor.form');
        Route::post('vendor/register', [VendorController::class, 'submitForm'])->name('vendor.register');

});
require __DIR__.'/auth.php';

