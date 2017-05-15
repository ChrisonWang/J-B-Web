<div class="panel-member-body" id="s_suggestions" hidden>
    @if(isset($suggestions_list) && count($suggestions_list)>0)
        <table class="table">
            <thead>
            <tr>
                <th>受理编号</th>
                <th>分类</th>
                <th style="width: 25%">留言主题</th>
                <th>留言时间</th>
                <th>答复时间</th>
                <th>问题状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($suggestions_list as $suggestions)
                <tr>
                    <td style="vertical-align: middle">{{ $suggestions['record_code'] }}</td>
                    <td style="vertical-align: middle">{{ $suggestions_type[$suggestions['type']] }}</td>
                    <td style="vertical-align: middle">{{ spilt_title($suggestions['title'], 20) }}</td>
                    <td style="vertical-align: middle">{{ date("Y-m-d H:i", strtotime($suggestions['create_date'])) }}</td>
                    <td style="vertical-align: middle">{{ empty(strtotime($suggestions['answer_date'])) || $suggestions['answer_date']=='0000-00-00 00:00:00' ? '待回复' : date("Y-m-d H:i", strtotime($suggestions['answer_date'])) }}</td>
                    <td style="vertical-align: middle">
                        @if($suggestions['status'] == 'answer')
                            已回复
                        @else
                            待回复
                        @endif
                    </td>
                    <td style="vertical-align: middle">
                        <a href="javascript: void(0); " class="tb_btn" data-key="{{ $suggestions['record_code'] }}" style="color: #000000" onclick="member_show_reason($(this))">查看</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!--分页-->
        <div class="zwr_ft" style="display: none">
            <div class="fy_left">
                <span>
                    <a href="javascript: void(0) ;" data-type="suggestions" data-method="first" data-now="{{ $suggestions_pages['now_page'] }}" data-c="s_suggestions" onclick="service_page($(this))"> 首页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="suggestions" data-method="per" data-now="{{ $suggestions_pages['now_page'] }}" data-c="s_suggestions" onclick="service_page($(this))">上一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="suggestions" data-method="next" data-now="{{ $suggestions_pages['now_page'] }}" data-c="s_suggestions" onclick="service_page($(this))">下一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="suggestions" data-method="last" data-now="{{ $suggestions_pages['now_page'] }}" data-c="s_suggestions" onclick="service_page($(this))"> 尾页</a>
                </span>
                <div class="fy_right">
                    <span>总记录数：{{ $suggestions_pages['count'] }}</span>
                    <span>每页显示10条记录</span>
                    <span>当前页: {{ $suggestions_pages['now_page'] }}/{{ $suggestions_pages['count_page'] }}</span>
                </div>
            </div>
        </div>

    @else
        <p class="lead text-center">无记录</p>
    @endif
</div>