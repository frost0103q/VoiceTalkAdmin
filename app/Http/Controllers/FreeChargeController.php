<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 7/10/2017
 * Time: 9:38 PM
 */

namespace App\Http\Controllers;

use App\Models\FreeChargeHistory;
use App\Models\User;
use Config;
use DB;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Log;
use Request;
use Session;

class FreeChargeController extends BasicController
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


    public function adsync_delivery_point(HttpRequest $request)
    {

        $partner = $request->input('partner');    // 파트너 앱(서비스) ID
        $cust_id = $request->input('cust_id');    // 파트너 회원 ID
        $ad_no = $request->input('ad_no');        // 적립 광고 번호
        $seq_id = $request->input('seq_id');        // 포인트 적립 고유 ID
        $point = $request->input('point');        // 적립 포인트
        $ad_title = $request->input('ad_title');    // 적립 광고 제목
        $valid_key = $request->input('valid_key');    // 유효성 확인 Key

        // 서비스 처리 코딩 후 결과를 기본값
        $arr_result = array('Result' => true, 'ResultCode' => 1, 'ResultMsg' => "성공");

        // 전달 받은 파라미터 체크 부분
        if (empty($partner) || empty($cust_id) || empty($ad_no) || empty($point) || empty($seq_id) || empty($valid_key) || empty($ad_title)) {
            //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 4;
            $arr_result['ResultMsg'] = "파라미터 오류";
            Log::debug($arr_result);
            echo json_encode($arr_result);
            exit;
        }

        //적립포인트 수신에 대한 유효성 체크 부분
        //유효성 확인키 생성: md5(발급받은인증키+서버로전달받은cust_id+서버로전달받은seq_id)
        //생성한 유효성 확인키와 서버로전달받은 valid_key 검증
        //AdSync로 부터 발급받은 인증키(파트너백오피스 혹은 메일로 발급 받은 인증키)
        $issuedAuthKey = "LPeS3OlVh7LU9To0xNawFFrNCSRUcrO7cwss640ooHg=";

        $checkValidKey = md5($issuedAuthKey . $cust_id . $seq_id);
        if ($checkValidKey != $valid_key) {
            //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 2;
            $arr_result['ResultMsg'] = "유효성 확인 key 오류";
            Log::debug($arr_result);
            echo json_encode($arr_result);
            exit;
        }


        // 적립포인트 수신에 대한 중복 체크 처리 부분(단일 고유키키값 seq_id)
        //서버로 전달받은 seq_id 값으로 기존 성공적으로 전달받은 seq_id 값이 있는지 DB 검사
        //ex)성공적으로 전달받은 적립포인트정보내역 테이블: partner_point
        $free_charge = FreeChargeHistory::where('seq_id', $seq_id)->first();
        if ($free_charge != null) {

            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 3;
            $arr_result['ResultMsg'] = "중복 지급  오류";
            Log::debug($arr_result);
            echo json_encode($arr_result);
            exit;
        }

        // 매체사 처리할 리워드 지급 ..
        $user = User::where('no', $cust_id)->first();
        if ($user == null) {
            //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 5;
            $arr_result['ResultMsg'] = "존재하지 않은 유저";
            Log::debug($arr_result);
            echo json_encode($arr_result);
            exit;
        }

        $nPoint = intval($point) * config('constants.FREE_CHARGE_RATIO_ADSYNC');
        $ret = $user->addPoint(config('constants.POINT_HISTORY_TYPE_FREE_CHARGE'), $nPoint);

        if ($ret == false) {
            //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 6;
            $arr_result['ResultMsg'] = "포인트 추가 오류";
            Log::debug($arr_result);
            echo json_encode($arr_result);
            exit;
        }

        // add history
        $history = new FreeChargeHistory();
        $history->user_no = $user->no;
        $history->seq_id = $seq_id;
        $history->point = $nPoint;
        $history->type = config('constants.FREE_CHARGE_TYPE_ADSYNC');
        $history->save();

        // 매체사 유저에게 리워드 지급 후 성공 Json string return >
        //AdSync Noti 서버가 위의 JSON 응답값을 확인 ResultCode값을 Parsing 하여 관련 이력을 처리(AdSync 백오피스에서 확인 가능)
        // "1"이 아닌 경우는 일일배치로 본페이지를 재호출함.

        $arr_result['Result'] = true;
        $arr_result['ResultCode'] = 1;
        $arr_result['ResultMsg'] = "success";
        Log::debug($arr_result);

        // send push
        $admin_no = config('constants.DEFAULT_ADMIN_NO');
        $data = array();
        $data['point'] = $nPoint;
        $this->sendAlarmMessage($admin_no,  $user->no, config('constants.NOTI_TYPE_SUCCESS_FREE_CHARGE'), $data);

        echo json_encode($arr_result);
    }

    public function nas_delivery_point(HttpRequest $request)
    {
        $seq_id = $request->input('s');    // [SEQ_ID] : 적립 고유 ID
        $user_no = $request->input('ud');    // 회원 정의 데이터
        $money = $request->input('r');        // 리워드 금액 (오퍼월에서 참여한 경우에만 값이 있음)
        $ads_id = $request->input('ai');        // 광고 ID
        $ads_key = $request->input('ak');        // 광고 KEY
        $ads_num = $request->input('n');    // 광고명
        $ads_kind = $request->input('t');    // 광고구분 (CPI, CPE, CPA, CPC, FACEBOOK)
        $adid = $request->input('adid');    // 사용자 기기 36자리 광고 ID (Android : ADID, iOS : IDFA)
        $ip = $request->input('ip');    //  사용자 IP 주소


        $response = config('constants.ERROR_NO');
        // 전달 받은 파라미터 체크 부분
        if (empty($seq_id) || empty($user_no) || empty($money) || empty($ads_id) || empty($ads_key) || empty($ads_num) || empty($ads_kind)) {
            //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 4;
            $arr_result['ResultMsg'] = "파라미터 오류";
            Log::debug($arr_result);

            return response($arr_result, 201);
        }

        // ip검사
        /*if ($ip != '222.122.49.171') {
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 2;
            $arr_result['ResultMsg'] = "유효성 아이피 오류";
            Log::debug($arr_result);
            return response($arr_result, 201);
        }*/

        // 매체사 처리할 리워드 지급 ..
        $user = User::where('no', $user_no)->first();
        if ($user == null) {
            //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 3;
            $arr_result['ResultMsg'] = "존재하지 않은 유저";
            Log::debug($arr_result);
            return response($arr_result, 201);
        }

        // seq id를 기준으로 중복지급방지
        $free_charge = FreeChargeHistory::where('seq_id', $seq_id)->first();
        if ($free_charge != null) {
            //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 5;
            $arr_result['ResultMsg'] = "중복성 지급 오류";
            Log::debug($arr_result);
            return response($arr_result, 201);
        }

        // user_no와 ad_id를 기준으로 중복지급방지.
        $free_charge = FreeChargeHistory::where('ad_id', $ads_id)->where('user_no', $user_no)->first();
        if ($free_charge != null) {
            //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 6;
            $arr_result['ResultMsg'] = "중복성 지급 오류";
            Log::debug($arr_result);
            return response($arr_result, 201);
        }

        $nPoint = intval($money) * config('constants.FREE_CHARGE_RATIO_NAS');
        $ret = $user->addPoint(config('constants.POINT_HISTORY_TYPE_FREE_CHARGE'), $nPoint);

        if ($ret == false) {
            //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 7;
            $arr_result['ResultMsg'] = "포인트 지급 오류";
            Log::debug($arr_result);
            return response($arr_result, 201);
        }

        // add history
        $history = new FreeChargeHistory();
        $history->user_no = $user->no;
        $history->point = $nPoint;
        $history->seq_id = $seq_id;
        $history->ad_id = $ads_id;
        $history->type = config('constants.FREE_CHARGE_TYPE_NAS');
        $history->save();

        // 매체사 유저에게 리워드 지급 후 성공 Json string return >
        //AdSync Noti 서버가 위의 JSON 응답값을 확인 ResultCode값을 Parsing 하여 관련 이력을 처리(AdSync 백오피스에서 확인 가능)
        // "1"이 아닌 경우는 일일배치로 본페이지를 재호출함.

        $arr_result['Result'] = true;
        $arr_result['ResultCode'] = 1;
        $arr_result['ResultMsg'] = "success";
        $arr_result['ResultKey'] = $ads_key;
        Log::debug($arr_result);

        // send push
        $admin_no = config('constants.DEFAULT_ADMIN_NO');
        $data = array();
        $data['point'] = $nPoint;
        $this->sendAlarmMessage($admin_no,  $user->no, config('constants.NOTI_TYPE_SUCCESS_FREE_CHARGE'), $data);

        return response($arr_result, 200);
    }

    public function igaworks_delivery_point(HttpRequest $request)
    {
        $signed_value = $request->input('signed_value');    // 리워드  요청  보안  체크  값
        $usn = $request->input('usn');    // 리워드를  지급할  유저  ID
        $reward_key = $request->input('reward_key');        // 리워드  요청에  대한  transaction_id(각  리워드  요청당  unique)
        $quantity = $request->input('quantity');        // 리워드  지급량
        $campaign_key = $request->input('campaign_key');        // 참여  완료한  캠페인  키

        $hash_key = "883c9df1350140e7";
        // < signed_value  체크  성공  >
        if ($signed_value == hash_hmac('md5', $usn . $reward_key . $quantity . $campaign_key, $hash_key)) {
            //  매체사  서버에서  IGAWorks 의  리워드  지급  요청을  처리하였음에도,
            //  네트워크  오류  등으로  인해  IGAWorks  서버에서  리워드  지급  요청이  실패했다고  판단하고  동일한  지급요청을  다시  보내는  경우가  발생할  수  있음.
            //  이  때  매체사  서버에서는  IGAWorks  서버가  보낸  reward_key 가  이미  지급  처리된  리워드  요청에  대한 reward_key 일  경우  아래와  같이  처리.
            // < reward_key 에  해당하는  리워드  지급이  이미  완료  되었는지  체크  >
            $free_charge = FreeChargeHistory::where('seq_id', $reward_key)->first();
            if ($free_charge != null) {
                //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
                $arr_result['Result'] = false;
                $arr_result['ResultCode'] = 3100;
                $arr_result['ResultMsg'] = "중복성 지급 오류";
                Log::debug($arr_result);
                return response($arr_result);
            } else {
                // 매체사 처리할 리워드 지급 ..
                $user = User::where('no', $usn)->first();
                if ($user == null) {
                    //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
                    $arr_result['Result'] = false;
                    $arr_result['ResultCode'] = 3;
                    $arr_result['ResultMsg'] = "존재하지 않은 유저";
                    Log::debug($arr_result);
                    return response($arr_result);
                }

                // <  유저에게  리워드  지급  후  성공  Json string return >
                // {"Result":true,"ResultCode":1,"ResultMsg":"success"}
                $nPoint = intval($quantity) * config('constants.FREE_CHARGE_RATIO_IGAWORKS');
                $ret = $user->addPoint(config('constants.POINT_HISTORY_TYPE_FREE_CHARGE'), $nPoint);

                if ($ret == false) {
                    //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
                    $arr_result['Result'] = false;
                    $arr_result['ResultCode'] = 7;
                    $arr_result['ResultMsg'] = "포인트 지급 오류";
                    Log::debug($arr_result);
                    return response($arr_result);
                }

                // add history
                $history = new FreeChargeHistory();
                $history->user_no = $user->no;
                $history->point = $nPoint;
                $history->seq_id = $reward_key;
                $history->type = config('constants.FREE_CHARGE_TYPE_IGAWORKS');
                $history->save();

                $arr_result['Result'] = true;
                $arr_result['ResultCode'] = 1;
                $arr_result['ResultMsg'] = "success";
                Log::debug($arr_result);

                // send push
                $admin_no = config('constants.DEFAULT_ADMIN_NO');
                $data = array();
                $data['point'] = $nPoint;
                $this->sendAlarmMessage($admin_no,  $user->no, config('constants.NOTI_TYPE_SUCCESS_FREE_CHARGE'), $data);

                return response($arr_result);
            }
        } else {
            // < signed_value  체크  실패  >
            // <  보안  체크  실패에  해당하는  Json string return >
            // {"Result":false,"ResultCode":1100,"ResultMsg":"invalid hash key"}
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 1100;
            $arr_result['ResultMsg'] = "유효성 오류";
            Log::debug($arr_result);
            return response($arr_result);
        }
    }
}
