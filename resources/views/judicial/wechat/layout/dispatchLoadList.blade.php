@foreach($dispatch_list as $record)
    <tr>
        <td>{{ $record['apply_date'] }}</td>
        <td>{{ spilt_title($record['record_code'], 13) }}</td>
        <td>
            @if($record['status'] == 'pass')
		        待指派
	        @elseif($record['status'] == 'dispatch')
		        已指派
	        @elseif($record['status'] == 'archived')
		        结案
            @elseif($record['status'] == 'reject')
                驳回
                <a href="javascript: void(0) ;" data-method="dispatch" data-key="{{ $record['record_code'] }}" onclick="show_reason($(this))">
                    查看原因
                </a>
            @else
                待审批
            @endif
        </td>
    </tr>
@endforeach