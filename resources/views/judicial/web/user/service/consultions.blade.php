<div class="panel-member-body" id="s_consultions" @if($consultions_count == 0 || $dispatch_count >0) hidden @endif>
    @if(isset($consultions_list) && count($consultions_list)>0)
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
            @foreach($consultions_list as $consultions)
                <tr>
                    <td style="vertical-align: middle">{{ $consultions['record_code'] }}</td>
                    <td style="vertical-align: middle">{{ $consultions_type[$consultions['type']] }}</td>
                    <td style="vertical-align: middle">{{ spilt_title($consultions['title'], 20) }}</td>
                    <td style="vertical-align: middle">{{ date("Y-m-d H:i", strtotime($consultions['create_date'])) }}</td>
                    <td style="vertical-align: middle">{{ empty(strtotime($consultions['answer_date'])) || $consultions['answer_date']=='0000-00-00 00:00:00' ? '待答复':date("Y-m-d H:i", strtotime($consultions['answer_date'])) }}</td>
                    <td style="vertical-align: middle"><a href="{{ URL::to('consultions/detail').'/'.$consultions['record_code'] }}" class="tb_btn">查看</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!--分页-->
        <div class="zwr_ft" style="display: none">
            <div class="fy_left">
                <span>
                    <a href="javascript: void(0) ;" data-type="consultions" data-method="first" data-now="{{ $consultions_pages['now_page'] }}" data-c="s_consultions" onclick="service_page($(this))"> 首页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="consultions" data-method="per" data-now="{{ $consultions_pages['now_page'] }}" data-c="s_consultions" onclick="service_page($(this))">上一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="consultions" data-method="next" data-now="{{ $consultions_pages['now_page'] }}" data-c="s_consultions" onclick="service_page($(this))">下一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="consultions" data-method="last" data-now="{{ $consultions_pages['now_page'] }}" data-c="s_consultions" onclick="service_page($(this))"> 尾页</a>
                </span>
                <div class="fy_right">
                    <span>总记录数：{{ $consultions_pages['count'] }}</span>
                    <span>每页显示10条记录</span>
                    <span>当前页: {{ $consultions_pages['now_page'] }}/{{ $consultions_pages['count_page'] }}</span>
                </div>
            </div>
        </div>

    @else
        <p class="lead text-center">无记录</p>
    @endif
</div>