/**
 * Created by Auser on 2017/2/10.
 */
$(function(){

    $(".idbr_top ul li").click(function(){
        $(this).siblings("li").removeClass("idbr_topsd");
        $(this).addClass("idbr_topsd");
    })
    $(".hd_ful .hd_lv3 span").click(function(){
        $(this).siblings("span").removeClass("lv3_sd");
        $(this).addClass("lv3_sd");
    })


})