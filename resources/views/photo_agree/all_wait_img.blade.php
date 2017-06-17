<div class="row">
    <div class="col-md-12">
        <div class="note note-success">
            <p>프로필사진</p>
        </div>
    </div>
    @foreach($user_profile_img as $user_profile_model)
        @if($user_profile_model->checked==config('constants.WAIT'))
            <div class="col-md-3 col-xs-4 col-sm-6">
                <div class="portlet light image-potlet">
                    <div class="profile-userpic">
                        <img src="{{$user_profile_model->path}}" class="img-responsive" alt="">
                    </div>
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name">
                            {{$user_profile_model->nickname}}
                        </div>
                        <div class="profile-usertitle-job">
                            {{'경고  '.$profile_img_declare[$user_profile_model->user_no]}}
                        </div>
                        <div class="profile-usertitle-job">
                            {{$profile_img_diff_time[$user_profile_model->user_no]}}
                        </div>
                    </div>
                    <div class="profile-userbuttons">
                        <button type="button" class="btn btn-circle green-haze btn-sm" onclick="get_user_data({{$user_profile_model->user_no}})">회원정보</button>
                        <button type="button" class="btn btn-circle btn-danger btn-sm"  onclick="confirm_talk({{$user_profile_model->user_no}})">Talk 확인</button>
                    </div>
                    <div class="profile-userbuttons">
                        <div class="md-radio-inline">
                            <div class="md-radio">
                                <input type="radio" id="{{'rd_A_'.$user_profile_model->no.'_y'}}" name="{{'rd_A_'.$user_profile_model->no}}" class="md-radiobtn" onclick="img_agree({{$user_profile_model->no}},this)">
                                <label for="{{'rd_A_'.$user_profile_model->no.'_y'}}">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                    승인 </label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="{{'rd_A_'.$user_profile_model->no.'_n'}}" name="{{'rd_A_'.$user_profile_model->no}}" class="md-radiobtn"  onclick="img_disagree({{$user_profile_model->no}},this)">
                                <label for="{{'rd_A_'.$user_profile_model->no.'_n'}}">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                    거절 </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" class="all_img_agree" value="{{$user_profile_model->no}}">
        @endif
    @endforeach


    <div class="col-md-12">
        <div class="note note-success">
            <p>
                Talk 사진
            </p>
        </div>
    </div>
    @foreach($talk_img as $talk_img_model)
        @if($talk_img_model->checked==config('constants.WAIT'))
            <div class="col-md-3 col-xs-4 col-sm-6">
                <div class="portlet light image-potlet">
                    <div class="profile-userpic">
                        <img src="{{$talk_img_model->path}}" class="img-responsive" alt="">
                    </div>
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name">
                            {{$talk_img_model->nickname}}
                        </div>
                        <div class="profile-usertitle-job">
                            {{'경고  '.$talk_img_declare[$talk_img_model->user_no]}}
                        </div>
                        <div class="profile-usertitle-job">
                            {{$talk_img_diff_time[$talk_img_model->user_no]}}
                        </div>
                    </div>
                    <div class="profile-userbuttons">
                        <button type="button" class="btn btn-circle green-haze btn-sm" onclick="get_user_data({{$talk_img_model->user_no}})">회원정보</button>
                        <button type="button" class="btn btn-circle btn-danger btn-sm"  onclick="confirm_talk({{$talk_img_model->user_no}})">Talk 확인</button>
                    </div>
                    <div class="profile-userbuttons">
                        <div class="md-radio-inline">
                            <div class="md-radio">
                                <input type="radio" id="{{'rd_A_'.$talk_img_model->no.'_y'}}" name="{{'rd_A_'.$talk_img_model->no}}" class="md-radiobtn" onclick="img_agree({{$talk_img_model->no}},this)">
                                <label for="{{'rd_A_'.$talk_img_model->no.'_y'}}">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                    승인 </label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="{{'rd_A_'.$talk_img_model->no.'_n'}}" name="{{'rd_A_'.$talk_img_model->no}}" class="md-radiobtn"  onclick="img_disagree({{$talk_img_model->no}},this)">
                                <label for="{{'rd_A_'.$talk_img_model->no.'_n'}}">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                    거절 </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" class="all_img_agree" value="{{$talk_img_model->no}}">
        @endif
    @endforeach
    <div class="col-md-12">
        <button type="button" class="btn blue" id="btn_all_img_agree">전체사진승인</button>
    </div>
</div>

<script>
    $("#btn_all_img_agree").click(function () {

        var img_no_array='';
        $(".all_img_agree").each(function () {
            img_no_array+=$(this).val()+',';
        });
        img_no_array=img_no_array.substr(0,img_no_array.length-1);

        $.ajax({
            url: "/all_img_agree",
            type: "get",
            data: {
                img_no_array : img_no_array
            },
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}')
                    toastr["error"]("승인이 실패하였습니다.", "알림");
                if(result=='{{config('constants.SUCCESS')}}')
                    toastr["success"]("정확히 승인되었습니다.", "알림");
            }
        });
    });
</script>