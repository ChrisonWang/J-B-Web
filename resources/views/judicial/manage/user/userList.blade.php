<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            用户管理
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="userMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="form-group col col-md-3">
                            <label for="search-login-name">账号：</label>
                            <input type="text" class="form-control" id="search-login-name" name="search-login-name" placeholder="请输入需要检索的账号">
                        </div>
                        <div class="form-group col col-md-3">
                            <label for="search-nickname">姓名：</label>
                            <input type="text" class="form-control" id="search-nickname" name="search-nickname" placeholder="请输入需要检索的姓名">
                        </div>
                        <div class="form-group col col-md-3">
                            <label for="search-cell-phone">手机号码：</label>
                            <input type="text" class="form-control" id="search-cell-phone" name="search-cell-phone" placeholder="请输入需要检索的手机号">
                        </div>
                        <br/>
                        <br/>
                        <div class="form-group col col-md-3">
                            <label for="search-status">状态：</label>
                            <select id="search-status" name="search-status" class="form-control">
                                <option value="none">不限</option>
                                <option value="no">正常</option>
                                <option value="yes">禁用</option>
                            </select>
                        </div>
                        <div class="form-group col col-md-3">
                            <label for="search-type">用户类型：</label>
                            <select id="search-type" name="search-type" class="form-control">
                                @if(isset($type_list))
                                    @foreach($type_list as $key=> $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group col col-md-3">
                            <label for="search-office">科室：</label>
                            <select id="search-office" name="search-office" class="form-control">
                                @if(isset($office_list))
                                    @foreach($office_list as $key=> $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <input type="hidden" name="s_type" value="users"/>
                        <button type="button" class="btn btn-info" onclick="search_list($(this), $('#this-container'))">搜索</button>
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
                        <th class="text-center">账号</th>
                        <th class="text-center">姓名</th>
                        <th class="text-center">手机号码</th>
                        <th class="text-center">用户类型</th>
                        <th class="text-center">是否启用</th>
                        <th class="text-center">创建时间</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($user_list as $user)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $user['key'] }}" data-type="{{ $user['type_id'] }}" data-method="show" onclick="userMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $user['key'] }}" data-type="{{ $user['type_id'] }}" data-method="edit" onclick="userMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $user['key'] }}" data-type="{{ $user['type_id'] }}" data-method="delete" data-title="{{ $user['login_name'] }}" onclick="userMethod($(this))">删除</a>
                    </td>
                    <td>{{ $user['login_name'] }}</td>
                    <td>{{ $user['nickname'] }}</td>
                    <td>{{ $user['cell_phone'] }}</td>
                    <td>{{ $type_list[$user['type_id']] }}</td>
                    <td>@if($user['disabled'] == 'no') 是 @else 否 @endif</td>
                    <td>{{ $user['create_date'] }}</td>
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