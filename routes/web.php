<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'LocationController@index')->name('locations.index');
Route::get('/locations/{slug}', 'LocationController@show')->name('locations.show');
Route::get('/statistics', 'StatisticController@index')->name('statistics.index');

/* *****************
 * Logged on users *
 ***************** */
Route::group(['middleware' => ['auth']], function () {
    /* ****************
     * Administrators *
     **************** */
    Route::group(['middleware' => ['admin']], function () {
        Route::resource('update_logs', 'UpdateLogController')->only(['index', 'show']);
    });
});

Auth::routes(['register' => false, 'reset' => false]);
