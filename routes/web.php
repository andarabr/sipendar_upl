<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NameListController;
use App\Http\Controllers\MasterCustomerController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [NameListController::class, 'index2'])->name('namelist.index');

Route::get('/index2', [NameListController::class, 'index2'])->name('namelist.index2');

Route::post('/import', [NameListController::class, 'importExcel'])->name('namelist.import');

Route::delete('/delete-all', [NameListController::class, 'destroy'])->name('namelist.destroy');

Route::get('/xml', [NameListController::class, 'xmlTest'])->name('namelist.xml');

Route::get('/xml/{namelist}', [NameListController::class, 'xmlShow'])->name('namelist.xmlshow');

Route::get('/customers', [MasterCustomerController::class, 'index'])->name('customers.index');

Route::get('/individu-lookup', [MasterCustomerController::class, 'lookupDataIndividu'])->name('individu.lookup');

Route::get('/korporasi-lookup', [MasterCustomerController::class, 'lookupDataKorporasi'])->name('korporasi.lookup');

Route::get('/xml-individu/{customer}', [MasterCustomerController::class, 'xmlDownIndividu'])->name('individu.xmldown');

Route::get('/xml-korporasi/{customer}', [MasterCustomerController::class, 'xmlDownKorporasi'])->name('korporasi.xmldown');

Route::get('/xml-all/{cust}', [MasterCustomerController::class, 'xmlAll'])->name('customer.xmlall');
