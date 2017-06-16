<div class="row">
    <div class="col-md-12">
        <div class="note note-success">
            <p>프로필사진</p>
        </div>
    </div>
    @foreach($user_profile_img as $img_model)
        @if($img_model->checked==config('constants.WAIT'))
            <?php
            $type='profile';
            $all_flag=true;
            ?>
            @include('photo_agree.img')
        @endif
    @endforeach


    <div class="col-md-12">
        <div class="note note-success">
            <p>
                Talk 사진
            </p>
        </div>
    </div>
    @foreach($talk_img as $img_model)
        @if($img_model->checked==config('constants.WAIT'))
            <?php
            $type='profile';
            $all_flag=true;
            ?>
            @include('photo_agree.img')
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