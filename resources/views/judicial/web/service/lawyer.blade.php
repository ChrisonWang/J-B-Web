<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndex')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

<!--内容-->
<div class="w1024 zw_mb">
    <!-- 左侧菜单 -->
    @include('judicial.web.layout.serviceLeft')

    <div class="zw_right w810">
        <div class="zwr_top">
            <span><a href="{{ URL::to('/') }}" style="color: #222222">首页&nbsp;&nbsp;>&nbsp;</a></span>
            <span><a href="/service" style="color: #222222">网上办事</a>&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #929292;">律师查询</span>
        </div>

        <div class="ws_cxt">
            <form method="post" action="{{ URL::to('service/lawyer/search') }}">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <ul>
                    <li>
                        <div class="law_cx_inp">
                            <label for="name" class="wsc_txt" style="margin-right: 10px!important;">律师姓名</label>
                        </div>
                        <div class="law_cx_inp">
                            <input class="form-control" type="text" id="name" name="name" value="{{ isset($last_search['name']) ? $last_search['name'] : '' }}" placeholder="请输入律师姓名">
                        </div>
                    </li>
                    <li>
                        <div class="law_cx_inp">
                            <label for="sex" class="wsc_txt" style="margin-right: 10px!important;">性别</label>
                        </div>
                        <div class="law_cx_inp">
                            <select class="form-control" id="sex" name="sex">
                                <option value="none">不限</option>
                                <option value="male" @if(isset($last_search['sex']) && $last_search['sex']=='male') selected @endif>男</option>
                                <option value="female" @if(isset($last_search['sex']) && $last_search['sex']=='female') selected @endif>女</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="law_cx_inp">
                            <label for="type" class="wsc_txt" style="margin-right: 10px!important;">律师类型</label>
                        </div>
                        <div class="law_cx_inp">
                            <select class="form-control" id="type" name="type">
                                <option value="none">不限</option>
                                <option value="full_time" @if(isset($last_search['type']) && $last_search['type']=='full_time') selected @endif >专职</option>
                                <option value="part_time" @if(isset($last_search['type']) && $last_search['type']=='part_time') selected @endif >兼职</option>
                                <option value="company" @if(isset($last_search['type']) && $last_search['type']=='company') selected @endif >公司</option>
                                <option value="officer" @if(isset($last_search['type']) && $last_search['type']=='officer') selected @endif >公职</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="law_cx_inp">
                            <label for="lawyer_office_name" class="wsc_txt" style="margin-right: 10px!important;">机构名称</label>
                        </div>
                        <div class="law_cx_inp">
                            <input type="text" class="form-control" value="{{ isset($last_search['lawyer_office_name']) ? $last_search['lawyer_office_name'] : '' }}" id="lawyer_office_name" name="lawyer_office_name" placeholder="请输入机构名称">
                        </div>
                    </li>
                    <li>
                        <div class="law_cx_inp">
                            <label for="certificate_code" class="wsc_txt" style="margin-right: 10px!important;">执业证号</label>
                        </div>
                        <div class="law_cx_inp">
                            <input type="text" class="form-control" value="{{ isset($last_search['certificate_code']) ? $last_search['certificate_code'] : '' }}" id="certificate_code" name="certificate_code" placeholder="请输入执业证号">
                        </div>
                    </li>
                </ul>
                <div class="col col-md-offset-4 col-md-3">
                    <input type="submit" class="btn btn-danger btn-block" value="查询" style="width: 200px; height: 40px; background: #E23939; border-radius: 2px;">
                </div>
            </form>
        </div>

        <table class="ws_table">
            @if(isset($lawyer_list) && is_array($lawyer_list) && count($lawyer_list)>0)
            <thead>
            <th style="text-align: center">姓名</th>
            <th style="text-align: center">性别</th>
            <th style="text-align: center">类型</th>
            <th style="text-align: center">执业证号</th>
            <th style="text-align: center">机构名称</th>
            <th style="text-align: center">状态</th>
            </thead>
            <tbody>
                @foreach($lawyer_list as $lawyer)
                    <tr onclick="javascript: window.location.href='{{ URL::to('service/lawyer/detail').'/'.$lawyer['key'] }}'" style="cursor: pointer">
                        <td>{{ $lawyer['name'] }}</td>
                        <td>{{ $lawyer['sex']=='female' ? '女' : '男' }}</td>
                        <td>{{ isset($type_list[$lawyer['type']]) ? $type_list[$lawyer['type']] : '-' }}</td>
                        <td>{{ $lawyer['certificate_code'] }}</td>
                        <td>{{ isset($office_list[$lawyer['lawyer_office']]) ? spilt_title($office_list[$lawyer['lawyer_office']],15) : '-' }}</td>
                        <td>{{ $lawyer['status']=='cancel' ? '注销' : '执业' }}</td>
                    </tr>
                @endforeach
            </tbody>
            @else
                <p style=" width:100%; margin: auto; text-align: center;font-family: MicrosoftYaHei;font-size: 14px;color: #929292;letter-spacing: 0; padding: 40px">
                    没有相关联的搜索结果！
                </p>
            @endif
        </table>

        @if(isset($pages) && is_array($pages))
            <div class="zwr_ft">
                <div class="fy_left">
                    <span>@if($pages['count_page']>1 )<a href="{{ URL::to('service/'.$pages['type']) }}"> 首页</a> @else 首页 @endif</span>
                <span>
                    @if($pages['now_page'] >1 ) <a href="{{ URL::to('service/'.$pages['type']).'/'.($pages['now_page']-1) }}">上一页</a> @else 上一页 @endif
                </span>
                <span>
                    @if($pages['now_page']<$pages['count_page'] ) <a href="{{ URL::to('service/'.$pages['type']).'/'.($pages['now_page']+1) }}">下一页</a> @else 下一页 @endif
                </span>
                    <span>@if($pages['count_page']>1 && $pages['now_page']<$pages['count_page'] )<a href="{{ URL::to('service/'.$pages['type']).'/'.$pages['count_page'] }}"> 尾页</a> @else 尾页 @endif</span>
                </div>
                <div class="fy_right">
                    <span>总记录数：{{ $pages['count'] }}</span>
                    <span>每页显示16条记录</span>
                    <span>当前页{{ $pages['now_page'] }}/{{ $pages['count_page'] }}</span>
                    <span>跳转至第<input id="page_no_input" type="text" value="1"/>页</span>
                    <a class="fy_btn" onclick="cms_page_jumps($(this))" data-type="{{ '/service/'.$pages['type'] }}">跳转</a>
                </div>
            </div>
        @endif
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>