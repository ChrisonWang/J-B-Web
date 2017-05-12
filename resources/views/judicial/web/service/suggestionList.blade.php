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
            <a href="{{ URL::to('consultions/add') }}" class="btn btn-danger btn-block" style="width: 180px; height:40px; line-height: 30px">咨询问题</a>
        </div>
        <div class=" col-md-3">
            <a href="{{ URL::to('suggestions/add') }}" class="btn btn-danger btn-block" style="width: 180px; height:40px; line-height: 30px">征求意见</a>
        </div>
    </div>
    <!--搜索栏-->
    <div class="container-fluid">
        <div class="left_title">
            <span>征求意见列表</span>
        </div>
        <div class="id_sch_r" style="padding-top: 30px">
            <form action="{{ URL::to('suggestions/search') }}" method="post">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="search" name="keywords" placeholder="查询受理编号/问题标题" value="" style="width: 450px">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit">搜索</button>
            </form>
        </div>
    </div>
    <div class="su_content">
        <div class="container-fluid" style="margin-top: 20px">
            @if(is_array($record_list) && count($record_list)>0)
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
                            <td>{{ spilt_title($record['title'], 50) }}</td>
                            <td>{{ $record['create_date'] }}</td>
                            <td>{{ $record['answer_date'] }}</td>
                            <td><a href="{{ URL::to('suggestions/detail').'/'.$record['record_code'] }}" class="tb_btn" style="color: #000000">查看</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                <h3 class="text-center">没有搜索到结果！</h3>
            @endif
        </div>
    </div>
    @if(isset($pages) && is_array($pages))
        <div class="zwr_ft">
            <div class="fy_left">
                <span>@if($pages['count_page']>1 )<a href="{{ URL::to(''.$pages['type']) }}"> 首页</a> @else 首页 @endif</span>
                <span>
                    @if($pages['now_page'] >1 ) <a href="{{ URL::to(''.$pages['type']).'/'.($pages['now_page']-1) }}">上一页</a> @else 上一页 @endif
                </span>
                <span>
                    @if($pages['now_page']<$pages['count_page'] ) <a href="{{ URL::to(''.$pages['type']).'/'.($pages['now_page']+1) }}">下一页</a> @else 下一页 @endif
                </span>
                <span>@if($pages['count_page']>1 && $pages['now_page']<$pages['count_page'] )<a href="{{ URL::to(''.$pages['type']).'/'.$pages['count_page'] }}"> 尾页</a> @else 尾页 @endif</span>
            </div>
            <div class="fy_right">
                <span>总记录数：{{ $pages['count'] }}</span>
                <span>每页显示12条记录</span>
                <span>当前页{{ $pages['now_page'] }}/{{ $pages['count_page'] }}</span>
                <span>跳转至第<input id="page_no_input" type="text" value="1"/>页</span>
                <a class="fy_btn" onclick="cms_page_jumps($(this))" data-type="{{ '/'.$pages['type'] }}">跳转</a>
            </div>
        </div>
    @endif
</div>

<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>