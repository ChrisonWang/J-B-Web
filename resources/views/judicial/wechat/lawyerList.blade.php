@include('judicial.wechat.chips.head')
<div class="container" id="main_container">
    <div class="container-fluid">
        <div class="page-header">
            <h3 class="text-center">
                查询结果
            </h3>
            <p class="text-right"><a href="{{ URL::to('wechat/lawyerSearch') }}">重新查询</a></p>
        </div>
    </div>

    @if(isset($lawyer_list) && is_array($lawyer_list) && count($lawyer_list) > 0)
        <table class="table table-striped table-condensed" id="list_table">
            <thead>
            <tr>
                <th>姓名</th>
                <th>性别</th>
                <th>类型</th>
                <th>执业证号</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($lawyer_list as $lawyer)
                <tr>
                    <td>{{ $lawyer['name'] }}</td>
                    <td>{{ $lawyer['sex']=='female' ? '女' : '男' }}</td>
                    <td>{{ $type_list[$lawyer['type']] }}</td>
                    <td>{{ $lawyer['certificate_code'] }}</td>
                    <td>{{ $lawyer['status']=='cancel' ? '注销' : '执业' }}</td>
                    <td><a href="{{ URL::to('wechat/lawyer/detail').'/'.$lawyer['key'] }}">查看</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="container-fluid" id="height_box">
            <h5 class="text-center"><small id="height_box_notice"></small></h5>
        </div>
    @else
        <h4 class="text-center">未能搜索到结果！</h4>
    @endif
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
                scrollLoad('lawyer', page_no);
            }
        });
    });
</script>

