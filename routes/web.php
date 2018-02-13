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
/**
 * 前台
 */


Route::group(['namespace' => 'App'], function () {
    Route::get('/test', 'HomeController@test');
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/post/{id}', 'HomeController@post')->name('post');
    Route::get('/tags/{flag}', 'HomeController@tags')->name('tags');
    Route::get('/category/{id}', 'HomeController@categories')->name('category');
    Route::resource('/comments', 'CommentsController');
});

/**
 * Backend
 */
Route::group(['prefix' => 'back', 'namespace' => 'Backend'], function () {
   Route::get('/', 'IndexController@index')->name('admin');
   Route::post('/auth/login', 'AuthController@authenticate');
   Route::post('/auth/check', 'AuthController@checkUser');
   Route::post('/auth/logout', 'AuthController@logout');

});

Route::group(['middleware' => 'auth', 'prefix' => 'back', 'namespace' => 'Backend'], function () {
    Route::resource('/users', 'UserController');
    Route::resource('/category', 'CategoryController');
    Route::resource('/posts', 'PostController');
    Route::resource('/comment', 'CommentController');
    Route::resource('/trashes', 'TrashController');
    Route::post('/dashboard', 'IndexController@statistical');
});
