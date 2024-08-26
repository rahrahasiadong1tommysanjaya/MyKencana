<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterGg\Http\Controllers\MasterGgController;

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
  Route::prefix('mastergg')->group(function () {
      Route::group(['middleware' => ['permission:view-master-gg']], function () {
          Route::get('/', [MasterGgController::class, 'index'])->name('master-gg');
          Route::get('/show', [MasterGgController::class, 'show'])->name('master-gg-show');
          Route::get('/showDetail', [MasterGgController::class, 'showDetail'])->name('master-gg-detail-show');
      });

      Route::group(['middleware' => ['permission:create-master-gg']], function () {
          Route::post('/store', [MasterGgController::class, 'store'])->name('master-gg-store');
          Route::post('/update', [MasterGgController::class, 'update'])->name('master-gg-update');
          Route::post('/storeDetail', [MasterGgController::class, 'storeDetail'])->name('master-gg-detail-store');
          Route::post('/updateDetail', [MasterGgController::class, 'updateDetail'])->name('master-gg-detail-update');
      });

      Route::group(['middleware' => ['permission:delete-master-gg']], function () {
          Route::delete('/destroy/{id}', [MasterGgController::class, 'destroy'])->name('master-gg-destroy');
          Route::delete('/destroyDetail/{id}', [MasterGgController::class, 'destroyDetail'])->name('master-gg-detail-destroy');
      });
  });
});
