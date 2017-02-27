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
})