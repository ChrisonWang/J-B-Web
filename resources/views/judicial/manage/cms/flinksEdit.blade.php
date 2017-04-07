<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            一/二级友情链接管理/编辑
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
        <form class="form-horizontal" id="flinksEditForm">
            <input type="hidden" id="key" name="key" value="{{ $flinks_detail['key'] }}" />
            <div class="form-group">
                <label for="title" class="col-md-2 control-label">名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="title" name="title" value="{{ $flinks_detail['title'] }}" placeholder="请输一级分类名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="menu-nodes" class="col-md-2 control-label">子链接：</label>
                <div class="col-md-8">
                    <div class="container-fluid">
                        <table class="table table-bordered table-hover table-condensed">
                            <thead>
                            <tr>
                                <th class="text-center">名称</th>
                                <th class="text-center">链接</th>
                                <th width="10%" class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody class="text-center" id="menu-nodes">
                            @if(isset($flinks_detail['sub_links']) && $flinks_detail['sub_links'] != 'none' && is_array($flinks_detail['sub_links']))
                                @foreach($flinks_detail['sub_links'] as $sub)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" data-key="{{ $sub['key'] }}" name="sub_title[]" value="{{ $sub['title'] }}" placeholder="请输链接名称" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="sub_link[]" value="{{ $sub['link'] }}" placeholder="请输链接地址" />
                                        </td>
                                        <td>
                                            <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="sub_title[]" placeholder="请输链接名称" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="sub_title[]" placeholder="请输链接地址" />
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
                    <p>{{ $flinks_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="flinksEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="editFlinks()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-flink2Mng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>