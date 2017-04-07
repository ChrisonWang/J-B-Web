<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            用户管理/查看
        </h3>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <div class="col col-md-2">
                    <button type="button" class="btn btn-info btn-block" data-key="{{ $user_detail['key'] }}" data-type="{{ $user_detail['type_id'] }}" data-method="edit" onclick="userMethod($(this))">编辑</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="user-userMng" onclick="loadContent($(this))">返回</button>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="userEditForm">
            <input type="hidden" name="key" value="{{ $user_detail['key'] }}" />
            <div class="form-group">
                <label for="login_name" class="col-md-2 control-label"><strong style="color: red">*</strong> 账号：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" value="{{ $user_detail['login_name'] }}" id="login_name" name="login_name" placeholder="请输菜单名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="user_type" class="col-md-2 control-label">用户类型：</label>
                <div class="col-md-3">
                    <select disabled class="form-control" name="user_type" id="user_type">
                        @foreach($type_list as $k=> $type)
                            <option value="{{ $k }}" @if($user_detail['type_id'] == $k) selected @endif data-type="{{ $type['type_key'] }}">{{ $type['type_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="user_office" class="col-md-2 control-label">科室：</label>
                <div class="col-md-3">
                    <select disabled class="form-control" name="user_office" id="user_office">
                        @foreach($office_list as $k=> $office)
                            <option value="{{ $k }}" @if($user_detail['office_id'] == $k) selected @endif>{{ $office }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="user_role" class="col-md-2 control-label">角色：</label>
                <div class="col-md-3">
                    <select disabled class="form-control" name="user_role" id="user_role">
                        @foreach($role_list as $k=> $role)
                            <option value="{{ $k }}" @if($user_detail['role_id'] == $k) selected @endif>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="cell_phone" class="col-md-2 control-label"><strong style="color: red">*</strong> 手机号码：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" value="{{ $user_detail['cell_phone'] }}" id="cell_phone" name="cell_phone" placeholder="请输手机号码" />
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-2 control-label">邮箱：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" value="{{ !empty($user_detail['email']) ? $user_detail['email'] : '未设置' }}" id="email" name="email" placeholder="请输邮箱" />
                </div>
            </div>
            <div class="form-group">
                <label for="nickname" class="col-md-2 control-label">显示名：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" value="{{ !empty($user_detail['nickname']) ? $user_detail['nickname'] : '未设置' }}" id="nickname" name="nickname" placeholder="请输显示名" />
                </div>
            </div>
            <div class="form-group">
                <label for="disabled" class="col-md-2 control-label">是否启用：</label>
                <div class="col-md-3">
                    <input disabled type="checkbox" id="disabled" name="disabled" class="" value="no" @if($user_detail['disabled'] == 'no') checked @endif>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <p>{{ $user_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="userEditNotice" style="color: red"></p>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(function(){
        var type = $('#user_type option:selected').data('type');
        if(type == 'user'){
            $('#user_office').parents('div.form-group').hide();
            $('#user_role').parents('div.form-group').hide();
        }
        if(type == 'manager'){
            $('#user_office').parents('div.form-group').show();
            $('#user_role').parents('div.form-group').show();
        }
        if(type == 'admin'){
            $('#user_office').parents('div.form-group').show();
            $('#user_role').parents('div.form-group').hide();
        }
    });
</script>