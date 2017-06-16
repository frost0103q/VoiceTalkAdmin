<div class="row">
    @foreach($talk_img as $talk_img_model)
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
                            <input type="radio" id="{{'rd_T_'.$talk_img_model->no.'_y'}}" name="{{'rd_T_'.$talk_img_model->no}}" class="md-radiobtn" onclick="img_agree({{$talk_img_model->no}},this)">
                            <label for="{{'rd_T_'.$talk_img_model->no.'_y'}}">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span>
                                승인 </label>
                        </div>
                        <div class="md-radio">
                            <input type="radio" id="{{'rd_T_'.$talk_img_model->no.'_n'}}" name="{{'rd_T_'.$talk_img_model->no}}" class="md-radiobtn"  onclick="img_disagree({{$talk_img_model->no}},this)">
                            <label for="{{'rd_T_'.$talk_img_model->no.'_n'}}">
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


