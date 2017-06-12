<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BasicController;
use App\Models\Admin;
use Request;
use DB;
use Redirect;
use URL;
use Session;
use Socialite;
use Config;

class AdminSettingController extends BasicController
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

	public function index()
	{
		return view('setting.index');
	}

    public function notify() {
        return view('setting.notify');
    }

    public function user_guide() {
        return view('setting.user_guide');
    }

    public function google_card_register_guide() {
        return view('setting.googlecard_useguide');
    }
	
	public function doSetting() {
		$params = Request::all();
		
		$o_pw = $params['old_password'];
		$n_pw = $params['new_password'];
		
		$email = Session::get('u_email');
		
		$results = Admin::where('email', $email)->get();
		
		if (!$results || count($results) == 0) {
			return view('login.index', config('constants.ERROR_NO_MATCH_INFORMATION'));
		}
		
		$db_password = $results[0]->password;
		if($db_password != md5($o_pw)) {
			return view('setting.index',  ['error'=>config('constants.ERROR_NO_MATCH_PASSWORD')]);
		}
		
		$n_pw = md5($n_pw);
		$results = Admin::where('email', $email)->update(['password' => $n_pw]);
		
		return view('setting.index',  ['success'=>true]);
	}
}