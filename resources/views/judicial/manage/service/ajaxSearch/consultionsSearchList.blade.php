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
	            @if( isset($is_rm) && $is_rm == 'yes')
		            <a href="javascript: void(0) ;" data-key="{{ $consultion['key'] }}" data-method="show" data-archived_key="{{ isset($archived_key)?$archived_key:'' }}" data-archived="{{ (isset($is_archived)&&$is_archived=='yes') ? 'yes' : 'no' }}" onclick="consultionsMethod($(this))">查看</a>
		            @if($consultion['status'] == 'waiting' && !isset($is_archived))
			            &nbsp;&nbsp;
			            <a href="javascript: void(0) ;" data-key="{{ $consultion['key'] }}" data-method="edit" onclick="consultionsMethod($(this))">答复</a>
		            @endif
		            &nbsp;&nbsp;
		            @if($consultion['is_hidden'] == 'yes')
			            <a href="javascript: void(0) ;" data-key="{{ $consultion['key'] }}" data-is_hidden="no" data-type="consultions" onclick=setHidden($(this))>取消隐藏</a>
		            @else
			            <a href="javascript: void(0) ;" data-key="{{ $consultion['key'] }}" data-is_hidden="yes" data-type="consultions" onclick=setHidden($(this))>隐藏</a>
		            @endif
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
            <td>@if(isset($type_list) && is_array($type_list) && count($type_list)>0){{ isset($type_list[$consultion['type_id']]) ? $type_list[$consultion['type_id']] : '-' }}@else - @endif</td>
            <td>{{ $consultion['create_date'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>