<div class="row">
    <div class="col-md-12">
        <div class="note note-success">
            <p>{{trans('lang.profile_photo')}}</p>
        </div>
    </div>
    <div id="profile_img_list">
        @foreach($user_profile_img as $img_model)
            @if($img_model->checked==config('constants.WAIT'))
                <?php
                $type='profile';
                $all_flag=true;
                ?>
                @include('photo_agree.img')
            @endif
        @endforeach
    </div>


    <div class="col-md-12">
        <div class="note note-success">
            <p>
                {{trans('lang.talk_photo')}}
            </p>
        </div>
    </div>
    <div id="talk_img_list">
        @foreach($talk_img as $img_model)
            @if($img_model->checked==config('constants.WAIT'))
                <?php
                $type='talk';
                $all_flag=true;
                ?>
                @include('photo_agree.img')
            @endif
        @endforeach
    </div>
    <div class="col-md-12">
        <button type="button" class="btn blue" id="btn_all_img_agree">{{trans('lang.all_agree_photo')}}</button>
    </div>
</div>

<script>
    $("#btn_all_img_agree").click(function () {

        var img_no_array='';
        $("#tab_1 .file_no").each(function () {
            img_no_array+=$(this).val()+',';
        });
        img_no_array=img_no_array.substr(0,img_no_array.length-1);
        $.ajax({
            url: "all_img_agree",
            type: "get",
            data: {
                img_no_array : img_no_array
            },
            success: function (result) {

                $("#profile_img_list .image-potlet").each(function () {
                    var file_no=$(this).find('input:eq(0)').val();
                    $(this).find('input:eq(0)').attr("checked","checked");

                    $(this).find('input:eq(0)').removeAttr("onclick");
                    $(this).find('input:eq(0)').attr("onclick","img_agree('"+file_no+"',this,'profile','1')");

                    $(this).find('input:eq(1)').removeAttr("onclick");
                    $(this).find('input:eq(1)').attr("onclick","img_disagree('"+file_no+"',this,'profile','1')");
                });

                $("#talk_img_list .image-potlet").each(function () {
                    var file_no=$(this).find('input:eq(0)').val();
                    $(this).find('input:eq(0)').attr("checked","");

                    $(this).find('input:eq(0)').removeAttr("onclick");
                    $(this).find('input:eq(0)').attr("onclick","img_agree('"+file_no+"',this,'talk','1')");

                    $(this).find('input:eq(1)').removeAttr("onclick");
                    $(this).find('input:eq(1)').attr("onclick","img_disagree('"+file_no+"',this,'talk','1')");
                });

                var profile_img_list_html=$("#profile_img_list").html();
                var talk_img_list_html=$("#talk_img_list").html();

                if(result=='{{config('constants.FAIL')}}')
                    toastr["error"]("{{trans('lang.fail_agree')}}", "{{trans('lang.notice')}}");
                if(result=='{{config('constants.SUCCESS')}}'){
                    $("#profile_img_list").empty();
                    $("#talk_img_list").empty();

                    $("#tab_2_2 .row").append(profile_img_list_html);
                    $("#tab_3_2 .row").append(talk_img_list_html);
                    toastr["success"]("{{trans('lang.success_agree')}}", "{{trans('lang.notice')}}");
                }
            }
        });
    });
</script>