<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="lawyerMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="name">姓名：</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="请输入律师姓名">
                        </div>
                        <div class="form-group">
                            <label for="type">人员类型：</label>
                            <select class="form-control" name="type" id="type">
                                <option value="none">不限</option>
                                @foreach($type_list as $key=> $type)
                                    <option value="{{ $key }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="certificate_code">执业证号：</label>
                            <input type="text" class="form-control" id="certificate_code" name="certificate_code" placeholder="请输入执业证号">
                        </div>
                        <div class="form-group">
                            <label for="lawyer_office_name">机构名称：</label>
                            <input type="text" class="form-control" id="lawyer_office_name" name="lawyer_office_name" placeholder="请输入机构名称">
                        </div>
                        <div class="form-group">
                            <label for="status">状态：</label>
                            <select class="form-control" name="status" id="status">
                                <option value="none">不限</option>
                                <option value="normal">执业</option>
                                <option value="cancel">注销</option>
                            </select>
                        </div>
                        <button id="search" type="button" class="btn btn-info" onclick="search_lawyer($(this), $('#this-container'))">搜索</button>
                    </form>
                </div>
            </div>
        </div>
        <hr/>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">姓名</th>
                        <th class="text-center">性别</th>
                        <th class="text-center">类型</th>
                        <th class="text-center">职业证编号</th>
                        <th class="text-center">机构名称</th>
                        <th class="text-center">状态</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($lawyer_list as $lawyer)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $lawyer['key'] }}" data-method="show" onclick="lawyerMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $lawyer['key'] }}" data-method="edit" onclick="lawyerMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $lawyer['key'] }}" data-method="delete" data-title="{{ $lawyer['name'] }}" onclick="lawyerMethod($(this))">删除</a>
                    </td>
                    <td>{{ $lawyer['name'] }}</td>
                    <td>{{ $lawyer['sex']=='female'? '女' : '男' }}</td>
                    <td>{{ isset($type_list[$lawyer['type']]) ? $type_list[$lawyer['type']] : '-' }}</td>
                    <td>{{ $lawyer['certificate_code'] }}</td>
                    <td>{{ isset($office_list[$lawyer['lawyer_office']]) ? $office_list[$lawyer['lawyer_office']] : '-' }}</td>
                    <td>{{ $lawyer['status']=='normal' ? '正常' : '注销' }}</td>

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