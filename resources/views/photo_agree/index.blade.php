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
                            <a href="#tab_1" data-toggle="tab">
                                미 승인건 모아보기 </a>
                        </li>
                        <li>
                            <a href="#tab_2" data-toggle="tab">
                                프로필사진 </a>
                        </li>
                        <li>
                            <a href="#tab_3" data-toggle="tab">
                                Talk 사진 </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_1">
                            @include('photo_agree.all_img')
                        </div>
                        <div class="tab-pane fade" id="tab_2">
                            @include('photo_agree.profile_img')
                        </div>
                        <div class="tab-pane fade" id="tab_3">
                            @include('photo_agree.talk_img')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

//        $(document).ready(function () {
//           resizeView();
//        });

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
            alert(t_file_no);
        }

        /*Disagree Img*/
        function img_disagree(t_file_no,obj) {
            alert(t_file_no);
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


