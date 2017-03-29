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
    Route::get('/', 'Web\Index@index');

    Route::get('/verify', 'Web\Index@verify');

    Route::get('user/verify/{tmp?}','Web\User@captcha');

    Route::get('user/login','Web\User@login');

    Route::get('user/logout', 'Web\User@logout');

    Route::post('user/login',['as'=>'userLoginUrl', 'uses'=>'Web\User@doLogin']);

    Route::post('user/signup',['as'=>'signupUrl', 'uses'=>'Web\User@signup']);

    Route::get('user','Web\User@index');

    Route::post('user/login/changeTab','Web\User@_ajax_changeTab');

    Route::post('user/login/checkInput','Web\User@_ajax_checkInput');

    Route::post('user/createMember','Web\User@createMember');

    Route::post('user/sendVerify','Web\User@sendVerify');

    Route::group(['middleware' => ['user.verify']], function () {

        Route::post('user/changePassword','Web\User@changePassword');

        Route::post('user/changeInfo','Web\User@changeInfo');

        Route::get('user/changePhone','Web\User@changePhone');

        Route::post('user/changePhone','Web\User@doChangePhone');

        //公检法指派

        Route::get('service/aidDispatch/detail/{record_code}', 'Service\AidDispatch@show');

        Route::get('service/aidDispatch/edit/{record_code}', 'Service\AidDispatch@edit');

        Route::get('service/aidDispatch/apply', 'Service\AidDispatch@add');

        Route::post('service/aidDispatch/doApply', 'Service\AidDispatch@store');

        //群众预约法律援助
        Route::get('service/aid/list/{page?}', 'Service\AidApply@index');

        Route::get('service/aidApply/detail/{record_code}', 'Service\AidApply@show');

        Route::get('service/aidApply/edit/{record_code}', 'Service\AidApply@edit');

        Route::get('service/aidApply/apply', 'Service\AidApply@add');

        Route::post('service/aidApply/doApply', 'Service\AidApply@store');

        //司法鉴定
        Route::get('service/expertise/list/{page?}', 'Service\Expertise@index');

        Route::get('service/expertise/apply', 'Service\Expertise@add');

        Route::get('service/expertise/edit/{record_code}', 'Service\Expertise@edit');

        Route::get('service/expertise/detail/{record_code}', 'Service\Expertise@show');

        Route::post('service/expertise/doApply', 'Service\Expertise@store');

        Route::post('user/service/list/', 'Web\User@getServiceListPage');

    });

    //司法鉴定表格下载
    Route::get('service/expertise/downloadForm/{page?}', 'Service\Expertise@downloadForm');

    //网上办事路由
    Route::get('service','Service\Index@index');

    Route::post('service/getOpinion','Service\Index@getOpinion');

    //征求意见
    Route::get('suggestions/list/{page?}','Service\Suggestions@index');

    Route::get('suggestions/detail/{record_code}','Service\Suggestions@show');

    Route::get('suggestions/add','Service\Suggestions@add');

    Route::post('suggestions/add','Service\Suggestions@doAdd');

    Route::post('suggestions/search','Service\Suggestions@search');

    //问题咨询
    Route::get('consultions/list/{page?}','Service\Consultions@index');

    Route::get('consultions/detail/{record_code}','Service\Consultions@show');

    Route::get('consultions/add','Service\Consultions@add');

    Route::post('consultions/add','Service\Consultions@doAdd');

    Route::post('consultions/search','Service\Consultions@search');

    //律师管理
    Route::get('service/lawyer/{page?}','Service\Lawyer@index');

    Route::get('service/lawyer/detail/{key}','Service\Lawyer@show');

    Route::post('service/lawyer/search','Service\Lawyer@search');

    //律所管理
    Route::get('service/lawyerOffice/{page?}','Service\LawyerOffice@index');

    Route::get('service/lawyerOffice/detail/{key}','Service\LawyerOffice@show');

    Route::get('service/lawyerOffice/area/{key}','Service\LawyerOffice@area');

    Route::post('service/lawyerOffice/search','Service\LawyerOffice@search');

    //网上办事
    Route::get('service/list/{cid}/{page?}','Service\Index@article_list');

    Route::get('/article/{article_code}','Service\Index@article_content');


    //前台CMS路由
    Route::post('search',['as'=>'search', 'uses'=>'Web\Index@search']);

    Route::post('get',['as'=>'search', 'uses'=>'Web\Index@search']);

    Route::get('list/{cid}/{page?}','Web\Index@article_list');

    Route::get('tagList/{tid}/{page?}','Web\Index@tag_list');

    Route::get('picture/{page?}','Web\Index@picture_list');

    Route::get('video/{page?}','Web\Index@video_list');

    Route::get('article/{article_code}','Web\Index@article_content');

    Route::get('videoContent/{article_code}','Web\Index@video_content');

    Route::get('intro','Web\Index@introduction');

    Route::get('leader','Web\Index@leader');

    Route::get('department','Web\Index@department');

    Route::get('department/intro/{key?}','Web\Index@departmentIntro');

    Route::post('index/loadArticle','Web\Index@loadArticleList');

    Route::post('download','Web\Index@download');

