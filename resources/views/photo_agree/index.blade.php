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
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green" style="border: none">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-photo"></i>사진승인
                    </div>
                </div>
                <div class="portlet-body">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab">미 승인건 모아보기 </a>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">프로필사진 <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="#tab_2_1" tabindex="-1" data-toggle="tab">대기 </a>
                                </li>
                                <li>
                                    <a href="#tab_2_2" tabindex="-1" data-toggle="tab">승인 </a>
                                </li>
                                <li>
                                    <a href="#tab_2_3" tabindex="-1" data-toggle="tab">거부 </a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">Talk 사진 <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="#tab_3_1" tabindex="-1" data-toggle="tab">대기 </a>
                                </li>
                                <li>
                                    <a href="#tab_3_2" tabindex="-1" data-toggle="tab">승인 </a>
                                </li>
                                <li>
                                    <a href="#tab_3_3" tabindex="-1" data-toggle="tab">거부 </a>
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
        function img_agree(t_file_no,obj) {
            $.ajax({
                url: "/img_agree",
                type: "get",
                data: {
                    t_file_no : t_file_no
                },
                success: function (result) {
                    if(result=='{{config('constants.FAIL')}}')
                        toastr["error"]("승인이 실패하였습니다.", "알림");
                    if(result=='{{config('constants.SUCCESS')}}')
                        toastr["success"]("정확히 승인되었습니다.", "알림");
                }
            });
        }

        /*Disagree Img*/
        function img_disagree(t_file_no,obj) {
            $.ajax({
                url: "/img_disagree",
                type: "get",
                data: {
                    t_file_no : t_file_no
                },
                success: function (result) {
                    if(result=='{{config('constants.FAIL')}}')
                        toastr["error"]("거절이 실패하였습니다.", "알림");
                    if(result=='{{config('constants.SUCCESS')}}')
                        toastr["success"]("정확히 거절되었습니다.", "알림");
                }
            });
        }

        /*Get user data*/
        function get_user_data(user_no) {
            alert(user_no)
        }

        /*Talk Confirm*/
        function confirm_talk(user_no) {
            alert(user_no)
        }
    </script>
@stop


