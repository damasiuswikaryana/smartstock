<?php

use App\Http\Controllers\Admin\TransferStockController;
use Illuminate\Support\Facades\Route;

Route::controller(TransferStockController::class)->name('stocktransfer.')->prefix('stock')->group(function () {
    Route::get('/transfer',                          'index')->name('index');
    Route::post('/transfer/simpan',                  'store')->name('simpan');
    Route::get('/transfer/{id}/detail',              'detail')->name('detail');
    Route::get('/transfer/{id}/ubah',                'edit')->name('ubah');
    Route::put('/transfer/{id}/update',              'update')->name('update');
    Route::delete('/transfer/{id}/hapus',            'destroy')->name('hapus');
    Route::post('/transfer/{id}/upload-document',    'upload')->name('upload');
    Route::delete('/transfer/{id}/hapus-document',   'destroy_photo')->name('hapusPhoto');
    Route::post('/transfer/{id}/approve',            'approveOut')->name('approve');
});
