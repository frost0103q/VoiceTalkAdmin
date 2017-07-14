@extends('layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green" style="border: none">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-list"></i>Talk/{{trans('lang.user_manage')}}
                    </div>
                </div>
                <div class="portlet-body">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab">{{trans('lang.user_list')}}</a>
                        </li>
                        <li>
                            <a href="#tab_2" data-toggle="tab">{{trans('lang.talk_list')}}</a>
                        </li>
                        <li>
                            <a href="#tab_3" data-toggle="tab">{{trans('lang.declare_content')}}</a>
                        </li>
                        <li>
                            <a href="#tab_4" data-toggle="tab">{{trans('lang.exit_request')}}</a>
                        </li>
                        <li>
                            <a href="#tab_5" data-toggle="tab">{{trans('lang.exit_user')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_1">
                            @include('talk_user_mgr.user_list')
                        </div>
                        <div class="tab-pane fade" id="tab_2">
                            @include('talk_user_mgr.talk_list')
                        </div>
                        <div class="tab-pane fade" id="tab_3">
                            @include('talk_user_mgr.declare_content')
                        </div>
                        <div class="tab-pane fade" id="tab_4">
                            @include('talk_user_mgr.exit_request_user_list')
                        </div>
                        <div class="tab-pane fade" id="tab_5">
                            @include('talk_user_mgr.exit_user_list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            init_tbl_user();
        });

        $(".nav.nav-tabs li:eq(1)").click(function () {
            init_tbl_talk();
        });
        $(".nav.nav-tabs li:eq(2)").click(function () {
            init_tbl_declare();
        });
        $(".nav.nav-tabs li:eq(3)").click(function () {
            init_tbl_er_user();
        });
        $(".nav.nav-tabs li:eq(4)").click(function () {
            init_tbl_e_user();
        });
    </script>

@stop


