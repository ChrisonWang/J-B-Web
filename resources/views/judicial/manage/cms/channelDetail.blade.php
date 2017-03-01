<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            频道管理/查看
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

    <div class="panel-body">
        <form class="form-horizontal" id="channelEditForm">
            <input type="hidden" name="key" value="{{ $channel_detail['key'] }}"/>
            <div class="form-group">
                <label for="channel_title" class="col-md-1 control-label">菜单名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="{{ $channel_detail['channel_title'] }}" id="channel_title" name="channel_title" placeholder="请输菜单名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="is_recommend" class="col-md-1 control-label">是否首页推荐：</label>
                <div class="col-md-3">
                    <input type="checkbox" class="form-control" id="is_recommend" name="is_recommend" value="yes" @if($channel_detail['is_recommend'] == 'yes') checked @endif />
                </div>
            </div>
            <div class="form-group">
                <label for="form_download" class="col-md-1 control-label">是否开启表单下载：</label>
                <div class="col-md-3">
                    <input type="checkbox" class="form-control" id="form_download" name="form_download" value="yes" @if($channel_detail['form_download'] == 'yes') checked @endif/>
                </div>
            </div>
            <div class="form-group">
                <label for="zwgk" class="col-md-1 control-label">是否归属政务公开：</label>
                <div class="col-md-3">
                    <input type="checkbox" class="form-control" id="zwgk" name="zwgk" value="yes"  @if($channel_detail['zwgk'] == 'yes') checked @endif/>
                </div>
            </div>
            <div class="form-group">
                <label for="wsbs" class="col-md-1 control-label">是否归属网上办事：</label>
                <div class="col-md-3">
                    <input type="checkbox" class="form-control" id="wsbs" name="wsbs" value="yes"  @if($channel_detail['wsbs'] == 'yes') checked @endif/>
                </div>
            </div>
            <div class="form-group">
                <label for="sort" class="col-md-1 control-label">排序权重：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="sort" name="sort" value="{{ $channel_detail['sort'] }}" placeholder="请输入权重（数字越大越靠前）" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-1 control-label">子频道：</label>
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
                            @if($subs != 'none' && is_array($subs))
                                @foreach($subs as $sub)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="sub-channel_title" data-key="{{ $sub['key'] }}" value="{{ $sub['channel_title'] }}" placeholder="请输频道名称" />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="sub-zwgk" value="yes" @if($sub['zwgk'] == 'yes') checked @endif/>
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="sub-wsbs" value="yes" @if($sub['wsbs'] == 'yes') checked @endif/>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="sub-sort" value="{{ $sub['sort'] }}" placeholder="请输入权重（数字越大越靠前）" />
                                        </td>
                                        <td>
                                            <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                                        </td>
                                    </tr>
                                @endforeach
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
                    <p>{{ $channel_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="channelEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-channelMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>