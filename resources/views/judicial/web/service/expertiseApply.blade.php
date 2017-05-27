<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndexWB')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

<!--内容-->
<div class="w1024 zw_mb">
    <!-- 左侧菜单 -->
    @include('judicial.web.layout.serviceLeft')

    <div class="zw_right w810">
        <div class="zwr_top">
            <span><a href="{{ URL::to('/') }}" style="color: #222222">首页&nbsp;&nbsp;>&nbsp;</a></span>
            <span><a href="/service" style="color: #222222">网上办事</a>&nbsp;&nbsp;>&nbsp;</span>
            <span><a href="/service/expertise/list/1" style="color: #222222">司法鉴定</a>&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #929292;">提交审核</span>
        </div>

        <div class="wsfy_tit">
            <span class="wfb_tit">司法鉴定申请流程</span>
            <ul>
                <li><i>■</i>申请人根据司法鉴定类型下载对应表格。</li>
                <li><i>■</i>申请人在本系统提交司法鉴定申请，并上传相关附件。</li>
                <li><i>■</i>申请人在收到司法局审批通过短信通知后，携带所有资料到司法局一次性办理业务。</li>
                <li><i>■</i>如果审批不通过，请按提示重新提交申请。</li>
            </ul>
        </div>
        <div class="xx_tit">
            司法鉴定申请
        </div>
        <div class="text_a post_btn gjf" style="height: auto; padding-bottom: 30px">
            <form id="expertiseForm">
            <ul>
                <li>
                    <span class="wsc_txt" style="width: 70px">申请人姓名</span>
                    <div class="cx_inp">
                        <input type="text" name="apply_name" placeholder="请输入申请人姓名" />
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="width: 70px">联系电话</span>
                    <div class="cx_inp">
                        <input type="text" name="cell_phone" placeholder="请输入联系电话" />
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="width: 70px">类型</span>
                    <div class="cx_inp">
                        <select name="type_id" style="" onchange="loadExpertiseFile($(this))">
                            @if($type_list != 'none' && is_array($type_list))
                                <option value="none">请选择类型！</option>
                                @foreach($type_list as $k=>$name)
                                    <option value="{{ $k }}">{{ $name }}</option>
                                @endforeach
                            @else
                                <option value="none">未设置类型！</option>
                            @endif
                        </select>
                    </div>
                </li>
            </ul>
            <div class="mt_btn">
                <span class="wsc_txt" style="width: 42px">附件</span>

                    <input type="file" name="file" />


                <span class="mtb_m" id="expertiseFile">
                    <a href="javascript: alert('请先选择司法鉴定类型！') ;">请先选择类型</a>
                </span>
            </div>

                <div class=mt_last>
                    <span class="wsc_txt" style="width: 75px; float: left; color: #E23939; font-size: 12px">温馨提示：</span>
                    <div class="mt_ul" style="float: left">
                        <span style="font-size: 12px; margin-left: 0px">如果有多个文件可放入文件夹压缩后再上传压缩文件。</span>
                    </div>
                </div>
                <div class="clear"></div>

            <div class="last_btn" onclick="expertiseApply()">
                提交申请
            </div>
            </form>
        </div>
    </div>
</div>

<!--弹窗-->
<div class="alert_sh" style="display: none">
    <a href="javascript:void(0)" class="closed">X</a>
    <div class="als_top">提交中</div>
    <div class="als_down"></div>
</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>