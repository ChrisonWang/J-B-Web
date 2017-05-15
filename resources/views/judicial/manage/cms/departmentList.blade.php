<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            机构管理
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="departmentMethod($(this))" class="btn btn-primary">新增</a>
            <a type="button" data-node="cms-departmentType" onclick="loadContent($(this))" class="btn btn-info">机构分类</a>
        </div>
        <hr/>
        <!--筛选-->
        <div class="panel panel-default">
            <div class="panel-body">
                <form class="form-inline">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label for="search-department_name">名称：</label>
                            <input type="text" class="form-control" id="search-department_name" name="search-department_name" placeholder="请输入部门名称">
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <label for="search-type">分类：</label>
                            <select id="search-type" name="search-type" class="form-control">
                                @if(isset($type_data) && is_array($type_data))
                                    <option value="none">不限分类</option>
                                    @foreach($type_data as $type_id=> $type_name)
                                        <option value="{{ $type_id }}">{{ $type_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <input type="hidden" name="s_type" value="department"/>
                        <button id="search" type="button" class="btn btn-info" onclick="search_list($(this), $('#this-container'))">搜索</button>
                    </div>
                </form>
            </div>
        </div>
        <hr/>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">名称</th>
                        <th class="text-center">分类</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($department_list as $department)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $department['key'] }}" data-method="show" onclick="departmentMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $department['key'] }}" data-method="edit" onclick="departmentMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $department['key'] }}" data-method="delete" data-title="{{ $department['department_name'] }}" onclick="departmentMethod($(this))">删除</a>
                    </td>
                    <td>{{ $department['department_name'] }}</td>
                    <td>{{ $department['type_name'] }}</td>
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