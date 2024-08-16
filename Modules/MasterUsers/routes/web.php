<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterUsers\Http\Controllers\MasterUsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth']], function () {
    Route::prefix('masterusers')->group(function () {
        Route::group(['middleware' => ['permission:view-user']], function () {
            Route::get('/', [MasterUsersController::class, 'index'])->name('master-users');
            Route::get('/show', [MasterUsersController::class, 'show'])->name('master-users-show');
        });

        Route::group(['middleware' => ['permission:create-user']], function () {
            Route::post('/store', [MasterUsersController::class, 'store'])->name('master-user-store');
        });

        Route::group(['middleware' => ['permission:edit-user']], function () {
            Route::get('/edit/{id}', [MasterUsersController::class, 'edit'])->name('user-edit');
            Route::post('/update', [MasterUsersController::class, 'update'])->name('user-update');
        });

        Route::group(['middleware' => ['permission:delete-user']], function () {
            Route::delete('/destroy/{id}', [MasterUsersController::class, 'destroy'])->name('user-destroy');
        });
        
        Route::group(['middleware' => ['permission:view-menu-user']], function () {
            Route::get('/menu/{id}', [MasterUsersController::class, 'menu'])->name('master-users-menu');
        });

        Route::get('/menu/getListPermission/{menuId}',  [MasterUsersController::class, 'getListPermission'])->name('user-menu-listpermission');
        Route::group(['middleware' => ['permission:edit-permission-user']], function () {
            Route::get('/menu/getListPermissionUser/{menuId}/{userId}',  [MasterUsersController::class, 'getListPermissionUser'])->name('user-menu-listpermissionUser');
            Route::post('/menu/updateMenuPermission/{userId}',  [MasterUsersController::class, 'updateMenuPermission'])->name('user-menu-permission-update');
        });

        Route::group(['middleware' => ['permission:sort-menu-user']], function () {
            Route::post('/menu/updateSortMenu/{id}',  [MasterUsersController::class, 'updateSortMenu'])->name('user-menu-sort');
        });

        Route::get('/menu/getListMenu/{id}',  [MasterUsersController::class, 'getListMenu'])->name('user-menu');
        Route::get('/menu/getListmenuUser/{id}',  [MasterUsersController::class, 'getListmenuUser'])->name('user-menu-listmenu');
        Route::group(['middleware' => ['permission:create-menu-user']], function () {
            Route::post('/menu/storeMenuPermission', [MasterUsersController::class, 'storeMenuPermission'])->name('user-menu-store');
        });

        Route::group(['middleware' => ['permission:delete-menu-user']], function () {
            Route::delete('/menu/destrouMenuByUser/{menuId}/{userId}', [MasterUsersController::class, 'destrouMenuByUser'])->name('user-menu-destroy');
        });

    });
});
