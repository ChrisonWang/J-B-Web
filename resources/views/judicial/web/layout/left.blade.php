<div class="zw_left">
    <ul>
        @foreach($channel_list as $channel)
        <li>
            <div>{{ $channel['channel_title'] }}<i class="r_awry"></i></div>
            @if($channel['sub_channel']!='none' && is_array($channel['sub_channel']))
                <div class="law_body">
                    @foreach($channel['sub_channel'] as $key=> $title)
                        <span><a href="{{ URL::to('/list').'/'.$key }}">{{ $title }}</a></span>
                    @endforeach
                </div>
            @endif
        </li>
        @endforeach
    </ul>
</div>