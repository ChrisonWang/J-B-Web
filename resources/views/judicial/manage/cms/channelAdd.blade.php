<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            频道管理/新增
        </h3>
    </div>
    <!--隐藏的模板-->
    <div id="node-row" hidden >
        <table class="table table-bordered table-hover table-condensed">
            <tbody class="text-center">
            <tr>
                <td>
                    <input type="text" class="form-control" name="sub-channel_title" placeholder="请输频道名称" />
                </td>
                <td>
                    <input type="checkbox" class="form-control" name="sub-zwgk" value="yes"/>
                </td>
                <td>
                    <input type="checkbox" class="form-control" name="sub-wsbs" value="yes"/>
                </td>
                <td>
                    <input type="text" class="form-control" name="sub-sort" placeholder="请输入权重（数字越大越靠前）" />
                </td>
                <td>
                    <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="panel-body">
        <form class="form-horizontal" id="channelAddForm">
            <div class="form-group">
                <label for="channel_title" class="col-md-1 control-label"><strong style="color: red">*</strong> 频道名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="channel_title" name="channel_title" placeholder="请输频道名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="is_recommend" class="col-md-1 control-label">是否首页推荐：</label>
                <div class="col-md-3">
                    <input type="checkbox" class="form-control" id="is_recommend" name="is_recommend" value="yes" checked/>
                </div>
            </div>
            <div class="form-group">
                <label for="form_download" class="col-md-1 control-label">是否开启表单下载：</label>
                <div class="col-md-3">
                    <input type="checkbox" class="form-control" id="form_download" name="form_download" value="yes" checked/>
                </div>
            </div>
            <div class="form-group">
                <label for="zwgk" class="col-md-1 control-label">是否归属政务公开：</label>
                <div class="col-md-3">
                    <input type="checkbox" class="form-control" id="zwgk" name="zwgk" value="yes" onclick="checkBoxDisabled($(this))" checked/>
                </div>
            </div>
            <div class="form-group">
                <label for="wsbs" class="col-md-1 control-label">是否归属网上办事：</label>
                <div class="col-md-3">
                    <input type="checkbox" class="form-control" id="wsbs" name="wsbs" value="yes" onclick="checkBoxDisabled($(this))" checked/>
                </div>
            </div>
            <div class="form-group">
                <label for="sort" class="col-md-1 control-label">排序权重：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="sort" name="sort" placeholder="请输入权重（数字越大越靠前）" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-1 control-label"><strong style="color: red">*</strong> 子频道：</label>
                <div class="col-md-8">
                    <div class="container-fluid">
                        <table class="table table-bordered table-hover table-condensed">
                            <thead>
                            <tr>
                                <th class="text-center">名称</th>
                                <th class="text-center">归属政务公开</th>
                                <th class="text-center">归属网上办事</th>
                                <th class="text-center">排序</th>
                                <th width="10%" class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody class="text-center" id="menu-nodes">
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="sub-channel_title" placeholder="请输频道名称" />
                                </td>
                                <td>
                                    <input type="checkbox" class="form-control" name="sub-zwgk" value="yes" checked/>
                                </td>
                                <td>
                                    <input type="checkbox" class="form-control" name="sub-wsbs" value="yes" checked/>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="sub-sort" placeholder="请输入权重（数字越大越靠前）" />
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
                            <a class="btn btn-default btn-block" onclick="addRow()">
                                添加
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-3">
                    <p>自动生成</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="addChannelNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-info btn-block" onclick="addChannel()">确认</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-channelMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>