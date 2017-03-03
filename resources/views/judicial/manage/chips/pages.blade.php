<!--分页-->
<div class="container-fluid text-center">
    <hr/>
    <nav aria-label="Page navigation" >
        <ul class="pagination">
            <li @if($pages['now_page'] - 1 == 0)class="disabled"@endif>
                <a href="javascript: void(0); " onclick="list_page('{{$pages['type']}}', {{$pages['now_page']-1}});" data-pageno="{{ $pages['now_page'] - 1 }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            @for ($i = 1; $i <= $pages['count_page']; $i++)
                <li @if($pages['now_page'] == $i)class="active"@endif>
                    <a href="javascript: void(0); " onclick="list_page('{{$pages['type']}}', {{$i}});" data-pageno="{{ $i }}">
                        {{ $i }}
                        @if($pages['now_page'] == $i)<span class="sr-only">(current)</span>@endif
                    </a>
                </li>
            @endfor
            <li @if($pages['now_page'] == $pages['count'])class="disabled"@endif>
                <a href="javascript: void(0); " onclick="list_page('{{$pages['type']}}', {{$pages['now_page']+1}})"; data-pageno="{{ $pages['now_page'] + 1 }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>