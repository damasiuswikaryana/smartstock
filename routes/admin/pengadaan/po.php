<?php

use App\Http\Controllers\Admin\AdmPoController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmPoController::class)->name('po.')->prefix('po')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/simpan', 'store')->name('simpan');
    Route::get('/{id}/detail', 'detail')->name('detail');
    Route::get('/{id}/ubah', 'edit')->name('ubah');
    Route::put('/{id}/update', 'update')->name('update');
    Route::delete('/{id}/hapus', 'destroy')->name('hapus');
    Route::get('/{id}/download', 'downloadPo')->name('downloadPo');

    Route::post('/{id}/approve', 'approved')->name('approved');
    Route::post('/{id}/checked', 'checked')->name('checked');
});
