<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndexWB')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

<!--内容-->
<div class="w900 zmhd">

    <div class="zw_right w900">
        <div class="zwr_top">
            <span onclick="javascript: window.location.href='{{ URL::to('/') }}'">首页&nbsp;&nbsp;>&nbsp;</span>
            <span onclick="javascript: window.location.href='{{ URL::to('consultions/list') }}'">民政互动&nbsp;&nbsp;>&nbsp;</span>
            <span onclick="javascript: window.location.href='{{ URL::to('consultions/add') }}'">问题咨询&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #101010;">问题详情</span>
        </div>

        <div class="qui">
            <span class="vd_tit">咨询内容</span>
            <ul>
                <li class="w295">受理编号：{{ $record_detail['record_code'] }}</li>
                <li class="w295">问题分类：{{ $type_list[$record_detail['type_id']] }}</li>
                <li class="w295">咨询时间：{{ $record_detail['create_date'] }}</li>
                <li class="qui_q">{{ $record_detail['title'] }}</li>
                <li class="mor_d">
                    {!! $record_detail['content'] !!}
                </li>
            </ul>
        </div>
        <div class="qui">
            <span class="vd_tit">咨询回复</span>
            <ul>
                @if(!empty($record_detail['answer_content']))
                <li class="w295">回复时间：{{ $record_detail['create_date'] }}</li>
                <li class="mor_d">
                    {!! $record_detail['answer_content'] !!}
                </li>
                @else
                    <li class="w295">回复时间：</li>
                    <li class="mor_d">

                    </li>
                @endif
            </ul>
        </div>

    </div>

</div>

<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>