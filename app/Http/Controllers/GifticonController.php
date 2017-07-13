<?php

namespace App\Http\Controllers;

use App\Models\AppUser;
use App\Models\GifticonProduct;
use Illuminate\Http\Request as HttpRequest;
use Config;
use DB;

class GifticonController extends BasicController
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

    public function requestGiftIcon(HttpRequest $request) {
        $goods_id = $request->input('goods_id');
        $user_no = $request->input('user_no');

        if($goods_id == null || $user_no == null){
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $user = AppUser::where('no', $user_no)->first();
        if($user == null) {
            $response = config('constants.ERROR_NO_INFORMATION');
            return response()->json($response);
        }
        //
        // 기프트엔 쿠폰 발행.
        //
        $w_ch = curl_init();
        $w_title = urlencode("제목");
        $w_content = urlencode("내용");
        $w_co_tid = '0';
        $cid = Config::get('config.giftN')['cid'];
        $ckey = Config::get('config.giftN')['ckey'];
        $w_enc = urlencode(md5($ckey . $cid . $goods_id . $w_co_tid));
        $w_url = "https://wapi.gift-n.net/SendEPin?cid=" . $cid . "&enc=" . $w_enc . "&goods_id=" . $goods_id . "&count=1&title=" . $w_title . "&content=" . $w_content . "&mdn=" .$user->phone_number . "&receive_tel=" . $user->phone_number . "&co_tid=" . $w_co_tid . "&reserved=0";
        $w_opt_arr = array(
            CURLOPT_URL => $w_url,
            CURLOPT_POST => false,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_RETURNTRANSFER => true,
        );
        curl_setopt_array($w_ch, $w_opt_arr);
        $w_result = curl_exec($w_ch);
        curl_close($w_ch);

        if ($w_result === false || json_decode($w_result, true) == null) {
            $response = config('constants.ERROR_FAILED_PURCHASE');
            return response()->json($response);
        } else {
            $w_result_json = json_decode($w_result, true);
            if ($w_result_json['rstCode'] != '0') {
                $w_err_msg = $w_result_json['rstMsg'];
                $response = config('constants.ERROR_FAILED_PURCHASE');
                $response['message'] = $w_err_msg;
                return response()->json($response);
            } else {
                $w_epin = $w_result_json['epin'];
                $w_order_id = $w_result_json['order_id'];

                //. 상품구매내역에 저장
                $product = GifticonProduct::where('product_id', $goods_id)->first();
                $product->epin = $w_epin;
                $product->order_id = $w_order_id;
                return response()->json($product);
            }
        }
    }
}