//后台相关路由
    Route::group(['middleware' => ['manage.verify']], function () {

        Route::get('manage/dashboard',['as'=>'dashBoard', 'uses'=>'Manage\Dashboard@index']);

        Route::post('manage/dashboard/editManagerInfo','Manage\Dashboard@toEditManagerInfo');

        Route::post('manage/ajax/{action}','Manage\Login@ajaxRequest');

        Route::post('manage/changePassword','Manage\Dashboard@changePassword');

        //页面入口路由
        Route::post('manage/cmsLoadContent', 'Manage\CmsLoadContent@loadContent');

        Route::post('manage/userLoadContent','Manage\Dashboard@loadContent');

        Route::post('manage/serviceLoadContent','Manage\ServiceLoadContent@loadContent');

        Route::post('manage/systemLoadContent','Manage\SystemLoadContent@loadContent');

        //CMS标签标签管理
        Route::get('manage/cms/tags/show','Manage\Cms\Tags@show');

        Route::get('manage/cms/tags/add','Manage\Cms\Tags@create');

        Route::post('manage/cms/tags/add','Manage\Cms\Tags@store');

        Route::get('manage/cms/tags/edit','Manage\Cms\Tags@edit');

        Route::post('manage/cms/tags/edit','Manage\Cms\Tags@doEdit');

        Route::get('manage/cms/tags/delete','Manage\Cms\Tags@doDelete');

        Route::post('manage/cms/tagsList/{page?}','Manage\Cms\Tags@index');

        //CMS机构分类管理
        Route::get('manage/cms/department/type/show','Manage\Cms\DepartmentType@show');

        Route::get('manage/cms/department/type/add','Manage\Cms\DepartmentType@create');

        Route::post('manage/cms/department/type/add','Manage\Cms\DepartmentType@store');

        Route::get('manage/cms/department/type/edit','Manage\Cms\DepartmentType@edit');

        Route::post('manage/cms/department/type/edit','Manage\Cms\DepartmentType@doEdit');

        Route::get('manage/cms/department/type/delete','Manage\Cms\DepartmentType@doDelete');

        Route::post('manage/cms/departmentTypeList/{page?}','Manage\Cms\DepartmentType@index');

        //CMS机构管理
        Route::get('manage/cms/department/show','Manage\Cms\Department@show');

        Route::get('manage/cms/department/add','Manage\Cms\Department@create');

        Route::post('manage/cms/department/add','Manage\Cms\Department@store');

        Route::get('manage/cms/department/edit','Manage\Cms\Department@edit');

        Route::post('manage/cms/department/edit','Manage\Cms\Department@doEdit');

        Route::get('manage/cms/department/delete','Manage\Cms\Department@doDelete');

        Route::post('manage/cms/departmentList/{page?}','Manage\Cms\Department@index');

        //CMS领导简介管理
        Route::get('manage/cms/leader/show','Manage\Cms\Leader@show');

        Route::get('manage/cms/leader/add','Manage\Cms\Leader@create');

        Route::post('manage/cms/leader/add','Manage\Cms\Leader@store');

        Route::get('manage/cms/leader/edit','Manage\Cms\Leader@edit');

        Route::post('manage/cms/leader/edit','Manage\Cms\Leader@doEdit');

        Route::get('manage/cms/leader/delete','Manage\Cms\Leader@doDelete');

        Route::post('manage/cms/leaderList/{page?}','Manage\Cms\Leader@index');

        //CMS宣传视频管理
        Route::get('manage/cms/video/show','Manage\Cms\Video@show');

        Route::get('manage/cms/video/add','Manage\Cms\Video@create');

        Route::post('manage/cms/video/add','Manage\Cms\Video@store');

        Route::get('manage/cms/video/edit','Manage\Cms\Video@edit');

        Route::post('manage/cms/video/edit','Manage\Cms\Video@doEdit');

        Route::get('manage/cms/video/delete','Manage\Cms\Video@doDelete');

        Route::post('manage/cms/videoList/{page?}','Manage\Cms\Video@index');

        //CMS后台推荐链接管理
        Route::get('manage/cms/recommend/show','Manage\Cms\Recommend@show');

        Route::get('manage/cms/recommend/add','Manage\Cms\Recommend@create');

        Route::post('manage/cms/recommend/add','Manage\Cms\Recommend@store');

        Route::get('manage/cms/recommend/edit','Manage\Cms\Recommend@edit');

        Route::post('manage/cms/recommend/edit','Manage\Cms\Recommend@doEdit');

        Route::get('manage/cms/recommend/delete','Manage\Cms\Recommend@doDelete');

        Route::post('manage/cms/recommendList/{page?}','Manage\Cms\Recommend@index');

        //CMS司法局简介管理
        Route::get('manage/cms/intro/show','Manage\Cms\Introduction@show');

        Route::get('manage/cms/intro/add','Manage\Cms\Introduction@create');

        Route::post('manage/cms/intro/add','Manage\Cms\Introduction@store');

        Route::get('manage/cms/intro/edit','Manage\Cms\Introduction@edit');

        Route::post('manage/cms/intro/edit','Manage\Cms\Introduction@doEdit');

        Route::get('manage/cms/intro/delete','Manage\Cms\Introduction@doDelete');

        Route::post('manage/cms/introList/{page?}','Manage\Cms\Introduction@index');

        //CMS一级友情链接管理
        Route::get('manage/cms/flinkImg/show','Manage\Cms\FlinksImg@show');

        Route::get('manage/cms/flinkImg/add','Manage\Cms\FlinksImg@create');

        Route::post('manage/cms/flinkImg/add','Manage\Cms\FlinksImg@store');

        Route::get('manage/cms/flinkImg/edit','Manage\Cms\FlinksImg@edit');

        Route::post('manage/cms/flinkImg/edit','Manage\Cms\FlinksImg@doEdit');

        Route::get('manage/cms/flinkImg/delete','Manage\Cms\flinkImg@doDelete');

        Route::post('manage/cms/flinkImgList/{page?}','Manage\Cms\flinkImg@index');

        //CMS二级友情链接管理
        Route::get('manage/cms/flinks/show','Manage\Cms\Flinks@show');

        Route::get('manage/cms/flinks/add','Manage\Cms\Flinks@create');

        Route::post('manage/cms/flinks/add','Manage\Cms\Flinks@store');

        Route::get('manage/cms/flinks/edit','Manage\Cms\Flinks@edit');

        Route::post('manage/cms/flinks/edit','Manage\Cms\Flinks@doEdit');

        Route::get('manage/cms/flinks/delete','Manage\Cms\Flinks@doDelete');

        Route::post('manage/cms/flinksList/{page?}','Manage\Cms\Flinks@index');

        //科室管理
        Route::get('manage/user/office/show','Manage\User\Office@show');

        Route::get('manage/user/office/add','Manage\User\Office@create');

        Route::post('manage/user/office/add','Manage\User\Office@store');

        Route::get('manage/user/office/edit','Manage\User\Office@edit');

        Route::post('manage/user/office/edit','Manage\User\Office@doEdit');

        Route::get('manage/user/office/delete','Manage\User\Office@doDelete');

        Route::post('manage/cms/officeList/{page?}','Manage\User\Office@index');

        //功能点管理
        Route::get('manage/user/nodes/show','Manage\User\Nodes@show');

        Route::get('manage/user/nodes/add','Manage\User\Nodes@create');

        Route::post('manage/user/nodes/add','Manage\User\Nodes@store');

        Route::get('manage/user/nodes/edit','Manage\User\Nodes@edit');

        Route::post('manage/user/nodes/edit','Manage\User\Nodes@doEdit');

        Route::get('manage/user/nodes/delete','Manage\User\Nodes@doDelete');

        Route::post('manage/cms/nodesList/{page?}','Manage\User\Nodes@index');

        //用户管理
        Route::get('manage/user/users/show','Manage\User\Users@show');

        Route::get('manage/user/users/add','Manage\User\Users@create');

        Route::post('manage/user/users/add','Manage\User\Users@store');

        Route::get('manage/user/users/edit','Manage\User\Users@edit');

        Route::post('manage/user/users/edit','Manage\User\Users@doEdit');

        Route::get('manage/user/users/delete','Manage\User\Users@doDelete');

        Route::post('manage/cms/usersList/{page?}','Manage\User\Users@index');

        Route::post('manage/user/users/searchUser','Manage\User\Users@searchUser');

        //菜单管理
        Route::get('manage/user/menus/show','Manage\User\Menus@show');

        Route::get('manage/user/menus/add','Manage\User\Menus@create');

        Route::post('manage/user/menus/add','Manage\User\Menus@store');

        Route::get('manage/user/menus/edit','Manage\User\Menus@edit');

        Route::post('manage/user/menus/edit','Manage\User\Menus@doEdit');

        Route::get('manage/user/menus/delete','Manage\User\Menus@doDelete');

        Route::post('manage/cms/menusList/{page?}','Manage\User\Menus@index');

        //角色管理
        Route::get('manage/user/roles/show','Manage\User\Roles@show');

        Route::get('manage/user/roles/add','Manage\User\Roles@create');

        Route::post('manage/user/roles/add','Manage\User\Roles@store');

        Route::get('manage/user/roles/edit','Manage\User\Roles@edit');

        Route::post('manage/user/roles/edit','Manage\User\Roles@doEdit');

        Route::get('manage/user/roles/delete','Manage\User\Roles@doDelete');

        Route::post('manage/cms/rolesList/{page?}','Manage\User\Roles@index');

        Route::post('manage/cms/roles/get_sub_node','Manage\User\Roles@getSubNode');

        //频道管理
        Route::get('manage/cms/channel/show','Manage\Cms\Channel@show');

        Route::get('manage/cms/channel/add','Manage\Cms\Channel@create');

        Route::post('manage/cms/channel/add','Manage\Cms\Channel@store');

        Route::get('manage/cms/channel/edit','Manage\Cms\Channel@edit');

        Route::post('manage/cms/channel/edit','Manage\Cms\Channel@doEdit');

        Route::get('manage/cms/channel/delete','Manage\Cms\Channel@doDelete');

        Route::post('manage/cms/channelList/{page?}','Manage\Cms\Channel@index');

        //表单管理
        Route::get('manage/cms/forms/show','Manage\Cms\Forms@show');

        Route::get('manage/cms/forms/add','Manage\Cms\Forms@create');

        Route::post('manage/cms/forms/add','Manage\Cms\Forms@store');

        Route::get('manage/cms/forms/edit','Manage\Cms\Forms@edit');

        Route::post('manage/cms/forms/edit','Manage\Cms\Forms@doEdit');

        Route::get('manage/cms/forms/delete','Manage\Cms\Forms@doDelete');

        Route::post('manage/cms/formList/{page?}','Manage\Cms\Forms@index');

        //文章管理
        Route::get('manage/cms/article/show','Manage\Cms\Article@show');

        Route::get('manage/cms/article/add','Manage\Cms\Article@create');

        Route::post('manage/cms/article/add','Manage\Cms\Article@store');

        Route::get('manage/cms/article/edit','Manage\Cms\Article@edit');

        Route::post('manage/cms/article/edit','Manage\Cms\Article@doEdit');

        Route::get('manage/cms/article/delete','Manage\Cms\Article@doDelete');

        Route::post('manage/cms/article/upload','Manage\Cms\Article@uploadFiles');

        Route::post('manage/cms/article/searchTags','Manage\Cms\Article@searchTags');

        Route::post('manage/cms/articleList/{page?}','Manage\Cms\Article@index');

        Route::post('manage/cms/article/get_sub_channel','Manage\Cms\Article@ajaxGetChannel');

        //后台ajax搜索列表
        Route::post('manage/searchList','Manage\Dashboard@ajaxSearchList');

        //律师服务管理,区域管理
        Route::get('manage/service/area/show','Manage\Service\Area@show');

        Route::get('manage/service/area/add','Manage\Service\Area@create');

        Route::post('manage/service/area/add','Manage\Service\Area@store');

        Route::get('manage/service/area/edit','Manage\Service\Area@edit');

        Route::post('manage/service/area/edit','Manage\Service\Area@doEdit');

        Route::get('manage/service/area/delete','Manage\Service\Area@doDelete');

        Route::post('manage/service/areaList/{page?}','Manage\Service\Area@index');

        //事务所管理
        Route::get('manage/service/lawyerOffice/show','Manage\Service\LawyerOffice@show');

        Route::get('manage/service/lawyerOffice/add','Manage\Service\LawyerOffice@create');

        Route::post('manage/service/lawyerOffice/add','Manage\Service\LawyerOffice@store');

        Route::get('manage/service/lawyerOffice/edit','Manage\Service\LawyerOffice@edit');

        Route::post('manage/service/lawyerOffice/edit','Manage\Service\LawyerOffice@doEdit');

        Route::get('manage/service/lawyerOffice/delete','Manage\Service\LawyerOffice@doDelete');

        Route::post('manage/service/lawyerOffice/search','Manage\Service\LawyerOffice@search');

        Route::post('manage/service/lawyerOfficeList/{page?}','Manage\Service\LawyerOffice@index');

        //律师管理
        Route::get('manage/service/lawyer/show','Manage\Service\Lawyer@show');

        Route::get('manage/service/lawyer/add','Manage\Service\Lawyer@create');

        Route::post('manage/service/lawyer/add','Manage\Service\Lawyer@store');

        Route::get('manage/service/lawyer/edit','Manage\Service\Lawyer@edit');

        Route::post('manage/service/lawyer/edit','Manage\Service\Lawyer@doEdit');

        Route::get('manage/service/lawyer/delete','Manage\Service\Lawyer@doDelete');

        Route::post('manage/service/lawyer/search','Manage\Service\Lawyer@search');

        Route::post('manage/service/lawyerList/{page?}','Manage\Service\Lawyer@index');

        //短信模板管理
        Route::get('manage/service/messageTmp/show','Manage\Service\MessageTmp@show');

        Route::get('manage/service/messageTmp/add','Manage\Service\MessageTmp@create');

        Route::post('manage/service/messageTmp/add','Manage\Service\MessageTmp@store');

        Route::get('manage/service/messageTmp/edit','Manage\Service\MessageTmp@edit');

        Route::post('manage/service/messageTmp/edit','Manage\Service\MessageTmp@doEdit');

        Route::get('manage/service/messageTmp/delete','Manage\Service\MessageTmp@doDelete');

        Route::post('manage/service/messageTmpList/{page?}','Manage\Service\MessageTmp@index');

        //短信发送管理
        Route::get('manage/service/messageSend/show','Manage\Service\MessageSend@show');

        Route::get('manage/service/messageSend/add','Manage\Service\MessageSend@create');

        Route::post('manage/service/messageSend/add','Manage\Service\MessageSend@store');

        Route::get('manage/service/messageSend/edit','Manage\Service\MessageSend@edit');

        Route::post('manage/service/messageSend/edit','Manage\Service\MessageSend@doEdit');

        Route::get('manage/service/messageSend/delete','Manage\Service\MessageSend@doDelete');

        Route::get('manage/service/messageSend/getContent','Manage\Service\MessageSend@getTemp');

        Route::post('manage/service/messageSendList/{page?}','Manage\Service\MessageSend@index');
        //异步加载发送对象
        Route::post('manage/service/messageSend/loadMembers','Manage\Service\MessageSend@loadMembers');

        Route::post('manage/service/messageSend/searchMembers','Manage\Service\MessageSend@searchMembers');

        Route::post('manage/service/messageSend/searchOffice','Manage\Service\MessageSend@searchOffice');

        //证书持有人管理
        Route::post('manage/service/certificate/send','Manage\Service\Certificate@sendMessage');

        Route::get('manage/service/certificate/show','Manage\Service\Certificate@show');

        Route::get('manage/service/certificate/add','Manage\Service\Certificate@create');

        Route::post('manage/service/certificate/add','Manage\Service\Certificate@store');

        Route::get('manage/service/certificate/edit','Manage\Service\Certificate@edit');

        Route::post('manage/service/certificate/edit','Manage\Service\Certificate@doEdit');

        Route::get('manage/service/certificate/delete','Manage\Service\Certificate@doDelete');

        Route::get('manage/service/certificate/download','Manage\Service\Certificate@downloadTemp');

        Route::post('manage/service/certificate/search','Manage\Service\Certificate@search');

        Route::post('manage/service/certificate/import','Manage\Service\Certificate@doImport');

        Route::post('manage/service/certificateList/{page?}','Manage\Service\Certificate@index');

        //司法鉴定类型管理
        Route::get('manage/service/expertiseType/show','Manage\Service\ExpertiseType@show');

        Route::get('manage/service/expertiseType/add','Manage\Service\ExpertiseType@create');

        Route::post('manage/service/expertiseType/add','Manage\Service\ExpertiseType@store');

        Route::get('manage/service/expertiseType/edit','Manage\Service\ExpertiseType@edit');

        Route::post('manage/service/expertiseType/edit','Manage\Service\ExpertiseType@doEdit');

        Route::get('manage/service/expertiseType/delete','Manage\Service\ExpertiseType@doDelete');

        Route::post('manage/service/expertiseTypeList/{page?}','Manage\Service\ExpertiseType@index');

        //司法鉴定申请管理
        Route::get('manage/service/expertiseApply/show','Manage\Service\ExpertiseApply@show');

        Route::get('manage/service/expertiseApply/edit','Manage\Service\ExpertiseApply@edit');

        Route::post('manage/service/expertiseApply/edit','Manage\Service\ExpertiseApply@doEdit');

        Route::post('manage/service/expertiseApply/pass','Manage\Service\ExpertiseApply@doPass');

        Route::post('manage/service/expertiseApply/reject','Manage\Service\ExpertiseApply@doReject');

        Route::post('manage/service/expertiseApply/search','Manage\Service\ExpertiseApply@search');

        Route::post('manage/service/expertiseApplyList/{page?}','Manage\Service\ExpertiseApply@index');

        //征求意见管理
        Route::get('manage/service/suggestions/show','Manage\Service\Suggestions@show');

        Route::get('manage/service/suggestions/edit','Manage\Service\Suggestions@edit');

        Route::post('manage/service/suggestions/edit','Manage\Service\Suggestions@doEdit');

        Route::post('manage/service/suggestions/answer','Manage\Service\Suggestions@doAnswer');

        Route::post('manage/service/suggestions/search','Manage\Service\Suggestions@search');

        Route::post('manage/service/suggestionsList/{page?}','Manage\Service\Suggestions@index');

        //问题咨询管理
        Route::get('manage/service/consultions/show','Manage\Service\Consultions@show');

        Route::get('manage/service/consultions/edit','Manage\Service\Consultions@edit');

        Route::post('manage/service/consultions/edit','Manage\Service\Consultions@doEdit');

        Route::post('manage/service/consultions/answer','Manage\Service\Consultions@doAnswer');

        Route::post('manage/service/consultions/search','Manage\Service\Consultions@search');

        Route::post('manage/service/consultionsList/{page?}','Manage\Service\Consultions@index');

        //群众预约援助管理
        Route::get('manage/service/aidApply/show','Manage\Service\AidApply@show');

        Route::get('manage/service/aidApply/edit','Manage\Service\AidApply@edit');

        Route::post('manage/service/aidApply/edit','Manage\Service\AidApply@doEdit');

        Route::post('manage/service/aidApply/pass','Manage\Service\AidApply@doPass');

        Route::post('manage/service/aidApply/reject','Manage\Service\AidApply@doReject');

        Route::post('manage/service/aidApply/search','Manage\Service\AidApply@search');

        Route::post('manage/service/aidApplyList/{page?}','Manage\Service\AidApply@index');

        //公检法指派管理
        Route::get('manage/service/aidDispatch/show','Manage\Service\AidDispatch@show');

        Route::get('manage/service/aidDispatch/edit','Manage\Service\AidDispatch@edit');

        Route::post('manage/service/aidDispatch/edit','Manage\Service\AidDispatch@doEdit');

        Route::post('manage/service/aidDispatch/pass','Manage\Service\AidDispatch@doPass');

        Route::post('manage/service/aidDispatch/reject','Manage\Service\AidDispatch@doReject');

        Route::post('manage/service/aidDispatch/search','Manage\Service\AidDispatch@search');

        Route::post('manage/service/aidDispatchList/{page?}','Manage\Service\AidDispatch@index');

        //车辆管理
        Route::get('manage/system/vehicle/show','Manage\System\Vehicles@show');

        Route::get('manage/system/vehicle/add','Manage\System\Vehicles@create');

        Route::post('manage/system/vehicle/add','Manage\System\Vehicles@store');

        Route::get('manage/system/vehicle/edit','Manage\System\Vehicles@edit');

        Route::post('manage/system/vehicle/edit','Manage\System\Vehicles@doEdit');

        Route::get('manage/system/vehicle/delete','Manage\System\Vehicles@doDelete');

        Route::post('manage/system/vehicle/search','Manage\System\Vehicles@search');

        Route::post('manage/system/vehicleList/{page?}','Manage\System\Vehicles@index');

        //归档管理
        Route::get('manage/system/archived/add','Manage\System\Archived@create');

        Route::post('manage/system/archived/add','Manage\System\Archived@store');

        Route::get('manage/system/archived/show','Manage\System\Archived@archivedList');

        Route::get('manage/system/archived/delete','Manage\System\Archived@doDelete');

        Route::post('manage/system/archivedList/{page?}','Manage\System\Archived@index');

        //日志管理
        Route::get('manage/system/log/show','Manage\System\Log@show');

        Route::post('manage/system/log/search','Manage\System\Log@search');

        Route::post('manage/system/logList/{page?}','Manage\System\Log@index');

        //备份管理
        Route::get('manage/system/backup/add','Manage\System\Backup@create');

        Route::post('manage/system/backup/add','Manage\System\Backup@store');

        Route::get('manage/system/backup/delete','Manage\System\Backup@doDelete');

        Route::post('manage/system/backupList/{page?}','Manage\System\Backup@index');

    });

    Route::get('manage', 'Manage\Login@index');

    Route::get('manage/logout', 'Manage\Login@logout');

    Route::post('manage/login',['as'=>'loginUrl', 'uses'=>'Manage\Login@doLogin']);

    //微信路由
    Route::get('wechat/login', 'Wechat\Index@login');

    Route::post('wechat/login', 'Wechat\Index@doLogin');

    //律师服务
    Route::get('wechat/lawyer', 'Wechat\Lawyer@index');

    Route::get('wechat/lawyer/detail/{key}', 'Wechat\Lawyer@lawyerDetail');

    Route::get('wechat/lawyerList', 'Wechat\Lawyer@lawyerList');

    Route::post('wechat/lawyerList', 'Wechat\Lawyer@lawyerSearch');

    Route::get('wechat/lawyerSearch', 'Wechat\Lawyer@lawyerSearch');

    Route::post('wechat/lawyerSearch', 'Wechat\Lawyer@lawyerDoSearch');

    Route::post('wechat/scrollLoad', 'Wechat\Lawyer@scrollLoadLawyer');

    //司法鉴定查询
    Route::get('wechat/expertiseList', 'Wechat\Expertise@index');

    Route::post('wechat/reason/expertise', 'Wechat\Expertise@reason');

    //法律援助查询

    Route::get('wechat/aid/list', 'Wechat\Aid@index');

    Route::post('wechat/reason/apply', 'Wechat\Aid@applyReason');

    Route::post('wechat/reason/dispatch', 'Wechat\Aid@DispatchReason');



    Route::get('wechat/lawyerOffice/detail/{key}', 'Wechat\Lawyer@lawyerOfficeDetail');

    Route::get('wechat/lawyerOfficeList', 'Wechat\Lawyer@lawyerOfficeList');

    Route::get('wechat/lawyerOfficeSearch', 'Wechat\Lawyer@lawyerOfficeSearch');

    Route::post('wechat/lawyerOfficeSearch', 'Wechat\Lawyer@lawyerOfficeDoSearch');

    Route::get('wechat/lawyerOfficeArea/{area_id}', 'Wechat\Lawyer@lawyerOfficeArea');

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
