<?php

use App\Http\Controllers\Admin\InitationStockController;
use Illuminate\Support\Facades\Route;

Route::controller(InitationStockController::class)->name('stockinit.')->prefix('stock')->group(function () {
    Route::get('/init', 'index')->name('index');
    Route::post('/init/simpan', 'store')->name('simpan');
});
