<div class="panel-member-body" id="s_dispatch" hidden>
    <div class="container-fluid" style="padding-left: 0; background: #F9F9F9">
        <div class="left_title" style="margin-top: 10px">
            <span>我的公检法指派记录</span>
        </div>
    </div>
    @if(isset($dispatch_list) && count($dispatch_list)>0)
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
            @foreach($dispatch_list as $dispatch)
                <tr>
                    <td style="vertical-align: middle">{{ date("Y-m-d H:i", strtotime($dispatch->apply_date)) }}</td>
                    <td style="vertical-align: middle">{{ $dispatch->record_code }}</td>
                    <td style="vertical-align: middle">{{ $dispatch->apply_office }}</td>
                    <td style="vertical-align: middle">
                        @if($dispatch->status == 'archived')
                            <div class="shtg" style="color: #4684CD;">已结案</div>
                        @elseif($dispatch->status == 'pass')
		                    <div class="dsh" style="color: #7DA750;">待指派</div>
	                    @elseif($dispatch->status == 'dispatch')
		                    <div class="shtg" style="color: #4684CD;">已指派</div>
                        @elseif($dispatch->status == 'reject')
                            <div class="btg" style="color: #222222;">审核不通过/
                                <a href="#" data-key="{{ $dispatch->record_code }}" data-type="service_legal_aid_dispatch" onclick="show_opinion($(this))" style="color: #DD3938">查看原因</a>
                            </div>
                        @else
                            <div class="dsh" style="color: #7DA750">待审核</div>
                        @endif
                    </td>
                    <td style="vertical-align: middle">
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
        <div class="zwr_ft" style="display: none">
            <div class="fy_left">
                <span>
                    <a href="javascript: void(0) ;" data-type="dispatch" data-method="first" data-now="{{ $dispatch_pages['now_page'] }}" data-c="s_dispatch" onclick="service_page($(this))"> 首页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="dispatch" data-method="per" data-now="{{ $dispatch_pages['now_page'] }}" data-c="s_dispatch" onclick="service_page($(this))">上一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="dispatch" data-method="next" data-now="{{ $dispatch_pages['now_page'] }}" data-c="s_dispatch" onclick="service_page($(this))">下一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="dispatch" data-method="last" data-now="{{ $dispatch_pages['now_page'] }}" data-c="s_dispatch" onclick="service_page($(this))"> 尾页</a>
                </span>
                <div class="fy_right">
                    <span>总记录数：{{ $dispatch_pages['count'] }}</span>
                    <span>每页显示10条记录</span>
                    <span>当前页: {{ $dispatch_pages['now_page'] }}/{{ $dispatch_pages['count_page'] }}</span>
                </div>
            </div>
        </div>

    @else
        <p class="lead text-center">无记录</p>
    @endif
</div>