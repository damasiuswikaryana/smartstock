<?php

use App\Http\Controllers\Admin\AdmStockMutationController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmStockMutationController::class)->name('stockMutation.')->prefix('stock')->group(function () {
    Route::get('/mutation', 'index')->name('index');
    Route::get('/mutation/{id}/detail', 'detail')->name('detail');
});
