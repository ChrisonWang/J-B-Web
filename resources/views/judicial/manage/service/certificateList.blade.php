<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <a type="button" data-key='none' data-method="add" onclick="certificateMethod($(this))" class="btn btn-primary">新增</a>
        </div>
        <hr/>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%" class="text-center">操作</th>
                        <th class="text-center">姓名</th>
                        <th class="text-center">证件号码</th>
                        <th class="text-center">证书编号</th>
                        <th class="text-center">取得证书日期</th>
                        <th class="text-center">联系方式</th>
                        <th class="text-center">最近一条短信状态</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($certificate_list as $certificate)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $certificate['key'] }}" data-method="show" onclick="certificateMethod($(this))">查看</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $certificate['key'] }}" data-method="edit" onclick="certificateMethod($(this))">编辑</a>
                        &nbsp;&nbsp;
                        <a href="javascript: void(0) ;" data-key="{{ $certificate['key'] }}" data-method="delete" data-title="{{ $certificate['name'] }}" onclick="certificateMethod($(this))">删除</a>
                    </td>
                    <td>{{ $certificate['name'] }}</td>
                    <td>{{ $certificate['citizen_code'] }}</td>
                    <td>{{ $certificate['certi_code'] }}</td>
                    <td>{{ $certificate['certificate_date'] }}</td>
                    <td>{{ $certificate['phone'] }}</td>
                    <td>{{ $certificate['last_status'] }}</td>
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