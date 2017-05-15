@if(isset($list) && count($list)>0)
    <table class="table">
        <thead>
        <tr>
            <th>提交时间</th>
            <th>审批编号</th>
            <th>申请单位</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $dispatch)
            <tr>
                <td>{{ date("Y-m-d H:i", strtotime($dispatch->apply_date)) }}</td>
                <td>{{ $dispatch->record_code }}</td>
                <td>{{ $dispatch->apply_office }}</td>
                <td>
                    @if($dispatch->status == 'pass')
                        <div class="shtg">审核通过</div>
                    @elseif($dispatch->status == 'reject')
                        <div class="btg">审核不通过/
                            <a href="#" data-key="{{ $dispatch->record_code }}" data-type="service_legal_aid_dispatch" onclick="show_opinion($(this))">查看原因</a>
                        </div>
                    @else
                        <div class="dsh">待审核</div>
                    @endif
                </td>
                <td>
                    @if($dispatch->status == 'reject')
                        <a href="{{ URL::to('service/aidDispatch/edit').'/'.$dispatch->record_code }}" class="tb_btn">编辑</a>
                    @else
                        <a href="{{ URL::to('service/aidDispatch/detail').'/'.$dispatch->record_code }}" class="tb_btn">查看</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!--分页-->
    <div class="zwr_ft" hidden>
        <div class="fy_left">
                <span>
                    <a href="javascript: void(0) ;" data-type="dispatch" data-method="first" data-now="{{ $pages['now_page'] }}" data-c="s_dispatch" onclick="service_page($(this))"> 首页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="dispatch" data-method="per" data-now="{{ $pages['now_page'] }}" data-c="s_dispatch" onclick="service_page($(this))">上一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="dispatch" data-method="next" data-now="{{ $pages['now_page'] }}" data-c="s_dispatch" onclick="service_page($(this))">下一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="dispatch" data-method="last" data-now="{{ $pages['now_page'] }}" data-c="s_dispatch" onclick="service_page($(this))"> 尾页</a>
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