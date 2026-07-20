<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/login',        [LoginController::class, 'index'])->name('login');
Route::post('post-login',   [LoginController::class, 'postLogin'])->name('login.post');
Route::post('logout',       [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('storage-link', function () {
    $targetFolder = base_path() . '/storage/app/public';
    $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage';
    symlink($targetFolder, $linkFolder);
    echo 'Symlink process successfully completed';
});

Route::group(['middleware' => ['isAdmin', 'auth']], function () {
    Route::get('/',                         [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('profile',                   [HomeController::class, 'profile'])->name('profile');
    Route::put('edit-profile',              [HomeController::class, 'updateProfile'])->name('update-profile');
    Route::put('edit-password',             [HomeController::class, 'updatePassword'])->name('update-password');
    Route::post('profile/{id}/upload-foto', [HomeController::class, 'storeFoto'])->name('upload-foto');
    Route::post('change-mode',              [HomeController::class, 'change_mode'])->name('change_mode');
    Route::get('log-activity',              [HomeController::class, 'logActivity'])->name('log-activity');

    // ajax
    Route::get('get-item-variant/{id}',                 [AjaxController::class, 'getVariants'])->name('getVariants');
    Route::get('get-item-variant-stocks/{id}/{whid}',   [AjaxController::class, 'getVariantStocks'])->name('getVariantStocks');
    Route::get('get-item-by-category/{id}',             [AjaxController::class, 'getItembyCategory'])->name('getItembyCategory');

    // PENGADAAN
    require __DIR__ . '/admin/pengadaan/fullfillment.php';

    //GUDANG
    // -----> Stock
    require __DIR__ . '/admin/stock/stockin.php';
    require __DIR__ . '/admin/stock/stockout.php';
    require __DIR__ . '/admin/stock/transfer.php';
    require __DIR__ . '/admin/stock/mutation.php';
    require __DIR__ . '/admin/stock/current.php';
    require __DIR__ . '/admin/stock/initation.php';
    // -----> Item Master
    require __DIR__ . '/admin/item/category.php';
    require __DIR__ . '/admin/item/satuan.php';
    require __DIR__ . '/admin/item/vendor.php';
    require __DIR__ . '/admin/item/item.php';
    require __DIR__ . '/admin/item/item_variant.php';

    // MASTER DATA
    require __DIR__ . '/admin/master/outlet.php';
    require __DIR__ . '/admin/master/bank_account.php';
    require __DIR__ . '/admin/master/entitas.php';
    require __DIR__ . '/admin/master/project.php';
    require __DIR__ . '/admin/user/user.php';
});
