@extends('layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green" style="border: none">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-bank"></i> {{trans('lang.cach_manage')}}
                    </div>
                </div>
                <div class="portlet-body">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab">{{trans('lang.cash_manage')}}</a>
                        </li>
                        <li>
                            <a href="#tab_2" data-toggle="tab">{{trans('lang.req_withdraw')}}</a>
                        </li>
                        <li>
                            <a href="#tab_3" data-toggle="tab">{{trans('lang.gifticon_buy')}}</a>
                        </li>
                        <li>
                            <a href="#tab_4" data-toggle="tab">{{trans('lang.point_present_content')}}</a>
                        </li>
                        <li>
                            <a href="#tab_5" data-toggle="tab">{{trans('lang.point_rank')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_1">
                            @include('withdraw.cash')
                        </div>
                        <div class="tab-pane fade" id="tab_2">
                            @include('withdraw.withdraw')
                        </div>
                        <div class="tab-pane fade" id="tab_3">
                            @include('withdraw.gifticon')
                        </div>
                        <div class="tab-pane fade" id="tab_4">
                            @include('withdraw.point_present')
                        </div>
                        <div class="tab-pane fade" id="tab_5">
                            @include('withdraw.point_rank')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop


