/**
 * Created by Administrator on 2017/2/20.
 */
function delRow(t){
    if($("#menu-nodes tr").length <= 1){
        $("#add-row-notice").removeClass("hidden");
        $("#add-row-notice").text("至少添加一条信息");
        return
    }
    t.parents("tr").remove();
}

function delRowChannel(t){
    t.parents("tr").remove();
}

function addRow(){
    var content = $("#node-row tbody").html()
    $("#menu-nodes").append(content);
    $("#add-row-notice").removeClass("hidden");
    $("#add-row-notice").text("请勿添加重复的信息，系统会自动删除");
    return
}


//标签管理
function tagMethod(t){
    var tagKey = t.data('key');
    var method = t.data('method');
    var url = '/manage/cms/tags/'+method;
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
        data: 'key='+tagKey,
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
        data: 'key='+typeKey,
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
    $('#leaderEditForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url,
        data: $('#leaderEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
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
    $('#leaderAddForm').ajaxSubmit({
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
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addLeaderNotice'));
            }
        }
    });
}

function upload_img(t){
    if( typeof(FileReader) != "undefined" ){
        var image_holder = $("#image-holder");
        var image_thumbnail = $("#image-thumbnail");
        image_holder.empty();
        image_thumbnail.addClass("hidden");
        var reader = new FileReader();
        reader.onload = function(e){
            $("<img />", {
                "src": e.target.result,
                "class": "img-thumbnail img-responsive"
            }).appendTo(image_holder);
        };

        image_thumbnail.removeClass("hidden");
        image_holder.show();
        reader.readAsDataURL(t[0].files[0]);
        $('input[name="have_photo"]').val('no');
    }
    else {
        alert("您的浏览器不支持控件，无法预览图片！");
    }
}

//视频管理
function videoMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var archived = t.data('archived');
    var archived_key = t.data('archived_key');
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
        data: 'key='+ key +'&archived=' + archived +'&archived_key=' + archived_key,
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
    $('#videoEditForm').ajaxSubmit({
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
    $('#videoAddForm').ajaxSubmit({
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
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addIntroNotice'));
            }
        }
    });
}

//一级友情链接管理
function flinkImgMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/cms/flinkImg/'+method;
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

function editFlinkImg(){
    var url = '/manage/cms/flinkImg/edit';
    $('#flinkImgEditForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#flinkImgEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#flinkImgEditNotice'));
            }
        }
    });
}

function addFlinkImg(){
    var url = '/manage/cms/flinkImg/add';
    $('#flinkImgAddForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#flinkImgAddForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addFlinkImgNotice'));
            }
        }
    });
}

//二级友情链接管理
function flinkMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/cms/flinks/'+method;
    if(method == 'delete'){
        var c = confirm("确认删除："+ t.data('title')+"？该分类下的子链接都会被删除！");
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

function editFlinks(){
    $('#add-row-notice').addClass('hidden');
    var url = '/manage/cms/flinks/edit';
    var sub_input = $("#menu-nodes").find("tr");
    var sub_list = new Array();
    var v = true;
    sub_list['add'] = new Array();
    sub_list['edit'] = new Array();
    sub_input.each(function(i,tr){
        var k = $(this).find("input[name='sub_title[]']").data('key');
        var t = $(this).find("input[name='sub_title[]']").val();
        var l = $(this).find("input[name='sub_link[]']").val();
        if( t =='' || l == '' ){
            $('#add-row-notice').removeClass('hidden');
            $('#add-row-notice').text('请填写完整的名称和链接');
            v = false;
            return false
        }
        else {
            if(typeof(k) == 'undefined'){
                var sub = {key:'', sub_title:t, sub_link: l, method: 'add'};
            }
            else{
                var sub = {key:k, sub_title:t, sub_link: l, method: 'edit'};
            }
            sub_list[i] = sub;
        }
    });
    if(v == true){
        var data = {key: $("input[name='key']").val(), title: $("input[name='title']").val(), sub: JSON.stringify(sub_list)};
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: url,
            data: data,
            success: function(re){
                if(re.status == 'succ'){
                    alert("修改成功！！！");
                    ajaxResult(re);
                }
                else if(re.status == 'failed') {
                    ajaxResult(re,$('#flinksEditNotice'));
                }
            }
        });
    }
    return;
}

