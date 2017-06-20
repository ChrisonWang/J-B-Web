<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/编辑
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="editLawyerForm">
            <input type="hidden" value="{{ $lawyer_detail['key'] }}" id="key" name="key" />
            <div class="form-group">
                <label for="name" class="col-md-2 control-label"><strong style="color: red">*</strong> 姓名：</label>
                <div class="col-md-3">
                    <input type="text" value="{{ $lawyer_detail['name'] }}" class="form-control" id="name" name="name" placeholder="请输入律师姓名" />
                </div>
            </div>
            <div class="form-group">
                <label for="thumb" class="col-md-2 control-label">照片：</label>
                <div class="col-md-3">
                    <i class="fa fa-paperclip"></i>上传头像图片
                    <input type="file" id="thumb" class="btn btn-file" name="thumb" onchange="upload_img($(this))"/>
                </div>
            </div>
            @if( isset($lawyer_detail['thumb']) && $lawyer_detail['thumb'] != "none" )
                <div class="form-group" id="image-thumbnail">
                    <input type="hidden" name="have_photo" value="yes">
                    <label for="leader_photo" class="col-md-2 control-label">预览：</label>
                    <div class="col-md-3" id="image-holder">
                        <img src="{{ $lawyer_detail['thumb'] }}" class="img-thumbnail img-responsive">
                    </div>
                </div>
            @else
                <div class="form-group" id="image-thumbnail">
                    <label for="leader_photo" class="col-md-2 control-label">预览：</label>
                    <div class="col-md-3" id="image-holder">
                        <label for="create_date" class="control-label">未上传照片！</label>
                    </div>
                </div>
            @endif
            <div class="form-group">
                <label for="sex" class="col-md-2 control-label">性别：</label>
                <div class="col-md-3">
                    <select class="form-control" id="sex" name="sex">
                        <option value="male" @if( $lawyer_detail['sex'] == 'male' ) selected @endif>男</option>
                        <option value="female" @if( $lawyer_detail['sex'] == 'female' ) selected @endif>女</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="nationality" class="col-md-2 control-label">民族：</label>
                <div class="col-md-3">
                    <input type="text" value="{{ $lawyer_detail['nationality'] }}" class="form-control" id="nationality" name="nationality" placeholder="请输入民族" />
                </div>
            </div>
            <div class="form-group">
                <label for="education" class="col-md-2 control-label">学历（最高）：</label>
                <div class="col-md-3">
                    <input type="text" value="{{ $lawyer_detail['education'] }}" class="form-control" id="education" name="education" placeholder="请输入学历" />
                </div>
            </div>
            <div class="form-group">
                <label for="major" class="col-md-2 control-label">专业：</label>
                <div class="col-md-3">
                    <input type="text" value="{{ $lawyer_detail['major'] }}" class="form-control" id="major" name="major" placeholder="请输入专业" />
                </div>
            </div>
            <div class="form-group">
                <label for="political" class="col-md-2 control-label">政治面貌：</label>
                <div class="col-md-3">
                    <select class="form-control" id="political" name="political">
                        <option value="citizen" @if( $lawyer_detail['political'] == 'citizen') selected @endif>群众</option>
                        <option value="cp" @if( $lawyer_detail['political'] == 'cp' ) selected @endif>党员</option>
                        <option value="cyl" @if( $lawyer_detail['political'] == 'cyl' ) selected @endif>团员</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="religion" class="col-md-2 control-label">宗教：</label>
                <div class="col-md-3">
                    <input type="text" value="{{ $lawyer_detail['religion'] }}" class="form-control" id="religion" name="religion" placeholder="请输入宗教信仰" />
                </div>
            </div>
            <div class="form-group">
                <label for="is_partner" class="col-md-2 control-label">是否合伙人：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">
                        <input type="checkbox" class="" id="is_partner" name="is_partner" value="yes" @if($lawyer_detail['is_partner'] == 'yes') checked @endif/>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="partnership_date" class="col-md-2 control-label">首次成为合伙人时间：</label>
                <div class="col-md-3">
                    <input id="partnership_date" class="form-control" name="partnership_date" type="text" >
                </div>
            </div>
            <div class="form-group">
                <label for="certificate_type" class="col-md-2 control-label">证书类型：</label>
                <div class="col-md-3">
                    <input type="text" value="{{ $lawyer_detail['certificate_type'] }}" class="form-control" id="certificate_type" name="certificate_type" placeholder="请输入证书类型" />
                </div>
            </div>
            <div class="form-group">
                <label for="certificate_date" class="col-md-2 control-label">证书取得时间：</label>
                <div class="col-md-3">
                    <input id="certificate_date" class="form-control" name="certificate_date" type="text" >
                </div>
            </div>
            <div class="form-group">
                <label for="province" class="col-md-2 control-label">首次执业省市：</label>
                <div class="col-md-3">
                    <input id="province" value="{{ $lawyer_detail['province'] }}" class="form-control" name="province" type="text" >
                </div>
            </div>
            <div class="form-group">
                <label for="job_date" class="col-md-2 control-label">首次执业时间：</label>
                <div class="col-md-3">
                    <input id="job_date" class="form-control" name="job_date" type="text" >
                </div>
            </div>
            <div class="form-group">
                <label for="office_phone" class="col-md-2 control-label">单位电话：</label>
                <div class="col-md-3">
                    <input type="text" value="{{ $lawyer_detail['office_phone'] }}" class="form-control" id="office_phone" name="office_phone" placeholder="请输入单位电话" />
                </div>
            </div>
            <div class="form-group">
                <label for="zip_code" class="col-md-2 control-label">邮编：</label>
                <div class="col-md-3">
                    <input type="text" value="{{ $lawyer_detail['zip_code'] }}" class="form-control" id="zip_code" name="zip_code" placeholder="请输入邮编" />
                </div>
            </div>
            <div class="form-group">
                <label for="is_pra" class="col-md-2 control-label">具有何国永久居留权：</label>
                <div class="col-md-3">
                    <input type="text" value="{{ $lawyer_detail['is_pra'] }}" class="form-control" id="is_pra" name="is_pra" placeholder="具有何国永久居留权" />
                </div>
            </div>
            <div class="form-group">
                <label for="type" class="col-md-2 control-label">人员类型：</label>
                <div class="col-md-3">
                    <select class="form-control" id="type" name="type">
                        <option value="full_time" @if( $lawyer_detail['type'] == 'full_time') selected @endif>专职</option>
                        <option value="part_time" @if( $lawyer_detail['type'] == 'part_time') selected @endif>兼职</option>
                        <option value="company" @if( $lawyer_detail['type'] == 'company') selected @endif>公司</option>
                        <option value="officer" @if( $lawyer_detail['type'] == 'officer') selected @endif>公职</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="col-md-2 control-label">状态：</label>
                <div class="col-md-3">
                    <select class="form-control" id="status" name="status">
                        <option value="normal" @if( $lawyer_detail['status'] == 'normal') selected @endif>执业</option>
                        <option value="cancel" @if( $lawyer_detail['status'] == 'cancel') selected @endif>注销</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="lawyer_office" class="col-md-2 control-label">所属律所：</label>
                <div class="col-md-3">
                    <select class="form-control" id="lawyer_office" name="lawyer_office">
                        @if($office_list != 'none' && is_array($office_list))
                            @foreach($office_list as $key=> $office_name)
                                <option value="{{ $key }}" @if($lawyer_detail['lawyer_office'] == $key) selected @endif>{{ $office_name }}</option>
                            @endforeach
                        @else
                            <option value="none" selected>请录入律师事务所</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="department" class="col-md-2 control-label">部门：</label>
                <div class="col-md-3">
                    <input type="text" value="{{ $lawyer_detail['department'] }}" class="form-control" id="department" name="department" placeholder="请输入部门" />
                </div>
            </div>
            <div class="form-group">
                <label for="position" class="col-md-2 control-label">职位：</label>
                <div class="col-md-3">
                    <input type="text" value="{{ $lawyer_detail['position'] }}" class="form-control" id="position" name="position" placeholder="请输入职位" />
                </div>
            </div>
            <div class="form-group">
                <label for="certificate_code" class="col-md-2 control-label">执业资格证书编号</label>
                <div class="col-md-3">
                    <input type="text" value="{{ $lawyer_detail['certificate_code'] }}" class="form-control" id="certificate_code" name="certificate_code" placeholder="请输入执业资格证书编号" />
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-8">
                    <label for="create_date" class="control-label">{{ $lawyer_detail['create_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="editLawyerNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="editLawyer()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-lawyerMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
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
        format: "Y-m-d H:i",
        todayButton: true,
        onChangeDateTime: logic,
        onShow: logic
    }).setLocale('zh');
    $('#certificate_date').datetimepicker({
        lang: 'zh',
        format: "Y-m-d H:i",
        todayButton: true,
        onChangeDateTime: logic,
        onShow: logic
    }).setLocale('zh');
    $('#job_date').datetimepicker({
        lang: 'zh',
        format: "Y-m-d H:i",
        todayButton: true,
        onChangeDateTime: logic,
        onShow: logic
    }).setLocale('zh');
</script>