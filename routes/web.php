<?php

Route::get('/', 'LocationController@index')->name('locations.index');
Route::get('/{slug}', 'LocationController@show')->name('locations.show');