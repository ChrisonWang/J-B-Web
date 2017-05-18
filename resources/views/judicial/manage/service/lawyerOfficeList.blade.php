<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="lawyerOfficeMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="container-fluid">
                            <div class="form-group" style="padding: 5px">
                                <label for="name">名称：</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="请输入律所名称">
                            </div>
                            <div class="form-group" style="padding: 5px">
                                <label for="type">类型：</label>
                                <select class="form-control" name="type" id="type">
                                    <option value="none">不限</option>
                                    @foreach($type_list as $key=> $type)
                                        <option value="{{ $key }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="padding: 5px">
                                <label for="usc_code">社会统一信用代码：</label>
                                <input type="text" class="form-control" id="usc_code" name="usc_code" placeholder="请输入社会统一信用代码">
                            </div>
                            <div class="form-group" style="padding: 5px">
                                <label for="director">负责人：</label>
                                <input type="text" class="form-control" id="director" name="director" placeholder="请输入负责人姓名">
                            </div>
                            <div class="form-group" style="padding: 5px">
                                <label for="area_id">区域：</label>
                                <select class="form-control" name="area_id" id="area_id">
                                    <option value="none">不限</option>
                                    @foreach($area_list as $key=> $type)
                                        <option value="{{ $key }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button id="search" type="button" class="btn btn-info" onclick="search_lawyerOffice($(this), $('#this-container'))">搜索</button>
                        </div>
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
                        <th class="text-center">名称</th>
                        <th class="text-center">负责人</th>
                        <th class="text-center">统一社会信用代码</th>
                        <th class="text-center">类型</th>
                        <th class="text-center">区域</th>
                        <th class="text-center">状态</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($office_list as $office)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $office['key'] }}" data-method="show" onclick="lawyerOfficeMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $office['key'] }}" data-method="edit" onclick="lawyerOfficeMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $office['key'] }}" data-method="delete" data-title="{{ $office['name'] }}" onclick="lawyerOfficeMethod($(this))">删除</a>
                    </td>
                    <td>{{ $office['name'] }}</td>
                    <td>{{ isset($office['director']) ? $office['director'] : '-' }}</td>
                    <td>{{ isset($office['usc_code']) ? $office['usc_code'] : '-' }}</td>
                    <td>{{ isset($area_list)&&is_array($area_list) ? $area_list[$office['area_id']] : '-' }}</td>
                    <td>{{ isset($type_list[$office['type']]) ? $type_list[$office['type']] : '-'}}</td>
                    <td>{{ $office['status']=='normal' ? '正常' : '注销' }}</td>
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