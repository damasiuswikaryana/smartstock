<?php

use App\Http\Controllers\Admin\AdmEntitasController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmEntitasController::class)->name('entitas.')->prefix('entitas')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/simpan', 'store')->name('simpan');
    Route::get('/{id}/ubah', 'edit')->name('ubah');
    Route::put('/{id}/update', 'update')->name('update');
    Route::delete('/{id}/hapus', 'destroy')->name('hapus');
});
