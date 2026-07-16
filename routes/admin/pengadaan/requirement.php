<?php

use App\Http\Controllers\Admin\AdmProjectRequirementController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmProjectRequirementController::class)->name('prreq.')->prefix('prreq')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/tambah', 'create')->name('tambah');
    Route::post('/simpan', 'store')->name('simpan');
    Route::get('/{id}', 'show')->name('detail');
    Route::get('/{id}/ubah', 'edit')->name('ubah');
    Route::put('/{id}/update', 'update')->name('update');
    Route::delete('/{id}/hapus', 'destroy')->name('hapus');
});
