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
            <span><a href="/service/lawyerOffice/1" style="color: #222222">事务所查询查询</a>&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #101010;">事务所查询</span>
        </div>

        <div class="ws_cxt container-fluid" style="height: auto; padding-bottom: 20px" >
            <form method="post" action="{{ URL::to('service/lawyerOffice/search') }}">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <ul>
                    <li style="margin-left: 0">
                        <div class="law_cx_inp">
                            <label for="name" class="wsc_txt" style="margin-right: 10px!important;">事务所名称</label>
                        </div>
                        <div class="law_cx_inp">
                            <input class="form-control" type="text" id="name" name="name" value="{{ isset($last_search['name']) ? $last_search['name'] : '' }}" placeholder="请输入事务所名称">
                        </div>
                    </li>
                    <li>
                        <div class="law_cx_inp">
                            <label for="director" class="wsc_txt" style="margin-right: 10px!important;">负责人姓名</label>
                        </div>
                        <div class="law_cx_inp">
                            <input type="text" class="form-control" value="{{ isset($last_search['director']) ? $last_search['director'] : '' }}" id="director" name="director" placeholder="请输入负责人姓名">
                        </div>
                    </li>
                    <li>
                        <div class="law_cx_inp">
                            <label for="type" class="wsc_txt" style="margin-right: 10px!important;">律师类型</label>
                        </div>
                        <div class="law_cx_inp">
                            <select class="form-control" id="type" name="type">
                                <option value="none">不限</option>
                                <option value="head" @if(isset($last_search['type']) && $last_search['type']=='head') selected @endif >总所</option>
                                <option value="branch" @if(isset($last_search['type']) && $last_search['type']=='branch') selected @endif >分所</option>
                                <option value="personal" @if(isset($last_search['type']) && $last_search['type']=='personal') selected @endif >个人</option>
                            </select>
                        </div>
                    </li>
                    <li style="margin-left: 0">
                        <div class="law_cx_inp">
                            <label for="usc_code" class="wsc_txt" style="margin-right: 10px!important;">统一社会信用代码</label>
                        </div>
                        <div class="law_cx_inp">
                            <input type="text" class="form-control"value="{{ isset($last_search['usc_code']) ? $last_search['usc_code'] : '' }}" id="usc_code" name="usc_code" placeholder="统一社会信用代码">
                        </div>
                    </li>
                    <li>
                        <div class="law_cx_inp">
                            <label for="area_id" class="wsc_txt" style="margin-right: 10px!important;">所在区域</label>
                        </div>
                        <div class="law_cx_inp">
                            <select class="form-control" id="area_id" name="area_id">
                                <option value="none">不限</option>
                                @foreach($area_list as $key=> $name)
                                    <option value="{{ $key }}" @if(isset($last_search['area_id']) && $last_search['area_id']==$key) selected @endif>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </li>
                </ul>
                <div class="col col-md-offset-4 col-md-3">
                    <input type="submit" class="btn btn-danger btn-block" value="查询" style="width: 200px; height: 40px; background: #E23939; border-radius: 2px;">
                </div>
            </form>
        </div>

        <table class="ws_table">
            @if(isset($office_list) && is_array($office_list) && count($office_list)>0)
            <thead>
            <th style="text-align: center">事务所名称</th>
            <th style="text-align: center">负责人</th>
            <th style="text-align: center">统一社会信用代码</th>
            <th style="text-align: center">类型</th>
            <th style="text-align: center">区域</th>
            <th style="text-align: center">状态</th>
            </thead>
            <tbody>
                @foreach($office_list as $office)
                    <tr onclick="javascript: window.location.href='{{ URL::to('service/lawyerOffice/detail').'/'.$office['key'] }}'"  style="cursor: pointer">
                        <td>{{ spilt_title($office['name'], 15) }}</td>
                        <td>{{ $office['director'] }}</td>
                        <td>{{ $office['usc_code'] }}</td>
                        <td>{{ isset($type_list[$office['type']]) ? $type_list[$office['type']] : '-' }}</td>
                        <td>{{ isset($area_list[$office['area_id']]) ? $area_list[$office['area_id']] : '-' }}</td>
                        <td>{{ $office['status']=='cancel' ? '注销' : '执业' }}</td>
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