<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Url decode.
     *
     * @param p_text : Data to decode
     *
     * @return text : Decoded text
     */
    public static function url_decord($p_text)
    {
        $p_text = urldecode($p_text);

        //
        // [2014/09/26 10:41]urldecode()ยÜบใฐก '()'ฑจย๖ถฆ ผณร๚รล ดัฟธดล ยืผ่ ทรยืฒ๗ หหบ๏หุหม.
        // &#40;, &#41;ตแ ดัฟธดลด๖.
        //
        $p_text = str_replace('&#40;', '(', $p_text);
        $p_text = str_replace('&#41;', ')', $p_text);

        return $p_text;
    }

    public static function getTimeInDefaultFormat() {
        return  date("Y-m-d H:i:s");
    }

    public static function diffTime($time1, $time2) {
        $to_time = strtotime($time1);
        $from_time = strtotime($time2);
        return round(abs($to_time - $from_time) / 60, 2);
    }
}
