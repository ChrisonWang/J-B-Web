<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="messageTmpMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">标题</th>
                        <th width="20%" class="text-center">创建时间</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($tmp_list as $tmp)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $tmp['key'] }}" data-method="show" onclick="messageTmpMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $tmp['key'] }}" data-method="edit" onclick="messageTmpMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $tmp['key'] }}" data-method="delete" data-title="{{ $tmp['title'] }}" onclick="messageTmpMethod($(this))">删除</a>
                    </td>
                    <td>{{ $tmp['title'] }}</td>
                    <td>{{ $tmp['create_date'] }}</td>
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