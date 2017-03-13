<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndex');
<body>
<!--头部导航-->
@include('judicial.web.chips.nav');

<!--内容-->
<div class="w1024 zw_mb">
    <div class="container-fluid">
        <div class="col-md-offset-3 col-md-3">
            <a href="{{ URL::to('consultions/list') }}" class="btn btn-danger btn-block">咨询问题</a>
        </div>
        <div class=" col-md-3">
            <a href="{{ URL::to('suggestions/list') }}" class="btn btn-danger btn-block">征求意见</a>
        </div>
    </div>
    <!--搜索栏-->
    <div class="container-fluid">
        <div class="id_sch_l">

        </div>
        <div class="id_sch_r">
            <form action="{{ URL::to('consultions/search') }}" method="post">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="search" name="keywords" placeholder="查询受理编号/问题标题" value="">
                <button type="submit">搜索</button>
            </form>
        </div>
    </div>
    <div class="su_content">
        <div class="container-fluid" style="margin-top: 20px">
            <table class="ws_table sh_tb" style="width: 100%">
                <thead>
                <th>受理编号</th>
                <th>问题分类</th>
                <th>问题标题</th>
                <th>咨询时间</th>
                <th>答复时间</th>
                <th>操作</th>
                </thead>
                <tbody>
                @foreach($record_list as $record)
                    <tr>
                        <td>{{ $record['record_code'] }}</td>
                        <td>{{ $type_list[$record['type']] }}</td>
                        <td>{{ $record['title'] }}</td>
                        <td>{{ $record['create_date'] }}</td>
                        <td>{{ $record['answer_date'] }}</td>
                        <td><a href="{{ URL::to('consultions/detail').'/'.$record['record_code'] }}" class="tb_btn">查看</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="zwr_ft">
        <div class="fy_left">
            <span>@if($pages['count_page']>1 )<a href="{{ URL::to('service/'.$pages['type']) }}"> 首页</a> @else 首页 @endif</span>
                <span>
                    @if($pages['now_page'] >1 ) <a href="{{ URL::to('service/'.$pages['type']).'/'.($pages['now_page']-1) }}">上一页</a> @else 上一页 @endif
                </span>
                <span>
                    @if($pages['now_page']<$pages['count_page'] ) <a href="{{ URL::to('service/'.$pages['type']).'/'.($pages['now_page']+1) }}">下一页</a> @else 下一页 @endif
                </span>
            <span>@if($pages['count_page']>1 && $pages['now_page']<$pages['count_page'] )<a href="{{ URL::to('service/'.$pages['type']).'/'.$pages['count_page'] }}"> 尾页</a> @else 尾页 @endif</span>
        </div>
        <div class="fy_right">
            <span>总记录数：{{ $pages['count'] }}</span>
            <span>每页显示12条记录</span>
            <span>当前页{{ $pages['now_page'] }}/{{ $pages['count_page'] }}</span>
        </div>
    </div>
</div>

<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>