function addFlinks(){
    var url = '/manage/cms/flinks/add';
    var sub_input = $("#menu-nodes").find("tr");
    var sub_list = new Array();
    var v = true;
    sub_input.each(function(i,tr){
        var t = $(this).find("input[name='sub_title[]']").val();
        var l = $(this).find("input[name='sub_link[]']").val();
        if( t =='' || l == '' ){
            $('#add-row-notice').removeClass('hidden');
            $('#add-row-notice').text('请填写完整的名称和链接');
            v = false;
            return;
        }
        else {
            var sub = {key:'', sub_title:t, sub_link: l};
            sub_list[i] = sub;
        }
    });
    if(v == true){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: url,
            data: $('#flinksAddForm').serialize(),
            success: function(re){
                if(re.status == 'succ'){
                    alert("添加成功！！！");
                    ajaxResult(re);
                }
                else if(re.status == 'failed') {
                    ajaxResult(re,$('#addFlinksNotice'));
                }
            }
        });
    }
    return;
}

//科室管理
function officeMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/user/office/'+method;
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

function editOffice(){
    var url = '/manage/user/office/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#officeEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#officeEditNotice'));
            }
        }
    });
}

function addOffice(){
    var url = '/manage/user/office/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#officeAddForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addOfficeNotice'));
            }
        }
    });
}

//功能点管理
function nodeMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/user/nodes/'+method;
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

function editNode(){
    var url = '/manage/user/nodes/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#nodeEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#nodeEditNotice'));
            }
        }
    });
}

function addNode(){
    var url = '/manage/user/nodes/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#nodeAddForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addNodeNotice'));
            }
        }
    });
}

//菜单管理
function menuMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/user/menus/'+method;
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

function editMenu(){
    var url = '/manage/user/menus/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#menuEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#menuEditNotice'));
            }
        }
    });
}

function addMenu(){
    var url = '/manage/user/menus/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#menuAddForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addMenuNotice'));
            }
        }
    });
}

//角色管理
function rolesMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/user/roles/'+method;
    if(method == 'delete'){
        var c = confirm("确认删除角色："+ t.data('title')+"？");
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

function editRoles(){
    var url = '/manage/user/roles/edit';
    var sub_input = $("#menu-nodes").find("tr");
    var p_list = new Array();
    var v = true;
    sub_input.each(function(i,tr){
        var menus = $(this).find("select[name='menus'] option:selected").val();
        var nodes = $(this).find("select[name='nodes'] option:selected").val();
        var permission = $(this).find("select[name='permission'] option:selected").val();
        if( menus == '' || nodes == '' || permission == ''){
            $('#add-row-notice').removeClass('hidden');
            $('#add-row-notice').text('请填写完整的频道名称');
            v = false;
            return false
        }
        var p = {menus:menus, nodes: nodes, permission: permission};
        p_list[i] = p;
    });
    if(v == true){
        var data = {
            key: $("input[name='key']").val(),
            title: $("input[name='title']").val(),
            sub: JSON.stringify(p_list)};
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: url,
            data: data,
            success: function(re){
                if(re.status == 'succ'){
                    alert("修改成功！！！");
                    ajaxResult(re);
                }
                else if(re.status == 'failed') {
                    ajaxResult(re,$('#rolesEditNotice'));
                }
            }
        });
    }
}

function addRoles(){
    var url = '/manage/user/roles/add';
    var sub_input = $("#menu-nodes").find("tr");
    var p_list = new Array();
    var v = true;
    sub_input.each(function(i,tr){
        var menus = $(this).find("select[name='menus'] option:selected").val();
        var nodes = $(this).find("select[name='nodes'] option:selected").val();
        var permission = $(this).find("select[name='permission'] option:selected").val();
        if( menus == '' || nodes == '' || permission == ''){
            $('#add-row-notice').removeClass('hidden');
            $('#add-row-notice').text('请填写完整的频道名称');
            v = false;
            return false
        }
        var p = {menus:menus, nodes: nodes, permission: permission};
        p_list[i] = p;
    });
    if(v == true){
        var data = {
            title: $("input[name='title']").val(),
            sub: JSON.stringify(p_list)};
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: url,
            data: data,
            success: function(re){
                if(re.status == 'succ'){
                    alert("添加成功！！！");
                    ajaxResult(re);
                }
                else if(re.status == 'failed') {
                    ajaxResult(re,$('#addRolesNotice'));
                }
            }
        });
    }
}

