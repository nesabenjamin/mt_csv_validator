<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'ConfigurationController@index');



Route::get('upload', 'DocumentController@index')->name('upload');
Route::get('configure', 'ConfigurationController@index')->name('configure');
Route::post('configure', 'ConfigurationController@store')->name('configure.post');
Route::post('store', 'DocumentController@store')->name('store');


Route::get('getFields', 'ConfigurationController@getFields');
Route::get('documentation', 'DocumentController@documentation')->name('documentation');
