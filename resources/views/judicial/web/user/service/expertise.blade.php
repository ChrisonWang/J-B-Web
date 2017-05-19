<div class="panel-member-body" id="s_expertise">
    @if(isset($expertise_list) && count($expertise_list)>0)
        <table class="table">
            <thead>
            <tr>
                <th>提交时间</th>
                <th>审批编号</th>
                <th>鉴定类型</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($expertise_list as $expertise)
                <tr>
                    <td style="vertical-align: middle">{{ date("Y-m-d H:i", strtotime($expertise->apply_date)) }}</td>
                    <td style="vertical-align: middle">{{ $expertise->record_code }}</td>
                    <td style="vertical-align: middle">{{ isset($expertise_type[$expertise->type_id]) ? $expertise_type[$expertise->type_id] : '-' }}</td>
                    <td style="vertical-align: middle">
                        @if($expertise->approval_result == 'pass')
                            <div class="shtg" style="color: #4684CD;">审核通过</div>
                        @elseif($expertise->approval_result == 'reject')
                            <div class="btg" style="color: #222222;">审核不通过/
                                <a href="#" data-key="{{ $expertise->record_code }}" data-type="service_judicial_expertise" onclick="show_opinion($(this))">查看原因</a>
                            </div>
                        @else
                            <div class="dsh" style="color: #7DA750">待审核</div>
                        @endif
                    </td>
                    <td style="vertical-align: middle">
                        @if($expertise->approval_result == 'reject')
                            <a href="{{ URL::to('service/expertise/edit').'/'.$expertise->record_code }}" class="tb_btn">编辑</a>
                        @else
                            <a href="{{ URL::to('service/expertise/detail').'/'.$expertise->record_code }}" class="tb_btn">查看</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!--分页-->
        <div class="zwr_ft" style="display: none">
            <div class="fy_left">
                <span>
                    <a href="javascript: void(0) ;" data-type="expertise" data-method="first" data-now="{{ $expertise_pages['now_page'] }}" data-c="s_expertise" onclick="service_page($(this))"> 首页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="expertise" data-method="per" data-now="{{ $expertise_pages['now_page'] }}" data-c="s_expertise" onclick="service_page($(this))">上一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="expertise" data-method="next" data-now="{{ $expertise_pages['now_page'] }}" data-c="s_expertise" onclick="service_page($(this))">下一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="expertise" data-method="last" data-now="{{ $expertise_pages['now_page'] }}" data-c="s_expertise" onclick="service_page($(this))"> 尾页</a>
                </span>
                <div class="fy_right">
                    <span>总记录数：{{ $expertise_pages['count'] }}</span>
                    <span>每页显示10条记录</span>
                    <span>当前页: {{ $expertise_pages['now_page'] }}/{{ $expertise_pages['count_page'] }}</span>
                </div>
            </div>
        </div>

    @else
        <p class="lead text-center">无记录</p>
    @endif
</div>