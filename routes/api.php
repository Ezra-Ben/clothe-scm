<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController\InventoryController;
use App\Http\Controllers\InventoryController\ProcurementController;
use App\Http\Controllers\InventoryController\SupplierController;
use App\Http\Controllers\InventoryController\NotificationController;


// Inventory APIs
Route::post('/inventory/handle-order', [InventoryController::class, 'handleOrderRequest']);
Route::get('/inventory/product-exists/{id}', [InventoryController::class, 'checkProductExists']);
Route::get('/inventory/product-quantity/{id}', [InventoryController::class, 'checkProductQuantity']);
Route::post('/inventory/reserve/{id}', [InventoryController::class, 'reserveProduct']);
Route::post('/inventory/increase/{id}', [InventoryController::class, 'increaseStock']);

// Procurement APIs
Route::post('/procurement/request', [ProcurementController::class, 'createRequest']);
Route::post('/procurement/deliver/{id}', [ProcurementController::class, 'logDelivery']);
Route::post('/procurement/approve/{id}', [ProcurementController::class, 'approve']);
Route::post('/procurement/reject/{id}', [ProcurementController::class, 'reject']);
Route::get('/procurement/requests', [ProcurementController::class, 'index']);

// Supplier APIs
Route::get('/suppliers', [SupplierController::class, 'index']);
Route::post('/suppliers/{id}/activate', [SupplierController::class, 'activate']);
Route::post('/suppliers/{id}/deactivate', [SupplierController::class, 'deactivate']);

// Notification APIs
Route::get('/notifications', [NotificationController::class, 'index']);

// Test endpoint (for debugging)
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});