<?php

use App\Http\Controllers\Admin\AdmPtwController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmPtwController::class)->name('ptw.')->prefix('ptw')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/simpan', 'store')->name('simpan');
    Route::get('/{id}/detail', 'detail')->name('detail');
    Route::get('/{id}/ubah', 'edit')->name('ubah');
    Route::put('/{id}/update', 'update')->name('update');
    Route::post('/update-item', 'updateItem')->name('updateItem');
    Route::post('/add-po/{id}', 'addPo')->name('addPo');
    Route::delete('/{id}/hapus', 'destroy')->name('hapus');
    Route::delete('/{id}/hapus-po', 'destroyPo')->name('hapusPo');

    Route::get('/{id}/download', 'downloadPtw')->name('downloadPtw');
});
