<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            机构分类管理
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="departmentEditForm">
            <input type="hidden" name="key" value="{{ $department_detail['key'] }}" />
            <div class="form-group">
                <label for="department_name" class="col-md-1 control-label">名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="{{ $department_detail['department_name'] }}" id="department_name" name="department_name" placeholder="请输入分类名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="type_id" class="col-md-1 control-label">分类：</label>
                <div class="col-md-3">
                    <select id="type_id" name="type_id" class="form-control">
                        @foreach ($type_list as $type)
                            <option value="{{ $type['type_id'] }}" @if($type['checked']=='yes') selected @endif>{{ $type['type_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="typeName" class="col-md-1 control-label">排序：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="{{ $department_detail['sort'] }}" id="sort" name="sort" placeholder="请输入权重（数字越大越靠前）" />
                </div>
            </div>
            <div class="form-group">
                <label for="UE_Content" class="col-md-1 control-label">简介：</label>
                <div class="col-md-5">
                    <script id="UE_Content" name="description" type="text/plain"></script>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="departmentEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-info btn-block" onclick="editDepartment()">确认</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-department" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var UE_Content = UE.getEditor('UE_Content');
    UE_Content.ready(function(){
        var value = '{{ $department_detail['description'] }}';
        UE_Content.execCommand('insertHtml',value);
    });
</script>