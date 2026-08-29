<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('landing.index');
})->name('landing');

// MRP & Production
Route::get('/MRP', function () {return view('MRP&Production.page.dashboard');})->name('mrp.dashboard');
Route::get('/MRP/demand-planning', function () {return view('MRP&Production.page.demand-planning');})->name('mrp.demand-planning');
Route::get('/MRP/master-production-schedule', function () {return view('MRP&Production.page.master-production-schedule');})->name('mrp.master-production-schedule');
Route::get('/MRP/material-requirements', function () {return view('MRP&Production.page.material-requirements');})->name('mrp.material-requirements');
Route::get('/MRP/products', function () {return view('MRP&Production.page.products');})->name('mrp.products');
Route::get('/MRP/bill-of-materials', function () {return view('MRP&Production.page.bill-of-materials');})->name('mrp.bill-of-materials');
Route::get('/MRP/routing', function () {return view('MRP&Production.page.routing');})->name('mrp.routing');
Route::get('/MRP/work-centers', function () {return view('MRP&Production.page.work-centers');})->name('mrp.work-centers');
Route::get('/MRP/production-orders', function () {return view('MRP&Production.page.production-orders');})->name('mrp.production-orders');
Route::get('/MRP/production-schedule', function () {return view('MRP&Production.page.production-schedule');})->name('mrp.production-schedule');
Route::get('/MRP/material-consumption', function () {return view('MRP&Production.page.material-consumption');})->name('mrp.material-consumption');
Route::get('/MRP/work-orders', function () {return view('MRP&Production.page.work-orders');})->name('mrp.work-orders');
Route::get('/MRP/production-results', function () {return view('MRP&Production.page.production-results');})->name('mrp.production-results');
Route::get('/MRP/material-availability', function () {return view('MRP&Production.page.material-availability');})->name('mrp.material-availability');
Route::get('/MRP/material-issue', function () {return view('MRP&Production.page.material-issue');})->name('mrp.material-issue');
Route::get('/MRP/finished-goods', function () {return view('MRP&Production.page.finished-goods');})->name('mrp.finished-goods');
Route::get('/MRP/stock-movement', function () {return view('MRP&Production.page.stock-movement');})->name('mrp.stock-movement');
Route::get('/MRP/quality-inspection', function () {return view('MRP&Production.page.quality-inspection');})->name('mrp.quality-inspection');
Route::get('/MRP/inspection-results', function () {return view('MRP&Production.page.inspection-results');})->name('mrp.inspection-results');
Route::get('/MRP/non-conformance', function () {return view('MRP&Production.page.non-conformance');})->name('mrp.non-conformance');
Route::get('/MRP/rework', function () {return view('MRP&Production.page.rework');})->name('mrp.rework');
Route::get('/MRP/equipment', function () {return view('MRP&Production.page.equipment');})->name('mrp.equipment');
Route::get('/MRP/maintenance-schedule', function () {return view('MRP&Production.page.maintenance-schedule');})->name('mrp.maintenance-schedule');
Route::get('/MRP/maintenance-history', function () {return view('MRP&Production.page.maintenance-history');})->name('mrp.maintenance-history');
Route::get('/MRP/product-cost', function () {return view('MRP&Production.page.product-cost');})->name('mrp.product-cost');
Route::get('/MRP/material-cost', function () {return view('MRP&Production.page.material-cost');})->name('mrp.material-cost');
Route::get('/MRP/labor-cost', function () {return view('MRP&Production.page.labor-cost');})->name('mrp.labor-cost');
Route::get('/MRP/overhead', function () {return view('MRP&Production.page.overhead');})->name('mrp.overhead');
Route::get('/MRP/production-cost', function () {return view('MRP&Production.page.production-cost');})->name('mrp.production-cost');
Route::get('/MRP/production-report', function () {return view('MRP&Production.page.production-report');})->name('mrp.production-report');
Route::get('/MRP/production-efficiency', function () {return view('MRP&Production.page.production-efficiency');})->name('mrp.production-efficiency');
Route::get('/MRP/waste-scrap', function () {return view('MRP&Production.page.waste-scrap');})->name('mrp.waste-scrap');
Route::get('/MRP/machine-utilization', function () {return view('MRP&Production.page.machine-utilization');})->name('mrp.machine-utilization');
Route::get('/MRP/production-settings', function () {return view('MRP&Production.page.production-settings');})->name('mrp.production-settings');
Route::get('/MRP/production-calendar', function () {return view('MRP&Production.page.production-calendar');})->name('mrp.production-calendar');
Route::get('/MRP/units', function () {return view('MRP&Production.page.units');})->name('mrp.units');
Route::get('/MRP/numbering', function () {return view('MRP&Production.page.numbering');})->name('mrp.numbering');

// HRIS
Route::get('/HRIS', function () {return view('HRIS.page.dashboard');})->name('hris.dashboard');

// FICO
Route::get('/FICO', function () {return view('FICO.page.dashboard');})->name('fico.dashboard');

// CRM
Route::get('/CRM', function () {return view('Sales&CRM.page.dashboard');})->name('crm.dashboard');

// SCM
Route::get('/SCM', function () {return view('SCM.page.dashboard');})->name('scm.dashboard');