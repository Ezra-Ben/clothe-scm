<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarrierController;
use App\Http\Controllers\CarrierDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DistributionAndLogisticsController;
use App\Http\Controllers\InboundShipmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Admin\AdminSupplierController;
use App\Http\Controllers\Supplier\PerformanceController;
use App\Http\Controllers\Supplier\ContractController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Supplier\SupplierController;

Route::view('/', 'welcome');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    Route::view('profile', 'profile')->name('profile');
    Route::view('dashboard', 'dashboard')->middleware('verified')->name('dashboard');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');});

    /**
     * Supplier Views (for suppliers with read-only access)
     */
    Route::middleware(['can:view-readonly'])->group(function () {
        Route::get('/supplier/profile', [SupplierController::class, 'profile'])->name('supplier.profile');
        Route::patch('/supplier/update', [SupplierController::class, 'update'])->name('supplier.update');
        Route::get('/supplier/dashboard', [SupplierController::class, 'dashboard'])->name('supplier.dashboard');
        Route::get('/supplier/performance', [PerformanceController::class, 'index'])->name('supplier.performance');

        // Place static before dynamic to avoid conflict
        Route::get('/supplier/contracts', [ContractController::class, 'index'])->name('supplier.contracts.index');
        Route::get('/supplier/contracts/{contractId}', [ContractController::class, 'show'])->name('supplier.contracts.show');
    });

    /**
     * Editable supplier views (admin or supplier_manager)
     */
    Route::middleware(['can:manage-suppliers'])->group(function () {
        Route::get('/supplier/select', [AdminSupplierController::class, 'select'])->name('admin.select.supplier');
        Route::get('/manage/supplier/contracts', [ContractController::class, 'index'])->name('manage.supplier.contracts.index');

        // Avoid collision by placing "create" above dynamic
        Route::get('/supplier/{id}/contracts/create', [ContractController::class, 'create'])->name('manage.supplier.contracts.create');
        Route::get('/supplier/{contractId}/contracts/{id}', [ContractController::class, 'show'])->name('manage.supplier.contracts.show');

        Route::post('/supplier/{id}/contracts', [ContractController::class, 'store'])->name('manage.supplier.contracts.store');
        Route::put('/supplier/{id}/contracts/{contractId}', [ContractController::class, 'update'])->name('manage.supplier.contracts.update');

        Route::get('/supplier/{id}/performance', [PerformanceController::class, 'index'])->name('manage.supplier.performance');
        Route::post('/supplier/{id}/performance', [PerformanceController::class, 'store'])->name('manage.supplier.performance.store');
    });

    /**
     * Vendor Registration
     */
    Route::get('vendor/register', [VendorController::class, 'showForm'])->name('vendor.form');
    Route::post('vendor/register', [VendorController::class, 'submitForm'])->name('vendor.register');

/**
 * User Dashboard
 */
Route::middleware(['auth', 'can:user'])->get('/distributionandlogistics/users/dashboard', [UserDashboardController::class, 'index'])
    ->name('distributionandlogistics.users.dashboard');

Route::middleware(['can:admin'])->get('/distributionandlogistics/admin', [DistributionAndLogisticsController::class, 'index'])
    ->name('distributionandlogistics.admin.index');

// Consolidated Carrier Routes
Route::middleware(['auth'])->prefix('distributionandlogistics/carriers')->name('distributionandlogistics.carriers.')->group(function () {
    // Routes accessible to all authenticated users
    Route::get('/create', [CarrierController::class, 'create'])
        ->middleware('can:user|admin|carrier')
        ->name('create');
    
    Route::post('/', [CarrierController::class, 'store'])
        ->middleware('can:user|admin|carrier')
        ->name('store');
    
    Route::get('/{carrier}', [CarrierController::class, 'show'])
        ->name('show');

    // Admin-only routes
    Route::middleware(['can:admin'])->group(function () {
        Route::get('/admin/create', [CarrierController::class, 'adminCreate'])->name('admin.create');
        Route::post('/admin', [CarrierController::class, 'adminStore'])->name('admin.store');
        Route::get('/{carrier}/edit', [CarrierController::class, 'edit'])->name('edit');
        Route::put('/{carrier}', [CarrierController::class, 'update'])->name('update');
        Route::delete('/{carrier}', [CarrierController::class, 'destroy'])->name('destroy');
    });

    // Carrier-only routes
    Route::middleware(['can:carrier'])->group(function () {
        Route::get('/dashboard', [CarrierDashboardController::class, 'index'])->name('dashboard');
    });
});

