<?php

use App\Http\Controllers\Admin\AdminSupplierController;
use App\Http\Controllers\Supplier\PerformanceController;
use App\Http\Controllers\Supplier\ContractController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\InventoryController\SupplierController as InventorySupplierController;
use App\Http\Controllers\InventoryController\ProcurementController;
use App\Http\Controllers\InventoryController\InventoryController;
use App\Http\Controllers\InventoryController\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
    Route::view('dashboard', 'dashboard')->middleware('verified')->name('dashboard');
    
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Supplier views (for supplier)
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

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers/{id}/activate', [SupplierController::class, 'activate'])->name('suppliers.activate');
    Route::post('/suppliers/{id}/deactivate', [SupplierController::class, 'deactivate'])->name('suppliers.deactivate');

    // Procurement Requests (Admin Approval)
    Route::get('/procurement/requests', [ProcurementController::class, 'index'])->name('procurement.requests.index');
    Route::post('/procurement/approve/{id}', [ProcurementController::class, 'approve'])->name('procurement.approve');
    Route::post('/procurement/reject/{id}', [ProcurementController::class, 'reject'])->name('procurement.reject');

    // Inventory Management
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

require __DIR__.'/auth.php';