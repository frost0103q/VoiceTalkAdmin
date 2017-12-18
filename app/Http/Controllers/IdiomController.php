<?php

namespace App\Http\Controllers;

use Config;
use DB;
use Session;
use Illuminate\Http\Request as HttpRequest;
use Redirect;
use Request;
use Socialite;
use URL;


class IdiomController extends BasicController
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
            return redirect("/login");
        }

        $interdict_idiom = DB::table('t_interdict_idiom')->first();
        if ($interdict_idiom != null)
            $content = $interdict_idiom->content;
        else
            $content = "";
        return view('idiom_manage.index', ['menu_index' => 9, 'content' => $content]);
    }

    public function includeForbidden($text)
    {
        $interdict_idiom = DB::table('t_interdict_idiom')->first();
        if ($interdict_idiom == null || $text == null || $text == '') {
            return false;
        }

        $arr_idiom = explode(",", $interdict_idiom->content);

        $size = count($arr_idiom);
        $include = false;
        for ($i = 0; $i < $size; $i++) {
            $idiom = trim($arr_idiom[$i]);
            if($idiom == null || $idiom == '') {
                continue;
            }
            if (strpos($text, $idiom) !== false) {
                $include = true;
                break;
            }
        }

        return $include;
    }

    public function getIdiomList() {
        $interdict_idiom = DB::table('t_interdict_idiom')->first();
        if ($interdict_idiom == null) {
            return array();
        }

        $arr_idiom = explode(",", $interdict_idiom->content);
        $cnt = count($arr_idiom);
        for($i = ($cnt - 1); $i >= 0; $i--) {
            if(trim($arr_idiom[$i]) == '' ) {
                $arr_idiom = array_splice($arr_idiom, $i, 1);
            }
        }

        return $arr_idiom;
    }

    public function save_interdict_idiom()
    {

        $content = $_POST['idiom_str'];

        $interdict_idiom = DB::table('t_interdict_idiom')->first();
        if ($interdict_idiom == null) {

            $result = DB::insert('insert into t_interdict_idiom (no,content,created_at) values(1,?,?)', [$content, date('Y-m-d H:i:s')]);

            if ($result) {
                return view('idiom_manage.select_idiom', ['content' => $content]);
            } else
                return config('constants.FAIL');
        } else {
            $no = $interdict_idiom->no;
            $content_str = $interdict_idiom->content . ',' . $content;

            $result = DB::table('t_interdict_idiom')
                ->where('no', $no)
                ->update(['content' => $content_str, 'updated_at' => date('Y-m-d H:i:s')]);

            if ($result) {
                return view('idiom_manage.select_idiom', ['content' => $content_str]);
            } else
                return config('constants.FAIL');
        }

    }

    public function del_selected_idiom()
    {
        $content = $_POST['idiom_str'];
        $interdict_idiom = DB::table('t_interdict_idiom')->first();
        if ($interdict_idiom == null) {
            $result = DB::table('t_interdict_idiom')->insert(
                ['content' => $content, 'updated_at' => '', 'created_at' => date('Y-m-d H:i:s')]);
            if ($result) {
                return view('idiom_manage.select_idiom', ['content' => $content]);
            } else
                return config('constants.FAIL');
        } else {
            $no = $interdict_idiom->no;

            $result = DB::table('t_interdict_idiom')
                ->where('no', $no)
                ->update(['content' => $content, 'updated_at' => date('Y-m-d H:i:s')]);

            if ($result) {
                return view('idiom_manage.select_idiom', ['content' => $content]);
            } else
                return config('constants.FAIL');
        }
    }
}
