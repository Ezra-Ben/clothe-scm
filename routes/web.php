<?php

use App\Http\Controllers\Admin\AdminSupplierController;
use App\Http\Controllers\Supplier\PerformanceController;
use App\Http\Controllers\Supplier\ContractController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\InventoryController\SupplierManagementController;
use App\Http\Controllers\InventoryController\SupplierController as InventorySupplierController;
use App\Http\Controllers\InventoryController\ProcurementController;
use App\Http\Controllers\InventoryController\InventoryController;
use App\Http\Controllers\InventoryController\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Chat\ChatController;

Route::view('/', 'welcome');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::view('profile', 'profile')->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Supplier-only views
    Route::middleware(['can:view-readonly'])->group(function () {
        Route::get('/supplier/profile', [SupplierController::class, 'profile'])->name('supplier.profile');
        Route::patch('/supplier/update', [SupplierController::class, 'update'])->name('supplier.update');
        Route::get('/supplier/dashboard', [SupplierController::class, 'dashboard'])->name('supplier.dashboard');
        Route::get('/supplier/performance', [PerformanceController::class, 'index'])->name('supplier.performance');
        Route::get('/supplier/contracts', [ContractController::class, 'index'])->name('supplier.contracts.index');
        Route::get('/supplier/contracts/{contractId}', [ContractController::class, 'show'])->name('supplier.contracts.show');
        Route::get('/supplier/procurement/accept/{id}', [SupplierController::class, 'acceptProcurement'])->name('supplier.procurement.accept');
        Route::get('/supplier/procurement/cancel/{id}', [SupplierController::class, 'cancelProcurement'])->name('supplier.procurement.cancel');
        Route::get('/supplier/procurement/form/{id}', [SupplierController::class, 'showProcurementForm'])->name('supplier.procurement.form');
        Route::post('/supplier/procurement/deliver/{id}', [SupplierController::class, 'deliverProcurement'])->name('supplier.procurement.deliver');
    });

    // Admin or supplier_manager views
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

    // Inventory manager/admin views
    Route::middleware(['can:manage-inventory'])->group(function () {
        Route::get('/suppliers', [SupplierManagementController::class, 'index'])->name('suppliers.index');
        Route::post('/suppliers/{id}/activate', [SupplierManagementController::class, 'activate'])->name('suppliers.activate');
        Route::post('/suppliers/{id}/deactivate', [SupplierManagementController::class, 'deactivate'])->name('suppliers.deactivate');
        Route::get('/procurement/requests', [ProcurementController::class, 'index'])->name('procurement.requests.index');
        Route::post('/procurement/approve/{id}', [ProcurementController::class, 'approve'])->name('procurement.approve');
        Route::post('/procurement/reject/{id}', [ProcurementController::class, 'reject'])->name('procurement.reject');
        Route::post('/admin/procurement/confirm-delivery/{id}', [ProcurementController::class, 'confirmDelivery'])->name('admin.procurement.confirmDelivery');
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/products/create', [InventoryController::class, 'createProductForm'])->name('products.create');
        Route::post('/products', [InventoryController::class, 'storeProduct'])->name('products.store');
        Route::get('/admin/inventory/dashboard', [InventoryController::class, 'dashboard'])->name('admin.inventory.dashboard');
        Route::get('/admin/inventory/order-requests', [InventoryController::class, 'orderRequests'])->name('inventory.order.requests');
        Route::get('/admin/inventory/order-requests/{id}', [InventoryController::class, 'showOrderRequest'])->name('inventory.order.requests.show');
        Route::post('/inventory/add', [InventoryController::class, 'addProduct'])->name('inventory.add');
        Route::delete('/inventory/{id}', [InventoryController::class, 'deleteProduct'])->name('inventory.delete');
    });

    // Vendor registration
    Route::get('vendor/register', [VendorController::class, 'showForm'])->name('vendor.form');
    Route::post('vendor/register', [VendorController::class, 'submitForm'])->name('vendor.register');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/conversation/{id}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/start', [ChatController::class, 'startConversation'])->name('chat.start');

    // Universal dashboard redirect
    Route::get('/dashboard', function() {
        $user = auth()->user();
        if ($user->role && $user->role->name === 'supplier') {
            return redirect()->route('supplier.dashboard');
        }
        if ($user->role && $user->role->name === 'inventory_manager') {
            return redirect()->route('admin.inventory.dashboard');
        }
        // Add more roles as needed
        return '/';
    })->name('dashboard');
});

require __DIR__.'/auth.php';