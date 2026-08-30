<?php

use App\Http\Controllers\BomApiController;
use App\Http\Controllers\BomComponentController;
use App\Http\Controllers\BomVersionController;
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

/*
|--------------------------------------------------------------------------
| BOM API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('boms')->group(function () {

    // BOM CRUD
    Route::get('/', [BomApiController::class, 'index']);
    Route::post('/', [BomApiController::class, 'store']);
    Route::get('/{bom}', [BomApiController::class, 'show']);
    Route::put('/{bom}', [BomApiController::class, 'update']);
    Route::post('/{bom}/archive', [BomApiController::class, 'archive']);
    Route::post('/{bom}/restore', [BomApiController::class, 'restore']);
    Route::post('/{bom}/duplicate', [BomApiController::class, 'duplicate']);
    Route::get('/{bom}/where-used', [BomApiController::class, 'whereUsed']);
    Route::get('/{bom}/compare', [BomVersionController::class, 'compare']);
    Route::get('/{bom}/history', [BomVersionController::class, 'history']);

    // Version Management
    Route::post('/{bom}/versions', [BomVersionController::class, 'store']);
});

Route::prefix('bom-versions')->group(function () {
    Route::put('/{version}', [BomVersionController::class, 'update']);
    Route::post('/{version}/submit', [BomVersionController::class, 'submit']);
    Route::post('/{version}/approve', [BomVersionController::class, 'approve']);
    Route::post('/{version}/expire', [BomVersionController::class, 'expire']);
    Route::post('/{version}/primary', [BomVersionController::class, 'setPrimary']);
});

Route::prefix('bom-versions')->group(function () {
    Route::post('/{version}/components', [BomComponentController::class, 'store']);
});

Route::prefix('bom-components')->group(function () {
    Route::put('/{component}', [BomComponentController::class, 'update']);
    Route::post('/{component}/destroy', [BomComponentController::class, 'destroy']);
    Route::post('/{component}/substitutes', [BomComponentController::class, 'addSubstitute']);
});

Route::prefix('bom-substitutes')->group(function () {
    Route::put('/{substitute}', [BomComponentController::class, 'updateSubstitute']);
    Route::post('/{substitute}/destroy', [BomComponentController::class, 'removeSubstitute']);
});

Route::get('/products/search', [BomApiController::class, 'productSearch']);
Route::get('/uom-list', [BomApiController::class, 'uomList']);
Route::get('/companies', [BomApiController::class, 'companies']);
Route::get('/plants', [BomApiController::class, 'plants']);
Route::get('/production-processes', [BomApiController::class, 'productionProcesses']);
Route::get('/routing-versions', [BomApiController::class, 'routingVersions']);
