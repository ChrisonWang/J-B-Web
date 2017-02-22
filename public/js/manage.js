/**
 * Created by Administrator on 2017/2/20.
 */
//标签管理
function tagMethod(t){
    var tagKey = t.data('key');
    var method = t.data('method');
    var url = '/manage/cms/tags/'+method;
    if(method == 'delete'){
        var c = confirm("确认删除标签："+ t.data('title')+"？");
        if(c != true){
            return false;
        }
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "GET",
        url: url,
        data: 'tag_key='+tagKey,
        success: function(re){
            if(re.status == 'succ'){
                if(method == 'delete'){
                    alert('删除成功！！！');
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed'){
                alert(re.res);
            }
        }
    });
}

function editTag(){
    var url = '/manage/cms/tags/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#tagEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#tagEditNotice'));
            }
        }
    });
}

function addTag(){
    var url = '/manage/cms/tags/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#tagAddForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addTagNotice'));
            }
        }
    });
}

//部门分类
function typeMethod(t){
    var typeKey = t.data('key');
    var method = t.data('method');
    var url = '/manage/cms/department/type/'+method;
    if(method == 'delete'){
        var c = confirm("确认删除分类："+ t.data('title')+"？");
        if(c != true){
            return false;
        }
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "GET",
        url: url,
        data: 'type_key='+typeKey,
        success: function(re){
            if(re.status == 'succ'){
                if(method == 'delete'){
                    alert('删除成功！！！');
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed'){
                alert(re.res);
            }
        }
    });
}

function editDepartmentType(){
    var url = '/manage/cms/department/type/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#typeEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#typeEditNotice'));
            }
        }
    });
}

function addDepartmentType(){
    var url = '/manage/cms/department/type/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#typeAddForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addTypeNotice'));
            }
        }
    });
}

//部门简介
function departmentMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/cms/department/'+method;
    if(method == 'delete'){
        var c = confirm("确认删除简介："+ t.data('title')+"？");
        if(c != true){
            return false;
        }
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "GET",
        url: url,
        data: 'key='+key,
        success: function(re){
            if(re.status == 'succ'){
                if(method == 'delete'){
                    alert('删除成功！！！');
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed'){
                alert(re.res);
            }
        }
    });
}

function editDepartment(){
    var url = '/manage/cms/department/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#departmentEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#departmentEditNotice'));
            }
        }
    });
}

function addDepartment(){
    var url = '/manage/cms/department/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#departmentAddForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addDepartmentNotice'));
            }
        }
    });
}

//领导简介
function leaderMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/cms/leader/'+method;
    if(method == 'delete'){
        var c = confirm("确认删除简介："+ t.data('title')+"？");
        if(c != true){
            return false;
        }
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "GET",
        url: url,
        data: 'key='+key,
        success: function(re){
            if(re.status == 'succ'){
                if(method == 'delete'){
                    alert('删除成功！！！');
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed'){
                alert(re.res);
            }
        }
    });
}

function editLeader(){
    var url = '/manage/cms/leader/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#leaderEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#leaderEditNotice'));
            }
        }
    });
}

function addLeader(){
    var url = '/manage/cms/leader/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#leaderAddForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addLeaderNotice'));
            }
        }
    });
}

//视频管理
function videoMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/cms/video/'+method;
    if(method == 'delete'){
        var c = confirm("确认删除视频："+ t.data('title')+"？");
        if(c != true){
            return false;
        }
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "GET",
        url: url,
        data: 'key='+key,
        success: function(re){
            if(re.status == 'succ'){
                if(method == 'delete'){
                    alert('删除成功！！！');
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed'){
                alert(re.res);
            }
        }
    });
}

function editVideo(){
    var url = '/manage/cms/video/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#videoEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#videoEditNotice'));
            }
        }
    });
}

function addVideo(){
    var url = '/manage/cms/video/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#videoAddForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addVideoNotice'));
            }
        }
    });
}

//推荐链接
function recommendMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/cms/recommend/'+method;
    if(method == 'delete'){
        var c = confirm("确认删除链接："+ t.data('title')+"？");
        if(c != true){
            return false;
        }
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "GET",
        url: url,
        data: 'key='+key,
        success: function(re){
            if(re.status == 'succ'){
                if(method == 'delete'){
                    alert('删除成功！！！');
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed'){
                alert(re.res);
            }
        }
    });
}

function editRecommend(){
    var url = '/manage/cms/recommend/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#recommendEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#recommendEditNotice'));
            }
        }
    });
}

function addRecommend(){
    var url = '/manage/cms/recommend/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#recommendAddForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addRecommendNotice'));
            }
        }
    });
}

//司法局简介
function introMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/cms/intro/'+method;
    if(method == 'delete'){
        var c = confirm("确认删除："+ t.data('title')+"？");
        if(c != true){
            return false;
        }
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "GET",
        url: url,
        data: 'key='+key,
        success: function(re){
            if(re.status == 'succ'){
                if(method == 'delete'){
                    alert('删除成功！！！');
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed'){
                alert(re.res);
            }
        }
    });
}

function editIntro(){
    var url = '/manage/cms/intro/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#introEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#introEditNotice'));
            }
        }
    });
}

function addIntro(){
    var url = '/manage/cms/intro/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#introAddForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addIntroNotice'));
            }
        }
    });
}
