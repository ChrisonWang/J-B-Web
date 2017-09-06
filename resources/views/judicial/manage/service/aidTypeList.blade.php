<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="aidTypeMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">名称</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($type_list as $type)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $type['key'] }}" data-method="show" onclick="aidTypeMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $type['key'] }}" data-method="edit" onclick="aidTypeMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $type['key'] }}" data-method="delete" data-title="{{ $type['type_name'] }}" onclick="aidTypeMethod($(this))">删除</a>
                    </td>
                    <td>{{ $type['type_name'] }}</td>
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