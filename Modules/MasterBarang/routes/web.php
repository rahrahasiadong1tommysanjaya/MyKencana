<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterBarang\Http\Controllers\MasterBarangController;

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
    Route::prefix('masterbarang')->group(function () {
        Route::group(['middleware' => ['permission:view-barang']], function () {
            Route::get('/', [MasterBarangController::class, 'index'])->name('master-barang');
            Route::get('/show', [MasterBarangController::class, 'show'])->name('master-barang-show');
        });

        Route::group(['middleware' => ['permission:create-barang']], function () {
            Route::post('/store', [MasterBarangController::class, 'store'])->name('master-barang-store');
            Route::get('/listSatuan', [MasterBarangController::class, 'listSatuan'])->name('master-barang-list-satuan');
            Route::get('/listJenisBarang', [MasterBarangController::class, 'listJenisBarang'])->name('master-barang-list-jenis-barang');
        });

        Route::group(['middleware' => ['permission:edit-barang']], function () {
            Route::get('/edit/{id}', [MasterBarangController::class, 'edit'])->name('master-barang-edit');
            Route::post('/update', [MasterBarangController::class, 'update'])->name('master-barang-update');
        });

        Route::group(['middleware' => ['permission:delete-barang']], function () {
            Route::delete('/destroy/{id}', [MasterBarangController::class, 'destroy'])->name('master-barang-destroy');
        });
    });
});
