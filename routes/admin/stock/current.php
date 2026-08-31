<?php

use App\Http\Controllers\Admin\AdmStockCurrentController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmStockCurrentController::class)->name('stockCurrent.')->prefix('stock')->group(function () {
    Route::get('/current', 'index')->name('index');
    Route::get('/current/{id}/ubah', 'edit')->name('ubah');
    Route::put('/current/{id}/update', 'update')->name('update');
    Route::get('/current/download/{whid}/{cat}/{entitas}', 'downloadReport')->name('downloadReport');
    Route::delete('/current/{id}/hapus', 'destroy')->name('hapus');
});
