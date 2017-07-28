<?php

namespace App\Http\Controllers;

use DB;
use Session;

class HomeController extends BasicController
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


        return view('home.index', ['menu_index' => 0,
            'today_connect_cnt' => $this->get_connect_cnt_by_date(date('Y-m-d')),
            'yesterday_connect_cnt' => $this->get_connect_cnt_by_date($this->getChangeDate(date('Y-m-d'), -1)),
            'total_connect_cnt' => $this->get_total_connect_cnt(),
            'max_connect_day_cnt' => $this->get_max_connect_cnt_by_date(),
            'today_sale' => $this->get_sale_by_date(date('Y-m-d')),
            'today_withdraw' => $this->get_withdraw_by_date(date('Y-m-d')),
            'yesterday_sale' => $this->get_sale_by_date($this->getChangeDate(date('Y-m-d'), -1)),
            'total_withdraw_and_sale' => $this->get_month_withdraw_and_sale()
        ]);
    }

    public function redraw_statistic()
    {
        return view('home.statistic',
            [
            'today_connect_cnt' => $this->get_connect_cnt_by_date(date('Y-m-d')),
            'yesterday_connect_cnt' => $this->get_connect_cnt_by_date($this->getChangeDate(date('Y-m-d'), -1)),
            'total_connect_cnt' => $this->get_total_connect_cnt(),
            'max_connect_day_cnt' => $this->get_max_connect_cnt_by_date(),
            'today_sale' => $this->get_sale_by_date(date('Y-m-d')),
            'today_withdraw' => $this->get_withdraw_by_date(date('Y-m-d')),
            'yesterday_sale' => $this->get_sale_by_date($this->getChangeDate(date('Y-m-d'), -1)),
            'total_withdraw_and_sale' => $this->get_month_withdraw_and_sale()
        ]);
    }

    public function get_connect_cnt_by_date($date)
    {
        $result = DB::select("SELECT COUNT(*) as cnt from t_login_history WHERE created_at like '%" . $date . "%'");
        if ($result != null)
            return $result[0]->cnt;
        else
            return 0;
    }

    public function get_total_connect_cnt()
    {
        $result = DB::select("SELECT COUNT(*) as cnt from t_login_history");
        if ($result != null)
            return $result[0]->cnt;
        else
            return 0;
    }

    public function get_max_connect_cnt_by_date()
    {
        $result = DB::select("SELECT MIN(created_at) min_date FROM t_login_history");
        $from_date = substr($result[0]->min_date, 0, 10);

        $result = DB::select("SELECT MAX(created_at) max_date FROM t_login_history");
        $to_date = substr($result[0]->max_date, 0, 10);

        $day_cnt = $this->getDayCount($from_date, $to_date);

        $connect_cnt_array = array();
        for ($i = 0; $i < $day_cnt; $i++) {
            $date = $this->getChangeDate($from_date, $i);
            array_push($connect_cnt_array, ($this->get_connect_cnt_by_date($date)));
        }

        arsort($connect_cnt_array);

        foreach ($connect_cnt_array as $val) {
            return $val;
        }
    }

    public function get_sale_by_date($date)
    {

        $result = DB::select("SELECT SUM(cash_amount) as total from t_cash_history WHERE  created_at like '%" . $date . "%'");

        if ($result != null)
            return abs($result[0]->total);
        else
            return 0;
    }

    public function get_withdraw_by_date($date)
    {

        $result = DB::select("SELECT SUM(money) as total from t_withdraw WHERE  created_at like '%" . $date . "%' and status='1'");

        if ($result != null)
            return abs($result[0]->total);
        else
            return 0;
    }

    public function get_month_withdraw_and_sale()
    {
        return $this->get_sale_by_date(date('Y-m')) - $this->get_withdraw_by_date(date('Y-m'));
    }
}
