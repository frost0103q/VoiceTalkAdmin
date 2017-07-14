@extends('layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green" style="border: none">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-credit-card"></i> {{trans('lang.do_cash_question')}}
                    </div>
                </div>
                <div class="portlet-body">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab">{{trans('lang.do_cash_question')}}</a>
                        </li>
                        <li>
                            <a href="#tab_2" data-toggle="tab">{{trans('lang.do_declare')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_1">
                            @include('cash_question.cash_question')
                        </div>
                        <div class="tab-pane fade" id="tab_2">
                            @include('cash_question.cash_declare')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            init_tbl_cash_question();
        });

        $(".nav.nav-tabs li:eq(1)").click(function () {
            init_tbl_declare();
        });
    </script>
@stop


