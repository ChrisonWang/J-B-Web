<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="addConsultionTypesForm">
            <div class="form-group">
                <label for="type_name" class="col-md-2 control-label"><strong style="color: red">*</strong> 分类名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="type_name" name="type_name" value="" placeholder="请输入分类名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="office_id" class="col-md-2 control-label">科室：</label>
                <div class="col-md-3">
                    <select class="form-control" id="office_id" name="office_id" onchange="getManager($(this), $('#manager_code'))">
	                    @if(is_null($office) || count($office)<=0)
                            <option value="none">未设置科室</option>
							@else
								@foreach($office as $o)
									<option value="{{ $o->id }}" >{{ $o->office_name }}</option>
								@endforeach
	                    @endif
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="manager_code" class="col-md-2 control-label">负责人：</label>
                <div class="col-md-3">
                    <select class="form-control" id="manager_code" name="manager_code">
                        @if(is_null($managers) || count($managers)<=0)
                            <option value="none">未设负责人</option>
							@else
								@foreach($managers as $m)
									<option value="{{ $m['manager_code'] }}">
										{{ !empty($m['nickname']) ? $m['nickname'].' ['.$m['cell_phone'].']' : $m['login_name'].' ['.$m['cell_phone'].']' }}
									</option>
								@endforeach
	                    @endif
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addConsultionTypesNotice" style="color: red"></label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" data-method="pass" onclick="addConsultionTypes()">提交</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-consultionTypesMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>