//频道管理
function channelMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/cms/channel/'+method;
    if(method == 'delete'){
        var c = confirm("确认删除："+ t.data('title')+"？该频道下的子频道也会被删除！");
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

function editChannel(){
    $('#add-row-notice').addClass('hidden');
    var url = '/manage/cms/channel/edit';
    var sub_input = $("#menu-nodes").find("tr");
    var sub_list = new Array();
    var v = true;
    sub_list['add'] = new Array();
    sub_list['edit'] = new Array();
    sub_input.each(function(i,tr){
        var k = $(this).find("input[name='sub-channel_title']").data('key');
        var title = $(this).find("input[name='sub-channel_title']").val();
        var sort = (typeof($(this).find("input[name='sub-sort']").val())=='undefined' || $(this).find("input[name='sub-sort']").val()=='') ? 0 : $(this).find("input[name='sub-sort']").val();
        var zwgk = ($(this).find("input[name='sub-zwgk']").is(':checked')) ==true ? 'yes' : 'no';
        var wsbs = ($(this).find("input[name='sub-wsbs']").is(':checked')) ==true ? 'yes' : 'no';
        if( title =='' ){
            $('#add-row-notice').removeClass('hidden');
            $('#add-row-notice').text('请填写完整的名称和链接');
            v = false;
            return false
        }
        else {
            if(typeof(k) == 'undefined'){
                var sub = {key:'', sub_title:title, sub_zwgk: zwgk, sub_wsbs: wsbs, sub_sort: sort, method: 'add'};
            }
            else{
                var sub = {key:k, sub_title:title, sub_zwgk: zwgk, sub_wsbs: wsbs, sub_sort: sort, method: 'edit'};
            }
            sub_list[i] = sub;
        }
    });
    if(v == true){
        var data = {
            key: $("input[name='key']").val(),
            channel_title: $("input[name='channel_title']").val(),
            sort: $("input[name='sort']").val(),
            is_recommend: ($("input[name='is_recommend']").is(':checked') == true) ?  'yes' : 'no',
            form_download: ($("input[name='form_download']").is(':checked') == true) ? 'yes' : 'no',
            zwgk: ($("input[name='zwgk']").is(':checked') == true) ? 'yes' : 'no',
            wsbs: ($("input[name='wsbs']").is(':checked') == true) ? 'yes' : 'no',
            sub: JSON.stringify(sub_list)
        };
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: url,
            data: data,
            success: function(re){
                if(re.status == 'succ'){
                    alert("修改成功！！！");
                    ajaxResult(re);
                }
                else if(re.status == 'failed') {
                    ajaxResult(re,$('#channelEditNotice'));
                }
            }
        });
    }
    return;
}

function addChannel(){
    $('#add-row-notice').addClass('hidden');
    var url = '/manage/cms/channel/add';
    var sub_input = $("#menu-nodes").find("tr");
    var sub_list = new Array();
    var v = true;
    sub_input.each(function(i,tr){
        var title = $(this).find("input[name='sub-channel_title']").val();
        var sort = (typeof($(this).find("input[name='sub-sort']").val())=='undefined' || $(this).find("input[name='sub-sort']").val()=='') ? 0 : $(this).find("input[name='sub-sort']").val();
        var zwgk = ($(this).find("input[name='sub-zwgk']").is(':checked')) ==true ? 'yes' : 'no';
        var wsbs = ($(this).find("input[name='sub-wsbs']").is(':checked')) ==true ? 'yes' : 'no';
        if( title ==''){
            return true
            /*$('#add-row-notice').removeClass('hidden');
            $('#add-row-notice').text('请填写完整的频道名称名称');
            v = false;
            return false*/
        }
        var sub = {sub_title:title, sub_zwgk: zwgk, sub_wsbs: wsbs, sub_sort: sort};
        sub_list[i] = sub;
    });
    if(v == true){
        var data = {
            channel_title: $("input[name='channel_title']").val(),
            sort: $("input[name='sort']").val(),
            is_recommend: ($("input[name='is_recommend']").is(':checked') == true) ?  'yes' : 'no',
            form_download: ($("input[name='form_download']").is(':checked') == true) ? 'yes' : 'no',
            zwgk: ($("input[name='zwgk']").is(':checked') == true) ? 'yes' : 'no',
            wsbs: ($("input[name='wsbs']").is(':checked') == true) ? 'yes' : 'no',
            sub: JSON.stringify(sub_list)};
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: url,
            data: data,
            success: function(re){
                if(re.status == 'succ'){
                    alert("添加成功！！！");
                    ajaxResult(re);
                }
                else if(re.status == 'failed') {
                    ajaxResult(re,$('#addChannelNotice'));
                }
            }
        });
    }
    return;
}

//表单管理
function formsMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/cms/forms/'+method;
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

function editForms(){
    var url = '/manage/cms/forms/edit';
    $('#formsEditForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#formsEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#formsEditNotice'));
            }
        }
    });
}

function addForms(){
    var url = '/manage/cms/forms/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#formsAddForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addFormsNotice'));
            }
        }
    });
}

function changeFile(){
    $('#change_box').addClass("hidden");
    $('#file_box').removeClass("hidden");
}

