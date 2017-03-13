/**
 * Created by Auser on 2017/2/10.
 */
$(function(){
    //首页选中
    $(".idbr_top ul li").click(function(){
        $(this).siblings("li").removeClass("idbr_topsd");
        $(this).addClass("idbr_topsd");
    })
    $(".hd_ful .hd_lv3 span").click(function(){
        $(this).siblings("span").removeClass("lv3_sd");
        $(this).addClass("lv3_sd");
    })

    //政务公开
    $(".zw_left li i").click(function(){
        //$(this).parent().parent().siblings("li").removeClass("lb_select");
        $(this).parent().parent().toggleClass("lb_select");
    })
    $(".law_body span").click(function(){
        $(this).siblings("span").removeClass("lb_act");
        $(this).addClass("lb_act");
    })

    $(".closed").click(function(){
        $(".alert_sh").hide();
    })

});

function file_download(t){
    var file = t.data('link');
    var url = '/download';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async:false,
        url: url,
        method: "POST",
        data: {file:file},
        success: function(re){
            re;
        }
    });
}

function sendVerify(){
    var nu = $('#cellPhone').val();
    var url = '/user/sendVerify';
    var imgVerifyCode = $('#imgVerifyCode').val();
    $("#message_notice").addClass('hidden');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: {phone:nu, img:imgVerifyCode},
        success: function(re){
            if(re.status == 'succ'){
                alert("已发送，请注意查收");
                var count = 60;
                var countdown = setInterval(CountDown, 1000);
                function CountDown() {
                    $("#sendVerifyBtn").attr("disabled", true);
                    $("#sendVerifyBtn").val(count + "秒");
                    if (count == 0) {
                        $("#sendVerifyBtn").removeAttr("disabled");
                        $("#sendVerifyBtn").val('获取验证码');
                        clearInterval(countdown);
                    }
                    count--;
                }
            }
            else if(re.status == 'failed'){
                $("#message_notice").removeClass('hidden');
                $("#message_notice").find("p").text(re.res);
            }
        }
    });
}

function jumpToFlink(s){
    var url = s.find("option:selected").val();
    if(url == 'type'){
        return true;
    }
    else {
        window.open(url);
    }
}

function changeFontSize(s){
    var s = s||'12px';
    $("#content").css("font-size", s);
}

function loadArticle(t,c){
    var channel = t.data('channel')
    var channel_id = t.data('key')
    var url = 'index/loadArticle'
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: {channel_id: channel_id},
        success:function(re){
            if(channel == 'zwgk'){
                if(re.status == 'succ'){
                    var list = jQuery.parseJSON(re.res);
                    var a_list = '';
                    $.each(list, function(i,sub){
                        a_list += '<li><a href="'+sub.url+'"> <span class="idbrd_l">'+sub.article_title+'</span> <span class="idbrd_r">'+sub.publish_date+'</span></a></li>';
                    });
                    c.html(a_list);
                }
                else if(re.status == 'failed'){
                    c.html('暂无数据');
                    return;
                }
            }
            else if(channel == 'sfdt'){
                if(re.status == 'succ'){
                    var list = jQuery.parseJSON(re.res);
                    var a_list = '';
                    $.each(list, function(i,sub){
                        a_list += '<li><span class="idbrd_l"><a href="'+sub.url+'" target="_blank">'+sub.article_title+'</a></span><span class="idbrd_r">'+sub.publish_date+'</span></li>';
                    });
                    c.html(a_list);
                }
                else if(re.status == 'failed'){
                    c.html('暂无数据');
                    return;
                }
            }
        }
    });
    return;
}

function addSuggestion(){
    var c = confirm("确认提交？");
    if(c != true){
        return false;
    }
    $('#suggestionNotice').addClass('hidden');
    var url = '/suggestions/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: $('#suggestionForm').serialize(),
        success: function(re){
            if(re.status == 'failed'){
                $('#suggestionNotice').removeClass('hidden');
                $('#suggestionNotice').text(re.res);
            }
            else if(re.status == 'succ'){
                alert('提交成功！');
            }
        }
    });

}

function addConsultion(){
    var c = confirm("确认提交？");
    if(c != true){
        return false;
    }
    $('#consultionNotice').addClass('hidden');
    var url = '/consultions/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async:false,
        url: url,
        method: "POST",
        data: $('#consultionForm').serialize(),
        success: function(re){
            if(re.status == 'failed'){
                $('#consultionNotice').removeClass('hidden');
                $('#consultionNotice').text(re.res);
            }
            else if(re.status == 'succ'){
                alert('提交成功！');
            }
        }
    });
}

function aidDispatchApply(){
    var c = confirm("确认提交？");
    if(c != true){
        return false;
    }
    $('#notice').attr('hidden',true);
    var url = '/service/aidDispatch/doApply';
    $('#aidDispatchForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async:false,
        url: url,
        method: "POST",
        data: $('#aidDispatchForm').serialize(),
        success: function(re){
            if(re.status == 'failed'){
                alert('提交错误：'+re.res+'！');
            }
            else if(re.status == 'succ'){
                alert('提交成功！');
                window.location.href=re.link;
            }
        }
    });
}

function aidApply(){
    var c = confirm("确认提交？");
    if(c != true){
        return false;
    }
    $('#notice').attr('hidden',true);
    var url = '/service/aidApply/doApply';
    $('#aidApplyForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async:false,
        url: url,
        method: "POST",
        data: $('#aidApplyForm').serialize(),
        success: function(re){
            if(re.status == 'failed'){
                alert('提交错误：'+re.res+'！');
            }
            else if(re.status == 'succ'){
                alert('提交成功！');
                window.location.href=re.link;
            }
        }
    });
}

function expertiseApply(){
    var c = confirm("确认提交？");
    if(c != true){
        return false;
    }
    $('#notice').attr('hidden',true);
    var url = '/service/expertise/doApply';
    $('#expertiseForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async:false,
        url: url,
        method: "POST",
        data: $('#expertiseForm').serialize(),
        success: function(re){
            if(re.status == 'failed'){
                alert('提交错误：'+re.res+'！');
            }
            else if(re.status == 'succ'){
                alert('提交成功！');
                window.location.href=re.link;
            }
        }
    });
}

function switch_service(t){
    var id = 's_'+t.data('key');
    $(".panel-member-switch").removeClass('on');
    t.parents(".panel-member-switch").addClass('on');

    $("#s_apply").hide();
    $("#s_dispatch").hide();
    $("#s_consultions").hide();
    $("#s_expertise").hide();
    $("#s_suggestions").hide();

    $("#"+id).show();
}

function switch_service2(t){
    $(".wsrb_left").find('li').find('a').removeClass('wsrb_hv');
    t.addClass('wsrb_hv');

    $('.wsrb_right').hide();
    $("#"+ t.data('key')).show();
}

function show_opinion(t){
    var record_code = t.data('key');
    var type = t.data('type');
    var url = '/service/getOpinion';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: {record_code:record_code, type:type},
        success: function(re){
            if(re.status == 'succ'){
                $('.alert_sh').show();
                $('.alert_sh').find('.als_down').text(re.res);
            }
        }
    });
}