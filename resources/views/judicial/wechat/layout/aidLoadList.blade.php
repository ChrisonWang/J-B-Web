@foreach($apply_list as $record)
    <tr>
        <td>{{ $record['apply_date'] }}</td>
        <td>{{ spilt_title($record['record_code'], 13) }}</td>
        <td>
            @if($record['status'] == 'pass')
                审核通过
            @elseif($record['status'] == 'reject')
                驳回
                <a href="javascript: void(0) ;" data-method="apply" data-key="{{ $record['record_code'] }}" onclick="show_reason($(this))">
                    查看原因
                </a>
            @else
                待审核
            @endif
        </td>
    </tr>
@endforeach