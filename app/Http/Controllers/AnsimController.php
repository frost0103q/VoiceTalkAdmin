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

    private function ansimCheck($name, $identi_num) {
        if($name == null || $identi_num == null) {
            return false;
        }
        $sSiteID = "BN61";  	// NICE평가정보에서 부여받은 사이트아이디(사이트코드)를 수정한다.
        $sSitePW = "82689845";    // NICE평가정보에서 부여받은 비밀번호 수정한다.
        $cb_encode_path = "/var/www/html/ansimtest/cb_namecheck64";			// cb_namecheck 모듈이 설치된 위치의 절대경로와 cb_namecheck 모듈명까지 입력한다.
        $strName =  iconv("utf-8","euc-kr",$name);
        $strJumin = $identi_num;

        ///////////////////////////////////////////문자열 점검///////////////////////////////////////////////////
        if(preg_match("/[#\&\\+\-%@=\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sSiteID, $match)) return false;
        if(preg_match("/[#\&\\+\-%@=\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sSitePW, $match)) return false;
        if(preg_match("/[#\&\\+\-%@=V\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $strJumin, $match)) return false;
        if(preg_match("/[#\&\\+\-%@=\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $strName, $match)) return false;
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////

        $iReturnCode  = "";

        // shell_exec() 와 같은 실행함수 호출부 입니다. 홑따옴표가 아니오니 이점 참고해 주세요.
        $iReturnCode = `$cb_encode_path $sSiteID $sSitePW $strJumin $strName`;	//실행함수 호출하여 iReturnCode 의 변수에 값을 담는다.

        //iReturnCode 변수값에 따라 아래 참고하셔서 처리해주세요.(결과값의 자세한 사항은 리턴코드.txt 파일을 참고해 주세요~)
        //iReturnCode :	1 이면 --> 실명인증 성공 : XXX.php 로 페이지 이동.
        //							2 이면 --> 실명인증 실패 : 주민과 이름이 일치하지 않음. 사용자가 직접 www.namecheck.co.kr 로 접속하여 등록 or 1600-1522 콜센터로 접수요청.
        //												아래와 같이 NICE평가정보에서 제공한 자바스크립트 이용하셔도 됩니다.
        //							3 이면 --> NICE평가정보 해당자료 없음 : 사용자가 직접 www.namecheck.co.kr 로 접속하여 등록 or 1600-1522 콜센터로 접수요청.
        //												아래와 같이 NICE평가정보에서 제공한 자바스크립트 이용하셔도 됩니다.
        //							5 이면 --> 체크썸오류(주민번호생성규칙에 어긋난 경우: 임의로 생성한 값입니다.)
        //							50이면 -->  NICE지키미의 명의도용차단 서비스 가입자임 : 직접 명의도용차단 해제 후 실명인증 재시도.
        //												아래와 같이 NICE평가정보에서 제공한 자바스크립트 이용하셔도 됩니다.
        //							그밖에 --> 30번대, 60번대 : 통신오류 ip: 203.234.219.72 port: 81~85(5개) 방화벽 관련 오픈등록해준다.
        //												(결과값의 자세한 사항은 리턴코드.txt 파일을 참고해 주세요~)

        if($iReturnCode == 1) {
            return true;
        }

        return false;
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

        $auth = $this ->ansimCheck($name, $ident_num);

        if($auth == true) {
            $response = config('constants.ERROR_NO');
            $ansim = DB::table('t_ansim')->where('user_no', $user_no)->first();
            $update_array =  [
                'user_no' => $user_no,
                'created_at' => date('Y-m-d H:i:s'),
                'name' => $name,
                'address' => $address,
                'sex' => $sex,
                'ident_num' => $ident_num,
                'status' =>config('constants.VERIFIED'),
            ];
            if($ansim == null) {
                DB::table('t_ansim')->insert($update_array);
            }
            else {
                DB::table('t_ansim')->where('user_no', $user_no)->update($update_array);
            }

            return response()->json($response);
        }

        $response = config('constants.ERROR_NO_INFORMATION');
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

        // DB::table('t_ansim')->where('no', $ansim->no)->update(array('status'=>config('constants.VERIFIED')));

        return response()->json($response);
    }

}