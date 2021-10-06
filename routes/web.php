<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NameListController;
use App\Http\Controllers\MasterCustomerController;
use App\Http\Controllers\ProaktifController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [NameListController::class, 'index2'])->name('namelist.index');

Route::get('/index2', [NameListController::class, 'index2'])->name('namelist.index2');

Route::post('/import', [NameListController::class, 'importExcel'])->name('namelist.import');

Route::delete('/delete-all-namelist', [NameListController::class, 'destroy'])->name('namelist.destroy');

Route::get('/xml', [NameListController::class, 'xmlTest'])->name('namelist.xml');

Route::get('/xml/{namelist}', [NameListController::class, 'xmlShow'])->name('namelist.xmlshow');

Route::post('/import-master-data', [MasterCustomerController::class, 'importExcel'])->name('customer.import');

Route::get('/customers', [MasterCustomerController::class, 'index2'])->name('customers.index');

Route::get('/individu-lookup', [MasterCustomerController::class, 'lookupDataIndividu'])->name('individu.lookup');

Route::get('/individu-lookup-filter/', [MasterCustomerController::class, 'lookupDataIndividuFilter'])->name('individu.lookup.filter');

Route::get('/korporasi-lookup', [MasterCustomerController::class, 'lookupDataKorporasi'])->name('korporasi.lookup');

Route::get('/korporasi-lookup-filter/', [MasterCustomerController::class, 'lookupDataKorporasiFilter'])->name('korporasi.lookup.filter');
// Route::get('/xml-individu/{customer}', [MasterCustomerController::class, 'xmlDownRekursif'])->name('individu.xmldown');

Route::post('/xml-individu/{customer}', [MasterCustomerController::class, 'xmlDownIndividu'])->name('individu.xml');

// Route::get('/xml-korporasi/{customer}', [MasterCustomerController::class, 'xmlDownKorporasi'])->name('korporasi.xmldown');

Route::post('/xml-korporasi/{customer}', [MasterCustomerController::class, 'xmlDownKorporasi'])->name('korporasi.xml');

//Route::get('/xml-all/{cust}', [MasterCustomerController::class, 'xmlAll'])->name('customer.xmlall');

Route::post('/xml-all/{cust}', [MasterCustomerController::class, 'xmlAll'])->name('customer.xmlall');

Route::delete('/delete-all-master', [MasterCustomerController::class, 'destroy'])->name('customer.destroy');

Route::get('/data-not-found', [MasterCustomerController::class, 'dataNotFound'])->name('data.not.found');

Route::get('/ajax-test/', [ProaktifController::class, 'index'])->name('ajax.test');

Route::get('/cbs-upload-format/', [MasterCustomerController::class, 'downloadFormat'])->name('cbs.download.format');

Route::get('/ppatk-upload-format/', [NameListController::class, 'downloadFormat'])->name('ppatk.download.format');


