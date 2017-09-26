<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="addLawyerOfficeForm">
            <div class="form-group">
                <label for="name" class="col-md-2 control-label"><strong style="color: red">*</strong> 名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="请输入事务所名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-md-2 control-label">英文名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="" name="en_name" placeholder="请输入事务所英文名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="address" class="col-md-2 control-label">地址：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="address" name="address" placeholder="请输入所在地址" />
                </div>
            </div>
            <div class="form-group">
                <label for="zip_code" class="col-md-2 control-label">邮编：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="请输入邮编" />
                </div>
            </div>
            <div class="form-group">
                <label for="area" class="col-md-2 control-label">区域：</label>
                <div class="col-md-3">
                    <select class="form-control" id="area" name="area">
                        @if($area_list != 'none' && is_array($area_list))
                            @foreach($area_list as $key=> $area)
                                <option value="{{ $key }}">{{ $area }}</option>
                            @endforeach
                            @else
                            <option value="none">请先设置区域！</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="justice_bureau" class="col-md-2 control-label">主管司法局：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="justice_bureau" name="justice_bureau" placeholder="请输入主管司法局名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="usc_code" class="col-md-2 control-label">统一社会信用代码：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="usc_code" name="usc_code" placeholder="请输入统一社会信用代码" />
                </div>
            </div>
            <div class="form-group">
                <label for="certificate_date" class="col-md-2 control-label">发证日期：</label>
                <div class="col-md-3">
                    <input id="certificate_date" class="form-control" name="certificate_date" type="text" >
                </div>
            </div>
            <div class="form-group">
                <label for="director" class="col-md-2 control-label">主任：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="director" name="director" placeholder="请输入事务所主任姓名" />
                </div>
            </div>
            <div class="form-group">
                <label for="type" class="col-md-2 control-label">类型：</label>
                <div class="col-md-3">
                    <select class="form-control" id="type" name="type">
                        <option value="head">总所</option>
                        <option value="branch">分所</option>
                        <option value="personal">个人</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="col-md-2 control-label">状态：</label>
                <div class="col-md-3">
                    <select class="form-control" id="status" name="status">
                        <option value="normal">正常</option>
                        <option value="cancel">注销</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="status_description" class="col-md-2 control-label">状态说明：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="status_description" name="status_description" placeholder="请输入状态说明" />
                </div>
            </div>
            <div class="form-group">
                <label for="group_type" class="col-md-2 control-label">组织形式：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="group_type" name="group_type" placeholder="请输入组织形式" />
                </div>
            </div>
            <div class="form-group">
                <label for="fund" class="col-md-2 control-label">注册资金（万元）：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="fund" name="fund" placeholder="请输入注册资金" />
                </div>
            </div>
            <div class="form-group">
                <label for="office_phone" class="col-md-2 control-label">办公电话：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="office_phone" name="office_phone" placeholder="请输入办公电话号码" />
                </div>
            </div>
            <div class="form-group">
                <label for="fax" class="col-md-2 control-label">传真：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="fax" name="fax" placeholder="请输入传真号码" />
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-2 control-label">Email：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="email" name="email" placeholder="请输入事务所Email" />
                </div>
            </div>
            <div class="form-group">
                <label for="web_site" class="col-md-2 control-label">主页：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="web_site" name="web_site" placeholder="请输入事务所主页" />
                </div>
            </div>
            <div class="form-group">
                <label for="office_area" class="col-md-2 control-label">场地面积（平米）：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="office_area" name="office_area" placeholder="请输入办公场地面面积" />
                </div>
            </div>
            <div class="form-group">
                <label for="office_space_type" class="col-md-2 control-label">场所性质：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="office_space_type" name="office_space_type" placeholder="请输入场所性质" />
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="col-md-2 control-label">事务所简介：</label>
                <div class="col-md-3">
                    <textarea id="description" name="description" class="form-control"  placeholder="请输入事务所简介"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="map_code" class="col-md-2 control-label">地图代码：</label>
                <div class="col-md-3">
                    <textarea id="map_code" name="map_code" class="form-control"  placeholder="请输入事务地图代码"></textarea>
                    <br/>
                    <h5 class="text-left">
                        地图代码获取地址：<a href="http://api.map.baidu.com/mapCard/setInformation.html" target="_blank">点击获取代码</a>
                    </h5>
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
                    <p class="text-left hidden" id="addLawyerOfficeNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addLawyerOffice()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-lawyerOfficeMng" onclick="loadContent($(this))">返回列表</button>
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
    $('#certificate_date').datetimepicker({
        lang: 'zh',
        format: "Y-m-d",
        formatDate: "Y-m-d",
        todayButton: true,
        timepicker:false,
        onChangeDateTime: logic,
        onShow: logic
    }).setLocale('zh');
</script>