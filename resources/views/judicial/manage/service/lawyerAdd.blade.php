<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="addLawyerForm">
            <div class="form-group">
                <label for="name" class="col-md-1 control-label"><strong style="color: red">*</strong> 姓名：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="请输入律师姓名" />
                </div>
            </div>
            <div class="form-group">
                <label for="thumb" class="col-md-1 control-label">头像：</label>
                <div class="col-md-3">
                    <i class="fa fa-paperclip"></i>上传头像图片
                    <input type="file" class="btn btn-default btn-file form-control" id="thumb" name="thumb" onchange="upload_img($(this))" />
                </div>
            </div>
            <div class="form-group hidden" id="image-thumbnail">
                <label for="image-holder" class="col-md-1 control-label">预览：</label>
                <div class="col-md-3" id="image-holder"></div>
            </div>
            <div class="form-group">
                <label for="sex" class="col-md-1 control-label">性别：</label>
                <div class="col-md-3">
                    <select class="form-control" id="sex" name="sex">
                        <option value="male" selected>男</option>
                        <option value="female">女</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="nationality" class="col-md-1 control-label">民族：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="nationality" name="nationality" placeholder="请输入民族" />
                </div>
            </div>
            <div class="form-group">
                <label for="education" class="col-md-1 control-label">学历（最高）：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="education" name="education" placeholder="请输入学历" />
                </div>
            </div>
            <div class="form-group">
                <label for="major" class="col-md-1 control-label">专业：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="major" name="major" placeholder="请输入专业" />
                </div>
            </div>
            <div class="form-group">
                <label for="political" class="col-md-1 control-label">政治面貌：</label>
                <div class="col-md-3">
                    <select class="form-control" id="political" name="political">
                        <option value="citizen">群众</option>
                        <option value="cp">党员</option>
                        <option value="cyl">团员</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="religion" class="col-md-1 control-label">宗教：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="religion" name="religion" placeholder="请输入宗教信仰" />
                </div>
            </div>
            <div class="form-group">
                <label for="is_partner" class="col-md-1 control-label">是否合伙人：</label>
                <input type="checkbox" class="form-control" id="is_partner" name="is_partner" value="yes"/>
            </div>
            <div class="form-group">
                <label for="partnership_date" class="col-md-1 control-label">首次成为合伙人时间：</label>
                <div class="col-md-3">
                    <input id="partnership_date" class="form-control" name="partnership_date" type="text" >
                </div>
            </div>
            <div class="form-group">
                <label for="certificate_type" class="col-md-1 control-label">证书类型：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="certificate_type" name="certificate_type" placeholder="请输入证书类型" />
                </div>
            </div>
            <div class="form-group">
                <label for="certificate_date" class="col-md-1 control-label">证书取得时间：</label>
                <div class="col-md-3">
                    <input id="certificate_date" class="form-control" name="certificate_date" type="text" >
                </div>
            </div>
            <div class="form-group">
                <label for="province" class="col-md-1 control-label">首次执业省市：</label>
                <div class="col-md-3">
                    <input id="province" class="form-control" name="province" type="text" >
                </div>
            </div>
            <div class="form-group">
                <label for="job_date" class="col-md-1 control-label">首次执业时间：</label>
                <div class="col-md-3">
                    <input id="job_date" class="form-control" name="job_date" type="text" >
                </div>
            </div>
            <div class="form-group">
                <label for="office_phone" class="col-md-1 control-label">单位电话：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="office_phone" name="office_phone" placeholder="请输入单位电话" />
                </div>
            </div>
            <div class="form-group">
                <label for="zip_code" class="col-md-1 control-label">邮编：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="请输入邮编" />
                </div>
            </div>
            <div class="form-group">
                <label for="is_pra" class="col-md-1 control-label">具有何国永久居留权：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="is_pra" name="is_pra" placeholder="具有何国永久居留权" />
                </div>
            </div>
            <div class="form-group">
                <label for="type" class="col-md-1 control-label">人员类型：</label>
                <div class="col-md-3">
                    <select class="form-control" id="type" name="type">
                        <option value="full_time" selected>专职</option>
                        <option value="part_time">兼职</option>
                        <option value="company">公司</option>
                        <option value="officer">公职</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="col-md-1 control-label">状态：</label>
                <div class="col-md-3">
                    <select class="form-control" id="status" name="status">
                        <option value="normal" selected>执业</option>
                        <option value="cancel">注销</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="lawyer_office" class="col-md-1 control-label">所属律所：</label>
                <div class="col-md-3">
                    <select class="form-control" id="lawyer_office" name="lawyer_office">
                        @if($office_list != 'none' && is_array($office_list))
                            @foreach($office_list as $key=> $office_name)
                                <option value="{{ $key }}" selected>{{ $office_name }}</option>
                            @endforeach
                        @else
                            <option value="none" selected>请录入律师事务所</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="department" class="col-md-1 control-label">部门：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="department" name="department" placeholder="请输入部门" />
                </div>
            </div>
            <div class="form-group">
                <label for="position" class="col-md-1 control-label">职位：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="position" name="position" placeholder="请输入职位" />
                </div>
            </div>
            <div class="form-group">
                <label for="certificate_code" class="col-md-1 control-label"><strong style="color: red">*</strong> 执业资格证书编号</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="certificate_code" name="certificate_code" placeholder="请输入执业资格证书编号" />
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-8">
                    <p>自动生成</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="addLawyerNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-info btn-block" onclick="addLawyer()">确认</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-lawyerMng" onclick="loadContent($(this))">返回列表</button>
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
    $('#partnership_date').datetimepicker({
        lang: 'zh',
        format: "Y-m-d",
        formatDate: "Y-m-d",
        todayButton: true,
        timepicker:false,
        onChangeDateTime: logic,
        onShow: logic
    });
    $('#certificate_date').datetimepicker({
        lang: 'zh',
        format: "Y-m-d",
        formatDate: "Y-m-d",
        todayButton: true,
        timepicker:false,
        onChangeDateTime: logic,
        onShow: logic
    });
    $('#job_date').datetimepicker({
        lang: 'zh',
        format: "Y-m-d",
        formatDate: "Y-m-d",
        todayButton: true,
        timepicker:false,
        onChangeDateTime: logic,
        onShow: logic
    });
</script>