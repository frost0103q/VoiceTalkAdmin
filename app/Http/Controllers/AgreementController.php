<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BasicController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;

class AgreementController extends BasicController
{
    /*
     |--------------------------------------------------------------------------
     | HomeController Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles authenticating users for the application and
     | redirecting them to your home screen. The controller uses a trait
     | to conveniently provide its functionality to your applications.
     |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function non_agree_img()
    {
        return view('agreement.non_agree_img',['menu_index'=>1]);
    }

    public function profile_img()
    {
        return view('agreement.profile_img',['menu_index'=>2]);
    }

    public function talk_img()
    {
        return view('agreement.talk_img',['menu_index'=>3]);
    }
}
