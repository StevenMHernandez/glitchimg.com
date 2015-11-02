<?php

Route::get('/', [
    'as' => 'index', 'uses' => 'SiteController@index'
]);

Route::get('/login', [
    'as' => 'login', 'uses' => 'SiteController@login', 'middleware' => 'guest'
]);

Route::get('/logout', [
    'as' => 'logout', 'uses' => 'Auth\AuthController@logout', 'middleware' => 'auth'
]);

Route::get('login/{provider?}', [
    'uses' =>'Auth\AuthController@login', 'middleware' => 'guest'
]);

Route::get('/profile', [
    'as' => 'profile', 'uses' => 'PhotosController@index', 'middleware' => 'auth'
]);

Route::get('/settings', [
    'as' => 'settings', 'uses' => 'SettingsController@index', 'middleware' => 'auth'
]);

Route::post('/settings', [
    'as' => 'settings.update', 'uses' => 'SettingsController@update', 'middleware' => 'auth'
]);

Route::get('/glitch/{filename}', [
    'as' => 'photos.show', 'uses' => 'PhotosController@show'
]);

Route::get('/editor', [
    'as' => 'editor', 'uses' => 'EditorController@index', 'middleware' => 'auth'
]);

Route::post('/upload', [
    'as' => 'photo.upload', 'uses' => 'PhotosController@upload', 'middleware' => 'auth'
]);