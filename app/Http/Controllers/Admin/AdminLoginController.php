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

class AdminLoginController extends BasicController
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

        $email = Session::get('u_email');
        if (!isset($email) || $email == null) {
            return view('login.index');
        }
        else{
            return redirect("home");
        }
    }

    public function doLogout()
    {

        @session_set_cookie_params(0);
        @session_start();

        $email = Session::get('u_email');
        if (isset($email) && $email != "") {

            Session::put('u_email', "");
            @session_destroy();
            @session_unset();

        }

        return redirect("/login");
    }

    public function doLogin()
    {
        $params = Request::all();

        $email = $params['email'];
        $password = $params['password'];

        $results = Admin::where('email', $email)->get();

        if (!$results || count($results) == 0) {
            return view('login.index', ['error' => config('constants.INVALID_EMAIL')]);
        }

        $db_password = $results[0]->password;
        if ($db_password != md5($password)) {
            return view('login.index', ['error' => config('constants.INVALID_PASSWORD')]);
        }

        Session::put('u_email', $email);
        Session::put('u_no', $results[0]->no);
        Session::put('u_nickname', $results[0]->nickname);

        return redirect('/home');
    }

	public function auto_login(){
		$email = Session::get('u_email');
		if (isset($email) && $email != null) {
			die("success");
		}
		else
			die("fail");
	}
}
