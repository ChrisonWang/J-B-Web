<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="areaMethod($(this))" class="btn btn-primary">新增</a>
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
                @foreach($area_list as $area)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $area['key'] }}" data-method="show" onclick="areaMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $area['key'] }}" data-method="edit" onclick="areaMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $area['key'] }}" data-method="delete" data-title="{{ $area['area_name'] }}" onclick="areaMethod($(this))">删除</a>
                    </td>
                    <td>{{ $area['area_name'] }}</td>
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