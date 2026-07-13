<?php

use App\Http\Controllers\Admin\InOutStockController;
use Illuminate\Support\Facades\Route;

Route::controller(InOutStockController::class)->name('stockin.')->prefix('stock')->group(function () {
    Route::get('/in', 'index')->name('index');
    Route::post('/in/simpan', 'store')->name('simpan');
    Route::get('/in/{id}/detail', 'detail')->name('detail');
    Route::get('/in/{id}/ubah', 'edit')->name('ubah');
    Route::put('/in/{id}/update', 'update')->name('update');
    Route::delete('/in/{id}/hapus', 'destroy')->name('hapus');
    Route::post('/in/{id}/upload-document', 'upload')->name('upload');
    Route::delete('/in/{id}/hapus-document', 'destroy_photo')->name('hapusPhoto');
    Route::post('/in/{id}/approve', 'approveIn')->name('approve');
});
