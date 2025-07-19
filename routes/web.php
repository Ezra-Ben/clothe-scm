<?php
use App\Http\Controllers\Admin\AdminSupplierController;
use App\Http\Controllers\Supplier\PerformanceController;
use App\Http\Controllers\Supplier\ContractController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BomController;
use App\Http\Controllers\ProductionCoordinatorController;
use App\Http\Controllers\Reports\ProductionReportController;
use App\Http\Controllers\Resource\ResourceController;
use App\Http\Controllers\Resource\CapacityController; // For capacity planning


use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');

    Route::view('dashboard', 'dashboard')->middleware('verified')->name('dashboard');
    
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
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
        
Route::middleware(['auth'])->prefix('production')->name('production.')->group(function () {
    Route::get('/dashboard', [ProductionCoordinatorController::class, 'dashboard'])->name('dashboard');

    Route::get('/orders', [ProductionCoordinatorController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [ProductionCoordinatorController::class, 'showOrder'])->name('orders.show');

    Route::get('/batches', [ProductionCoordinatorController::class, 'batches'])->name('batches');
    Route::get('/batches/{id}', [ProductionCoordinatorController::class, 'showBatch'])->name('batches.show');

    Route::get('/schedules', [ProductionCoordinatorController::class, 'schedules'])->name('schedules');
    Route::post('/schedules', [ProductionCoordinatorController::class, 'storeSchedule'])->name('schedules.store');
    Route::put('/schedules/{id}', [ProductionCoordinatorController::class, 'updateSchedule'])->name('schedules.update');
    Route::delete('/schedules/{id}', [ProductionCoordinatorController::class, 'destroySchedule'])->name('schedules.destroy');
});
Route::resource('boms', BomController::class); 
Route::get('/', function () {
    return redirect()->route('production.dashboard');
})->middleware('auth');
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ProductionReportController::class, 'index'])->name('index');
    Route::get('/summary', [ProductionReportController::class, 'productionSummary'])->name('production_summary');
    Route::get('/resource-utilization', [ProductionReportController::class, 'resourceUtilization'])->name('resource_utilization');

});
Route::resource('resources', ResourceController::class); // Standard CRUD for resources
Route::get('capacity-planning', [CapacityController::class, 'index'])->name('capacity_planning.index');
Route::post('capacity-planning/assign', [CapacityController::class, 'assignResource'])->name('capacity_planning.assign');

Route::post('/production/batches/{batch}/activities', [ProductionCoordinatorController::class, 'storeActivity'])->name('production.batches.activities.store');
Route::put('/production/batches/{batch}/status', [ProductionCoordinatorController::class, 'updateBatchStatus'])->name('production.batches.status.update');
require __DIR__.'/auth.php';




