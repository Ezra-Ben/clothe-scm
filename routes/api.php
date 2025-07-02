<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductionApiController;
use App\Http\Controllers\Api\ProductionOrderApiController;
use App\Http\Controllers\Api\BomApiController;
use App\Http\Controllers\ProductionController;

Route::get('/production-batches', [ProductionApiController::class, 'index']);
Route::patch('/production-batches/{id}/complete', [ProductionApiController::class, 'complete']);
Route::post('/api/start-production', [ProductionController::class,'startProduction']);

Route::apiResource('api/production-orders', ProductionOrderApiController::class)->only(['index', 'show']);

Route::get('api/boms/{bom}', [BomApiController::class, 'show']);