// Consolidated Delivery Routes
Route::middleware(['auth'])->prefix('distributionandlogistics/deliveries')->name('distributionandlogistics.deliveries.')->group(function () {
    // Common routes
    Route::get('/{delivery}', [DeliveryController::class, 'show'])->name('show');
    
    // Admin-only routes
    Route::middleware(['can:admin'])->group(function () {
        Route::get('/create', [DeliveryController::class, 'create'])->name('create');
        Route::post('/', [DeliveryController::class, 'store'])->name('store');
        Route::get('/{delivery}/edit', [DeliveryController::class, 'edit'])->name('edit');
        Route::put('/{delivery}', [DeliveryController::class, 'update'])->name('update');
        Route::get('/{delivery}/change-status', [DeliveryController::class, 'changeStatusForm'])->name('status.change.form');
        Route::post('/{delivery}/status', [DeliveryController::class, 'updateStatus'])->name('status.update');
        Route::get('/{delivery}/change-address', [DeliveryController::class, 'ChangeAddressForm'])->name('change.address.form');
    });

    // User routes
    Route::middleware(['can:user'])->group(function () {
        Route::get('/{delivery}/edit', [DeliveryController::class, 'edit'])->name('edit');
        Route::put('/{delivery}', [DeliveryController::class, 'update'])->name('update');
        Route::get('/{delivery}/change-address', [DeliveryController::class, 'ChangeAddressForm'])->name('change.address.form');
    });

    // Carrier routes
    Route::middleware(['can:carrier'])->group(function () {
        Route::get('/{delivery}/change-status', [DeliveryController::class, 'changeStatusForm'])->name('status.change.form');
        Route::post('/{delivery}/status', [DeliveryController::class, 'updateStatus'])->name('status.update');
        Route::get('/{delivery}/edit', [DeliveryController::class, 'carrierEdit'])->name('carrier.edit');
        Route::put('/{delivery}', [DeliveryController::class, 'carrierUpdate'])->name('carrier.update');
    });
});

// Consolidated Inbound Routes
Route::middleware(['auth'])->prefix('inbound')->name('inbound.')->group(function () {
    // Common routes
    Route::get('/{shipment}/details', [InboundShipmentController::class, 'show'])->name('show');
    
    // Admin-only routes
    Route::middleware(['can:admin'])->group(function () {
        Route::get('/create', [InboundShipmentController::class, 'create'])->name('create');
        Route::post('/', [InboundShipmentController::class, 'store'])->name('store');
        Route::get('/{shipment}/receive', [InboundShipmentController::class, 'showReceiveForm'])->name('receive.form');
        Route::post('/{shipment}/receive', [InboundShipmentController::class, 'receive'])->name('receive');
        Route::get('/{shipment}/change-status', [InboundShipmentController::class, 'changeStatusForm'])->name('status.change.form');
        Route::post('/{shipment}/status', [InboundShipmentController::class, 'updateStatus'])->name('status.update');
        Route::get('/{shipment}/receipt', [InboundShipmentController::class, 'showReceipt'])->name('receipt.view');
        Route::get('/{shipment}/edit', [InboundShipmentController::class, 'edit'])->name('edit');
        Route::put('/{shipment}', [InboundShipmentController::class, 'update'])->name('update');
    });

    // Carrier routes
    Route::middleware(['can:carrier'])->group(function () {
        Route::get('/{shipment}/change-status', [InboundShipmentController::class, 'changeStatusForm'])->name('status.change.form');
        Route::post('/{shipment}/status', [InboundShipmentController::class, 'updateStatus'])->name('status.update');
        Route::get('/{shipment}/edit', [InboundShipmentController::class, 'carrierEdit'])->name('carrier.edit');
        Route::put('/{shipment}', [InboundShipmentController::class, 'carrierUpdate'])->name('carrier.update');
    });
});


// Auth routes
require __DIR__.'/auth.php';
