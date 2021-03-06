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
    @foreach($suggestion_list as $suggestion)
        <tr>
            <td>
                <a href="javascript: void(0) ;" data-key="{{ $suggestion['key'] }}" data-method="show" onclick="suggestionsMethod($(this))">查看</a>
                @if( isset($is_rm) && $is_rm == 'yes')
		            @if($suggestion['status'] == 'waiting' && !isset($is_archived))
			            &nbsp;&nbsp;
			            <a href="javascript: void(0) ;" data-key="{{ $suggestion['key'] }}" data-method="edit" onclick="suggestionsMethod($(this))">答复</a>
		            @endif
		            &nbsp;&nbsp;
	                @if($suggestion['is_hidden'] == 'yes')
						<a href="javascript: void(0) ;" data-key="{{ $suggestion['key'] }}" data-is_hidden="no" data-type="suggestions" onclick=setHidden($(this))>取消隐藏</a>
					@else
			            <a href="javascript: void(0) ;" data-key="{{ $suggestion['key'] }}" data-is_hidden="yes" data-type="suggestions" onclick=setHidden($(this))>隐藏</a>
					@endif
	            @endif
            </td>
            <td>{{ $suggestion['record_code'] }}</td>
            <td>{{ $suggestion['title'] }}</td>
            <td>
                @if($suggestion['status'] == 'answer')
                    <p style="color:green; font-weight: bold">已答复</p>
                @else
                    <p style="color:#FFA500; font-weight: bold">待答复</p>
                @endif
            </td>
            <td>@if(isset($type_list) && is_array($type_list) && count($type_list)>0){{ isset($type_list[$suggestion['type_id']]) ? $type_list[$suggestion['type_id']] : '-' }}@else - @endif</td>
            <td>{{ $suggestion['create_date'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>