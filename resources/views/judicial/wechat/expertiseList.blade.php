@include('judicial.wechat.chips.head')

<div class="container" id="main_container">
    <div class="container-fluid">
        <div class="page-header">
            <h3 class="text-center">
                司法鉴定申请记录
            </h3>
        </div>
    </div>
    @if(isset($record_list) && is_array($record_list) && count($record_list) > 0)
        <table class="table table-striped table-condensed" id="list_table">
            <thead>
            <tr>
                <th width="35%">申请时间</th>
                <th>审批编号</th>
                <th width="28%">状态</th>
            </tr>
            </thead>
            <tbody>
            @foreach($record_list as $record)
                <tr>
                    <td>{{ $record['apply_date'] }}</td>
                    <td>{{ spilt_title($record['record_code'], 13) }}</td>
                    <td>
                        @if($record['approval_result'] == 'pass')
                            审核通过
                        @elseif($record['approval_result'] == 'reject')
                            驳回
                            <a href="javascript: void(0) ;" data-method="expertise" data-key="{{ $record['record_code'] }}" onclick="show_reason($(this))">
                                查看原因
                            </a>
                        @else
                            待审核
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="container-fluid" id="height_box">
            <h5 class="text-center"><small id="height_box_notice">数据已全部加载完成</small></h5>
        </div>
    @else
        <h4 class="text-center">您暂时还没有申请记录！</h4>
    @endif
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
        window.localStorage.setItem('page_no', 1);
        var count_page = {{ $count_page }};

        if($(window).height() >= $(document).height()){
            var add_height = $(window).height() - $("#main_container").height() + 50;
            $("#height_box").show();
            $("#height_box").css('height', add_height);
        }
        $(window).scroll(function(){
            var page_no = 1;
            var range = 10;             //距下边界长度/单位px
            var elemt = 500;           //插入元素高度/单位px
            var totalheight = 0;
            var srollPos = $(window).scrollTop();
            totalheight = parseFloat($(window).height()) + parseFloat(srollPos);
            if(($(document).height()-range) <= totalheight) {
                var page_no = window.localStorage.getItem('page_no');
                if(page_no >= count_page){
                    window.localStorage.setItem('page_no', count_page);
                    $("#height_box").show();
                    $("#height_box_notice").text('数据已全部加载完成');
                    return false;
                }
                $("#height_box_notice").text(' 加载中... ');
                scrollLoadExpertise(page_no);
            }
        });
    });
</script>

