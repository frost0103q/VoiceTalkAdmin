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
           /* -webkit-border-radius: 50% !important;
            -moz-border-radius: 50% !important;
            border-radius: 50% !important;*/
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green" style="border: none">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-photo"></i>{{trans('lang.photo_agree')}}
                    </div>
                </div>
                <div class="portlet-body">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab">{{trans('lang.wait_all_view')}} </a>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">{{trans('lang.profile_photo')}} <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="#tab_2_1" tabindex="-1" data-toggle="tab">{{trans('lang.wait')}} </a>
                                </li>
                                <li>
                                    <a href="#tab_2_2" tabindex="-1" data-toggle="tab">{{trans('lang.agree')}} </a>
                                </li>
                                <li>
                                    <a href="#tab_2_3" tabindex="-1" data-toggle="tab">{{trans('lang.disagree')}} </a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">{{trans('lang.talk_photo')}} <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="#tab_3_1" tabindex="-1" data-toggle="tab">{{trans('lang.wait')}} </a>
                                </li>
                                <li>
                                    <a href="#tab_3_2" tabindex="-1" data-toggle="tab">{{trans('lang.agree')}} </a>
                                </li>
                                <li>
                                    <a href="#tab_3_3" tabindex="-1" data-toggle="tab">{{trans('lang.disagree')}} </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_1">
                            @include('photo_agree.all_wait_img')
                        </div>
                        <div class="tab-pane fade" id="tab_2_1">
                            @include('photo_agree.profile_img_wait')
                        </div>
                        <div class="tab-pane fade" id="tab_2_2">
                            @include('photo_agree.profile_img_agree')
                        </div>
                        <div class="tab-pane fade" id="tab_2_3">
                            @include('photo_agree.profile_img_disagree')
                        </div>
                        <div class="tab-pane fade" id="tab_3_1">
                            @include('photo_agree.talk_img_wait')
                        </div>
                        <div class="tab-pane fade" id="tab_3_2">
                            @include('photo_agree.talk_img_agree')
                        </div>
                        <div class="tab-pane fade" id="tab_3_3">
                            @include('photo_agree.talk_img_disagree')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('photo_agree.user_info')
    @include('photo_agree.talk_confirm')
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

        /*Agree Img*/
        function img_agree(t_file_no,obj,type,cur_status) {
            $.ajax({
                url: "img_agree",
                type: "get",
                data: {
                    t_file_no : t_file_no,
                    type : type
                },
                success: function (result) {
                    if(result=='{{config('constants.FAIL')}}')
                        toastr["error"]("{{trans('lang.fail_agree')}}", "{{trans('lang.notice')}}");
                    else{
                        toastr["success"]("{{trans('lang.success_agree')}}", "{{trans('lang.notice')}}");
                        if(cur_status!='{{config('constants.AGREE')}}'){
                            $(obj).closest(".portlet.light.image-potlet").parent('div').remove();

                            if(type=='talk')
                                $("#tab_3_2 .row").append(result);
                            else
                                $("#tab_2_2 .row").append(result);
                        }
                    }
                }
            });
        }

        /*Disagree Img*/
        function img_disagree(t_file_no,obj,type,cur_status) {
            $.ajax({
                url: "img_disagree",
                type: "get",
                data: {
                    t_file_no : t_file_no,
                    type : type
                },
                success: function (result) {
                    if(result=='{{config('constants.FAIL')}}')
                        toastr["error"]("{{trans('lang.fail_disagree')}}", "{{trans('lang.notice')}}");
                    else{
                        toastr["success"]("{{trans('lang.success_disagree')}}", "{{trans('lang.notice')}}");
                        if(cur_status!='{{config('constants.DISAGREE')}}'){
                            $(obj).closest(".portlet.light.image-potlet").parent('div').remove();

                            if(type=='talk')
                                $("#tab_3_3 .row").append(result);
                            else
                                $("#tab_2_3 .row").append(result);
                        }
                    }
                }
            });
        }

       /*Get user data*/
        function get_user_data(user_no) {
            $.ajax({
                type: "POST",
                data: {
                    no: user_no,
                    _token: "{{csrf_token()}}"
                },
                url: 'get_user_data',
                success: function (result) {
                    if (result == "{{config('constants.FAIL')}}") {
                        $toast = toastr["error"]("{{trans('lang.fail_detail_view')}}", "");

                    } else {
                        var data1 = JSON.parse(result);
                        var data = data1.info;
                        $("#nickname").val(data.nickname);
                        $("#email").val(data.email);
                        $("#profile_img").attr("src", data1.path);
                        $("#profile_img_fancy").attr("href", data1.path);
                        if (data.status == "{{config('constants.TALK_POSSIBLE')}}")
                            $("#status").html("{{trans('lang.enable_talk')}}");
                        else if (data.status == "{{config('constants.AWAY')}}")
                            $("#status").html("{{trans('lang.away')}}");
                        else if (data.status == "{{config('constants.TALKING')}}")
                            $("#status").html("{{trans('lang.in_talking')}}");
                        if (data.sex == "{{config('constants.MALE')}}")
                            $("#sex").val("{{trans('lang.man')}}");
                        else if (data.sex == "{{config('constants.FEMALE')}}")
                            $("#sex").val("{{trans('lang.woman')}}");
                        $("#age").val(data.age);
                        $("#subject").val(data.subject);
                        if (data.verified == "{{config('constants.VERIFIED')}}")
                            $("#verify").removeClass("hidden");
                        else
                            $("#verify").addClass("hidden");
                        $("#point").html(data.point);
                        $("#phone_number").val(data.phone_number);

                        $("#btn_get_data").trigger('click');
                    }
                }
            });
        }

        /*Talk Confirm*/
        function confirm_talk(user_no) {
            $.ajax({
                type: "POST",
                data: {
                    user_no: user_no,
                    _token: "{{csrf_token()}}"
                },
                url: 'talk_confirm',
                success: function (result) {
                    if (result == "{{config('constants.FAIL')}}") {
                        toastr["error"]("{{trans('lang.no_talk')}}");
                    } else {
                        var jsonData = JSON.parse(result);
                        var talk = jsonData.info;
                        var talk_img=jsonData.img_path;
                        var talk_voice=jsonData.voice_path;

                        if(talk_img!="" || talk_img!=null){
                             $("#talk_img").attr("src",talk_img);
                        }
                        else
                            $("#talk_img").parent('div').addClass("hidden");

                        if(talk_voice!="" || talk_voice!=null){
                            $("#voice_path").attr("src",talk_voice);
                        }
                        else
                            $("#voice_path").parent('div').addClass("hidden");

                        if(talk['voice_type']==0) $("#voice_type").val('{{trans('lang.nomal_voice')}}');
                        if(talk['voice_type']==1) $("#voice_type").val('{{trans('lang.pretty_voice')}}');
                        if(talk['voice_type']==2) $("#voice_type").val('{{trans('lang.weight_voice')}}');
                        if(talk['voice_type']==3) $("#voice_type").val('{{trans('lang.tt_voice')}}');
                        if(talk['voice_type']==4) $("#voice_type").val('{{trans('lang.love_voice')}}');

                        $("#talk_subject").val(talk['subject']);
                        $("#greeting").val(talk['greeting']);
                        $("#talk_nickname").val(talk['nickname']);
                        $("#talk_age").val(talk['age']);

                        $("#btn_talk_confirm").trigger('click');
                    }
                }
            });
        }
    </script>
@stop


