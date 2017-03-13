<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="messageSendMethod($(this))" class="btn btn-primary">新增</a>
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
                        <a href="javascript: void(0) ;" data-key="{{ $send['key'] }}" data-method="show" onclick="messageSendMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $send['key'] }}" data-method="edit" onclick="messageSendMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $send['key'] }}" data-method="delete" data-title="{{ $temp_list[$send['temp_code']] }}" onclick="messageSendMethod($(this))">删除</a>
                    </td>
                    <td>{{ $temp_list[$send['temp_code']] }}</td>
                    <td>{{ $send['send_date'] }}</td>
                    <td>{{ $send['send_date'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!--分页-->
        @if(isset($pages) && is_array($pages) && $pages != 'none')
            @include('judicial.manage.chips.pages')
        @endif
    </div>
</div>