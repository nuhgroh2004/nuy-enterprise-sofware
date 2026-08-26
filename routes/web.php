<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/MRP', function () {return view('MRP&Production.page.dashboard');})->name('dashboard');
Route::get('/HRIS', function () {return view('HRIS.page.dashboard');})->name('dashboard');
Route::get('/FICO', function () {return view('FICO.page.dashboard');})->name('dashboard');
Route::get('/CRM', function () {return view('Sales&CRM.page.dashboard');})->name('dashboard');
Route::get('/SCM', function () {return view('SCM.page.dashboard');})->name('dashboard');