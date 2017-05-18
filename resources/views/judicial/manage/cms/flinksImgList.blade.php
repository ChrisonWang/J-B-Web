<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            图片友情链接
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="flinkImgMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 20%">操作</th>
                        <th class="text-center" style="width: 20%">标题</th>
                        <th class="text-center">链接地址</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($flink_list as $flink)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $flink['key'] }}" data-method="show" onclick="flinkImgMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $flink['key'] }}" data-method="edit" onclick="flinkImgMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $flink['key'] }}" data-method="delete" data-title="{{ $flink['fi_title'] }}" onclick="flinkImgMethod($(this))">删除</a>
                    </td>
                    <td>{{ $flink['fi_title'] }}</td>
                    <td><a href="{{ $flink['fi_links'] }}" target="_blank">{{ spilt_title($flink['fi_links'], 50) }}</a></td>
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