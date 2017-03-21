@include('judicial.wechat.chips.head')

<div class="container-fluid">
    <div class="page-header">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="col-xs-6 text-center active">
                <a href="#apply" id="apply-tab" data-toggle="tab" role="tab" aria-controls="apply" aria-expanded="false">
                    群众预约援助申请<span class="badge">{{ $count['apply'] }}</span>
                </a>
            </li>
            <li role="presentation" class="col-xs-6 text-center">
                <a href="#dispatch" id="dispatch-tab" data-toggle="tab" role="tab" aria-controls="dispatch" aria-expanded="false">
                    司法指派援助申请<span class="badge">{{ $count['dispatch'] }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="container-fluid">
    <div id="myTabContent" class="tab-content">
        <div role="tabpanel" class="tab-pane fade active in" id="apply" aria-labelledby="apply-tab">
            @include('judicial.wechat.layout.applyList')</div>
        <div role="tabpanel" class="tab-pane fade" id="dispatch" aria-labelledby="dispatch-tab">
            @include('judicial.wechat.layout.dispatchList')</div>
    </div>
</div>

    <!-- 模态框 -->
    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id='reason_notice'>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">查看驳回原因</h4>
                </div>
                <div class="modal-body">
                    <p class="text-left lead" id="p_notice"></p>
                    <h5 class="text-left lead">
                        <small>温馨提示：请登录PC端网站：www.smxsfj.com 根据要求重新提交申请</small>
                    </h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>

@include('judicial.wechat.chips.foot')

