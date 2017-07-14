<?php

namespace App\Http\Controllers;

use App\Models\ManageNotice;
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
        $param = $this->getPage(config("constants.MOBILE_SERVICE_PAGE"));
        return view('mobile.index', $param);
    }

    public function agreement_service()
    {
        $params = Request::all();
        if ($params == null) {
            $params = $this->getPage(config("constants.MOBILE_SERVICE_PAGE"));
        }
        return view('mobile.service', $params);
    }

    public function agreement_privacy()
    {
        $params = Request::all();
        if ($params == null) {
            $params = $this->getPage(config("constants.MOBILE_PRIVACY_PAGE"));
        }
        return view('mobile.privacy', $params);
    }

    public function agreement_gps()
    {
        $params = Request::all();
        if ($params == null) {
            $params = $this->getPage(config("constants.MOBILE_GPS_PAGE"));
        }
        return view('mobile.gps', $params);
    }

    public function use_guide()
    {
        $params = Request::all();
        if ($params == null) {
            $params = $this->getPage(config("constants.MOBILE_USE_GUIDE_PAGE"));
        }
        return view('mobile.use_guide', $params);
    }

    public function google_card_register_guide()
    {
        $params = Request::all();
        if ($params == null) {
            $params = $this->getPage(config("constants.MOBILE_GOOGLE_PAY_PAGE"));
        }
        return view('mobile.google_card_register_guide', $params);
    }

    public function notify()
    {
        $params = Request::all();

        if ($params == null) {
            $params = $this->getPage(config("constants.MOBILE_NOTIFY_PAGE"));
        } else {
            if (array_key_exists("no", $params) == true) {
                $params = ManageNotice::where('no', $params['no'])->first();
            }
        }
        return view('mobile.notify', $params);
    }

    private function get_page_url($type)
    {
        $url = "";
        if ($type == config("constants.MOBILE_SERVICE_PAGE")) {
            $url = URL::to('/agreement/service');
        } else if ($type == config("constants.MOBILE_PRIVACY_PAGE")) {
            $url = URL::to('/agreement/privacy');
        } else if ($type == config("constants.MOBILE_GPS_PAGE")) {
            $url = URL::to('/agreement/gps');
        } else if ($type == config("constants.MOBILE_GOOGLE_PAY_PAGE")) {
            $url = URL::to('/google_card_register_guide');
        } else if ($type == config("constants.MOBILE_USE_GUIDE_PAGE")) {
            $url = URL::to('/use_guide');
        } else if ($type == config("constants.MOBILE_NOTIFY_PAGE")) {
            $url = URL::to('/notify');
        }

        return $url;
    }

    private function getPage($type)
    {
        $mobile_page = MobilePage::where('type', $type)->first();

        if ($mobile_page == null) {
            $mobile_page = new MobilePage();
            $mobile_page->url = $this->get_page_url($type);
            $mobile_page->type = $type;
            $mobile_page->content = "";
        }
        return $mobile_page;
    }

    public function get_mobile_page()
    {
        $params = Request::all();
        $type = $params['type'];
        $result = $this->getPage($type);
        if ($result) {
            return json_encode($result);
        } else
            return config('constants.FAIL');
    }

    public function get_mobile_page_url()
    {
        $params = Request::all();
        $type = $params['type'];
        $result = $this->get_page_url($type);
        if ($result) {
            return $result;
        } else
            return config('constants.FAIL');
    }


    public function save_mobile_page()
    {
        $params = Request::all();
        $data['type'] = $params['type'];
        $isurl = $params['is_url'];

        if ($isurl == "false") {
            $data['content'] = $params['content'];
            $data['url'] = $this->get_page_url($data['type']);
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
