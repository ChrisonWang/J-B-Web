<table class="table table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th width="20%" class="text-center">操作</th>
        <th class="text-center">受理编号</th>
        <th class="text-center">主题</th>
        <th class="text-center">状态</th>
        <th class="text-center">类别</th>
        <th class="text-center">创建时间</th>
    </tr>
    </thead>
    <tbody class="text-center">
    @foreach($consultion_list as $consultion)
        <tr>
            <td>
                <a href="javascript: void(0) ;" data-key="{{ $consultion['key'] }}" data-method="show" onclick="consultionsMethod($(this))">查看</a>
                @if($consultion['status'] == 'waiting' && !isset($is_archived))
                    &nbsp;&nbsp;
                    <a href="javascript: void(0) ;" data-key="{{ $consultion['key'] }}" data-method="edit" onclick="consultionsMethod($(this))">答复</a>
                    &nbsp;&nbsp;
                @endif
            </td>
            <td>{{ $consultion['record_code'] }}</td>
            <td>{{ $consultion['title'] }}</td>
            <td>
                @if($consultion['status'] == 'answer')
                    <p style="color:green; font-weight: bold">已答复</p>
                @else
                    <p style="color:#FFA500; font-weight: bold">待答复</p>
                @endif
            </td>
            <td>@if(isset($type_list) && is_array($type_list) && count($type_list)>0){{ isset($type_list[$consultion['type']]) ? $type_list[$consultion['type']] : '-' }}@else - @endif</td>
            <td>{{ $consultion['create_date'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>