<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterCusSup\Http\Controllers\MasterCusSupController;

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
    Route::prefix('mastercussup')->group(function () {
        Route::group(['middleware' => ['permission:view-cussup']], function () {
            Route::get('/', [MasterCusSupController::class, 'index'])->name('master-cussup');
            Route::get('/show', [MasterCusSupController::class, 'show'])->name('master-cussup-show');
        });

        Route::get('/listKelurahan', [MasterCusSupController::class, 'listKelurahan'])->name('master-cussup-list-kelurahan');

        Route::group(['middleware' => ['permission:create-cussup']], function () {
            Route::post('/store', [MasterCusSupController::class, 'store'])->name('master-cussup-store');
        });

        Route::group(['middleware' => ['permission:edit-cussup']], function () {
            Route::get('/edit/{id}', [MasterCusSupController::class, 'edit'])->name('master-cussup-edit');
            Route::post('/update', [MasterCusSupController::class, 'update'])->name('master-cussup-update');
        });

        Route::group(['middleware' => ['permission:delete-cussup']], function () {
            Route::delete('/destroy/{id}', [MasterCusSupController::class, 'destroy'])->name('master-cussup-destroy');
        });
    });
});