//文章管理
function articleMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var archived = t.data('archived');
    var archived_key = t.data('archived_key');
    var url = '/manage/cms/article/'+method;
    if(method == 'delete'){
        var c = confirm("确认删除文章：《"+ t.data('title')+"》？");
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
        data: 'key='+ key +'&archived=' + archived +'&archived_key=' + archived_key,
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

function editArticle(){
    var url = '/manage/cms/article/edit';
    $('#articleEditForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#articleEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#articleEditNotice'));
            }
        }
    });
}

function addArticle(){
    var url = '/manage/cms/article/add';
    $('#articleAddForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#articleAddForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addArticleNotice'));
            }
        }
    });
}

function ajax_upload_file(t, type){
    var url = '/manage/cms/article/upload';
    var form = t.parents('form');
    form.ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(re){
            if(re.status == 'succ'){
                t.parent("tr").find("input[name='file-name']").val(re.filenames);
                if(type == 'add'){
                    $("#articleAddForm").append('<input type="hidden" value='+re.files+' name="files[]"><input type="hidden" value='+re.filenames+' name="file-names[]">');

                }
                else if(type == 'edit'){
                    $("#articleEditForm").append('<input type="hidden" value='+re.files+' name="files[]"><input type="hidden" value='+re.filenames+' name="file-names[]">');
                }
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#add-row-notice'));
            }
        }
    });
}

function getSubChannel(c,sub){
    sub.html('<option value="none" selected>暂无分类</option>');
    var url = '/manage/cms/article/get_sub_channel';
    var channel_key = c.find("option:selected").val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: 'channel_key='+channel_key,
        success:function(re){
            if(re.status == 'succ'){
                var list = jQuery.parseJSON(re.res);
                var options = '';
                $.each(list, function(i,sub){
                    options += '<option value="'+sub.channel_key+'">'+sub.channel_title+'</option>';
                });
                sub.html(options);
            }
            else if(re.status == 'failed'){
                return;
            }
            return;
        }
    });
}

function getSubChannel_S(c,sub){
    sub.html('<option value="none" selected>不限二级频道</option>');
    var url = '/manage/cms/article/get_sub_channel';
    var channel_key = c.find("option:selected").val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: 'channel_key='+channel_key,
        success:function(re){
            if(re.status == 'succ'){
                var list = jQuery.parseJSON(re.res);
                var options = '<option value="none" selected>不限二级分类</option>';
                $.each(list, function(i,sub){
                    options += '<option value="'+sub.channel_key+'">'+sub.channel_title+'</option>';
                });
                sub.html(options);
            }
            else if(re.status == 'failed'){
                return;
            }
            return;
        }
    });
}

function getSubNode(c){
    var sub = c.parent('td').parent('tr').find("select[name='nodes']");
    sub.html('<option value="none" selected>暂无功能点</option>');
    var url = '/manage/cms/roles/get_sub_node';
    var menu_key = c.find("option:selected").val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: 'menu_key='+menu_key,
        success:function(re){
            if(re.status == 'succ'){
                var list = jQuery.parseJSON(re.res);
                var options = '';
                $.each(list, function(i,sub){
                    options += '<option value="'+sub.node_key+'">'+sub.node_name+'</option>';
                });
                sub.html(options);
            }
            else if(re.status == 'failed'){
                return;
            }
            return;
        }
    });
}

//异步搜索标签
function searchTags(t){
    var url = '/manage/cms/article/searchTags';
    var keywords = t.val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: {keywords: keywords},
        success: function(re){
            if(re.status=='succ'){
                var str = "";
                $.each(re.res, function(i,v){
                    str += '<li style="list-style: none" data-key="'+ v.key +'"  onclick="liOnclick($(this))">'
                        + v.name +
                        '<input type="hidden" name="tags[]" />' +
                        '</li>'
                });
                $(".box_l").html(str);
            }
            else if(re.status=='failed'){
                $(".box_l").html('无结果！');
            }
        }
    });
}

//用户管理
function userMethod(t){
    var key = t.data('key');
    var type = t.data('type');
    var method = t.data('method');
    var url = '/manage/user/users/'+method;
    if(method == 'delete'){
        var c = confirm("确认删除用户："+ t.data('title')+"？");
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
        data: {key: key, type: type},
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

function editUser(){
    var url = '/manage/user/users/edit';
    $('#userEditForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#UserEditForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#userEditNotice'));
            }
        }
    });
}

function addUser(){
    var url = '/manage/user/users/add';
    $('#userAddForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#userAddForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addUserNotice'));
            }
        }
    });
}

