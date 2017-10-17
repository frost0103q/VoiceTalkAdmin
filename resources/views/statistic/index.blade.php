@extends('layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green" style="border: none">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-line-chart"></i> {{trans('lang.statistic_manage')}}
                    </div>
                </div>
                <div class="portlet-body">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab">{{trans('lang.connect_statistic')}}</a>
                        </li>
                        {{--<li>
                            <a href="#tab_2" data-toggle="tab">{{trans('lang.edwards_ad')}}</a>
                        </li>--}}
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_1">
                            @include('statistic.connect')
                        </div>
                        {{--<div class="tab-pane fade" id="tab_2">
                            @include('statistic.edwards')
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            init_tbl_connect();
        });

        $(".nav.nav-tabs li:eq(1)").click(function () {
            init_tbl_edwards();
        });
    </script>

@stop


