<?php

use App\Http\Controllers\Admin\AdmStockCurrentController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmStockCurrentController::class)->name('stockCurrent.')->prefix('stock')->group(function () {
    Route::get('/current', 'index')->name('index');
});
