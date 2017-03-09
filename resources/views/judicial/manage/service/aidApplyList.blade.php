<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="record_code">审批编号：</label>
                            <input type="text" class="form-control" id="record_code" name="record_code" placeholder="请输入审批编号">
                        </div>
                        <div class="form-group">
                            <label for="status">审批状态：</label>
                            <select class="form-control" name="status" id="status">
                                <option value="none">不限</option>
                                <option value="waiting">待审批</option>
                                <option value="pass">审批通过</option>
                                <option value="reject">审批未通过</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="apply_name">申请人姓名：</label>
                            <input type="text" class="form-control" id="apply_name" name="apply_name" placeholder="请输入申请人姓名">
                        </div>
                        <div class="form-group">
                            <label for="apply_phone">申请人电话：</label>
                            <input type="text" class="form-control" id="apply_phone" name="apply_phone" placeholder="请输入申请人电话">
                        </div>
                        <div class="form-group">
                            <label for="type">案件类型：</label>
                            <select class="form-control" name="type" id="type">
                                <option value="none">不限</option>
                                @foreach($type_list as $key=>$value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button id="search" type="button" class="btn btn-info" onclick="search_aidApply($(this), $('#this-container'))">搜索</button>
                    </form>
                </div>
            </div>
        </div>
        <hr/>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">申请编号</th>
                        <th class="text-center">审批状态</th>
                        <th class="text-center">申请人姓名</th>
                        <th class="text-center">申请人电话</th>
                        <th class="text-center">案件类型</th>
                        <th class="text-center">是否为讨薪</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($apply_list as $apply)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $apply['key'] }}" data-method="show" onclick="aidApplyMethod($(this))">查看</a>
                        @if($apply['status'] == 'waiting')
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $apply['key'] }}" data-method="edit" onclick="aidApplyMethod($(this))">审批</a>
                        &nbsp;&nbsp;
                        @endif
                    </td>
                    <td>{{ $apply['record_code'] }}</td>
                    <td>
                        @if($apply['status'] == 'pass')
                            <p style="color:green; font-weight: bold">审核通过</p>
                        @elseif($apply['status'] == 'reject')
                            <p style="color:red; font-weight: bold">审核未通过</p>
                        @else
                            <p style="color:#FFA500; font-weight: bold">待答复</p>
                        @endif
                    </td>
                    <td>{{ $apply['apply_name'] }}</td>
                    <td>{{ $apply['apply_phone'] }}</td>
                    <td>@if(isset($type_list) && is_array($type_list) && count($type_list)>0){{ isset($type_list[$apply['type']]) ? $type_list[$apply['type']] : '-' }}@else - @endif</td>
                    <td>{{ ($apply['salary_dispute']=='yes') ? '是' : '否' }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!--分页-->
        @if(isset($pages) && is_array($pages) && $pages != 'none')
            @include('judicial.manage.chips.pages')
        @endif
    </div>
</div>