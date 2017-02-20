/**
 * Created by Administrator on 2017/2/20.
 */

function tagMethod(t){
    var tagKey = t.data('tag-key');
    var method = t.data('method');
    var url = '/manage/cms/tags/'+method;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "POST",
        url: url,
        data: 'tag_key='+tagKey,
        success: function(re){
            ajaxResult(re);
        }
    });
}