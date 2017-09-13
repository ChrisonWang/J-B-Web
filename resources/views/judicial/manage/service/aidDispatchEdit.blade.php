<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/审批
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="editAidDispatchForm">
            <input type="hidden" name="key" value="{{ $apply_detail['key'] }}"/>

            <div class="form-group">
                <label for="record_code" class="col-md-2 control-label">编号：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['record_code'] }}</label>
                </div>
            </div>
	        <div class="form-group">
                <label for="aid_type" class="col-md-2 control-label">事项分类：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $legal_types[$apply_detail['aid_type']]['type_name'] }}</label>
                </div>
            </div>
	        <div class="form-group">
                <label for="case_type" class="col-md-2 control-label">案件分类：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $case_types[$apply_detail['case_type']] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="apply_office" class="col-md-2 control-label">申请单位：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['apply_office'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="apply_aid_office" class="col-md-2 control-label">申请援助单位：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['apply_aid_office'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="criminal_name" class="col-md-2 control-label">犯罪人姓名：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['criminal_name'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="criminal_id" class="col-md-2 control-label">犯罪人身份号：</label>
                <div class="col-md-3">
                     <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['criminal_id'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="case_name" class="col-md-2 control-label">案件名称：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['case_name'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="case_description" class="col-md-2 control-label">涉嫌犯罪内容：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['case_description'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="detention_location" class="col-md-2 control-label">收押居住地：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['detention_location'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="judge_description" class="col-md-2 control-label">判刑处罚内容：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">{{ $apply_detail['judge_description'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">附件：</label>
                <div class="col-md-3">
                    <label for="name" class="control-label" style="text-align: left">
                        @if((empty($apply_detail['file_name'])||empty($apply_detail['file'])))
                            未上传附件！
                        @else
                            <a href="{{$apply_detail['file']}}" target="_blank">{{$apply_detail['file_name']}}</a>
                        @endif
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">最新提交时间：</label>
                <div class="col-md-5">
                    <label for="name" class="control-label" style="text-align: left">
                        {{ $apply_detail['apply_date'] }}
                        （第 {{ $apply_detail['approval_count'] + 1 }} 次提交）
                    </label>
                </div>
            </div>

            <hr/>
	        {{--如果是待指派状态--}}
            @if(isset($apply_detail['status']) && $apply_detail['status'] == 'waiting')
				<div class="form-group">
                    <label for="name" class="col-md-2 control-label">申请状态：</label>
                    <div class="col-md-3">
                        <label for="name" class="control-label" style="text-align: left">
                            待指派
                        </label>
                    </div>
                </div>
	            <div class="form-group">
                    <label for="lawyer_office" class="col-md-2 control-label">指派事务所：</label>
                    <div class="col-md-3">
                        <select class="form-control" id="lawyer_office" name="lawyer_office" onchange="getLawyer($(this))">
	                        <option value="none" selected>请选择律师事务所</option>
	                        @if(isset($lawyer_office_list) && !empty($lawyer_office_list))
		                        @foreach($lawyer_office_list as $office)
			                        <option value=" {{ $office['id'] }} ">{{ $office['name'] }}</option>
		                        @endforeach
							@endif
                        </select>
                    </div>
                </div>
	            <div class="form-group">
                    <label for="lawyer" class="col-md-2 control-label">指派律师：</label>
                    <div class="col-md-3">
                        <select class="form-control" id="lawyer" name="lawyer">
	                        <option value="none" selected>请选择律师</option>
	                        @if(isset($lawyer_list) && !empty($lawyer_list))
		                        @foreach($lawyer_list as $lawyer)
			                        <option value=" {{ $lawyer['id'].'|'.$lawyer['office_phone'].'|'.$lawyer['name'] }} ">{{ $lawyer['name'] }}</option>
		                        @endforeach
							@endif
                        </select>
                    </div>
                </div>
				<div class="form-group">
                <label for="name" class="col-md-2 control-label">审批意见：</label>
	                <div class="col-md-3">
	                    <textarea class="form-control" name="approval_opinion" id="approval_opinion"></textarea>
	                </div>
                </div>
                <div class="form-group">
	                <div class="col-md-offset-1 col-md-10">
	                    <p class="text-left hidden" id="editAidDispatchNotice" style="color: red"></p>
	                </div>
                </div>
	            {{--按钮们--}}
	            <div class="form-group">
	                <hr/>
	                <div class="col-md-offset-1 col-md-2">
	                    <button type="button" class="btn btn-info btn-block" data-method="pass" onclick="editAidDispatch($(this))">确认指派</button>
	                </div>
	                <div class="col col-md-2">
	                    <button type="button" class="btn btn-info btn-block" data-method="reject" onclick="editAidDispatch($(this))">驳回</button>
	                </div>
	                <div class="col col-md-2">
	                    <button type="button" class="btn btn-danger btn-block" data-node="service-aidDispatchMng" onclick="loadContent($(this))">返回</button>
	                </div>
	            </div>

			{{--如果是已指派、未结案状态--}}
            @else
				<div class="form-group">
                    <label for="name" class="col-md-2 control-label">申请状态：</label>
                    <div class="col-md-3">
                        <label for="name" class="control-label" style="text-align: left">
                            待结案
                        </label>
                    </div>
                </div>
	            <div class="form-group">
                    <label for="name" class="col-md-2 control-label">指派事务所：</label>
                    <div class="col-md-3">
                        <label for="name" class="control-label" style="text-align: left">
                            {{ $lawyer_office_list[$apply_detail['lawyer_office_id']]['name'] }}
                        </label>
                    </div>
                </div>
	            <div class="form-group">
                    <label for="name" class="col-md-2 control-label">指派律师：</label>
                    <div class="col-md-3">
                        <label for="name" class="control-label" style="text-align: left">
                            {{ $lawyer_list[$apply_detail['lawyer_id']]['name'] }}
                        </label>
                    </div>
                </div>
                <div class="form-group">
	                <div class="col-md-offset-1 col-md-10">
	                    <p class="text-left hidden" id="editAidDispatchNotice" style="color: red"></p>
	                </div>
                </div>
	            {{--按钮们--}}
	            <div class="form-group">
	                <hr/>
	                <div class="col-md-offset-1 col-md-2">
	                    <button type="button" class="btn btn-info btn-block" data-method="archived" onclick="editAidDispatch($(this))">结案</button>
	                </div>
	                <div class="col col-md-2">
	                    <button type="button" class="btn btn-danger btn-block" data-node="service-aidDispatchMng" onclick="loadContent($(this))">返回</button>
	                </div>
	            </div>
            @endif
        </form>
    </div>
</div>

<script>
	function getLawyer(t) {
		var id = t.val();
		var url = '/manage/service/aidDispatch/getLawyer/' + id;
		$.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        type: "GET",
        url: url,
        success: function(re){
            if(re.status == 'succ'){
	            var list = re.res;
				var options = '<option value="none" selected>请选择律师</option>';
	            $.each(list, function(i,sub){
                    options += '<option value="'+sub.id+'|'+sub.office_phone+'|'+sub.name+'">'+sub.name+'</option>';
                });
                $('#lawyer').html(options);
            }
            else if(re.status == 'failed'){
                var options = '<option value="none" selected>该事务所下未找到律师</option>';
	            $('#lawyer').html(options);
            }
        }
    });
	}
</script>