<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BasicController;
use App\Models\AppUser;
use App\Models\Talk;
use App\Models\ServerFile;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;

use Config;
use DB;

class TalkController extends BasicController
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
        return view('welcome');
    }

    /**
     * getUserList
     *
     * @return json user array
     */
    public function talkList(HttpRequest $request)
    {
        $limit = $request->input('rows');
        $page = $request->input('page');
        $type = $request->input('type');
        $order = $request->input('order');
        $voice_type = $request->input('voice_type');
        $cur_lat = $request->input('cur_lat'); // 37.457087
        $cur_lng = $request->input('cur_lng'); // 126.705484

        if($limit == null) {
            $limit = Config::get('config.itemsPerPage.default');
        }

        if($page == null) {
            $params['page'] = 1;
        }

        if($order == 0) { // Distance sort
            $dist = DB::raw('(ROUND(6371 * ACOS(COS(RADIANS('.$cur_lat.')) * COS(RADIANS(t_user.latitude)) * COS(RADIANS(t_user.longitude) - RADIANS('.$cur_lng.')) + SIN(RADIANS('.$cur_lat.')) * SIN(RADIANS(t_user.latitude))),2))');

            $query = DB::table('t_talk')->where('type', $type);

            if($voice_type != 0) {
                $query = $query->where('voice_type', $voice_type);
            }

            $response = $query->join('t_user', 't_talk.user_no', '=', 't_user.no')
                ->select('t_talk.*')
                ->orderBy($dist)
                ->offset($limit*($page - 1))->limit($limit)
                ->get();
        }
        else  {            // Review sort
            $review = DB::raw('(select SUM(mark) from t_consultingreview where t_consultingreview.to_user_no=t_talk.user_no)');

            $query = DB::table('t_talk')->where('type', $type);

            if($voice_type != 0) {
                $query = $query->where('voice_type', $voice_type);
            }

            $response = $query
                ->join('t_user', 't_talk.user_no', '=', 't_user.no')
                ->select('t_talk.*')
                ->orderBy($review, 'desc')
                ->offset($limit*($page - 1))->limit($limit)
                ->get();
        }

        for($i = 0; $i < count($response); $i++) {
            $response[$i] = $this->getDetailTalkInfo($response[$i]);
            $this->addImageData($response[$i], $response[$i]->img_no);
        }

        return response()->json($response);
    }

    public function doTalk(HttpRequest $request){
        $response = config('constants.ERROR_NO');

        $oper = $request->input("oper");
        $no = $request->input('no');
        $subject = $request->input('subject');
        $greeting = $request->input('greeting');
        $voice_type = $request->input('voice_type');
        $voice_file = $request->file('talk_voice');
        $photo_file = $request->file('talk_photo');
        $user_no = $request->input('user_no');
        $type = $request->input('type') == null ? config('constants.TALK_CONSULTING')  : $request->input('type');
        $nick_name = $request->input('nickname');
        $age = $request->input('age');

        if($oper == 'add') {
            if($type == config('constants.TALK_CONSULTING') ) {
                if($greeting == null || $voice_type == null || $greeting == null || $user_no == null) {
                    $response = config('constants.ERROR_NO_PARMA');
                    return response()->json($response);
                }

                // image upload
                if($voice_file != null) {
                    $newfile = new ServerFile;
                    $voice_no = $newfile->uploadFile($voice_file, TYPE_VOICE);
                }
                else {
                    $voice_no = -1;
                }

                if($photo_file != null) {
                    $newfile = new ServerFile;
                    $photo_no = $newfile->uploadFile($photo_file, TYPE_IMAGE);
                }
                else {
                    $photo_no = -1;
                }

                if($photo_no == null) {
                    $response = config('constants.ERROR_UPLOAD_FAILED');
                    return response()->json($response);
                }

                $talk = new Talk;

                $talk->subject = $subject;
                $talk->greeting = $greeting;
                $talk->voice_type = $voice_type;
                $talk->voice_no = $voice_no;
                $talk->img_no = $photo_no;
                $talk->user_no = $user_no;
                $talk->type = $type;

                $talk->save();
                $response['no'] = $talk->no;
            }
            else {
                if($greeting == null || $nick_name == null || $age == null || $user_no == null) {
                    $response = config('constants.ERROR_NO_PARMA');
                    return response()->json($response);
                }

                $talk = new Talk;

                $talk->nickname = $nick_name;
                $talk->greeting = $greeting;
                $talk->age = $age;
                $talk->type = $type;
                $talk->user_no = $user_no;

                $talk->save();
                $response['no'] = $talk->no;
            }
        }
        else if($oper == 'edit') {
            if($no == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            $update_data = [];
            if($subject != null) {
                $update_data['subject'] = $subject;
            }
            if($voice_type != null) {
                $update_data['voice_type'] = $voice_type;
            }
            if($voice_file != null) {
                $newfile = new ServerFile;
                $voice_no = $newfile->uploadFile($voice_file, TYPE_VOICE);

                if($voice_no == null) {
                    $response = config('constants.ERROR_UPLOAD_FAILED');
                    return response()->json($response);
                }

                $update_data['voice_no'] = $voice_no;
            }
            if($user_no != null) {
                $update_data['user_no'] = $user_no;
            }

            if($photo_file != null) {
                $newfile = new ServerFile;
                $photo_no = $newfile->uploadFile($photo_file, TYPE_IMAGE);

                if($photo_no == null) {
                    $response = config('constants.ERROR_UPLOAD_FAILED');
                    return response()->json($response);
                }

                $update_data['img_no'] = $photo_no;
            }
            if($nick_name != null) {
                $update_data['nickname'] = $nick_name;
            }
            if($age != null) {
                $update_data['age'] = $age;
            }
            if($greeting != null) {
                $update_data['greeting'] = $greeting;
            }

            $results = Talk::where('no', $no)->update($update_data);
        }
        else if($oper == 'del') {
            if($no == null) {
                $response = config('constants.ERROR_NO_PARMA');
                return response()->json($response);
            }

            Talk::where('no', $no)->delete();
        }
        else {
            $response = config('constants.ERROR_NO_PARMA');
        }

        return response()->json($response);
    }

    public function talk(HttpRequest $request) {
        $user_no = $request->input('user_no');
        $type = $request->input('type');

        if($user_no == null || $type == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');
        $results = Talk::where('user_no', $user_no)->where('type', $type)->get();

        if ($results == null || count($results) == 0) {
            $response["no"] = -1;
            return response()->json($response);
        }
        else {
            $talk = $results[0];
            $talk = $this->getDetailTalkInfo($talk);
        }

        return response()->json($talk);
    }


    public function duplicateTalk(HttpRequest $request){
        $nickname = $request->input('nickname');

        if($nickname == null) {
            $response = config('constants.ERROR_NO_PARMA');
            return response()->json($response);
        }

        $response = config('constants.ERROR_NO');

        $nickname = $request->input('nickname');
        $results = Talk::where('nickname', $nickname)->get();

        if ($results != null && count($results) != 0) {
            $response = config('constants.ERROR_DUPLICATE_ACCOUNT');
            return response()->json($response);
        }

        return response()->json($response);
    }


    private function getDetailTalkInfo($talk) {
        $user = AppUser::where('no', $talk->user_no)->first();

        if($user != null) {
            $imagefile = ServerFile::where('no', $user->img_no)->first();
            if($imagefile != null) {
                $user->img_url = $imagefile->path;
            }
            else {
                $user->img_url = "";
            }
        }

        $file = ServerFile::where('no', $talk->voice_no)->first();
        $img = ServerFile::where('no', $talk->img_no)->first();
        $talk->user = $user;

        if($file != null) {
            $talk->voice_url = $file->path;
        }
        else {
            $talk->voice_url = "";
        }

        if($img != null) {
            $talk->img_url = $img->path;
        }
        else {
            $talk->img_url = "";
        }

        $arr_voice_type =   config('constants.TALK_VOICE_TYPE');
        $talk->voice_type_string = $arr_voice_type[$talk->voice_type];

        return $talk;
    }
}
