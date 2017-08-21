<?php
/**
 * Created by PhpStorm.
 * User: HappyMario
 * Date: 8/15/2017
 * Time: 8:53 PM
 */


namespace App\Http\Controllers;

use Config;
use DB;
use Illuminate\Http\Request as HttpRequest;
use Redirect;
use Request;
use Session;
use Socialite;
use URL;


class AnsimController extends BasicController
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

    public function requestAuthRealUser(HttpRequest $request) {
        $user_no = $request->input('user_no');
        $name = $request->input('name');
        $birth = $request->input('birth');
        $address = $request->input('address');
        $sex = $request->input('sex');
        $ident_num = $request->input('identi_num');

        if ($user_no == null || $name == null || $address == null || $sex == null || $ident_num == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        // TODO: ansim
        // $ansim = DB::table('t_ansim')->where('user_no', $user_no)->where('status',config('constants.VERIFIED' ))->first();

        $result = DB::table('t_ansim')->insert(
            [
                'user_no' => $user_no,
                'created_at' => date('Y-m-d H:i:s'),
                'name' => $name,
                'address' => $address,
                'sex' => $sex,
                'ident_num' => $ident_num,
            ]
        );
        $response = config('constants.ERROR_NO');
        return response()->json($response);
    }


    public function verifyRealUser(HttpRequest $request) {
        $user_no = $request->input('user_no');
        $verify_code = $request->input('verify_code');

        if ($user_no == null || $verify_code == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');

        $ansim = DB::table('t_ansim')->where('user_no', $user_no)->first();

        if($ansim == null) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }

        /*if(strcmp($ansim->verify_code, $verify_code) != 0) {
            $response = config('constants.ERROR_NOT_VERIFIED_USER');
            return response()->json($response);
        }*/

        DB::table('t_ansim')->where('no', $ansim->no)->update(array('status'=>config('constants.VERIFIED')));

        return response()->json($response);
    }

}