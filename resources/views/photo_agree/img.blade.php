<?php
/*
 ***Image disaplay page***
 inputdata:$img_model,$type('talk','profile'),$all_flag,array $talk_img_declare,array $talk_img_diff_time,array $profile_img_declare,array $profile_img_diff_time
 */
?>

<div class="col-md-3 col-xs-4 col-sm-6">
    <div class="portlet light image-potlet">
        <div class="profile-userpic">
            <img src="{{$img_model->path}}" class="img-responsive" alt="">
        </div>
        <div class="profile-usertitle">
            <div class="profile-usertitle-name">
                {{$img_model->nickname}}
            </div>
            <div class="profile-usertitle-job">
                @if($type=='talk')
                    {{'경고  '.$talk_img_declare[$img_model->user_no]}}
                @elseif($type=='profile')
                    {{'경고  '.$profile_img_declare[$img_model->user_no]}}
                @endif
            </div>
            <div class="profile-usertitle-job">
                @if($type=='talk')
                    {{$talk_img_diff_time[$img_model->user_no]}}
                @elseif($type=='profile')
                    {{$profile_img_diff_time[$img_model->user_no]}}
                @endif
            </div>
        </div>
        <div class="profile-userbuttons">
            <button type="button" class="btn btn-circle green-haze btn-sm" onclick="get_user_data({{$img_model->user_no}})">회원정보</button>
            <button type="button" class="btn btn-circle btn-danger btn-sm"  onclick="confirm_talk({{$img_model->user_no}})">Talk 확인</button>
        </div>
        <div class="profile-userbuttons">
            <div class="md-radio-inline">
                <div class="md-radio">
                    <?php
                    if($all_flag){
                        $rad_identy='rd_A_';
                    }
                    else{
                        if($type=='talk'){
                            $rad_identy='rd_T_';
                        }
                        elseif($type=='profile'){
                            $rad_identy='rd_P_';
                        }
                    }
                    ?>
                    <input type="radio" id="{{$rad_identy.$img_model->no.'_y'}}" name="{{$rad_identy.$img_model->no}}" class="md-radiobtn" onclick="img_agree('{{$img_model->no}}',this,'{{$type}}','{{$img_model->checked}}')" <?php if($img_model->checked==1) echo 'checked';?>>
                    <label for="{{$rad_identy.$img_model->no.'_y'}}">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span>
                        승인 </label>
                </div>
                <div class="md-radio">
                    <input type="radio" id="{{$rad_identy.$img_model->no.'_n'}}" name="{{$rad_identy.$img_model->no}}" class="md-radiobtn"  onclick="img_disagree('{{$img_model->no}}',this,'{{$type}}','{{$img_model->checked}}')"  <?php if($img_model->checked==0) echo 'checked';?>>
                    <label for="{{$rad_identy.$img_model->no.'_n'}}">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span>
                        거절 </label>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" class="all_img_agree" value="{{$img_model->no}}">