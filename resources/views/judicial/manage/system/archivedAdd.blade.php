<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="addArchivedForm">
            <div class="form-group">
                <label for="type" class="col-md-2 control-label"><strong style="color: red">*</strong> 功能点：</label>
                <div class="col-md-3">
                    <select id="type" name="type" class="form-control">
                        @if(isset($type_list) && is_array($type_list) && count($type_list)>0)
                            @foreach($type_list as $key=> $type)
                                <option value="{{ $key }}">{{ $type }}</option>
                            @endforeach
                        @else
                            <option value="none">请选择功能点</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="date" class="col-md-2 control-label"><strong style="color: red">*</strong> 数据创建截止日期：</label>
                <div class="col-md-3">
                    <input id="date" class="form-control" name="date" type="text" >
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-8">
                    <p>自动生成</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addArchivedNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addArchived()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="system-archivedMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
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
        format: "Y-m-d H:i",
        formatDate: "Y-m-d H:i",
        todayButton: true,
        timepicker:true,
        onChangeDateTime: logic,
        onShow: logic
    });
</script>