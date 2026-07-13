<?php

use App\Http\Controllers\Admin\AdmVendorController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmVendorController::class)->name('vendor.')->prefix('vendor-item')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/simpan', 'store')->name('simpan');
    Route::get('/{id}/ubah', 'edit')->name('ubah');
    Route::put('/{id}/update', 'update')->name('update');
    Route::delete('/{id}/hapus', 'destroy')->name('hapus');
});
