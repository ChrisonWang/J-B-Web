<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            菜单管理/编辑
        </h3>
    </div>
    <!--隐藏的模板-->
    <div id="node-row" hidden >
        <table class="table table-bordered table-hover table-condensed">
            <tbody class="text-center">
            <tr>
                <td>
                    <select name="nodes[]" class="form-control node-row">
                        @foreach($node_list as $node)
                            <option value="{{ $node['key'] }}">{{ $node['node_name'] }}</option>
                        @endforeach
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
        <form class="form-horizontal" id="menuEditForm">
            <input type="hidden" name="key" value="{{ $menu_detail['key'] }}"/>
            <div class="form-group">
                <label for="menu_name" class="col-md-2 control-label">菜单名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="menu_name" name="menu_name" value="{{ $menu_detail['menu_name'] }}" placeholder="请输菜单名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="node_schema" class="col-md-2 control-label">功能点：</label>
                <div class="col-md-5">
                    <div class="container-fluid" style="margin-left: 0; padding-left: 0">
                        <table class="table table-bordered table-hover table-condensed">
                            <thead>
                            <tr>
                                <th class="text-center">名称</th>
                                <th width="10%" class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody class="text-center" id="menu-nodes">
                            @if($menu_detail['nodes'] != 'none' && is_array($menu_detail['nodes']))
                                @foreach($menu_detail['nodes'] as $node_key=> $menu_node)
                                    <tr>
                                        <td>
                                            <select name="nodes[]" class="form-control" >
                                                @foreach($node_list as $node)
                                                    <option value="{{ $node['key'] }}" @if($node['key'] == $node_key) selected @endif>{{ $node['node_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>
                                        <select name="nodes[]" class="form-control">
                                            @foreach($node_list as $node)
                                                <option value="{{ $node['key'] }}">{{ $node['node_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="container-fluid">
                        <p class="text-left hidden" id="add-row-notice" style="color: red"></p>
                    </div>
                    <div class="container-fluid">
                        <hr/>
                        <div class="col-md-2">
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
                    <p class="text-left hidden" id="menuEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="editMenu()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="user-menuMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>