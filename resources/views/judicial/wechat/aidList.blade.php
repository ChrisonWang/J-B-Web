@include('judicial.wechat.chips.head')

<div class="container" id="main_container">
    <div class="container-fluid">
        <div class="page-header">
            <ul class="nav nav-tabs" role="tablist" id="title_tab">
                <li role="presentation" class="col-xs-6 text-center active">
                    <a href="#apply" id="apply-tab" data-toggle="tab" role="tab" aria-controls="apply" aria-expanded="false">
                        群众预约援助申请<span class="badge">{{ $count['apply'] }}</span>
                    </a>
                </li>
                <li role="presentation" class="col-xs-6 text-center">
                    <a href="#dispatch" id="dispatch-tab" data-toggle="tab" role="tab" aria-controls="dispatch" aria-expanded="false">
                        司法指派援助申请<span class="badge">{{ $count['dispatch'] }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <div id="myTabContent" class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="apply" aria-labelledby="apply-tab" data-type="apply" data-count="{{ $count['apply'] }}">
                @include('judicial.wechat.layout.applyList')</div>
            <div role="tabpanel" class="tab-pane fade" id="dispatch" aria-labelledby="dispatch-tab" data-type="dispatch" data-count="{{ $count['dispatch'] }}">
                @include('judicial.wechat.layout.dispatchList')</div>
        </div>
    </div>
</div>

<!-- 模态框 -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id='reason_notice'>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center">查看驳回原因</h4>
            </div>
            <div class="modal-body">
                <p class="text-left lead" id="p_notice"></p>
                <h5 class="text-left lead">
                    <small>温馨提示：请登录PC端网站：www.smxsfj.com 根据要求重新提交申请</small>
                </h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>

@include('judicial.wechat.chips.foot')

<script>
    $(document).ready(function(){
        window.localStorage.setItem('page_no_apply', 1);
        window.localStorage.setItem('page_no_dispatch', 1);
        if($(window).height() >= $(document).height()){
            var add_height = $(window).height() - $("#main_container").height() + 50;
            $("#height_box_apply").show();
            $("#height_box_apply").css('height', add_height);
        }
        $(window).scroll(function(){
            var type = $('#myTabContent').find('div.active').data('type');
            var count_page = $('#myTabContent').find('div.active').data('count');
            var page_no = 1;
            var range = 10;             //距下边界长度/单位px
            var elemt = 500;           //插入元素高度/单位px
            var totalheight = 0;
            var srollPos = $(window).scrollTop();
            totalheight = parseFloat($(window).height()) + parseFloat(srollPos);
            if(($(document).height()-range) <= totalheight) {
                var page_no = window.localStorage.getItem('page_no_'+type);
                if(page_no >= count_page){
                    window.localStorage.setItem('page_no_'+type, count_page);
                    $("#height_box").show();
                    $("#height_box_notice").text('数据已全部加载完成');
                    return false;
                }
                $("#height_box_notice").text(' 加载中... ');
                scrollLoadAis(type, page_no);
            }
        });
    });
</script>
