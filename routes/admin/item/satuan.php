<?php
use App\Http\Controllers\Admin\AdmSatuanController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmSatuanController::class)->name('satuan.')->prefix('satuan')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/simpan', 'store')->name('simpan');
    Route::get('/{id}/ubah', 'edit')->name('ubah');
    Route::put('/{id}/update', 'update')->name('update');
    Route::delete('/{id}/hapus', 'destroy')->name('hapus');
});
