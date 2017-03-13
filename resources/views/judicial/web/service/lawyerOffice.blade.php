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
            <span onclick="javascript: window.location.href='{{ URL::to('/') }}'">首页&nbsp;&nbsp;>&nbsp;</span>
            <span onclick="javascript: window.location.href='{{ URL::to('service') }}'">网上办事&nbsp;&nbsp;>&nbsp;</span>
            <span>律师服务&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #101010;">事务所查询</span>
        </div>

        <div class="ws_cxt container-fluid" style="height: auto; padding-bottom: 20px" >
            <form method="post" action="{{ URL::to('service/lawyerOffice/search') }}">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <ul>
                    <li style="width: auto">
                        <label for="name" class="wsc_txt" style="width: auto">事务所名称:</label>
                        <div class="cx_inp">
                            <input class="form-control" type="text" id="name" name="name" value="{{ isset($last_search['name']) ? $last_search['name'] : '' }}" placeholder="请输入事务所名称">
                        </div>
                    </li>
                    <li style="width: auto">
                        <label for="director" class="wsc_txt" style="width: auto">负责人姓名:</label>
                        <div class="cx_inp">
                            <input type="text" class="form-control" value="{{ isset($last_search['director']) ? $last_search['director'] : '' }}" id="director" name="director" placeholder="请输入负责人姓名">
                        </div>
                    </li>
                    <li style="width: auto">
                        <label for="type" class="wsc_txt" style="width: auto">律师类型:</label>
                        <div class="cx_inp">
                            <select class="form-control" id="type" name="type">
                                <option value="none">不限</option>
                                <option value="head" @if(isset($last_search['type']) && $last_search['type']=='head') selected @endif >总所</option>
                                <option value="branch" @if(isset($last_search['type']) && $last_search['type']=='branch') selected @endif >分所</option>
                                <option value="personal" @if(isset($last_search['type']) && $last_search['type']=='personal') selected @endif >个人</option>
                            </select>
                        </div>
                    </li>
                    <li style="width: auto">
                        <label for="usc_code" class="wsc_txt" style="width: auto">统一社会信用代码:</label>
                        <div class="cx_inp">
                            <input type="text" class="form-control" value="{{ isset($last_search['usc_code']) ? $last_search['usc_code'] : '' }}" id="usc_code" name="usc_code" placeholder="统一社会信用代码">
                        </div>
                    </li>
                    <li style="width: auto">
                        <label for="area_id" class="wsc_txt" style="width: auto">所在区域:</label>
                        <div class="cx_inp">
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
                    <input type="submit" class="btn btn-danger btn-block" value="查询">
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
                    <tr onclick="javascript: window.location.href='{{ URL::to('service/lawyerOffice/detail').'/'.$office['key'] }}'">
                        <td>{{ $office['name'] }}</td>
                        <td>{{ $office['director'] }}</td>
                        <td>{{ $office['usc_code'] }}</td>
                        <td>{{ isset($type_list[$office['type']]) ? $type_list[$office['type']] : '-' }}</td>
                        <td>{{ isset($area_list[$office['area_id']]) ? $area_list[$office['area_id']] : '-' }}</td>
                        <td>{{ $office['status']=='cancel' ? '注销' : '执业' }}</td>
                    </tr>
                @endforeach
            </tbody>
            @else
                <h3 class="text-center">没有相关联的搜索结果！</h3>
            @endif
        </table>

        <div class="zwr_ft">
            <div class="fy_left">
                <span>@if($pages['count_page']>1 )<a href="{{ URL::to('service/lawyerOffice'.$pages['type']) }}"> 首页</a> @else 首页 @endif</span>
                <span>
                    @if($pages['now_page'] >1 ) <a href="{{ URL::to('service/lawyerOffice'.$pages['type']).'/'.($pages['now_page']-1) }}">上一页</a> @else 上一页 @endif
                </span>
                <span>
                    @if($pages['now_page']<$pages['count_page'] ) <a href="{{ URL::to('service/lawyerOffice'.$pages['type']).'/'.($pages['now_page']+1) }}">下一页</a> @else 下一页 @endif
                </span>
                <span>@if($pages['count_page']>1 && $pages['now_page']<$pages['count_page'] )<a href="{{ URL::to('service/lawyerOffice'.$pages['type']).'/'.$pages['count_page'] }}"> 尾页</a> @else 尾页 @endif</span>
            </div>
            <div class="fy_right">
                <span>总记录数：{{ $pages['count'] }}</span>
                <span>每页显示16条记录</span>
                <span>当前页{{ $pages['now_page'] }}/{{ $pages['count_page'] }}</span>
            </div>
        </div>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>