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
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop


