@if(isset($list) && count($list)>0)
    <table class="table">
        <thead>
        <tr>
            <th>受理编号</th>
            <th>问题分类</th>
            <th>问题标题</th>
            <th>咨询时间</th>
            <th>答复时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $consultions)
            <tr>
                <td>{{ $consultions['record_code'] }}</td>
                <td>{{ $consultions_type[$consultions['type']] }}</td>
                <td>{{ $consultions['title'] }}</td>
                <td>{{ date("Y-m-d H:i", strtotime($consultions['create_date'])) }}</td>
                <td>{{ empty(strtotime($consultions['answer_date']))?'待答复':date("Y-m-d H:i", strtotime($consultions['answer_date'])) }}</td>
                <td><a href="{{ URL::to('consultions/detail').'/'.$consultions['record_code'] }}" class="tb_btn">查看</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!--分页-->
    <div class="zwr_ft" hidden>
        <div class="fy_left">
                <span>
                    <a href="javascript: void(0) ;" data-type="consultions" data-method="first" data-now="{{ $pages['now_page'] }}" data-c="s_consultions" onclick="service_page($(this))"> 首页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="consultions" data-method="per" data-now="{{ $pages['now_page'] }}" data-c="s_consultions" onclick="service_page($(this))">上一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="consultions" data-method="next" data-now="{{ $pages['now_page'] }}" data-c="s_consultions" onclick="service_page($(this))">下一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="consultions" data-method="last" data-now="{{ $pages['now_page'] }}" data-c="s_consultions" onclick="service_page($(this))"> 尾页</a>
                </span>
            <div class="fy_right">
                <span>总记录数：{{ $pages['count'] }}</span>
                <span>每页显示10条记录</span>
                <span>当前页: {{ $pages['now_page'] }}/{{ $pages['count_page'] }}</span>
            </div>
        </div>
    </div>

@else
    <p class="lead text-center">无记录</p>
@endif