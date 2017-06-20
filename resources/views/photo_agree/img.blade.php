<?php
/*
 ***Image disaplay page***
 inputdata:$img_model,$type('talk','profile'),$all_flag,array $talk_img_declare,array $talk_img_diff_time,array $profile_img_declare,array $profile_img_diff_time
 */
?>

<div class="col-md-3">
    <div class="portlet light image-potlet">
        <div class="profile-userpic">
            <img src="{{$img_model->path}}" class="img-responsive" alt="">
        </div>
        <div class="profile-usertitle">
            <div class="profile-usertitle-name">
                {{$img_model->nickname}}
            </div>
            <div class="profile-usertitle-job">
                {{trans('lang.declare').' '.$img_model->declare_cnt}}
            </div>
            <div class="profile-usertitle-job">
                @if($type=='talk')
                    <?php
                    if(isset($talk_img_diff_time[$img_model->no]))
                        echo $talk_img_diff_time[$img_model->no];
                    ?>
                @elseif($type=='profile')
                    <?php
                    if(isset($profile_img_diff_time[$img_model->no]))
                        echo $profile_img_diff_time[$img_model->no];
                    ?>
                @endif
            </div>
        </div>
        <div class="profile-userbuttons">
            <button type="button" class="btn btn-circle green-haze btn-sm" onclick="get_user_data({{$img_model->user_no}})">{{trans('lang.user_info')}}</button>
            <button type="button" class="btn btn-circle btn-danger btn-sm"  onclick="confirm_talk({{$img_model->user_no}})">{{trans('lang.talk_confirm')}}</button>
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
                            $rad_identy='rd_T_'.$img_model->talk_no.'_';
                        }
                        elseif($type=='profile'){
                            $rad_identy='rd_P_';
                        }
                    }
                    ?>
                    <input type="radio" id="{{$rad_identy.$img_model->no.'_'.$img_model->checked.'_y'}}" name="{{$rad_identy.$img_model->no.'_'.$img_model->checked}}" class="md-radiobtn" value="{{$img_model->no}}" onclick="img_agree('{{$img_model->no}}',this,'{{$type}}','{{$img_model->checked}}')" <?php if($img_model->checked==1) echo 'checked';?>>
                    <label for="{{$rad_identy.$img_model->no.'_'.$img_model->checked.'_y'}}">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span>
                        {{trans('lang.agree')}} </label>
                </div>
                <div class="md-radio">
                    <input type="radio" id="{{$rad_identy.$img_model->no.'_'.$img_model->checked.'_n'}}" name="{{$rad_identy.$img_model->no.'_'.$img_model->checked}}" class="md-radiobtn"  value="{{$img_model->no}}"  onclick="img_disagree('{{$img_model->no}}',this,'{{$type}}','{{$img_model->checked}}')"  <?php if($img_model->checked==0) echo 'checked';?>>
                    <label for="{{$rad_identy.$img_model->no.'_'.$img_model->checked.'_n'}}">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span>
                        {{trans('lang.disagree')}} </label>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" class="file_no" value="{{$img_model->no}}">