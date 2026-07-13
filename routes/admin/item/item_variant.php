<?php

use App\Http\Controllers\Admin\AdmItemController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmItemController::class)->name('item_variant.')->prefix('item_variant')->group(function () {
    Route::get('/{id}',             'varian_index')->name('index');
    Route::post('/{id}/simpan',     'varian_store')->name('simpan');
    Route::get('/{id}/ubah',        'varian_edit')->name('ubah');
    Route::put('/{id}/update',      'varian_update')->name('update');
    Route::delete('/{id}/hapus',    'varian_destroy')->name('hapus');
    Route::post('/{id}/upload-foto', 'storeFotoVariant')->name('storeFoto');
});
