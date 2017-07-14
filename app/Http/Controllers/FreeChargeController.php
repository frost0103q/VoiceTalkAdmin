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
use DB;
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

    public function adsync_delivery_point()
    {

        $partner = $_POST['partner'];    // 파트너 앱(서비스) ID
        $cust_id = $_POST['cust_id'];    // 파트너 회원 ID
        $ad_no = $_POST['ad_no'];        // 적립 광고 번호
        $seq_id = $_POST['seq_id'];        // 포인트 적립 고유 ID
        $point = $_POST['point'];        // 적립 포인트
        $ad_title = $_POST['ad_title'];    // 적립 광고 제목
        $valid_key = $_POST['valid_key'];    // 유효성 확인 Key

        // 서비스 처리 코딩 후 결과를 기본값
        $arr_result = array('Result' => true, 'ResultCode' => 1, 'ResultMsg' => "성공");

        // 전달 받은 파라미터 체크 부분
        if (empty($partner) || empty($cust_id) || empty($ad_no) || empty($point) || empty($seq_id) || empty($valid_key) || empty($ad_title)) {
            //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 4;
            $arr_result['ResultMsg'] = "파라미터 오류";
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
            echo json_encode($arr_result);
            exit;
        }


        // 적립포인트 수신에 대한 중복 체크 처리 부분(단일 고유키키값 seq_id)
        //서버로 전달받은 seq_id 값으로 기존 성공적으로 전달받은 seq_id 값이 있는지 DB 검사
        //ex)성공적으로 전달받은 적립포인트정보내역 테이블: partner_point

        $seq_cnt = 0;        // 0이상이면 증복이다.
        if ($seq_cnt != 0) {
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 3;
            $arr_result['ResultMsg'] = "중복 지급  오류";
            echo json_encode($arr_result);
            exit;
        }

        // 매체사 처리할 리워드 지급 ..
        $user = User::where('no', $cust_id)->first();
        if ($user == null) {
            //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 2;
            $arr_result['ResultMsg'] = "유효성 확인 key 오류";
            echo json_encode($arr_result);
            exit;
        }

        $ret = $user->addPoint(config('constants.POINT_HISTORY_TYPE_FREE_CHARGE'), $point);
        if ($ret == false) {
            //서비스 처리 코딩 후 결과를 JSON 형식으로 출력
            $arr_result['Result'] = false;
            $arr_result['ResultCode'] = 2;
            $arr_result['ResultMsg'] = "유효성 확인 key 오류";
            echo json_encode($arr_result);
            exit;
        }

        // add history
        $history = new FreeChargeHistory();
        $history->user_no = $user->no;
        $history->point = $point;
        $history->type = config('constants.FREE_CHARGE_TYPE_ADSYNC');
        $history->save();

        // 매체사 유저에게 리워드 지급 후 성공 Json string return >
        //AdSync Noti 서버가 위의 JSON 응답값을 확인 ResultCode값을 Parsing 하여 관련 이력을 처리(AdSync 백오피스에서 확인 가능)
        // "1"이 아닌 경우는 일일배치로 본페이지를 재호출함.

        $arr_result['Result'] = true;
        $arr_result['ResultCode'] = 1;
        $arr_result['ResultMsg'] = "success";

        echo json_encode($arr_result);
    }
}
