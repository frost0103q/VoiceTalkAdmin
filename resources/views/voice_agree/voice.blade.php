<?php
/*
 ***Voice disaplay page***
 inputdata:$voice_model,array $talk_voice_diff_time
 */
?>

<div class="col-md-3">
    <div class="portlet light image-potlet">
        <div class="profile-userpic">
            <img src="{{$voice_model->profile_path}}" class="img-responsive" alt="">
        </div>
        <div class="profile-usertitle">
            <div class="profile-usertitle-name">
                {{$voice_model->nickname}}
            </div>
            <div class="profile-usertitle-job">
                {{'경고  '.$voice_model->declare_cnt}}
            </div>
            <div class="profile-usertitle-job">
                @if(isset($talk_voice_diff_time[$voice_model->no]))
                    {{$talk_voice_diff_time[$voice_model->no]}}
                @endif
            </div>
        </div>
        <div class="profile-userbuttons">
            <button type="button" class="btn btn-circle green-haze btn-sm" onclick="get_confirm_voice('{{$voice_model->path}}')">{{trans('lang.hear_voice')}}</button>
        </div>
        <div class="profile-userbuttons">
            <div class="md-radio-inline">
                <div class="md-radio">
                    <?php
                    $rd_lavel='voice'.$voice_model->talk_no.$voice_model->checked.$voice_model->type;
                    ?>
                    <input type="radio" id="{{$rd_lavel.'_y'}}" name="{{$rd_lavel}}" class="md-radiobtn" value="{{$voice_model->no}}" onclick="voice_agree('{{$voice_model->no}}','{{$voice_model->talk_no}}',this,'{{$voice_model->checked}}')" <?php if($voice_model->checked==1) echo 'checked=""';?>>
                    <label for="{{$rd_lavel.'_y'}}">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span>
                        {{trans('lang.agree')}} </label>
                </div>
                <div class="md-radio">
                    <input type="radio" id="{{$rd_lavel.'_n'}}" name="{{$rd_lavel}}" class="md-radiobtn"  value="{{$voice_model->no}}"  onclick="voice_disagree('{{$voice_model->no}}','{{$voice_model->talk_no}}',this,'{{$voice_model->checked}}')"  <?php if($voice_model->checked==0) echo 'checked=""';?>>
                    <label for="{{$rd_lavel.'_n'}}">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span>
                        {{trans('lang.disagree')}}  </label>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" class="file_no" value="{{$voice_model->no}}">