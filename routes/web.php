<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authentications\Auth;
use App\Http\Controllers\RestoreDatabase;


// Main Page Route
Route::get('/', [Auth::class, 'index'])->name('auth-login');
Route::get('login', [Auth::class, 'index'])->name('login');
Route::post('auth_action', [Auth::class, 'auth_action'])->name('auth-action');

//Route Restore Database
Route::get('restore', [RestoreDatabase::class, 'index'])->name('restore');
Route::post('restoreDatabase', [RestoreDatabase::class, 'restoreDatabase'])->name('restore-database');

Route::group(['middleware' => ['auth']], function () {
    Route::post('logout', [Auth::class, 'logout'])->name('logout');
});
