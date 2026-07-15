<?php

use App\Http\Controllers\Admin\AdmProjectController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmProjectController::class)->name('project.')->prefix('project')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/simpan', 'store')->name('simpan');
    Route::get('/{id}/ubah', 'edit')->name('ubah');
    Route::put('/{id}/update', 'update')->name('update');
    Route::delete('/{id}/hapus', 'destroy')->name('hapus');
});
