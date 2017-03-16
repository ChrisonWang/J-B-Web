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
            <td>@if($certificate['last_status']=='waiting') 未发送 @else 发送成功！@endif</td>
        </tr>
    @endforeach
    </tbody>
</table>