<?php


use App\Http\Controllers\Admin\AdminSupplierDashboardController;
use App\Http\Controllers\Supplier\PerformanceController;
use App\Http\Controllers\Supplier\ContractController;
use App\Http\Controllers\Vendor\VendorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\InventoryController\SupplierController as InventorySupplierController;
use App\Http\Controllers\InventoryController\ProcurementController;
use App\Http\Controllers\InventoryController\InventoryController;
use App\Http\Controllers\InventoryController\NotificationController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::prefix('settings')->name('settings.')->middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        return view('livewire.settings.profile');
    })->name('profile');

    Route::get('/password', function () {
        return view('livewire.settings.password');
    })->name('password');

    Route::get('/appearance', function () {
        return view('livewire.settings.appearance');
    })->name('appearance');
});
Route::prefix('supplier/{supplier}')->group(function () {
    Route::get('/performance', [PerformanceController::class, 'index'])
         ->name('supplier.performance');
    
    Route::post('/performance', [PerformanceController::class, 'store']);
});
Route::get('/supplier/performance', function () {
    return view('supplier.performance.index');
});


Route::get('vendor/register', [VendorController::class, 'showForm'])->name('vendor.form');
Route::post('vendor/register', [VendorController::class, 'submitForm'])->name('vendor.register');


Route::middleware(['auth'])->group(function () {
    Route::get('/supplier/{id}/profile', [SupplierController::class, 'profile'])->name('supplier.profile');
    Route::get('/supplier/{id}/dashboard', [SupplierController::class, 'dashboard'])->name('supplier.dashboard');
});
Route::put('/supplier/{id}/update', [SupplierController::class, 'update'])->name('supplier.update');


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/suppliers/dashboard', [AdminSupplierDashboardController::class, 'index'])
        ->name('admin.supplier.dashboard');
});


require __DIR__.'/auth.php';


Route::middleware('auth')->group(function () {
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers/{id}/activate', [SupplierController::class, 'activate'])->name('suppliers.activate');
    Route::post('/suppliers/{id}/deactivate', [SupplierController::class, 'deactivate'])->name('suppliers.deactivate');
});

// Procurement Requests (Admin Approval)
Route::middleware('auth')->group(function () {
    Route::get('/procurement/requests', [ProcurementController::class, 'index'])->name('procurement.requests.index');
    Route::post('/procurement/approve/{id}', [ProcurementController::class, 'approve'])->name('procurement.approve');
    Route::post('/procurement/reject/{id}', [ProcurementController::class, 'reject'])->name('procurement.reject');
});

// Inventory Management
Route::middleware('auth')->group(function () {
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
});

Route::get('/notifications', [NotificationController::class, 'index'])->middleware('auth');
Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->middleware('auth');
Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->middleware('auth');
Route::get('/dashboard', [InventoryController::class, 'dashboard'])->middleware('auth');

Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::get('/procurement/requests', [ProcurementController::class, 'index'])->name('procurement.requests.index');
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');