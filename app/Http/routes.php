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
        return view('judicial.web.index');
    });
    Route::get('user','Web\User@index');

    Route::get('user/login','Web\User@login');

    Route::get('user/logout', 'Web\User@logout');

    Route::post('user/login',['as'=>'userLoginUrl', 'uses'=>'Web\User@doLogin']);

    Route::post('user/signup',['as'=>'signupUrl', 'uses'=>'Web\User@signup']);

    Route::post('user/login/changeTab','Web\User@_ajax_changeTab');

    Route::post('user/login/checkInput','Web\User@_ajax_checkInput');

    Route::post('user/createMember','Web\User@createMember');


//后台相关路由
    Route::get('manage', 'Manage\Login@index');

    Route::get('manage/logout', 'Manage\Login@logout');

    Route::post('manage/login',['as'=>'loginUrl', 'uses'=>'Manage\Login@doLogin']);

    Route::get('manage/dashboard',['as'=>'dashBoard', 'uses'=>'Manage\Dashboard@index']);

    Route::post('manage/ajax/{action}','Manage\Login@ajaxRequest');

    Route::post('manage/loadContent','Manage\Dashboard@loadContent');

    Route::post('manage/changePassword','Manage\Dashboard@changePassword');
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