function checkBoxDisabled(cb){
    if($("#wsbs").is(':checked') && $("#zwgk").is(':checked')){
        $("input[name='sub-zwgk']").attr('disabled',false);
        $("input[name='sub-wsbs']").attr('disabled',false);
    }
    else {
        var c_name = cb.attr('name');
        if(c_name == 'wsbs'){
            var list = $("input[name='sub-zwgk']");
            var r_list = $("input[name='sub-wsbs']");
        }
        else if(c_name == 'zwgk'){
            var list = $("input[name='sub-wsbs']");
            var r_list = $("input[name='sub-zwgk']");
        }
        $.each(list,function(i,input){
            if(cb.is(':checked')){
                $(r_list[i]).removeAttr('disabled');
            }
            else {
                $(this).removeAttr('checked');
                $(r_list[i]).attr('disabled','disabled');
            }
            $(this).removeAttr('checked');
            $(this).attr('disabled','disabled');
        });
    }
    return;
}

function searchUser(t, c){
    var data = t.parents('form').serialize()
    var url = '/manage/user/users/searchUser'
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: data,
        success: function(re){
            if(re.status == 'succ'){
                c.html(re.res);
            }
            else if(re.status == 'failed'){
                c.html('<h4 class="text-center">未能检索到信息！</h4>');
            }
        }
    });
    return;
}

function search_list(t, c){
    var data = t.parents('form').serialize()
    var url = '/manage/searchList'
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: data,
        success: function(re){
            if(re.status == 'succ'){
                c.html(re.res);
            }
            else if(re.status == 'failed'){
                c.html('<h4 class="text-center">未能检索到信息！</h4>');
            }
        }
    });
    return;
}

function list_page($type, $page){
    var url = '/manage/cms/'+$type+'List/'+$page;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        success: function(re){
            ajaxResult(re);
        }
    });
}

function list_page_service($type, $page){
    var url = '/manage/service/'+$type+'List/'+$page;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        success: function(re){
            ajaxResult(re);
        }
    });
}

function list_page_system($type, $page){
    var url = '/manage/system/'+$type+'List/'+$page;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        success: function(re){
            ajaxResult(re);
        }
    });
}

/**
 * 网上办事
 */
//区域管理
function areaMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/service/area/'+method;
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

function editArea(){
    var url = '/manage/service/area/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#editAreaForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#editAreaNotice'));
            }
        }
    });
}

function addArea(){
    var url = '/manage/service/area/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#addAreaForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addAreaNotice'));
            }
        }
    });
}

//律师管理
function lawyerMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/service/lawyer/'+method;
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

function editLawyer(){
    var url = '/manage/service/lawyer/edit';
    $('#editLawyerForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#editLawyerForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#editLawyerNotice'));
            }
        }
    });
}

function addLawyer(){
    var url = '/manage/service/lawyer/add';
    $('#addLawyerForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#addLawyerForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addLawyerNotice'));
            }
        }
    });
}

function search_lawyer(t, c){
    var data = t.parents('form').serialize()
    var url = '/manage/service/lawyer/search'
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: data,
        success: function(re){
            if(re.status == 'succ'){
                c.html(re.res);
            }
            else if(re.status == 'failed'){
                c.html('<h4 class="text-center">未能检索到信息！</h4>');
            }
        }
    });
    return;
}

//事务所管理
function lawyerOfficeMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/service/lawyerOffice/'+method;
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

function editLawyerOffice(){
    var url = '/manage/service/lawyerOffice/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#editLawyerOfficeForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#editLawyerOfficeNotice'));
            }
        }
    });
}

function addLawyerOffice(){
    var url = '/manage/service/lawyerOffice/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#addLawyerOfficeForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addLawyerOfficeNotice'));
            }
        }
    });
}

function search_lawyerOffice(t, c){
    var data = t.parents('form').serialize()
    var url = '/manage/service/lawyerOffice/search'
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: data,
        success: function(re){
            if(re.status == 'succ'){
                c.html(re.res);
            }
            else if(re.status == 'failed'){
                c.html('<h4 class="text-center">未能检索到信息！</h4>');
            }
        }
    });
    return;
}

//短信发送管理
function messageSendMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var archived = t.data('archived');
    var archived_key = t.data('archived_key');
    var url = '/manage/service/messageSend/'+method;
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
        data: 'key='+ key +'&archived=' + archived +'&archived_key=' + archived_key,
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

function editMessageSend(){
    var url = '/manage/service/messageSend/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#editMessageSendForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#editMessageSendNotice'));
            }
        }
    });
}

function addMessageSend(){
    var url = '/manage/service/messageSend/add';
    //参数
    var temp_code = $("#temp_code option:selected").val();
    var send_date = $("#send_date").val();
    var receiver_type = $("#receiver_type").val();
    var office_list = $(".box_r li");
    var member_list = $(".box_r_2 li");
    if(office_list!='' && typeof(office_list)!='undefined' ){
        var _office_list = new Array();
        $.each(office_list,function(i, o){
            _office_list[i] = $(o).data('key');
        });
        office_list = JSON.stringify(_office_list);
    }
    if(member_list!='' && typeof(member_list)!='undefined' ){
        var _member_list = new Array();
        $.each(member_list,function(i, o){
            _member_list[i] = $(o).data('phone');
        });
        member_list = JSON.stringify(_member_list);
    }
    //发送给后台的
    var data = {
        temp_code: temp_code,
        send_date: send_date,
        receiver_type: receiver_type,
        office_list: office_list,
        member_list: member_list
    };
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: data,
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addMessageSendNotice'));
            }
        }
    });
}

