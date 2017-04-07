<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            科室管理/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="officeEditForm">
            <input type="hidden" value="{{ $office_detail['key'] }}" name="key"/>
            <div class="form-group">
                <label for="office_name" class="col-md-2 control-label">科室名称：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="office_name" name="office_name" value="{{ $office_detail['office_name'] }}" placeholder="请输入科室名称" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">下属职员：</label>
                <div class="col-md-8">
                    <div class="container-fluid">
                        @if($manager_list != 'none' && is_array($manager_list))
                        <table class="table table-bordered table-hover table-condensed">
                            <thead>
                            <tr>
                                <th class="text-center">登录名</th>
                                <th class="text-center">显示名</th>
                                <th class="text-center">电话</th>
                                <th class="text-center">邮箱</th>
                                <th class="text-center">创建时间</th>
                            </tr>
                            </thead>
                            <tbody class="text-center" id="menu-nodes">
                            @foreach($manager_list as $manager)
                                <tr>
                                    <td>{{ $manager['login_name'] }}</td>
                                    <td>{{ $manager['nickname'] }}</td>
                                    <td>{{ $manager['cell_phone'] }}</td>
                                    <td>{{ $manager['email'] }}</td>
                                    <td>{{ $manager['create_date'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        该部门暂无职员
                    @endif
                    <div class="container-fluid">
                        <p class="text-left hidden" id="add-row-notice" style="color: red"></p>
                    </div>
                    <div class="container-fluid">
                        <hr/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <p>{{ $office_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="officeEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="user-officeMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>