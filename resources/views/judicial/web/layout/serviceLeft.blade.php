<div class="zw_left">
    <ul>
        <li><div>律师服务<i class="r_awry"></i></div>
            <div class="law_body">
                <span onclick="javascript: window.location.href='{{URL::to('service/lawyer/1')}}';">律师查询</span>
                <span onclick="javascript: window.location.href='{{URL::to('service/lawyerOffice/1')}}';">事务所查询</span>
                @if(isset($s_lsfw) && is_array($s_lsfw) && count($s_lsfw)>0)
                    @foreach($s_lsfw as $lsfw)
                        <span onclick="javascript: window.location.href='{{URL::to('service/list'.'/'.$lsfw['channel_id'].'/1')}}';">{{ $lsfw['channel_title'] }}</span>
                    @endforeach
                @endif
            </div>
        </li>
        <li><div>司法考试<i class="r_awry"></i></div>
            <div class="law_body">
                @if(isset($s_sfks) && is_array($s_sfks) && count($s_sfks)>0)
                    @foreach($s_sfks as $sfks)
                        <span onclick="javascript: window.location.href='{{URL::to('service/list'.'/'.$sfks['channel_id'].'/1')}}';">{{ $sfks['channel_title'] }}</span>
                    @endforeach
                @endif
            </div>
        </li>
        <li><div>司法鉴定<i class="r_awry"></i></div>
            <div class="law_body">
                <span onclick="javascript: window.location.href='{{URL::to('service/expertise/apply')}}';">提交审核</span>
                <span onclick="javascript: window.location.href='{{URL::to('service/expertise/list/1')}}';">审批状态查询</span>
                @if(isset($s_sfjd) && is_array($s_sfjd) && count($s_sfjd)>0)
                    @foreach($s_sfjd as $sfjd)
                        <span onclick="javascript: window.location.href='{{URL::to('service/list'.'/'.$sfjd['channel_id'].'/1')}}';">{{ $sfjd['channel_title'] }}</span>
                    @endforeach
                @endif
                <span onclick="javascript: window.location.href='{{URL::to('service/expertise/downloadForm')}}';">表格下载</span>
            </div>
        </li>
        <li><div>法律援助<i class="r_awry"></i></div>
            <div class="law_body">
                <span onclick="javascript: window.location.href='{{URL::to('service/aidApply/apply')}}';">群众预约援助</span>
                <span onclick="javascript: window.location.href='{{URL::to('service/aidDispatch/apply')}}';">公检法指派援助</span>
                <span onclick="javascript: window.location.href='{{URL::to('service/aid/list/1')}}';">办理进度查询</span>
                @if(isset($s_flyz) && is_array($s_flyz) && count($s_flyz)>0)
                    @foreach($s_flyz as $flyz)
                        <span onclick="javascript: window.location.href='{{URL::to('service/list'.'/'.$flyz['channel_id'].'/1')}}';">{{ $flyz['channel_title'] }}</span>
                    @endforeach
                @endif
            </div>
        </li>
    </ul>
</div>