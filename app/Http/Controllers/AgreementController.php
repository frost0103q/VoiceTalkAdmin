<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BasicController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use DB;

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

    public function agree_photo()
    {
        $user_profile_query="SELECT t_file.*,t_user.`no` AS usr_no,t_user.nickname FROM t_user LEFT JOIN t_file ON t_user.img_no=t_file.`no` WHERE checked!=1 AND type=0";
        $talk_img_query="SELECT A.*,t_user.nickname from (SELECT t_file.*,t_talk.user_no FROM t_talk LEFT JOIN t_file ON t_file.`no`=t_talk.img_no WHERE t_file.checked!=1 AND t_file.type=0) AS A LEFT JOIN t_user ON A.user_no=t_user.no";
        $user_profile_img=DB::select($user_profile_query);
        $talk_img=DB::select($talk_img_query);
        return view('photo_agree.index',['menu_index'=>1,'user_profile_img'=>$user_profile_img,'talk_img'=>$talk_img]);
    }
}

