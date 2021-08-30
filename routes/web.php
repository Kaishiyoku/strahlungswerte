<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\StatisticController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LocationController::class, 'index'])->name('locations.index');
Route::get('/locations/find', [LocationController::class, 'find'])->name('locations.find');
Route::get('/locations/{location}', [LocationController::class, 'show'])->name('locations.show');
Route::get('/statistics', [StatisticController::class, 'index'])->name('statistics.index');

/* *****************
 * Logged on users *
 ***************** */
Route::group(['middleware' => ['auth']], function () {
    /* ****************
     * Administrators *
     **************** */
    Route::group(['middleware' => ['admin']], function () {
        //
    });
});

Auth::routes(['register' => false, 'reset' => false]);
