<?php

Route::get('/', 'IndexController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');
