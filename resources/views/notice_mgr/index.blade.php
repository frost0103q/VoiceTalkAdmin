@extends('layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green" style="border: none">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-edit"></i> {{trans('lang.notice_manage')}}
                    </div>
                </div>
                <div class="portlet-body">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab">{{trans('lang.push_sending')}}</a>
                        </li>
                        {{--<li>
                            <a href="#tab_2" data-toggle="tab">{{trans('lang.banner_reg')}}</a>
                        </li>--}}
                        <li>
                            <a href="#tab_3" data-toggle="tab">{{trans('lang.talk_notice')}}</a>
                        </li>
                        <!--<li>
                            <a href="#tab_4" data-toggle="tab">{{trans('lang.message_notice')}}</a>
                        </li> -->
                        <li>
                            <a href="#tab_5" data-toggle="tab">{{trans('lang.sms_sending')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_1">
                            @include('notice_mgr.push')
                        </div>
                        {{--<div class="tab-pane fade" id="tab_2">
                            @include('notice_mgr.banner')
                        </div>--}}
                        <div class="tab-pane fade" id="tab_3">
                            @include('notice_mgr.talk')
                        </div>
                        {{-- <div class="tab-pane fade" id="tab_4">
                             @include('notice_mgr.message')
                         </div> --}}
                        <div class="tab-pane fade" id="tab_5">
                            @include('notice_mgr.sms')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            init_tbl_push();
        });

        /*$(".nav.nav-tabs li:eq(1)").click(function () {
            init_tbl_banner();
        });*/
        $(".nav.nav-tabs li:eq(1)").click(function () {
            init_tbl_talk();
        });
        $(".nav.nav-tabs li:eq(2)").click(function () {
            init_tbl_sms();
        });
    </script>
@stop


