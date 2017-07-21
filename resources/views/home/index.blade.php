@extends('layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green" style="border: none">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-home"></i> {{trans('lang.homepage')}}
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row" style="padding-top: 20px">
                        <div class="col-md-6">
                            <div class="note note-success">
                                <h4 class="block"><strong>{{trans('lang.connect_status')}}</strong></h4>
                                <p>{{trans('lang.today_connect')}} &nbsp;&nbsp;&nbsp; {{number_format($today_connect_cnt)}}</p>
                                <p>{{trans('lang.yesterday_connect')}} &nbsp;&nbsp;&nbsp; {{number_format($yesterday_connect_cnt)}}</p>
                                <p>{{trans('lang.max_day_connect')}} &nbsp;&nbsp;&nbsp; {{($max_connect_day_cnt)}}</p>
                                <p>{{trans('lang.total_statistic')}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{number_format($total_connect_cnt)}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="note note-success">
                                <h4 class="block"><strong>{{trans('lang.sale_status')}}</strong></h4>
                                <p>{{trans('lang.today_sale')}} &nbsp;&nbsp;&nbsp; {{number_format($today_sale)}}</p>
                                <p>{{trans('lang.today_withdraw')}} &nbsp;&nbsp;&nbsp; {{number_format($today_withdraw)}}</p>
                                <p>{{trans('lang.yesterday_sale')}} &nbsp;&nbsp;&nbsp; {{number_format($yesterday_sale)}}</p>
                                <p>{{trans('lang.month_statistic')}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{number_format($total_withdraw_and_sale)}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


