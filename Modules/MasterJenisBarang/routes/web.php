<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterJenisBarang\Http\Controllers\MasterJenisBarangController;

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
    Route::prefix('masterjenisbarang')->group(function () {
        Route::group(['middleware' => ['permission:view-master-jenisbarang']], function () {
            Route::get('/', [MasterJenisBarangController::class, 'index'])->name('master-jenisbarang');
            Route::get('/show', [MasterJenisBarangController::class, 'show'])->name('master-jenisbarang-show');
        });

        Route::group(['middleware' => ['permission:create-master-jenisbarang']], function () {
            Route::post('/store', [MasterJenisBarangController::class, 'store'])->name('master-jenisbarang-store');
        });

        Route::group(['middleware' => ['permission:edit-master-jenisbarang']], function () {
            Route::post('/update', [MasterJenisBarangController::class, 'update'])->name('master-jenisbarang-update');
        });

        Route::group(['middleware' => ['permission:delete-master-jenisbarang']], function () {
            Route::delete('/destroy/{id}', [MasterJenisBarangController::class, 'destroy'])->name('master-jenisbarang-destroy');
        });
    });
});