function getTempContent(t){
    var url = '/manage/service/messageSend/getContent'
    var temp_code = t.find("option:selected").val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "GET",
        url: url,
        data: 'temp_code='+temp_code,
        success: function(re){
            if(re.status == 'succ'){
                $('#temp_content').text(re.res);
            }
        }
    });
}

//隐藏与现实科室
function switch_hidden(){
    var type = $("#receiver_type option:selected").val();
    var url = "/manage/service/messageSend/loadMembers";
    if(type == 'member'){
        $(".office_switch").hide();
        $(".box_l_2").html('');
        $(".box_r_2").html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: url,
            data: {type: type},
            success: function(re){
                if(re.status=='succ'){
                    var str = "";
                    $.each(re.res, function(i,v){
                        str += '<li data-key="'+ v.key +'" data-phone="'+ v.cell_phone +'" style="list-style: none">'+ v.name + v.login_name +' -> '+ v.cell_phone +'' +
                            '<input type="hidden" name="member_list" value=""/></li>'
                    });
                    $(".box_l_2").html(str);
                }
                else if(re.status=='failed'){
                    $(".box_l_2").html('无结果！');
                }
            }
        });
        $(".member_switch").show();
        return false;
    }
    if(type == 'manager'){
        $(".office_switch").show();
        $(".box_l_2").html('');
        $(".box_r_2").html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: url,
            data: {type: type},
            success: function(re){
                if(re.status=='succ'){
                    var str = "";
                    $.each(re.res, function(i,v){
                        str += '<li data-key="'+ v.key +'" style="list-style: none">'+ v.name +'->'+ v.cell_phone +'' +
                            '<input type="hidden" name="member_list" value=""/></li>'
                    });
                    $(".box_l_2").html(str);
                }
                else if(re.status=='failed'){
                    $(".box_l_2").html('无结果！');
                }
            }
        });
        $(".member_switch").show();
        return false;
    }
    if(type == 'certificate'){
        $(".office_switch").hide();
        $(".box_l_2").html('');
        $(".box_r_2").html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            type: "POST",
            url: url,
            data: {type: type},
            success: function(re){
                if(re.status=='succ'){
                    var str = "";
                    $.each(re.res, function(i,v){
                        str += '<li data-key="'+ v.key +'" data-phone="'+ v.cell_phone +'" style="list-style: none">'+ v.name +'->'+ v.cell_phone +'' +
                            '<input type="hidden" name="member_list" value=""/></li>'
                    });
                    $(".box_l_2").html(str);
                }
                else if(re.status=='failed'){
                    $(".box_l_2").html('无结果！');
                }
            }
        });
        $(".member_switch").show();
        return false;
    }
    else {
        $(".office_switch").hide();
        $(".member_switch").hide();
    }
}

//异步搜索科室
function searchOffice(t){
    var url = '/manage/service/messageSend/searchOffice';
    var keywords = t.val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: {keywords: keywords},
        success: function(re){
            if(re.status=='succ'){
                var str = "";
                $.each(re.res, function(i,v){
                    str += '<li data-key="'+ v.key +'" style="list-style: none">'+ v.name +'' +
                        '<input type="hidden" name="office_list" value=""/></li>'
                });
                $(".box_l").html(str);
            }
            else if(re.status=='failed'){
                $(".box_l").html('无结果！');
            }
        }
    });
}

//异步搜索用户
function searchMembers(t){
    var url = '/manage/service/messageSend/searchMembers';
    var type = $("#receiver_type option:selected").val();
    var keywords = t.val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: {keywords: keywords, type:type},
        success: function(re){
            if(re.status=='succ'){
                var str = "";
                $.each(re.res, function(i,v){
                    str += '<li data-key="'+ v.key +'" data-phone="'+ v.cell_phone +'" style="list-style: none">'+ v.name +' -> '+ v.cell_phone +'' +
                        '<input type="hidden" name="member_list" value=""/></li>'
                });
                $(".box_l_2").html(str);
            }
            else if(re.status=='failed'){
                $(".box_l_2").html('无结果！');
            }
        }
    });
}

//短信模板管理
function messageTmpMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/service/messageTmp/'+method;
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

