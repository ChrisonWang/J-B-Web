/**
 * Created by Administrator on 2017/2/20.
 */

function tagMethod(t){
    var tagKey = t.data('tag-key');
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
                alert("添加成功！！！");
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