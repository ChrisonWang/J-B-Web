<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            宣传视频管理
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="videoMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th width="20%" class="text-center">标题</th>
                        <th width="40%" class="text-center">视频地址</th>
                        <th width="10%" class="text-center">是否发布</th>
                        <th width="10%" class="text-center">排序权重</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($video_list as $video)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $video['key'] }}" data-method="show" onclick="videoMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $video['key'] }}" data-method="edit" onclick="videoMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $video['key'] }}" data-method="delete" data-title="{{ $video['video_title'] }}" onclick="videoMethod($(this))">删除</a>
                    </td>
                    <td>{{ $video['video_title'] }}</td>
                    <td>{{ $video['video_link'] }}</td>
                    <td>@if($video['disabled']=='yes') 否 @else 是 @endif</td>
                    <td>{{ $video['sort'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>