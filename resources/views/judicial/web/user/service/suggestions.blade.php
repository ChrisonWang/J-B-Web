<div class="panel-member-body" id="s_suggestions" hidden>
    @if(isset($suggestions_list) && count($suggestions_list)>0)
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
            @foreach($suggestions_list as $suggestions)
                <tr>
                    <td>{{ $suggestions['record_code'] }}</td>
                    <td>{{ $suggestions_type[$suggestions['type']] }}</td>
                    <td>{{ $suggestions['title'] }}</td>
                    <td>{{ date("Y-m-d H:i", strtotime($suggestions['create_date'])) }}</td>
                    <td>{{ empty(strtotime($suggestions['answer_date']))?'待答复':date("Y-m-d H:i", strtotime($suggestions['answer_date'])) }}</td>
                    <td><a href="{{ URL::to('suggestions/detail').'/'.$suggestions['record_code'] }}" class="tb_btn">查看</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p class="lead text-center">无记录</p>
    @endif
</div>