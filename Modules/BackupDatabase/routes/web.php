<?php

use Illuminate\Support\Facades\Route;
use Modules\BackupDatabase\Http\Controllers\BackupDatabaseController;

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
    Route::prefix('backupdatabase')->group(function () {
        Route::get('/', [BackupDatabaseController::class, 'index'])->name('backupdatabase');
        Route::get('/show', [BackupDatabaseController::class, 'show'])->name('backupdatabase-show');
        Route::post('/backup', [BackupDatabaseController::class, 'backup'])->name('backupdatabase-backup');
    });
});
