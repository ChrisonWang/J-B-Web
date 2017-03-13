<div class="panel-member-body" id="s_consultions" hidden>
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
    @else
        <p class="lead text-center">无记录</p>
    @endif
</div>