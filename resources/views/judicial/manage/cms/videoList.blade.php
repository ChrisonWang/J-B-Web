<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            宣传视频管理
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            @if(!isset($is_archived))
                <a type="button" data-key='none' data-method="add" onclick="videoMethod($(this))" class="btn btn-primary">新增</a>
            @else
                <button type="button" class="btn btn-danger" data-node="system-archivedMng" onclick="loadContent($(this))">返回归档列表</button>
            @endif
        </div>
        <hr/>
        <div class="container-fluid">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th width="20%" class="text-center">标题</th>
                        <th width="40%" class="text-center">视频地址</th>
                        <th width="5%" class="text-center">是否发布</th>
                        <th width="10%" class="text-center">排序权重</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($video_list as $video)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $video['key'] }}" data-method="show" data-archived_key="{{ isset($archived_key)?$archived_key:'' }}" data-archived="{{ (isset($is_archived)&&$is_archived=='yes') ? 'yes' : 'no' }}" onclick="videoMethod($(this))">查看</a>
                        @if(!isset($is_archived))
                            &nbsp;&nbsp;
                            <a href="javascript: void(0) ;" data-key="{{ $video['key'] }}" data-method="edit" onclick="videoMethod($(this))">编辑</a>
                            &nbsp;&nbsp;
                            <a href="javascript: void(0) ;" data-key="{{ $video['key'] }}" data-method="delete" data-title="{{ $video['video_title'] }}" onclick="videoMethod($(this))">删除</a>
                        @endif
                    </td>
                    <td>{{ spilt_title($video['video_title'], 18) }}</td>
                    <td>{{ spilt_title($video['video_link'], 50) }}</td>
                    <td>@if($video['disabled']=='yes') 否 @else 是 @endif</td>
                    <td>{{ spilt_title($video['sort'], 10) }}</td>
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