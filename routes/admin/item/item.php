<?php

use App\Http\Controllers\Admin\AdmItemController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmItemController::class)->name('item.')->prefix('item')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/simpan', 'store')->name('simpan');
    Route::get('/{id}/ubah', 'edit')->name('ubah');
    Route::put('/{id}/update', 'update')->name('update');
    Route::delete('/{id}/hapus', 'destroy')->name('hapus');
    Route::post('/{id}/upload-foto', 'storeFoto')->name('storeFoto');
});
