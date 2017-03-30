@foreach($record_list as $record)
    <tr>
        <td>{{ $record['apply_date'] }}</td>
        <td>{{ spilt_title($record['record_code'], 13) }}</td>
        <td>
            @if($record['approval_result'] == 'pass')
                审核通过
            @elseif($record['approval_result'] == 'reject')
                驳回
                <a href="javascript: void(0) ;" data-method="expertise" data-key="{{ $record['record_code'] }}" onclick="show_reason($(this))">
                    查看原因
                </a>
            @else
                待审核
            @endif
        </td>
    </tr>
@endforeach