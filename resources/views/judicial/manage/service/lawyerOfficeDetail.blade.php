<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="editLawyerOfficeForm">
            <input type="hidden" name="key" value="{{ $office_detail['key'] }}">
            <div class="form-group">
                <label for="name" class="col-md-1 control-label"><strong style="color: red">*</strong> 名称：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="name" name="name" value="{{ $office_detail['name'] }}" placeholder="请输入事务所名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-md-1 control-label">英文名称：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="" name="en_name" value="{{ $office_detail['en_name'] }}" placeholder="请输入事务所英文名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="address" class="col-md-1 control-label">地址：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="address" name="address" value="{{ $office_detail['address'] }}" placeholder="请输入所在地址" />
                </div>
            </div>
            <div class="form-group">
                <label for="zip_code" class="col-md-1 control-label">邮编：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="zip_code" name="zip_code" value="{{ $office_detail['zip_code'] }}" placeholder="请输入邮编" />
                </div>
            </div>
            <div class="form-group">
                <label for="area" class="col-md-1 control-label">区域：</label>
                <div class="col-md-3">
                    <select disabled class="form-control" id="area" name="area">
                        @if($area_list != 'none' && is_array($area_list))
                            @foreach($area_list as $key=> $area)
                                <option value="{{ $key }}" @if($office_detail['area_id']==$key) selected @endif>{{ $area }}</option>
                            @endforeach
                            @else
                            <option value="none">请先设置区域！</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="justice_bureau" class="col-md-1 control-label">主管司法局：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="justice_bureau" name="justice_bureau" value="{{ $office_detail['justice_bureau'] }}" placeholder="请输入主管司法局名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="usc_code" class="col-md-1 control-label">统一社会信用代码：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="usc_code" name="usc_code" value="{{ $office_detail['usc_code'] }}" placeholder="请输入统一社会信用代码" />
                </div>
            </div>
            <div class="form-group">
                <label for="certificate_date" class="col-md-1 control-label">发证日期：</label>
                <div class="col-md-3">
                    <p>{{ $office_detail['certificate_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="director" class="col-md-1 control-label">主任：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="director" name="director" value="{{ $office_detail['director'] }}" placeholder="请输入事务所主任姓名" />
                </div>
            </div>
            <div class="form-group">
                <label for="type" class="col-md-1 control-label">类型：</label>
                <div class="col-md-3">
                    <select disabled class="form-control" id="type" name="type">
                        <option value="{{ $office_detail['type'] }}">{{ $type_list[$office_detail['type']] }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="col-md-1 control-label">状态：</label>
                <div class="col-md-3">
                    <select disabled class="form-control" id="status" name="status">
                        <option value="none">@if($office_detail['type']=='normal')正常@else注销@endif</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="status_description" class="col-md-1 control-label">状态说明：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="status_description" name="status_description" value="{{ $office_detail['status_description'] }}" placeholder="请输入状态说明" />
                </div>
            </div>
            <div class="form-group">
                <label for="group_type" class="col-md-1 control-label">组织形式：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="group_type" name="group_type" value="{{ $office_detail['group_type'] }}" placeholder="请输入组织形式" />
                </div>
            </div>
            <div class="form-group">
                <label for="fund" class="col-md-1 control-label">注册资金（万元）：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="fund" name="fund" value="{{ $office_detail['fund'] }}" placeholder="请输入注册资金" />
                </div>
            </div>
            <div class="form-group">
                <label for="office_phone" class="col-md-1 control-label">办公电话：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="office_phone" name="office_phone" value="{{ $office_detail['office_phone'] }}" placeholder="请输入办公电话号码" />
                </div>
            </div>
            <div class="form-group">
                <label for="fax" class="col-md-1 control-label">传真：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="fax" name="fax" value="{{ $office_detail['fax'] }}" placeholder="请输入传真号码" />
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-1 control-label">Email：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="email" name="email" value="{{ $office_detail['email'] }}" placeholder="请输入事务所Email" />
                </div>
            </div>
            <div class="form-group">
                <label for="web_site" class="col-md-1 control-label">主页：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="web_site" name="web_site" value="{{ $office_detail['web_site'] }}" placeholder="请输入事务所主页" />
                </div>
            </div>
            <div class="form-group">
                <label for="office_area" class="col-md-1 control-label">场地面积（平米）：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="office_area" name="office_area" value="{{ $office_detail['office_area'] }}" placeholder="请输入办公场地面面积" />
                </div>
            </div>
            <div class="form-group">
                <label for="office_space_type" class="col-md-1 control-label">场所性质：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="office_space_type" name="office_space_type" value="{{ $office_detail['office_space_type'] }}" placeholder="请输入场所性质" />
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="col-md-1 control-label">事务所简介：</label>
                <div class="col-md-3">
                    <textarea disabled id="description" name="description" class="form-control" placeholder="请输入事务所简介">
                        {{ $office_detail['description'] }}
                    </textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="map_code" class="col-md-1 control-label">地图代码：</label>
                <div class="col-md-3">
                    <textarea disabled id="map_code" name="map_code" class="form-control" placeholder="请输入事务地图代码">
                        {{ $office_detail['map_code'] }}
                    </textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-8">
                    <p>{{ $office_detail['create_date'] }}</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="editLawyerOfficeNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-lawyerOfficeMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>