<?php

namespace App\Http\Controllers;

use App\Models\MobilePage;
use App\Models\TalkReview;
use Config;
use DB;
use Illuminate\Support\Facades\URL;
use Request;

class MobilePageController extends BasicController
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
        return view('mobile.index');
    }

    private function getPage($type)
    {
        $mobile_page = MobilePage::where('type', $type)->first();
        return $mobile_page;
    }

    public function agreement_service()
    {
        return view('mobile.service', getPage(config("constants.MOBILE_SERVICE_PAGE")));
    }

    public function agreement_privacy()
    {
        return view('mobile.privacy', getPage(config("constants.MOBILE_PRIVACY_PAGE")));
    }

    public function agreement_gps()
    {
        return view('mobile.gps', getPage(config("constants.MOBILE_GPS_PAGE")));
    }

    public function notify()
    {
        return view('mobile.gps', getPage(config("constants.MOBILE_NOTIFY_PAGE")));
    }

    public function use_guide()
    {
        return view('mobile.use_guide', getPage(config("constants.MOBILE_USE_GUIDE_PAGE")));
    }

    public function google_card_register_guide()
    {
        return view('mobile.google_card_register_guide', getPage(config("constants.MOBILE_GOOGLE_PAY_PAGE")));
    }

    public function save_mobile_page()
    {
        $params = Request::all();
        $data['type'] = $params['type'];
        $isurl = $params['is_url'];

        if ($isurl == "false") {
            $data['content'] = $params['content'];
            if ($data['type'] == config("constants.MOBILE_SERVICE_PAGE")) {
                $data['url'] = URL::to('/agreement/service');
            } else if ($data['type'] == config("constants.MOBILE_PRIVACY_PAGE")) {
                $data['url'] = URL::to('/agreement/privacy');
            } else if ($data['type'] == config("constants.MOBILE_GPS_PAGE")) {
                $data['url'] = URL::to('/agreement/gps');
            } else if ($data['type'] == config("constants.MOBILE_GOOGLE_PAY_PAGE")) {
                $data['url'] = URL::to('/google_card_register_guide');
            } else if ($data['type'] == config("constants.MOBILE_USE_GUIDE_PAGE")) {
                $data['url'] = URL::to('/use_guide');
            } else if ($data['type'] == config("constants.MOBILE_NOTIFY_PAGE")) {
                $data['url'] = URL::to('/notify');
            }
        } else {
            $data['content'] = "";
            $data['url'] = $params['content'];
        }

        $mobile_page = MobilePage::where('type', $params['type'])->first();
        if ($mobile_page == null) {
            $mobile_page = new MobilePage();
        }
        $mobile_page->content = $data['content'];
        $mobile_page->url = $data['url'];
        $mobile_page->type = $data['type'];
        $result = $mobile_page->save();

        if ($result) {
            return config('constants.SUCCESS');
        } else
            return config('constants.FAIL');
    }
}
