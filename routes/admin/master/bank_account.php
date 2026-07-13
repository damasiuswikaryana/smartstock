<?php

use App\Http\Controllers\Admin\AdmBankAccountController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmBankAccountController::class)->name('bank-account.')->prefix('bank-account')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/simpan', 'store')->name('simpan');
    Route::get('/{id}/ubah', 'edit')->name('ubah');
    Route::put('/{id}/update', 'update')->name('update');
    Route::delete('/{id}/hapus', 'destroy')->name('hapus');
});
