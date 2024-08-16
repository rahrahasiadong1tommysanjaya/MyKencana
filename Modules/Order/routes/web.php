<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

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
    Route::prefix('order')->group(function () {
        Route::group(['middleware' => ['permission:view-order']], function () {
            Route::get('/', [OrderController::class, 'index'])->name('order');
            Route::get('/show', [OrderController::class, 'show'])->name('order-show');
            Route::get('/listCustomers', [OrderController::class, 'listCustomers'])->name('order-list-customers');
        });

        Route::group(['middleware' => ['permission:create-order']], function () {
            Route::post('/store', [OrderController::class, 'store'])->name('order-store');
            Route::post('/storeDetail', [OrderController::class, 'storeDetail'])->name('order-detail-store');
        });

        Route::group(['middleware' => ['permission:edit-order']], function () {
            Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('order-edit');
            Route::post('/update', [OrderController::class, 'update'])->name('order-update');
            Route::get('/showDetail', [OrderController::class, 'showDetail'])->name('order-detail-show');
            Route::get('/listInv', [OrderController::class, 'listInv'])->name('order-list-inv');
            Route::post('/updateDetail', [OrderController::class, 'updateDetail'])->name('order-detail-update');
        });

        Route::group(['middleware' => ['permission:delete-order']], function () {
            Route::delete('/destroyDetail/{id}', [OrderController::class, 'destroyDetail'])->name('order-detail-destroy');
            Route::delete('/destroy/{id}', [OrderController::class, 'destroy'])->name('order-destroy');
        });
    });
});
