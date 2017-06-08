<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="addBackupForm">
            <div class="form-group">
                <label for="backup_date" class="col-md-2 control-label"><strong style="color: red">*</strong> 备份时间：</label>
                <div class="col-md-3">
                    <input id="backup_date" class="form-control" name="backup_date" type="text" >
                </div>
            </div>
            <div class="form-group hidden">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-8">
                    <label for="create_date" class="control-label">自动生成</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addBackupNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addBackup()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="system-backupMng" onclick="loadContent($(this))">返回列表</button>
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
    $('#backup_date').datetimepicker({
        lang: 'zh',
        format: "Y-m-d H:i",
        formatDate: "Y-m-d H:i",
        todayButton: true,
        timepicker:true,
        onChangeDateTime: logic,
        onShow: logic
    });
</script>