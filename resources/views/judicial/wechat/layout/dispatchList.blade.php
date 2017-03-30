@if(isset($dispatch_list) && is_array($dispatch_list) && count($dispatch_list) > 0)
    <table class="table table-striped table-condensed" id="list_table_dispatch">
        <thead>
        <tr>
            <th width="35%">申请时间</th>
            <th>审批编号</th>
            <th width="28%">状态</th>
        </tr>
        </thead>
        <tbody>
        @foreach($dispatch_list as $record)
            <tr>
                <td>{{ $record['apply_date'] }}</td>
                <td>{{ spilt_title($record['record_code'], 13) }}</td>
                <td>
                    @if($record['status'] == 'pass')
                        审核通过
                    @elseif($record['status'] == 'reject')
                        驳回
                        <a href="javascript: void(0) ;" data-method="dispatch" data-key="{{ $record['record_code'] }}" onclick="show_reason($(this))">
                            查看原因
                        </a>
                    @else
                        待审核
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="container-fluid" id="height_box_dispatch">
        <h5 class="text-center"><small id="height_box_dispatch_notice">数据已全部加载完成</small></h5>
    </div>
@else
    <h4 class="text-center">您暂时还没有申请记录！</h4>
@endif