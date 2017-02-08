<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['middleware' => ['web']], function () {
    //前台相关路由
    Route::get('/', function () {
        return view('welcome');
    });

//后台相关路由
    Route::get('manage', 'Manage\Login@index');

    Route::post('manage/login',['as'=>'loginUrl', 'uses'=>'Manage\Login@doLogin']);

    Route::get('manage/dashboard',['as'=>'dashBoard', 'uses'=>'Manage\Dashboard@index']);

    Route::post('manage/ajax/{action}','Manage\Login@ajaxRequest');

    Route::post('manage/loadContent','Manage\Dashboard@loadContent');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
