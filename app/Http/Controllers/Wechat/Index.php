<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Session;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Models\Web\User\Members;

class Index extends Controller
{
    public function login()
    {
        return view('judicial.wechat.login');
    }

    public function doLogin(Request $request)
    {
        $login_name = $request->input('login_name');
        $password = $request->input('password');
    }
}
