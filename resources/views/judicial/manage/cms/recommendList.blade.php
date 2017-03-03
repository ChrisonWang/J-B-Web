<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            后台推荐链接管理
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="recommendMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">标题</th>
                        <th class="text-center">链接地址</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($r_list as $r)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $r['key'] }}" data-method="show" onclick="recommendMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $r['key'] }}" data-method="edit" onclick="recommendMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $r['key'] }}" data-method="delete" data-title="{{ $r['r_title'] }}" onclick="recommendMethod($(this))">删除</a>
                    </td>
                    <td>{{ $r['r_title'] }}</td>
                    <td>{{ $r['r_link'] }}</td>
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