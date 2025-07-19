<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductionApiController;
use App\Http\Controllers\Api\ProductionOrderApiController;
use App\Http\Controllers\Api\BomApiController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProductionCoordinatorController;

Route::get('/production-batches', [ProductionApiController::class, 'index']);
Route::patch('/production-batches/{id}/complete', [ProductionApiController::class, 'complete']);
Route::post('/api/start-production', [ProductionController::class,'startProduction']);

Route::apiResource('api/production-orders', ProductionOrderApiController::class)->only(['index', 'show']);

Route::get('api/boms/{bom}', [BomApiController::class, 'show']);
Route::middleware('auth:sanctum')->prefix('production')->group(function () {
    Route::get('/dashboard-counts', [ProductionApiController::class, 'getDashboardCounts']);
    Route::get('/recent-activities', [ProductionApiController::class, 'getRecentActivities']);

    Route::put('/batches/{id}/status', [ProductionApiController::class, 'updateBatchStatus']);
    Route::post('/batches/{id}/activities', [ProductionApiController::class, 'addBatchActivity']);
    Route::post('batches/{batch}/activities', [ProductionCoordinatorController::class, 'storeActivity']);

    Route::put('batches/{batch}/status', [ProductionCoordinatorController::class, 'updateBatchStatus']);
    Route::patch('batches/{batch}/status', [ProductionCoordinatorController::class, 'updateBatchStatus']);
});
 