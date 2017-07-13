<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BasicController;
use App\Models\Admin;
use Config;
use DB;
use Redirect;
use Request;
use Session;
use Socialite;
use URL;

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

		$db_password = $results[0]->password;
		if($db_password != md5($o_pw)) {
			return config('constants.INVALID_PASSWORD');
		}
		
		$n_pw = md5($n_pw);
		$results = Admin::where('email', $email)->update(['password' => $n_pw]);
		if(!$results)
			return config('constants.FAIL');
		else
			return config('constants.SUCCESS');
	}

    public function phpinfo()
    {
        echo phpinfo();
    }
}