<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class ServiceLoadContent extends Controller
{

    public function loadContent(Request $request)
    {
        $inputs = $request->input();
        $nodeId = $inputs['node_id'];
        $action = '_content_'.ucfirst($nodeId);
        if(!method_exists($this,$action)){
            $errorPage = view('judicial.notice.errorNode')->render();
            json_response(['status'=>'faild','type'=>'page', 'res'=>$errorPage]);
        }
        else{
            $this->$action($request);
        }
    }

    private function _content_AreaMng($request)
    {
        $this->page_data['thisPageName'] = '区域管理';
        //加载列表数据
        $area_list = array();
        $pages = '';
        $count = DB::table('service_area')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $areas = DB::table('service_area')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($areas) > 0){
            foreach($areas as $area){
                $area_list[] = array(
                    'key' => keys_encrypt($area->id),
                    'area_name'=> $area->area_name,
                    'create_date'=> $area->create_date,
                    'update_date'=> $area->update_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'area',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['area_list'] = $area_list;
        $pageContent = view('judicial.manage.service.areaList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_LawyerMng($request)
    {
        $this->page_data['thisPageName'] = '律师管理';
        //加载列表数据
        $lawyer_list = array();
        $office_list = array();
        $pages = '';
        $count = DB::table('service_lawyer')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $lawyers = DB::table('service_lawyer')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($lawyers) > 0){
            //取出事务所
            $office = DB::table('service_lawyer_office')->get();
            if(count($office) > 0){
                foreach($office as $o){
                    $office_list[keys_encrypt($o->id)] = $o->name;
                }
            }
            //格式化数据
            foreach($lawyers as $lawyer){
                $lawyer_list[] = array(
                    'key' => keys_encrypt($lawyer->id),
                    'name'=> $lawyer->name,
                    'sex'=> $lawyer->sex,
                    'type'=> $lawyer->type,
                    'certificate_code'=> $lawyer->certificate_code,
                    'lawyer_office'=> keys_encrypt($lawyer->lawyer_office),
                    'status'=> $lawyer->status,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'lawyer',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = ['full_time'=>'专职', 'part_time'=>'兼职', 'company'=>'公司', 'officer'=>'公职'];
        $this->page_data['office_list'] = $office_list;
        $this->page_data['lawyer_list'] = $lawyer_list;
        $pageContent = view('judicial.manage.service.lawyerList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_LawyerOfficeMng($request)
    {
        $this->page_data['thisPageName'] = '事务所管理';
        //加载列表数据
        $office_list = array();
        $area_list = array();
        $pages = '';
        $count = DB::table('service_lawyer_office')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $office = DB::table('service_lawyer_office')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($office) > 0){
            //取出区域
            $areas = DB::table('service_area')->get();
            if(count($areas) > 0){
                foreach($areas as $area){
                    $area_list[keys_encrypt($area->id)] = $area->area_name;
                }
            }

            //格式化数据
            foreach($office as $o){
                $office_list[] = array(
                    'key' => keys_encrypt($o->id),
                    'name'=> $o->name,
                    'director'=> $o->director,
                    'usc_code'=> $o->usc_code,
                    'type'=> $o->type,
                    'area_id'=> keys_encrypt($o->area_id),
                    'status'=> $o->status,
                    'create_date'=> $o->create_date,
                    'update_date'=> $o->update_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'lawyerOffice',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = array('head'=>'总所', 'branch'=>'分所', 'personal'=>'个人');
        $this->page_data['area_list'] = $area_list;
        $this->page_data['office_list'] = $office_list;
        $pageContent = view('judicial.manage.service.lawyerOfficeList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_CertificateMng($request)
    {
        $this->page_data['thisPageName'] = '证书持有人管理';
        //加载列表数据
        $certificate_list = array();
        $pages = '';
        $count = DB::table('service_certificate')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $certificates = DB::table('service_certificate')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($certificates) > 0){
            //格式化数据
            foreach($certificates as $certificate){
                $certificate_list[] = array(
                    'key' => keys_encrypt($certificate->id),
                    'name'=> $certificate->name,
                    'citizen_code'=> $certificate->citizen_code,
                    'certi_code'=> $certificate->certi_code,
                    'certificate_date'=> date('Y-m-d', strtotime($certificate->certificate_date)),
                    'phone'=> $certificate->phone,
                    'last_status'=> $certificate->last_status,
                    'create_date'=> $certificate->create_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'certificate',
            );
        }
        //模板
        $temp_list = array();
        $temps = DB::table('service_message_temp')->get();
        if(count($temps)>0){
            foreach($temps as $temp){
                $temp_list[] = array(
                    'title'=> $temp->title,
                    'temp_code'=> $temp->temp_code,
                    'content'=> $temp->content,
                );
            }
        }
        $this->page_data['temp_list'] = $temp_list;
        $this->page_data['pages'] = $pages;
        $this->page_data['certificate_list'] = $certificate_list;
        $pageContent = view('judicial.manage.service.certificateList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_MessageTmpMng($request)
    {
        $this->page_data['thisPageName'] = '短信模板管理';
        //加载列表数据
        $tmp_list = array();
        $pages = '';
        $count = DB::table('service_message_temp')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $tmps = DB::table('service_message_temp')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($tmps) > 0){
            //格式化数据
            foreach($tmps as $tmp){
                $tmp_list[] = array(
                    'key' => $tmp->temp_code,
                    'title'=> $tmp->title,
                    'content'=> $tmp->content,
                    'create_date'=> $tmp->create_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'messageTmp',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['tmp_list'] = $tmp_list;
        $pageContent = view('judicial.manage.service.messageTmpList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_MessageSendMng($request)
    {
        $this->page_data['thisPageName'] = '短信发送管理';
        //加载列表数据
        $send_list = array();
        $temp_list = array();
        $pages = '';
        $count = DB::table('service_message_list')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $list = DB::table('service_message_list')->where('archived', 'no')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($list) > 0){
            //格式化数据
            foreach($list as $l){
                $send_list[] = array(
                    'key' => keys_encrypt($l->id),
                    'temp_code' => $l->temp_code,
                    'send_date'=> $l->send_date,
                    'receiver_type'=> $l->receiver_type,
                    'received_office'=> $l->received_office,
                    'received_person'=> $l->received_person,
                    'create_date'=> $l->create_date,
                    'update_date'=> $l->update_date,
                );
            }
            //模板主题
            $temps = DB::table('service_message_temp')->get();
            if(count($temps)>0){
                foreach($temps as $temp){
                    $temp_list[$temp->temp_code] = $temp->title;
                }
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'messageSend',
            );

        }
        $this->page_data['pages'] = $pages;
        $this->page_data['temp_list'] = $temp_list;
        $this->page_data['send_list'] = $send_list;
        $pageContent = view('judicial.manage.service.messageSendList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_ExpertiseTypeMng($request)
    {
        $this->page_data['thisPageName'] = '司法鉴定类型管理';
        //加载列表数据
        $type_list = array();
        $pages = '';
        $count = DB::table('service_judicial_expertise_type')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $types = DB::table('service_judicial_expertise_type')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($types) > 0){
            foreach($types as $type){
                $type_list[] = array(
                    'key' => keys_encrypt($type->id),
                    'name'=> $type->name,
                    'create_date'=> $type->create_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'expertiseType',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = $type_list;
        $pageContent = view('judicial.manage.service.expertiseTypeList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_ExpertiseApplyMng($request)
    {
        $this->page_data['thisPageName'] = '司法鉴定申请管理';
        //加载列表数据
        $apply_list = array();
        $type_list = array();
        $pages = '';
        $count = DB::table('service_judicial_expertise')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $applies = DB::table('service_judicial_expertise')->where('archived', 'no')->orderBy('apply_date', 'desc')->skip(0)->take($offset)->get();
        if(count($applies) > 0){
            $types = DB::table('service_judicial_expertise_type')->get();
            if(count($types) > 0){
                foreach($types as $type){
                    $type_list[keys_encrypt($type->id)] = $type->name;
                }
            }
            foreach($applies as $apply){
                $apply_list[] = array(
                    'key' => keys_encrypt($apply->id),
                    'record_code'=> $apply->record_code,
                    'apply_name'=> $apply->apply_name,
                    'approval_result'=> $apply->approval_result,
                    'cell_phone'=> $apply->cell_phone,
                    'type_id'=> keys_encrypt($apply->type_id),
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'expertiseApply',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['type_list'] = $type_list;
        $this->page_data['apply_list'] = $apply_list;
        $pageContent = view('judicial.manage.service.expertiseApplyList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_SuggestionsMng($request)
    {
        $this->page_data['thisPageName'] = '征求意见管理';
        $this->page_data['type_list'] = ['opinion'=>'意见','suggest'=>'建议','complaint'=>'投诉','other'=>'其他'];
        //加载列表数据
        $suggestion_list = array();
        $pages = '';
        $count = DB::table('service_suggestions')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $suggestions = DB::table('service_suggestions')->where('archived', 'no')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($suggestions) > 0){
            foreach($suggestions as $suggestion){
                $suggestion_list[] = array(
                    'key' => keys_encrypt($suggestion->id),
                    'record_code' => $suggestion->record_code,
                    'title'=> spilt_title($suggestion->title, 30),
                    'type' => $suggestion->type,
                    'status' => $suggestion->status,
                    'create_date' => date('Y-m-d H:i', strtotime($suggestion->create_date)),
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'suggestion',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['suggestion_list'] = $suggestion_list;
        $pageContent = view('judicial.manage.service.suggestionList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_ConsultionsMng($request)
    {
        $this->page_data['thisPageName'] = '问题咨询管理';
        $this->page_data['type_list'] = ['exam'=>'司法考试','lawyer'=>'律师管理','notary'=>'司法公证','expertise'=>'司法鉴定','aid'=>'法律援助','other'=>'其他'];
        //加载列表数据
        $consultion_list = array();
        $pages = '';
        $count = DB::table('service_consultions')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $consultions = DB::table('service_consultions')->where('archived', 'no')->orderBy('create_date', 'desc')->skip(0)->take($offset)->get();
        if(count($consultions) > 0){
            foreach($consultions as $consultion){
                $consultion_list[] = array(
                    'key'=> keys_encrypt($consultion->id),
                    'record_code'=> $consultion->record_code,
                    'title'=> spilt_title($consultion->title, 30),
                    'type'=> $consultion->type,
                    'status'=> $consultion->status,
                    'create_date'=> date('Y-m-d H:i',strtotime($consultion->create_date)),
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'consultions',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['consultion_list'] = $consultion_list;
        $pageContent = view('judicial.manage.service.consultionsList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_AidApplyMng($request)
    {
        $this->page_data['thisPageName'] = '群众预约援助管理';
        $this->page_data['type_list'] = ['personality'=>'人格纠纷','marriage'=>'婚姻家庭纠纷','inherit'=>'继承纠纷','possession'=>'不动产登记纠纷','other'=>'其他'];
        //加载列表数据
        $apply_list = array();
        $pages = '';
        $count = DB::table('service_legal_aid_apply')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $applys = DB::table('service_legal_aid_apply')->where('archived', 'no')->orderBy('apply_date', 'desc')->skip(0)->take($offset)->get();
        if(count($applys) > 0){
            foreach($applys as $apply){
                $apply_list[] = array(
                    'key'=> keys_encrypt($apply->id),
                    'record_code'=> $apply->record_code,
                    'status'=> $apply->status,
                    'apply_name'=> $apply->apply_name,
                    'apply_phone'=> $apply->apply_phone,
                    'type'=> $apply->type,
                    'salary_dispute'=> $apply->salary_dispute=='yes' ? 'yes' : 'no',
                    'apply_date'=> date('Y-m-d',strtotime($apply->apply_date)),
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'aidApply',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['apply_list'] = $apply_list;
        $pageContent = view('judicial.manage.service.aidApplyList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    private function _content_AidDispatchMng($request)
    {
        $this->page_data['thisPageName'] = '公检法指派管理';
        //加载列表数据
        $apply_list = array();
        $pages = '';
        $count = DB::table('service_legal_aid_dispatch')->where('archived', 'no')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = 30;
        $applys = DB::table('service_legal_aid_dispatch')->where('archived', 'no')->orderBy('apply_date', 'desc')->skip(0)->take($offset)->get();
        if(count($applys) > 0){
            foreach($applys as $apply){
                $apply_list[] = array(
                    'key'=> keys_encrypt($apply->id),
                    'record_code'=> $apply->record_code,
                    'status'=> $apply->status,
                    'apply_office'=> $apply->apply_office,
                    'apply_aid_office'=> $apply->apply_aid_office,
                    'case_name'=> $apply->case_name,
                    'apply_date'=> date('Y-m-d',strtotime($apply->apply_date)),
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'aidDispatch',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['apply_list'] = $apply_list;
        $pageContent = view('judicial.manage.service.aidDispatchList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

}
