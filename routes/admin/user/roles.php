<?php

use App\Http\Controllers\Admin\AdmRolesController;
use Illuminate\Support\Facades\Route;

Route::controller(AdmRolesController::class)->name('roles.')->prefix('roles')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/{role_name}', 'rolesUpdate')->name('rolesUpdate');
});
