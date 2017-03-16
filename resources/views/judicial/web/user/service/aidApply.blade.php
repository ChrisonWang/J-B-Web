<div class="panel-member-body a" id="s_apply" hidden>
    @if(isset($apply_list) && count($apply_list)>0)
        <table class="table">
            <thead>
            <tr>
                <th>提交时间</th>
                <th>审批编号</th>
                <th>案件分类</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($apply_list as $apply)
                <tr>
                    <td>{{ date("Y-m-d H:i", strtotime($apply->apply_date)) }}</td>
                    <td>{{ $apply->record_code }}</td>
                    <td>{{ isset($apply_type[$apply->type]) ? $apply_type[$apply->type] : '-' }}</td>
                    <td>
                        @if($apply->status == 'pass')
                            <div class="shtg">审核通过</div>
                        @elseif($apply->status == 'reject')
                            <div class="btg">审核不通过/
                                <a href="#" data-key="{{ $apply->record_code }}" data-type="service_legal_aid_apply" onclick="show_opinion($(this))">查看原因</a>
                            </div>
                        @else
                            <div class="dsh">待审核</div>
                        @endif
                    </td>
                    <td>
                        @if($apply->status == 'reject')
                            <a href="{{ URL::to('service/aidApply/edit').'/'.$apply->record_code }}" class="tb_btn">编辑</a>
                        @else
                            <a href="{{ URL::to('service/aidApply/detail').'/'.$apply->record_code }}" class="tb_btn">查看</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!--分页-->
        <div class="zwr_ft">
            <div class="fy_left">
                <span>
                    <a href="javascript: void(0) ;" data-type="apply" data-method="first" data-now="{{ $apply_pages['now_page'] }}" data-c="s_apply" onclick="service_page($(this))"> 首页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="apply" data-method="per" data-now="{{ $apply_pages['now_page'] }}" data-c="s_apply" onclick="service_page($(this))">上一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="apply" data-method="next" data-now="{{ $apply_pages['now_page'] }}" data-c="s_apply" onclick="service_page($(this))">下一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="apply" data-method="last" data-now="{{ $apply_pages['now_page'] }}" data-c="s_apply" onclick="service_page($(this))"> 尾页</a>
                </span>
                <div class="fy_right">
                    <span>总记录数：{{ $apply_pages['count'] }}</span>
                    <span>每页显示10条记录</span>
                    <span>当前页: {{ $apply_pages['now_page'] }}/{{ $apply_pages['count_page'] }}</span>
                </div>
            </div>
        </div>

    @else
        <p class="lead text-center">无记录</p>
    @endif
</div>