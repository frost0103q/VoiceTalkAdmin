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
                    <div class="row" id="statistic_pad" style="padding-top: 30px">
                        @include('home.statistic');
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function () {
           setInterval(function () {
               redraw_statistic();
           },3000);
        });

        function redraw_statistic() {
            $.ajax({
                url: "redraw_statistic",
                type: "post",
                data: {
                    temp : "",
                    _token: "{{csrf_token()}}"
                },
                success: function (result) {
                    $('#statistic_pad').html(result);
                }
            });
        }
    </script>
@stop



