<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            一/二级友情链接管理/新增
        </h3>
    </div>
    <!--隐藏的模板-->
    <div id="node-row" hidden >
        <table class="table table-bordered table-hover table-condensed">
            <tbody class="text-center">
            <tr>
                <td>
                    <input type="text" class="form-control" name="sub_title[]" placeholder="请输链接名称" />
                </td>
                <td>
                    <input type="text" class="form-control" name="sub_link[]" placeholder="请输链接地址" />
                </td>
                <td>
                    <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="panel-body">
        <form class="form-horizontal" id="flinksAddForm">
            <div class="form-group">
                <label for="title" class="col-md-2 control-label"><strong style="color: red">*</strong> &nbsp;&nbsp; 菜单名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="title" name="title" placeholder="请输菜单名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="sub_links" class="col-md-2 control-label"><strong style="color: red">*</strong> 子链接：</label>
                <div class="col-md-8">
                    <div class="container-fluid" style="padding-left: 0; margin-left: 0">
                        <table class="table table-bordered table-hover table-condensed">
                            <thead>
                            <tr>
                                <th class="text-center">名称</th>
                                <th class="text-center">链接</th>
                                <th width="10%" class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody class="text-center" id="menu-nodes">
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="sub_title[]" placeholder="请输链接名称" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="sub_link[]" placeholder="请输链接地址" />
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
                        <div class="col-md-2">
                            <a href="javascript: void(0) ;" class="btn btn-default btn-block" onclick="addRow()">
                                添加
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group hidden">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">自动生成</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addFlinksNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addFlinks()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-flink2Mng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>