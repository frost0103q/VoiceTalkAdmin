@extends('layouts.main')

@section('content')

    <style>
        .profile-userpic img{
            width: 100%;
            border-radius: 0% !important;
        }
        .image-potlet{
            background-color: #f5f5f5 !important;
        }

        .profile-userinfo img {
            float: none;
            margin: 0 auto;
            -webkit-border-radius: 50% !important;
            -moz-border-radius: 50% !important;
            border-radius: 50% !important;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green" style="border: none">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-microphone"></i>{{trans('lang.voice_agree')}}
                    </div>
                </div>
                <div class="portlet-body">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab">{{trans('lang.wait_voice')}}</a>
                        </li>
                        <li>
                            <a href="#tab_2" data-toggle="tab">{{trans('lang.agree_voice')}}</a>
                        </li>
                        <li>
                            <a href="#tab_3" data-toggle="tab">{{trans('lang.disagree_voice')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_1">
                            @include('voice_agree.wait_voice')
                        </div>
                        <div class="tab-pane fade" id="tab_2">
                            @include('voice_agree.agree_voice')
                        </div>
                        <div class="tab-pane fade" id="tab_3">
                            @include('voice_agree.disagree_voice')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $(window).resize(function () {
                resizeView();
            });

            resizeView();
        });
        function resizeView() {
            $(".tab-pane.fade.active .img-responsive").each(function () {
                var height=$(this).width();
                $(this).css({'height': height + 'px'});
            });
        }

        $(".nav.nav-tabs li").on('click',function () {
            setTimeout(function () {
                resizeView();
            },200);
        });

        function voice_agree(file_no,talk_no,obj,cur_status) {
            $.ajax({
                url: "voice_agree",
                type: "get",
                data: {
                    t_file_no : file_no,
                    talk_no : talk_no
                },
                success: function (result) {
                    if(result=='{{config('constants.FAIL')}}')
                        toastr["error"]("{{trans('lang.fail_agree')}}", "{{trans('lang.notice')}}");
                    else{
                        toastr["success"]("{{trans('lang.success_agree')}}", "{{trans('lang.notice')}}");
                        if(cur_status!='{{config('constants.AGREE')}}'){
                            $(obj).closest(".portlet.light.image-potlet").parent('div').remove();
                            $("#tab_2 .row").append(result);
                        }
                    }
                }
            });
        }

        function voice_disagree(file_no,talk_no,obj,cur_status) {
            $.ajax({
                url: "voice_disagree",
                type: "get",
                data: {
                    t_file_no : file_no,
                    talk_no : talk_no
                },
                success: function (result) {
                    if(result=='{{config('constants.FAIL')}}')
                        toastr["error"]("{{trans('lang.fail_disagree')}}", "{{trans('lang.notice')}}");
                    else{
                        toastr["success"]("{{trans('lang.success_disagree')}}", "{{trans('lang.notice')}}");
                        if(cur_status!='{{config('constants.DISAGREE')}}'){
                            $(obj).closest(".portlet.light.image-potlet").parent('div').remove();
                            $("#tab_3 .row").append(result);
                        }
                    }
                }
            });
        }
    </script>
@stop


