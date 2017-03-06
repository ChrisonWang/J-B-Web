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
});

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