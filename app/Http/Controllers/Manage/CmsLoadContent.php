<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class CmsLoadContent extends Controller
{

    /**
     * 加载CMS模块页面的入口函数
     * @param Request $request
     * @throws \Exception
     * @throws \Throwable
     */
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

    /**
     * CMS标签管理
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function _content_TagsMng($request)
    {
        $tag_data = array();
        $tags = DB::table('cms_tags')->select('id','tag_title','tag_color','create_date')->get();
        foreach($tags as $key=> $tag){
            $tag_data[$key]['tag_key'] = keys_encrypt($tag->id);
            $tag_data[$key]['tag_title'] = $tag->tag_title;
            $tag_data[$key]['tag_color'] = $tag->tag_color;
            $tag_data[$key]['create_date'] = $tag->create_date;
        }
        $this->page_data['tag_list'] = $tag_data;
        $pageContent = view('judicial.manage.cms.tagList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }
}
