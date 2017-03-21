<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            @if(isset($is_archived))
                <button type="button" class="btn btn-danger" data-node="system-archivedMng" onclick="loadContent($(this))">返回归档列表</button>
            @endif
        </div>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">申请编号</th>
                        <th class="text-center">审批状态</th>
                        <th class="text-center">申请人姓名</th>
                        <th class="text-center">联系电话</th>
                        <th class="text-center">类型</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($apply_list as $apply)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $apply['key'] }}" data-archived_key="{{ $archived_key }}" data-method="show" data-archived="{{ (isset($is_archived)&&$is_archived=='yes') ? 'yes' : 'no' }}" onclick="expertiseApplyMethod($(this))">查看</a>
                        @if($apply['approval_result'] == 'waiting' && !isset($is_archived))
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $apply['key'] }}" data-method="edit" onclick="expertiseApplyMethod($(this))">审批</a>
                        &nbsp;&nbsp;
                        @endif
                    </td>
                    <td>{{ $apply['record_code'] }}</td>
                    <td>
                        @if($apply['approval_result'] == 'pass')
                            <p style="color:green; font-weight: bold">通过</p>
                        @elseif($apply['approval_result'] == 'reject')
                            <p style="color:red; font-weight: bold">驳回</p>
                        @else
                            <p style="color:#FFA500; font-weight: bold">待审核</p>
                        @endif
                    </td>
                    <td>{{ $apply['apply_name'] }}</td>
                    <td>{{ $apply['cell_phone'] }}</td>
                    <td>@if(isset($type_list) && is_array($type_list) && count($type_list)>0){{ isset($type_list[$apply['type_id']]) ? $type_list[$apply['type_id']] : '-' }}@else - @endif</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!--分页-->
        @if(isset($pages) && is_array($pages) && $pages != 'none')
            @include('judicial.manage.chips.servicePages')
        @endif
    </div>
</div>