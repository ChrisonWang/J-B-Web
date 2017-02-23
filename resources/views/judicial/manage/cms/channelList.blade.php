<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            频道管理
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="channelMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">名称</th>
                        <th width="10%" class="text-center">是否首页推荐</th>
                        <th width="10%" class="text-center">是否开启表单下载</th>
                        <th width="10%" class="text-center">排序权重</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($channel_list as $channel)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $channel['key'] }}" data-method="show" onclick="channelMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $channel['key'] }}" data-method="edit" onclick="channelMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $channel['key'] }}" data-method="delete" data-title="{{ $channel['channel_title'] }}" onclick="channelMethod($(this))">删除</a>
                    </td>
                    <td>{{ $channel['channel_title'] }}</td>
                    <td>@if($channel['is_recommend'] == 'yes') 是 @else 否 @endif</td>
                    <td>@if($channel['form_download'] == 'yes') 是 @else 否 @endif</td>
                    <td>{{ $channel['sort'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>