<?php

use App\Http\Controllers\Admin\OutStockController;
use Illuminate\Support\Facades\Route;

Route::controller(OutStockController::class)->name('stockout.')->prefix('stock')->group(function () {
    Route::get('/out',                          'index')->name('index');
    Route::post('/out/simpan',                  'store')->name('simpan');
    Route::get('/out/{id}/detail',              'detail')->name('detail');
    Route::get('/out/{id}/ubah',                'edit')->name('ubah');
    Route::put('/out/{id}/update',              'update')->name('update');
    Route::delete('/out/{id}/hapus',            'destroy')->name('hapus');
    Route::post('/out/{id}/upload-document',    'upload')->name('upload');
    Route::delete('/out/{id}/hapus-document',   'destroy_photo')->name('hapusPhoto');
    Route::post('/out/{id}/approve',            'approveOut')->name('approve');
});
