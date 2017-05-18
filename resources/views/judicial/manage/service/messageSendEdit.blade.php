<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}/编辑
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="editMessageSendForm">
            <input type="hidden" name="key" value="{{ $send_detail['key'] }}"/>
            <div class="form-group">
                <label for="temp_code" class="col-md-2 control-label"><strong style="color: red">*</strong> 模板：</label>
                <div class="col-md-3">
                    <select class="form-control" id="temp_code" name="temp_code" onchange="getTempContent($(this))">
                        @if(!isset($temp_list) || count($temp_list)<1)
                            <option value="none">请先设置短信模板！</option>
                        @else
                            @foreach($temp_list as $k=> $temp)
                                <option value="{{ $temp['temp_code'] }}" @if($k == $send_detail['temp_code'] ) selected @endif >{{ $temp['title'] }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-md-2 control-label">内容：</label>
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p class="lead" id="temp_content">
                                {!! $content !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="send_date" class="col-md-2 control-label"><strong style="color: red">*</strong> 发送时间：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="send_date" name="send_date" />
                </div>
            </div>
            <div class="form-group">
                <label for="receiver_type" class="col-md-2 control-label"> 发送用户类型：</label>
                <div class="col-md-3">
                    <select class="form-control" onchange="switch_hidden()" id="receiver_type" name="receiver_type">
                        <option value="member" @if($send_detail['receiver_type']=='member') selected @endif>前台用户</option>
                        <option value="manager" @if($send_detail['receiver_type']=='manager') selected @endif>后台用户</option>
                        <option value="certificate" @if($send_detail['receiver_type']=='certificate') selected @endif>证书持有人</option>
                    </select>
                </div>
            </div>

            <!-- 科室选择器 -->
            <div hidden class="form-group office_switch">
                <label for="create_date" class="col-md-2 control-label">发送科室：</label>
                <div class="col-md-2">
                    <input class="form-control" id="search_office" name="search_office" onkeyup="searchOffice($(this))" placeholder="搜索科室"/>
                </div>
            </div>
            <div hidden class="form-group office_switch">
                <div class="col-md-8">
                    <div class="box">
                        <div class="box_l">
                            @foreach($office_list as $key=> $office)
                                <li data-key="{{ $key }}" style="list-style: none">{{ $office }}</li>
                            @endforeach
                        </div>
                        <div class="box_m" id="office_selected">
                            <a href="javascript:" id="left" class="btn btn-default">
                                <span class="glyphicon glyphicon-arrow-left"></span>
                            </a>
                            <a href="javascript:" id="right" class="btn btn-default">
                                <span class="glyphicon glyphicon-arrow-right"></span>
                            </a>
                        </div>
                        <div class="box_r"></div>
                    </div>
                </div>
            </div><!-- 科室选择器End -->

            <!-- 人员选择器 -->
            <div hidden class="form-group member_switch">
                <label for="create_date" class="col-md-2 control-label">发送个人：</label>
                <div class="col-md-2">
                    <input class="form-control" id="search_member" name="search_office" onkeyup="searchMembers($(this))" placeholder="搜索个人"/>
                </div>
            </div>
            <div hidden class="form-group member_switch">
                <div class="col-md-8">
                    <div class="box_2">
                        <div class="box_l_2">
                        </div>
                        <div class="box_m_2" id="office_selected">
                            <a href="javascript:" id="left_2" class="btn btn-default">
                                <span class="glyphicon glyphicon-arrow-left"></span>
                            </a>
                            <a href="javascript:" id="right_2" class="btn btn-default">
                                <span class="glyphicon glyphicon-arrow-right"></span>
                            </a>
                        </div>
                        <div class="box_r_2"></div>
                    </div>
                </div>
            </div><!-- 人员选择器End -->

            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-8">
                    <label for="create_date" class="control-label">{{ $send_detail['send_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addMessageSendNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="editMessageSend()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="service-messageSendMng" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(".box").orso({
            boxl:".box_l",//左边大盒子
            boxr:".box_r",//右边大盒子
            boxlrX:"li",//移动小盒子
            boxon:"on",//点击添加属性
            idclass:true,//添加的属性是否为class//true=class; false=id;
            boxlan:"#left",//单个向左移动按钮
            boxran:"#right",//单个向右移动按钮
            boxtan:"#top",//单个向上移动按钮
            boxban:"#bottom",//单个向下移动按钮
            boxalllan:"#allleft",//批量向左移动按钮
            boxallran:"#allright",//批量向右移动按钮
            boxalltan:"#alltop",//移动第一个按钮
            boxallban:"#allbottom"//移动最后一个按钮
        })

        $(".box_2").orso({
            boxl:".box_l_2",//左边大盒子
            boxr:".box_r_2",//右边大盒子
            boxlrX:"li",//移动小盒子
            boxon:"on",//点击添加属性
            idclass:true,//添加的属性是否为class//true=class; false=id;
            boxlan:"#left_2",//单个向左移动按钮
            boxran:"#right_2",//单个向右移动按钮
            boxtan:"#top_2",//单个向上移动按钮
            boxban:"#bottom_2",//单个向下移动按钮
            boxalllan:"#allleft_2",//批量向左移动按钮
            boxallran:"#allright_2",//批量向右移动按钮
            boxalltan:"#alltop_2",//移动第一个按钮
            boxallban:"#allbottom_2"//移动最后一个按钮
        })
    });

    $('#send_date').datetimepicker({
        dayOfWeekStart : 1,
        value: '{{ $send_detail['send_date'] }}}',
        lang: 'zh',
        format: "Y-m-d H:i",
        todayButton: true,
        step:5
    }).setLocale('zh');
</script>