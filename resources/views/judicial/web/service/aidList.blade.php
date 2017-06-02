<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndexWB')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

<!--内容-->
<div class="w1024 zw_mb">
    <!-- 左侧菜单 -->
    @include('judicial.web.layout.serviceLeft')

    <!--弹窗-->
    <div class="alert_sh" style="display: none">
        <a href="javascript:void(0)" class="closed">X</a>
        <div class="als_top">审核不通过原因</div>
        <div class="als_down"></div>
    </div>

    <div class="zw_right w810">
        <div class="zwr_top">
            <span><a href="{{ URL::to('/') }}" style="color: #222222">首页&nbsp;&nbsp;>&nbsp;</a></span>
            <span><a href="/service" style="color: #222222">网上办事</a>&nbsp;&nbsp;>&nbsp;</span>
            <span><a href="/service/aidApply/apply" style="color: #222222">法律援助</a>&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #929292;">办理进度查询</span>
        </div>

        <!--我的法律援助预约记录-->
        <div style="width: 800px; height: 40px; margin-top:20px; border-left: 5px solid #DD3938; background: #F9F9F9">
            <span style="font-family: MicrosoftYaHei;font-size: 14px;color: #DD3938;letter-spacing: 0; line-height: 40px; padding-left: 10px";>
                我的法律援助预约记录
            </span>
        </div>
        @if(isset($apply_list) && count($apply_list)>0)
        <table class="ws_table sh_tb" style="margin-top: 0px">
            <thead>
            <th>提交时间</th>
            <th>审批编号</th>
            <th>案件分类</th>
            <th>状态</th>
            <th>操作</th>
            </thead>
            <tbody>
            @foreach($apply_list as $apply)
                <tr style="cursor: pointer">
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
        @else
            <p style="width: 100%; text-align: center; margin: 0 auto; line-height: 50px; padding: 10px; font-size: 14px; color: #929292">
                暂无记录！
            </p>
        @endif

        <!--我的公检法指派记录-->
        <div style="width: 800px; height: 40px; margin-top:20px; border-left: 5px solid #DD3938; background: #F9F9F9">
            <span style="font-family: MicrosoftYaHei;font-size: 14px;color: #DD3938;letter-spacing: 0; line-height: 40px; padding-left: 10px";>
                我的公检法指派记录
            </span>
            </div>
            @if(isset($dispatch_list) && count($dispatch_list)>0)
            <table class="ws_table sh_tb" style="margin-top: 0px">
                    <thead>
                    <th>提交时间</th>
                    <th>审批编号</th>
                    <th>申请单位</th>
                    <th>状态</th>
                    <th>操作</th>
                    </thead>
                    <tbody>
                    @foreach($dispatch_list as $dispatch)
                        <tr style="cursor: pointer">
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
        @else
                <p style="width: 100%; text-align: center; margin: 0 auto; line-height: 50px; padding: 10px; font-size: 14px; color: #929292">
                    暂无记录！
                </p>
        @endif

    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>