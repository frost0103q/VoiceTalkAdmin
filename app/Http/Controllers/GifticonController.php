<?php

namespace App\Http\Controllers;

use App\Models\GifticonProduct;
use App\Models\GifticonHistory;
use App\Models\User;
use Config;
use DB;
use Illuminate\Http\Request as HttpRequest;

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

    public function getGiftBrandList(HttpRequest $request) {
        $arr_product = GifticonProduct::select(DB::raw('brand_no, brand_name'))->groupby('brand_no')->get();
        $cid = Config::get('config.giftN')['cid'];

        for($i = 0; $i  < count($arr_product); $i++) {
            $brand = $arr_product[$i];

            $w_url = "https://wapi.gift-n.net/getBrandImage?cid=" . $cid . "&brand_id=" . $brand->brand_no;
            $w_opt_arr = array(
                CURLOPT_URL => $w_url,
                CURLOPT_POST => false,
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                CURLOPT_RETURNTRANSFER => true,
            );
            $w_ch = curl_init();
            curl_setopt_array($w_ch, $w_opt_arr);
            $w_result = curl_exec($w_ch);
            curl_close($w_ch);
            $w_result_json = json_decode($w_result, true);

            if($w_result_json["STATUS"] == 'Y') {
                $arr_product[$i]->img_url = $w_result_json['IMG_URL'];
            }
            else {
                $arr_product[$i]->img_url = "";
            }
        }

        return response()->json($arr_product);
    }

    public function getGiftListByCategory(HttpRequest $request) {
        $category_no = $request->input('category_no');

        if ($category_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $cid = Config::get('config.giftN')['cid'];
        $ckey = Config::get('config.giftN')['ckey'];

        $arr_product = GifticonProduct::where('category_no', $category_no)->get();

        for($i = 0; $i  < count($arr_product); $i++) {
            $product = $arr_product[$i];

            $w_enc = urlencode(md5($ckey . $cid . $product->product_id));

            $w_url = "https://wapi.gift-n.net/getGoodsInfo?cid=" . $cid . "&goods_id=" . $product->product_id. "&enc=" . $w_enc;
            $w_opt_arr = array(
                CURLOPT_URL => $w_url,
                CURLOPT_POST => false,
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                CURLOPT_RETURNTRANSFER => true,
            );
            $w_ch = curl_init();
            curl_setopt_array($w_ch, $w_opt_arr);
            $w_result = curl_exec($w_ch);
            curl_close($w_ch);
            $w_result_json = json_decode($w_result, true);

            if($w_result_json["STATUS"] == 'E') {
                $arr_product[$i]->img_url = "";
            }
            else {
                $arr_product[$i]->img_url = $w_result_json['IMG_URL'];
            }
        }

        return response()->json($arr_product);
    }

    public function getGiftList(HttpRequest $request) {
        $brand_no = $request->input('brand_no');

        if ($brand_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $cid = Config::get('config.giftN')['cid'];
        $ckey = Config::get('config.giftN')['ckey'];

        $arr_product = GifticonProduct::where('brand_no', $brand_no)->get();

        for($i = 0; $i  < count($arr_product); $i++) {
            $product = $arr_product[$i];

            $w_enc = urlencode(md5($ckey . $cid . $product->product_id));

            $w_url = "https://wapi.gift-n.net/getGoodsInfo?cid=" . $cid . "&goods_id=" . $product->product_id. "&enc=" . $w_enc;
            $w_opt_arr = array(
                CURLOPT_URL => $w_url,
                CURLOPT_POST => false,
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                CURLOPT_RETURNTRANSFER => true,
            );
            $w_ch = curl_init();
            curl_setopt_array($w_ch, $w_opt_arr);
            $w_result = curl_exec($w_ch);
            curl_close($w_ch);
            $w_result_json = json_decode($w_result, true);

            if($w_result_json["STATUS"] == 'E') {
                $arr_product[$i]->img_url = "";
            }
            else {
                $arr_product[$i]->img_url = $w_result_json['IMG_URL'];
            }
        }

        return response()->json($arr_product);
    }

    public function requestGiftIcon(HttpRequest $request)
    {
        $goods_id = $request->input('goods_id');
        $user_no = $request->input('user_no');

        if ($goods_id == null || $user_no == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $user = User::where('no', $user_no)->first();
        if ($user == null) {
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
        $w_url = "https://wapi.gift-n.net/SendEPin?cid=" . $cid . "&enc=" . $w_enc . "&goods_id=" . $goods_id . "&count=1&title=" . $w_title . "&content=" . $w_content . "&mdn=" . $user->phone_number . "&receive_tel=" . $user->phone_number . "&co_tid=" . $w_co_tid . "&reserved=0";
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

            $product = GifticonProduct::where('product_id', $goods_id)->first();

            // giftIcon history
            $gift_history = new GifticonHistory();
            $gift_history->cupon_number =  $product->product_id;
            $gift_history->mgr_number = "";
            $gift_history->pdt_nm =  $product->product_name;
            $gift_history->user_no =  $user->no;
            $gift_history->nomal_price =  $product->sell_price;
            $gift_history->sale_price =  $product->calc_price;
            $gift_history->real_price =  $product->calc_price;
            $gift_history->benefit =  ($product->sell_price - $product->calc_price);

            if ($w_result_json['rstCode'] != '0') {
                $w_err_msg = $w_result_json['rstMsg'];
                $response = config('constants.ERROR_FAILED_PURCHASE');
                $response['message'] = $w_err_msg;
                return response()->json($response);
            } else {
                $w_epin = $w_result_json['epin'];
                $w_order_id = $w_result_json['order_id'];

                //. 상품구매내역에 저장

                $product->epin = $w_epin;
                $product->order_id = $w_order_id;

                // user point 감소
                $user->addPoint(config('constants.POINT_HISTORY_TYPE_GIFTICON'), (-1) * ($product->calc_price));

                $gift_history->mgr_number = $w_order_id;
                $gift_history->status =  config('constants.GIFTICON_NOMAL');
                $gift_history->save();

                return response()->json($product);
            }
        }
    }
}
