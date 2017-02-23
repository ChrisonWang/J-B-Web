<?php
Route::group(['middleware' => ['web']], function () {
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
//前台相关路由
    Route::group(['middleware' => ['user.verify']], function () {

        Route::get('/', 'Web\Index@index');

        Route::get('user','Web\User@index');

        Route::get('user/login','Web\User@login');

        Route::get('user/logout', 'Web\User@logout');

        Route::post('user/login',['as'=>'userLoginUrl', 'uses'=>'Web\User@doLogin']);

        Route::post('user/signup',['as'=>'signupUrl', 'uses'=>'Web\User@signup']);

        Route::post('user/login/changeTab','Web\User@_ajax_changeTab');

        Route::post('user/login/checkInput','Web\User@_ajax_checkInput');

        Route::post('user/createMember','Web\User@createMember');

        Route::post('user/changePassword','Web\User@changePassword');

        Route::post('user/changeInfo','Web\User@changeInfo');

        Route::get('user/changePhone','Web\User@changePhone');

        Route::post('user/changePhone','Web\User@doChangePhone');
    });

//后台相关路由
    Route::group(['middleware' => ['manage.verify']], function () {

        Route::get('manage/dashboard',['as'=>'dashBoard', 'uses'=>'Manage\Dashboard@index']);

        Route::post('manage/dashboard/editManagerInfo','Manage\Dashboard@toEditManagerInfo');

        Route::post('manage/ajax/{action}','Manage\Login@ajaxRequest');

        Route::post('manage/changePassword','Manage\Dashboard@changePassword');

        //页面入口路由
        Route::post('manage/cmsLoadContent', 'Manage\CmsLoadContent@loadContent');

        Route::post('manage/userLoadContent','Manage\Dashboard@loadContent');

        //CMS标签标签管理
        Route::get('manage/cms/tags/show','Manage\Cms\Tags@show');

        Route::get('manage/cms/tags/add','Manage\Cms\Tags@create');

        Route::post('manage/cms/tags/add','Manage\Cms\Tags@store');

        Route::get('manage/cms/tags/edit','Manage\Cms\Tags@edit');

        Route::post('manage/cms/tags/edit','Manage\Cms\Tags@doEdit');

        Route::get('manage/cms/tags/delete','Manage\Cms\Tags@doDelete');

        //CMS机构分类管理
        Route::get('manage/cms/department/type/show','Manage\Cms\DepartmentType@show');

        Route::get('manage/cms/department/type/add','Manage\Cms\DepartmentType@create');

        Route::post('manage/cms/department/type/add','Manage\Cms\DepartmentType@store');

        Route::get('manage/cms/department/type/edit','Manage\Cms\DepartmentType@edit');

        Route::post('manage/cms/department/type/edit','Manage\Cms\DepartmentType@doEdit');

        Route::get('manage/cms/department/type/delete','Manage\Cms\DepartmentType@doDelete');

        //CMS机构管理
        Route::get('manage/cms/department/show','Manage\Cms\Department@show');

        Route::get('manage/cms/department/add','Manage\Cms\Department@create');

        Route::post('manage/cms/department/add','Manage\Cms\Department@store');

        Route::get('manage/cms/department/edit','Manage\Cms\Department@edit');

        Route::post('manage/cms/department/edit','Manage\Cms\Department@doEdit');

        Route::get('manage/cms/department/delete','Manage\Cms\Department@doDelete');

        //CMS领导简介管理
        Route::get('manage/cms/leader/show','Manage\Cms\Leader@show');

        Route::get('manage/cms/leader/add','Manage\Cms\Leader@create');

        Route::post('manage/cms/leader/add','Manage\Cms\Leader@store');

        Route::get('manage/cms/leader/edit','Manage\Cms\Leader@edit');

        Route::post('manage/cms/leader/edit','Manage\Cms\Leader@doEdit');

        Route::get('manage/cms/leader/delete','Manage\Cms\Leader@doDelete');

        //CMS宣传视频管理
        Route::get('manage/cms/video/show','Manage\Cms\Video@show');

        Route::get('manage/cms/video/add','Manage\Cms\Video@create');

        Route::post('manage/cms/video/add','Manage\Cms\Video@store');

        Route::get('manage/cms/video/edit','Manage\Cms\Video@edit');

        Route::post('manage/cms/video/edit','Manage\Cms\Video@doEdit');

        Route::get('manage/cms/video/delete','Manage\Cms\Video@doDelete');

        //CMS后台推荐链接管理
        Route::get('manage/cms/recommend/show','Manage\Cms\Recommend@show');

        Route::get('manage/cms/recommend/add','Manage\Cms\Recommend@create');

        Route::post('manage/cms/recommend/add','Manage\Cms\Recommend@store');

        Route::get('manage/cms/recommend/edit','Manage\Cms\Recommend@edit');

        Route::post('manage/cms/recommend/edit','Manage\Cms\Recommend@doEdit');

        Route::get('manage/cms/recommend/delete','Manage\Cms\Recommend@doDelete');

        //CMS司法局简介管理
        Route::get('manage/cms/intro/show','Manage\Cms\Introduction@show');

        Route::get('manage/cms/intro/add','Manage\Cms\Introduction@create');

        Route::post('manage/cms/intro/add','Manage\Cms\Introduction@store');

        Route::get('manage/cms/intro/edit','Manage\Cms\Introduction@edit');

        Route::post('manage/cms/intro/edit','Manage\Cms\Introduction@doEdit');

        Route::get('manage/cms/intro/delete','Manage\Cms\Introduction@doDelete');

        //CMS一级友情链接管理
        Route::get('manage/cms/flinkImg/show','Manage\Cms\FlinksImg@show');

        Route::get('manage/cms/flinkImg/add','Manage\Cms\FlinksImg@create');

        Route::post('manage/cms/flinkImg/add','Manage\Cms\FlinksImg@store');

        Route::get('manage/cms/flinkImg/edit','Manage\Cms\FlinksImg@edit');

        Route::post('manage/cms/flinkImg/edit','Manage\Cms\FlinksImg@doEdit');

        Route::get('manage/cms/flinkImg/delete','Manage\Cms\FlinksImg@doDelete');

        //科室管理
        Route::get('manage/user/office/show','Manage\User\Office@show');

        Route::get('manage/user/office/add','Manage\User\Office@create');

        Route::post('manage/user/office/add','Manage\User\Office@store');

        Route::get('manage/user/office/edit','Manage\User\Office@edit');

        Route::post('manage/user/office/edit','Manage\User\Office@doEdit');

        Route::get('manage/user/office/delete','Manage\User\Office@doDelete');

        //功能点管理
        Route::get('manage/user/nodes/show','Manage\User\Nodes@show');

        Route::get('manage/user/nodes/add','Manage\User\Nodes@create');

        Route::post('manage/user/nodes/add','Manage\User\Nodes@store');

        Route::get('manage/user/nodes/edit','Manage\User\Nodes@edit');

        Route::post('manage/user/nodes/edit','Manage\User\Nodes@doEdit');

        Route::get('manage/user/nodes/delete','Manage\User\Nodes@doDelete');

        //菜单管理
        Route::get('manage/user/menus/show','Manage\User\Menus@show');

        Route::get('manage/user/menus/add','Manage\User\Menus@create');

        Route::post('manage/user/menus/add','Manage\User\Menus@store');

        Route::get('manage/user/menus/edit','Manage\User\Menus@edit');

        Route::post('manage/user/menus/edit','Manage\User\Menus@doEdit');

        Route::get('manage/user/menus/delete','Manage\User\Menus@doDelete');

        //频道管理
        Route::get('manage/cms/channel/show','Manage\Cms\Channel@show');

        Route::get('manage/cms/channel/add','Manage\Cms\Channel@create');

        Route::post('manage/cms/channel/add','Manage\Cms\Channel@store');

        Route::get('manage/cms/channel/edit','Manage\Cms\Channel@edit');

        Route::post('manage/cms/channel/edit','Manage\Cms\Channel@doEdit');

        Route::get('manage/cms/channel/delete','Manage\Cms\Channel@doDelete');

    });

    Route::get('manage', 'Manage\Login@index');

    Route::get('manage/logout', 'Manage\Login@logout');

    Route::post('manage/login',['as'=>'loginUrl', 'uses'=>'Manage\Login@doLogin']);
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