function editMessageTmp(){
    var url = '/manage/service/messageTmp/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#editMessageTmpForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#editMessageTmpNotice'));
            }
        }
    });
}

function addMessageTmp(){
    var url = '/manage/service/messageTmp/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#addMessageTmpForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addMessageTmpNotice'));
            }
        }
    });
}

//证书持有人管理
function certificateMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/service/certificate/'+method;
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

function editCertificate(){
    var url = '/manage/service/certificate/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#editCertificateForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#editCertificateNotice'));
            }
        }
    });
}

function addCertificate(){
    var url = '/manage/service/certificate/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#addCertificateForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addCertificateNotice'));
            }
        }
    });
}

function search_certificate(t, c){
    var data = t.parents('form').serialize()
    var url = '/manage/service/certificate/search'
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: data,
        success: function(re){
            if(re.status == 'succ'){
                c.html(re.res);
            }
            else if(re.status == 'failed'){
                c.html('<h4 class="text-center">未能检索到信息！</h4>');
            }
        }
    });
    return;
}

function batchImport(){
    var url = '/manage/service/certificate/import';
    $('#import_modal').modal('show');
    $('#batch-form').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url,
        data: $('#batch-form').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                $("#import_notic").text(re.res);
            }
            else if(re.status == 'failed') {
                $("#import_notic").text(re.res);
            }
        }
    });
}

function sendMessage(){
    var c = confirm("确认发送?");
    if(c != true){
        return false;
    }
    var url = '/manage/service/certificate/send';
    var data = $("#send_form").serialize();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url,
        data: data,
        success: function(re){
            if(re.status == 'succ'){
                alert("成功添加到短信队列！");
                $('#sendMessage_modal').modal('hide');
            }
            else if(re.status == 'failed') {
                alert("发送失败！"+re.res);
            }
        }
    });
}

function loadContentC(){
    $('#import_modal').modal('hide');
    var url ='/manage/serviceLoadContent';
    var container = $("#page-wrapper");
    //获取模板
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: 'node_id=certificateMng',
        success: function(re){
            ajaxResult(re);
        }
    });
}

//司法鉴定类型管理
function expertiseTypeMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/service/expertiseType/'+method;
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

function editExpertiseType(){
    var url = '/manage/service/expertiseType/edit';
    $('#editExpertiseTypeForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#editExpertiseTypeForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#editExpertiseTypeNotice'));
            }
        }
    });
}

function addExpertiseType(){
    var url = '/manage/service/expertiseType/add';
    $('#addExpertiseTypeForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#addExpertiseTypeForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addExpertiseTypeNotice'));
            }
        }
    });
}

//司法鉴定申请管理
function expertiseApplyMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var archived = t.data('archived');
    var archived_key = t.data('archived_key');
    var url = '/manage/service/expertiseApply/'+method;
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
        data: 'key='+ key +'&archived=' + archived +'&archived_key=' + archived_key,
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

function editExpertiseApply(t){
    var method = t.data('method');
    if(method == 'pass'){
        var c = confirm("确认通过这条司法鉴定请求？");
        if(c != true){
            return false;
        }
    }
    if(method == 'reject'){
        var c = confirm("确认驳回这条司法鉴定请求？");
        if(c != true){
            return false;
        }
    }
    var url = '/manage/service/expertiseApply/'+method;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#editExpertiseApplyForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("操作成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#editExpertiseApplyNotice'));
            }
        }
    });
}

//征求意见管理
function suggestionsMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var archived = t.data('archived');
    var archived_key = t.data('archived_key');
    var url = '/manage/service/suggestions/'+method;
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
        data: 'key='+ key +'&archived=' + archived +'&archived_key=' + archived_key,
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

function editSuggestions(){
    var url = '/manage/service/suggestions/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#editSuggestionsForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("操作成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#editSuggestionsNotice'));
            }
        }
    });
}

function search_suggestions(t, c){
    var data = t.parents('form').serialize()
    var url = '/manage/service/suggestions/search'
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: data,
        success: function(re){
            if(re.status == 'succ'){
                c.html(re.res);
            }
            else if(re.status == 'failed'){
                c.html('<h4 class="text-center">未能检索到信息！</h4>');
            }
        }
    });
    return;
}

//问题咨询管理
function consultionsMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var archived = t.data('archived');
    var archived_key = t.data('archived_key');
    var url = '/manage/service/consultions/'+method;
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
        data: 'key='+ key +'&archived=' + archived +'&archived_key=' + archived_key,
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

function editConsultions(){
    var url = '/manage/service/consultions/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#editConsultionsForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("操作成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#editConsultionsNotice'));
            }
        }
    });
}

