<?php

Route::get('/', 'HomeController@index')->name('home.index');
Route::get('/{slug}', 'HomeController@show')->name('home.show');