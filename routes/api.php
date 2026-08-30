<?php

use App\Http\Controllers\PlanningController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Planning API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('planning')->group(function () {

    // Dashboard Stats
    Route::get('/dashboard-stats', [PlanningController::class, 'planningDashboardStats']);

    // Demand Planning
    Route::get('/demands', [PlanningController::class, 'demandApi']);
    Route::post('/demands', [PlanningController::class, 'demandStore']);
    Route::get('/demands/{demand}', [PlanningController::class, 'demandShow']);
    Route::put('/demands/{demand}', [PlanningController::class, 'demandUpdate']);
    Route::delete('/demands/{demand}', [PlanningController::class, 'demandDestroy']);
    Route::post('/demands/{demand}/submit', [PlanningController::class, 'demandSubmit']);
    Route::post('/demands/{demand}/cancel', [PlanningController::class, 'demandCancel']);

    // Master Production Schedule
    Route::get('/mps', [PlanningController::class, 'mpsApi']);
    Route::post('/mps', [PlanningController::class, 'mpsStore']);
    Route::get('/mps/{schedule}', [PlanningController::class, 'mpsShow']);
    Route::put('/mps/{schedule}', [PlanningController::class, 'mpsUpdate']);
    Route::delete('/mps/{schedule}', [PlanningController::class, 'mpsDestroy']);
    Route::post('/mps/{schedule}/submit', [PlanningController::class, 'mpsSubmit']);

    // Material Requirements
    Route::get('/mrp', [PlanningController::class, 'mrpApi']);
    Route::post('/mrp/calculate', [PlanningController::class, 'mrpCalculate']);

    // Planned Orders
    Route::get('/planned-orders', [PlanningController::class, 'plannedOrderApi']);
    Route::post('/planned-orders/{order}/firm', [PlanningController::class, 'plannedOrderFirm']);
});

/*
|--------------------------------------------------------------------------
| Products API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'api']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/{product}', [ProductController::class, 'apiShow']);
    Route::put('/{product}', [ProductController::class, 'update']);
    Route::post('/{product}/archive', [ProductController::class, 'archive']);
    Route::post('/{product}/restore', [ProductController::class, 'restore']);
    Route::post('/{product}/duplicate', [ProductController::class, 'duplicate']);
    Route::get('/{product}/variants', [ProductController::class, 'variants']);
    Route::post('/{product}/variants', [ProductController::class, 'storeVariant']);
    Route::put('/variants/{variant}', [ProductController::class, 'updateVariant']);
    Route::delete('/variants/{variant}', [ProductController::class, 'destroyVariant']);
    Route::get('/{product}/usage', [ProductController::class, 'usageSummary']);
});

Route::get('/product-types', [ProductController::class, 'productTypes']);
Route::get('/product-categories', [ProductController::class, 'productCategories']);
Route::get('/uoms', [ProductController::class, 'uoms']);
