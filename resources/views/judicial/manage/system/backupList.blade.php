<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            {{--<a type="button" data-key='none' data-method="add" onclick="backupMethod($(this))" class="btn btn-primary">新增</a>--}}
            <a type="button" data-key='none' onclick="javascript: $('#setting_modal').modal('show');" class="btn btn-primary">设置</a>
	        <!--自动备份设置模态框-->
            <div class="modal fade" tabindex="-1" role="dialog" id="setting_modal" aria-labelledby="gridSystemModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="gridSystemModalLabel">数据备份设置</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" id="setting_form">
	                            <div class="form-group">
                                    <label for="to_message" class="col-md-3 control-label">下次备份时间：</label>
                                    <div class="col-md-4">
	                                    <input @if($next_info['cycle_type'] != 'no') disabled @endif  type="text" class="form-control" id="date" name="date" placeholder="请选择周期" value="{{ $next_info['date'] }}">
	                                    <input type="hidden" class="form-control" id="cycle_date" name="cycle_date" value="{{ $next_info['date'] }}">
                                    </div>
		                            <div class="col-md-3">
	                                    <input @if($next_info['cycle_type'] != 'no') disabled @endif type="text" class="form-control" id="time" name="time" placeholder="请选择周期" value="{{ $next_info['time'] }}">
	                                    <input type="hidden" class="form-control" id="cycle_time" name="cycle_time" value="{{ $next_info['time'] }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="to_message" class="col-md-3 control-label">备份周期：</label>
                                    <div class="col-md-7">
                                        <select name="cycle" id="cycle" class="form-control" onchange="changeCycle($(this))">
                                            <option value="no" @if($next_info['cycle_type'] == 'no') selected @endif >无</option>
                                            <option value="day" @if($next_info['cycle_type'] == 'day') selected @endif>每天</option>
                                            <option value="week" @if($next_info['cycle_type'] == 'week') selected @endif>每七天</option>
                                            <option value="month" @if($next_info['cycle_type'] == 'month') selected @endif>每月</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="confirmSetting()">保存</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
                        </div>
                    </div>
                </div>
            </div><!--自动备份设置模态框End-->
        </div>
        <hr/>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">备份时间</th>
                        <th class="text-center">状态</th>
                        <th class="text-center">类型</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($backup_list as $backup)
                <tr>
                    <td>
                        <a href="{{ $backup['file_url'] }}" target="_blank">下载</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $backup['key'] }}" data-method="delete" onclick="backupMethod($(this))">删除</a>
                    </td>
                    <td>{{ $backup['backup_date'] }}</td>
                    <td>
                         已创建
                    </td>
	                <td>
		                @if($backup['type'] == 'auto')
							自动备份
							@else
							手动备份
		                @endif
	                </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!--分页-->
        @if(isset($pages) && is_array($pages) && $pages != 'none')
            @include('judicial.manage.chips.systemPages')
        @endif
    </div>
</div>

<script type="text/javascript">
    $.datetimepicker.setLocale('zh');
    var logic = function( currentDateTime ){
        if (currentDateTime && currentDateTime.getDay() == 6){
            this.setOptions({
                minTime:'11:00'
            });
        }else
            this.setOptions({
                minTime:'8:00'
            });
    };
    $('#date').datetimepicker({
        lang: 'zh',
        format: "Y-m-d",
        formatDate: "Y-m-d",
        todayButton: false,
        timepicker:false,
	    setLocale: "{{ $next_info['date'] }}"
    });
	$('#time').datetimepicker({
        lang: 'zh',
        format: "H:i",
        formatDate: "H:i",
        todayButton: false,
        datepicker:false,
		setLocale: "{{ empty($next_info['time']) ? '00:00' : $next_info['time'] }}"
    });
</script>