function search_consultions(t, c){
    var data = t.parents('form').serialize()
    var url = '/manage/service/consultions/search'
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: data,
        success: function(re){
            if(re.status == 'succ'){
                c.html(re.res);
            }
            else if(re.status == 'failed'){
                c.html('<h4 class="text-center">未能检索到信息！</h4>');
            }
        }
    });
    return;
}

//群众预约援助管理
function aidApplyMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var archived = t.data('archived');
    var archived_key = t.data('archived_key');
    var url = '/manage/service/aidApply/'+method;
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
        data: 'key='+ key +'&archived=' + archived +'&archived_key=' + archived_key,
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

function editAidApply(t){
    var method = t.data('method');
    if(method == 'pass'){
        var c = confirm("确认通过这条法律援助申请？");
        if(c != true){
            return false;
        }
    }
    if(method == 'reject'){
        var c = confirm("确认驳回这条法律援助申请？");
        if(c != true){
            return false;
        }
    }
    var url = '/manage/service/aidApply/'+method;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#editAidApplyForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("操作成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#editAidApplyNotice'));
            }
        }
    });
}

function search_aidApply(t, c){
    var data = t.parents('form').serialize()
    var url = '/manage/service/aidApply/search'
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: data,
        success: function(re){
            if(re.status == 'succ'){
                c.html(re.res);
            }
            else if(re.status == 'failed'){
                c.html('<h4 class="text-center">未能检索到信息！</h4>');
            }
        }
    });
}

//公检法指派管理
function aidDispatchMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var archived = t.data('archived');
    var archived_key = t.data('archived_key');
    var url = '/manage/service/aidDispatch/'+method;
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
        data: 'key='+ key +'&archived=' + archived +'&archived_key=' + archived_key,
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

function editAidDispatch(t){
    var method = t.data('method');
    if(method == 'pass'){
        var c = confirm("确认通过这条法律援助申请？");
        if(c != true){
            return false;
        }
    }
    if(method == 'reject'){
        var c = confirm("确认驳回这条法律援助申请？");
        if(c != true){
            return false;
        }
    }
    var url = '/manage/service/aidDispatch/'+method;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#editAidDispatchForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("操作成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#editAidDispatchNotice'));
            }
        }
    });
}

function search_aidDispatch(t, c){
    var data = t.parents('form').serialize()
    var url = '/manage/service/aidDispatch/search'
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: data,
        success: function(re){
            if(re.status == 'succ'){
                c.html(re.res);
            }
            else if(re.status == 'failed'){
                c.html('<h4 class="text-center">未能检索到信息！</h4>');
            }
        }
    });
}

//车辆管理
function vehicleMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/system/vehicle/'+method;
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

function editVehicle(){
    var url = '/manage/system/vehicle/edit';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#editVehicleForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("修改成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#editVehicleNotice'));
            }
        }
    });
}

function addVehicle(){
    var url = '/manage/system/vehicle/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#addVehicleForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("添加成功！！！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addVehicleNotice'));
            }
        }
    });
}

function search_vehicle(t, c){
    var data = t.parents('form').serialize()
    var url = '/manage/system/vehicle/search'
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: data,
        success: function(re){
            if(re.status == 'succ'){
                c.html(re.res);
            }
            else if(re.status == 'failed'){
                c.html('<h4 class="text-center">未能检索到信息！</h4>');
            }
        }
    });
    return;
}

//归档管理
function archivedMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/system/archived/'+method;
    if(method == 'delete'){
        var c = confirm("确认还原？");
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
                    alert('还原成功！');
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed'){
                alert(re.res);
            }
        }
    });
}

function addArchived(){
    var url = '/manage/system/archived/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#addArchivedForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("创建成功！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addArchivedNotice'));
            }
        }
    });
}

//日志管理
function logMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/system/log/'+method;
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

function search_log(t, c){
    var data = t.parents('form').serialize()
    var url = '/manage/system/log/search'
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: data,
        success: function(re){
            if(re.status == 'succ'){
                c.html(re.res);
            }
            else if(re.status == 'failed'){
                if(re.type=='alert'){
                    alert(re.res);
                    return false;
                }
                c.html('<h4 class="text-center">未能检索到信息！</h4>');
            }
        }
    });
    return;
}

//备份管理
function backupMethod(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/manage/system/backup/'+method;
    if(method == 'delete'){
        var c = confirm("确认删除？");
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
                    alert('删除成功！');
                }
                ajaxResult(re);
            }
            else if(re.status == 'failed'){
                alert(re.res);
            }
        }
    });
}

function addBackup(){
    var url = '/manage/system/backup/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: $('#addBackupForm').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                alert("创建成功！");
                ajaxResult(re);
            }
            else if(re.status == 'failed') {
                ajaxResult(re,$('#addBackupNotice'));
            }
        }
    });
}