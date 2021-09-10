<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NameListController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [NameListController::class, 'index'])->name('namelist.index');

Route::get('/index2', [NameListController::class, 'index2'])->name('namelist.index2');

Route::post('/import', [NameListController::class, 'importExcel'])->name('namelist.import');

Route::get('/delete-all/', [NameListController::class, 'destroy'])->name('namelist.destroy');
