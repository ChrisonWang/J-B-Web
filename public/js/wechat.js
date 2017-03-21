/**
 * Created by Administrator on 2017/3/17.
 */
$(function(){

});

function doLogin(){
    var url = '/wechat/login';
    var login_name = $('input[name="login_name"]').val();
    var password = $('input[name="password"]').val();
    if(typeof(login_name) == 'undefined' || login_name==''){
        $('#login_notice').modal('show');
        $('#p_notice').text('用户名不能为空！');
        return false;
    }
    if(typeof(password) == 'undefined' || password==''){
        $('#login_notice').modal('show');
        $('#p_notice').text('密码不能为空！');
        return false;
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        url: url,
        method: "POST",
        data: $('#login_form').serialize(),
        success: function(re){
            if(re.status == 'succ'){
                window.location.href=re.res;
            }
            else if(re.status == 'failed'){
                $('#login_notice').modal('show');
                $('#p_notice').text(re.res);
                return false;
            }
        }
    });
}

//查看审核不通过理由
function show_reason(t){
    var key = t.data('key');
    var method = t.data('method');
    var url = '/wechat/reason/' + method;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: {key: key},
        success: function(re){
            if(re.status == 'succ'){
                $('#reason_notice').modal('show');
                $('#p_notice').text('驳回原因:'+re.res);
                return false;
            }
            return false;
        }
    });
}

//下拉加载
function scrollLoad(type, page_no){
    var url = '/wechat/scrollLoad';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        url: url,
        method: "POST",
        data: {page_no: page_no, search_type: type},
        success: function(re){
            if(re.status == 'succ'){
                window.localStorage.setItem('page_no', re.page_no);
                var list = JSON.parse(re.res);
                var data = '';
                if(re.type == 'office'){
                    $.each(list, function(i ,v){
                        data += '<tr>' +
                            '<td>'+ v.name +'</td>' +
                            '<td>'+ v.area +'</td>' +
                            '<td>'+ v.status +'</td>' +
                            '<td><a href="'+ v.link +'">查看</a></td>' +
                            '</tr>';
                    });
                }
                else if(re.type == 'lawyer'){
                    $.each(list, function(i ,v){
                        data += '<tr>' +
                            '<td>'+ v.name +'</td>' +
                            '<td>'+ v.sex +'</td>' +
                            '<td>'+ v.type +'</td>' +
                            '<td>'+ v.certificate_code +'</td>' +
                            '<td>'+ v.status +'</td>' +
                            '<td><a href="'+ v.link +'">查看</a></td>' +
                            '</tr>';
                    });
                }

                $("#list_table tbody").append(data);
                $("#height_box_notice").text('');
                $("#height_box").hide();
            }
            else if(re.status == 'failed'){
                window.localStorage.setItem('page_no', re.page_no);
                $("#height_box_notice").text('数据已全部加载完成');
            }
            return false;
        }
    });
}