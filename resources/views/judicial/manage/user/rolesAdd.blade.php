<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            角色管理/新增
        </h3>
    </div>
    <!--隐藏的模板-->
    <div id="node-row" hidden >
        <table class="table table-bordered table-hover table-condensed">
            <tbody class="text-center">
            <tr>
                <td>
                    <select name="menus" class="form-control node-row" onchange="getSubNode($(this))">
                        @foreach($menu_list as $menu)
                            <option value="{{ $menu['key'] }}">{{ $menu['menu_name'] }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select id="nodes" name="nodes" class="form-control node-row">
                        @foreach($node_list as $node)
                            <option value={{ $node['node_key'] }}>{{ $node['node_name'] }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="permission" class="form-control node-row">
                        <option value="r">查看</option>
                        <option value="rw">编辑</option>
                    </select>
                </td>
                <td>
                    <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="panel-body">
        <form class="form-horizontal" id="rolesAddForm">
            <div class="form-group">
                <label for="title" class="col-md-2 control-label">角色名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="title" name="title" placeholder="请输如角色名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="sub_links" class="col-md-2 control-label">功能菜单：</label>
                <div class="col-md-10">
                    <div class="container-fluid" style="padding-left: 0; margin-left: 0">
                        <table class="table table-bordered table-hover table-condensed">
                            <thead>
                            <tr>
                                <th class="text-center">菜单名称</th>
                                <th class="text-center">功能点</th>
                                <th class="text-center">权限</th>
                                <th width="10%" class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody class="text-center" id="menu-nodes">
                            <tr>
                                <td>
                                    <select name="menus" class="form-control node-row" onchange="getSubNode($(this))">
                                        @foreach($menu_list as $menu)
                                            <option value="{{ $menu['key'] }}">{{ $menu['menu_name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="nodes" class="form-control node-row">
                                        @foreach($node_list as $node)
                                            <option value={{ $node['node_key'] }}>{{ $node['node_name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="permission" class="form-control node-row">
                                        <option value="r">查看</option>
                                        <option value="rw">编辑</option>
                                    </select>
                                </td>
                                <td>
                                    <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="container-fluid">
                        <p class="text-left hidden" id="add-row-notice" style="color: red"></p>
                    </div>
                    <div class="container-fluid">
                        <hr/>
                        <div class="col-md-3">
                            <a href="javascript: void(0) ;" class="btn btn-default btn-block" onclick="addRow()">
                                添加
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">自动生成</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addRolesNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addRoles()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="user-roleMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>