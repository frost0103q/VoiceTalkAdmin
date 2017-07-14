@extends('layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green" style="border: none">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-file-text-o"></i> {{trans('lang.admin_notice')}}
                    </div>
                </div>
                <div class="portlet-body">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab">{{trans('lang.opinion_share')}}</a>
                        </li>
                        <li>
                            <a href="#tab_2" data-toggle="tab">{{trans('lang.manage_notice')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_1">
                            @include('admin_notice.opinion')
                        </div>
                        <div class="tab-pane fade" id="tab_2">
                            @include('admin_notice.manage_notice')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            init_tbl_opinion();
        });

        $(".nav.nav-tabs li:eq(1)").click(function () {
            init_tbl_manage_notice();
        });
    </script>
@stop


