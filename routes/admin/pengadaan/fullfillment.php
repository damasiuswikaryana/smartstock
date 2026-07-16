<?php

use App\Http\Controllers\Admin\AdmFullfillmentController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmFullfillmentController::class)->name('fullfillment.')->prefix('fullfillment')->group(function () {
    Route::get('/',                 'index')->name('index');
    Route::get('/{id}/add',         'add')->name('add');
    Route::post('/{id}/store-item', 'storeItem')->name('storeItem');
    Route::get('/{id}/detail',      'detail')->name('detail');
});
