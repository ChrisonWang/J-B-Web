<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            @if(!isset($is_archived))
                <a type="button" data-key='none' data-method="add" onclick="messageSendMethod($(this))" class="btn btn-primary">新增</a>
            @else
                <button type="button" class="btn btn-danger" data-node="system-archivedMng" onclick="loadContent($(this))">返回归档列表</button>
            @endif
        </div>
        <hr/>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">主题</th>
                        <th class="text-center">发送时间</th>
                        <th class="text-center">发送对象</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($send_list as $send)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $send['key'] }}" data-method="show" data-archived_key="{{ isset($archived_key)?$archived_key:'' }}" data-archived="{{ (isset($is_archived)&&$is_archived=='yes') ? 'yes' : 'no' }}" onclick="messageSendMethod($(this))">查看</a>
                        @if($send['send_status'] == 'waiting' && !isset($is_archived))
                            &nbsp;&nbsp;
                            <a href="javascript: void(0) ;" data-key="{{ $send['key'] }}" data-method="edit" onclick="messageSendMethod($(this))">编辑</a>
                            &nbsp;&nbsp;
                            <a href="javascript: void(0) ;" data-key="{{ $send['key'] }}" data-method="delete" data-title="{{ $temp_list[$send['temp_code']] }}" onclick="messageSendMethod($(this))">删除</a>
                        @endif
                    </td>
                    <td>{{ $temp_list[$send['temp_code']] }}</td>
                    <td>{{ $send['send_date'] }}</td>
                    <td>
                        @if( $send['receiver_type']=='member' )
                            前台用户
                        @elseif( $send['receiver_type']=='manager' )
                            后台用户
                        @else
                            证书持有人
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!--分页-->
        @if(isset($pages) && is_array($pages) && $pages != 'none')
            @include('judicial.manage.chips.servicePages')
        @endif
    </div>
</div>