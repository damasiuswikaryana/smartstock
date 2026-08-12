<?php

use App\Http\Controllers\Admin\AdmQrController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmQrController::class)->name('qr.')->prefix('qr')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/simpan', 'store')->name('simpan');
    Route::get('/{id}/detail', 'detail')->name('detail');
    Route::get('/{id}/ubah', 'edit')->name('ubah');
    Route::put('/{id}/update', 'update')->name('update');
    Route::delete('/{id}/hapus', 'destroy')->name('hapus');
    Route::get('/{id}/download', 'downloadQr')->name('downloadQr');

    Route::post('/{id}/approve', 'approved')->name('approved');
    Route::post('/{id}/checked', 'checked')->name('checked');
    Route::post('/{id}/recorded', 'recorded')->name('recorded');
});
