<?php

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

Route::get('/', function () {
    return view('index');
});

/*
 * Backend
 * */
Route::group(['prefix' => 'back', 'namespace' => 'Backend'], function () {
   Route::get('/', 'IndexController@index')->name('admin');
   Route::post('/auth/login', 'AuthController@authenticate');
   Route::post('/auth/check', 'AuthController@checkUser');
   Route::post('/auth/logout', 'AuthController@logout');
});