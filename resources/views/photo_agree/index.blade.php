@extends('layouts.main')

@section('content')

    <style>
        .profile-userpic img{
            width: 70%;
            height: 70%;
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
                            <div class="row">
                                @foreach($user_profile_img as $user_profile_model)
                                <div class="col-md-3 col-xs-4 col-sm-4">
                                    <div class="portlet light image-potlet">
                                        <div class="profile-userpic">
                                            <img src="{{$user_profile_model->path}}" class="img-responsive" alt="">
                                        </div>
                                        <div class="profile-usertitle">
                                            <div class="profile-usertitle-name">
                                                {{$user_profile_model->nickname}}
                                            </div>
                                            <div class="profile-usertitle-job">
                                                Developer
                                            </div>
                                            <div class="profile-usertitle-job">
                                                Developer
                                            </div>
                                        </div>
                                        <div class="profile-userbuttons">
                                            <button type="button" class="btn btn-circle green-haze btn-sm">회원정보</button>
                                            <button type="button" class="btn btn-circle btn-danger btn-sm">Talk 확인</button>
                                        </div>
                                        <div class="profile-userbuttons">
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" id="radio6" name="radio2" class="md-radiobtn">
                                                    <label for="radio6">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        승인 </label>
                                                </div>
                                                <div class="md-radio">
                                                    <input type="radio" id="radio7" name="radio2" class="md-radiobtn" checked>
                                                    <label for="radio7">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        거절 </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_2">
                            <p>
                                Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.
                            </p>
                        </div>
                        <div class="tab-pane fade" id="tab_3">
                            <p>
                                Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
<script>
    $(document).ready(function () {
        setTimeout(resizeView(),200);
    })
    function resizeView() {
        $(".img-responsive").each(function () {
            var height=$(this).width();
            $(this).css({'height': height + 'px'});
        });
    }
</script>
@stop
