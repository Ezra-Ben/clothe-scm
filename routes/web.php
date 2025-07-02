<?php

use App\Http\Controllers\Admin\AdminSupplierController;
use App\Http\Controllers\Supplier\PerformanceController;
use App\Http\Controllers\Supplier\ContractController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\InventoryController\SupplierManagementController ;
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
        Route::get('/supplier/dashboard', [\App\Http\Controllers\InventoryController\SupplierController::class, 'dashboard'])->name('supplier.dashboard');
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
    Route::get('/supplier/procurement/accept/{id}', [SupplierController::class, 'acceptProcurement'])->name('supplier.procurement.accept');
    Route::get('/supplier/procurement/cancel/{id}', [SupplierController::class, 'cancelProcurement'])->name('supplier.procurement.cancel');
    Route::get('/supplier/procurement/form/{id}', [SupplierController::class, 'showProcurementForm'])->name('supplier.procurement.form');
    Route::post('/supplier/procurement/deliver/{id}', [SupplierController::class, 'deliverProcurement'])->name('supplier.procurement.deliver');
    Route::middleware(['can:manage-inventory'])->group(function () {


    Route::get('/suppliers', [SupplierManagementController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers/{id}/activate', [SupplierManagementController::class, 'activate'])->name('suppliers.activate');
    Route::post('/suppliers/{id}/deactivate', [SupplierManagementController::class, 'deactivate'])->name('suppliers.deactivate');

    // Procurement Requests (Admin Approval)
    Route::get('/procurement/requests', [ProcurementController::class, 'index'])->name('procurement.requests.index');
    Route::post('/procurement/approve/{id}', [ProcurementController::class, 'approve'])->name('procurement.approve');
    Route::post('/procurement/reject/{id}', [ProcurementController::class, 'reject'])->name('procurement.reject');
    Route::post('/admin/procurement/confirm-delivery/{id}', [ProcurementController::class, 'confirmDelivery'])->name('admin.procurement.confirmDelivery');

    // Inventory Management
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
     Route::get('/products/create', [InventoryController::class, 'createProductForm'])->name('products.create');
    Route::post('/products', [InventoryController::class, 'storeProduct'])->name('products.store');
});

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead'); 
    Route::get('/admin/inventory/dashboard', [InventoryController::class, 'dashboard'])->name('admin.inventory.dashboard');
    Route::get('/admin/inventory/order-requests', [InventoryController::class, 'orderRequests'])->name('inventory.order.requests');
    Route::get('/admin/inventory/order-requests/{id}', [InventoryController::class, 'showOrderRequest'])->name('inventory.order.requests.show');
    Route::post('/inventory/add', [InventoryController::class, 'addProduct'])->name('inventory.add');
    Route::delete('/inventory/{id}', [InventoryController::class, 'deleteProduct'])->name('inventory.delete');
    

});


require __DIR__.'/auth.php';