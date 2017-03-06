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
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
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
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
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
                    if(typeof(UE_Content)=="object"){
                        UE_Content.destroy();
                    }
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
                    if(typeof(UE_Content)=="object"){
                        UE_Content.destroy();
                    }
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
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
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
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
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
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
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
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
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
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
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
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
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
            $('#add-row-notice').text('请填写完整的频道名称名称');
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
                    if(typeof(UE_Content)=="object"){
                        UE_Content.destroy();
                    }
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
            $('#add-row-notice').text('请填写完整的频道名称名称');
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
                    if(typeof(UE_Content)=="object"){
                        UE_Content.destroy();
                    }
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
                    if(typeof(UE_Content)=="object"){
                        UE_Content.destroy();
                    }
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
            $('#add-row-notice').removeClass('hidden');
            $('#add-row-notice').text('请填写完整的频道名称名称');
            v = false;
            return false
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
                    if(typeof(UE_Content)=="object"){
                        UE_Content.destroy();
                    }
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
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
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
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
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
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
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
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
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
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
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
                if(typeof(UE_Content)=="object"){
                    UE_Content.destroy();
                }
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

