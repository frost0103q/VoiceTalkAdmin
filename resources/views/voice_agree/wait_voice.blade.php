<div class="row">
    <div class="col-md-12">
        <div class="note note-success">
            <p>{{trans('lang.wait_voice')}}</p>
        </div>
    </div>
    <div id="wait_voice_list">
        @foreach($talk_voice as $voice_model)
            @if($voice_model->checked==config('constants.WAIT'))
                @include('voice_agree.voice')
            @endif
        @endforeach
    </div>
    <div class="col-md-12">
        <button type="button" class="btn blue" id="btn_all_voice_agree">{{trans('lang.agree_all_voice')}}</button>
    </div>
</div>



<script>
    $("#btn_all_voice_agree").click(function () {

        var voice_no_array='';
        $("#tab_1 .file_no").each(function () {
            voice_no_array+=$(this).val()+',';
        });
        voice_no_array=voice_no_array.substr(0,voice_no_array.length-1);
        $.ajax({
            url: "all_voice_agree",
            type: "get",
            data: {
                voice_no_array : voice_no_array
            },
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}')
                    toastr["error"]("{{trans('lang.fail_agree')}}", "{{trans('lang.notice')}}");

                $("#tab_1 .voice-potlet").each(function () {
                    var file_no=$(this).find('.file_no').val();
                    var talk_no=$(this).find('.talk_no').val();
                    $(this).find('input:eq(0)').attr("checked","");

                    $(this).find('input:eq(0)').removeAttr("onclick");
                    $(this).find('input:eq(0)').attr("onclick","voice_agree('"+file_no+"','"+talk_no+"',this,'1')");

                    $(this).find('input:eq(1)').removeAttr("onclick");
                    $(this).find('input:eq(1)').attr("onclick","voice_disagree('"+file_no+"','"+talk_no+"',this,'1')");
                });

                if(result=='{{config('constants.SUCCESS')}}'){
                    toastr["success"]("{{trans('lang.success_agree')}}", "{{trans('lang.notice')}}");

                    var html=$("#wait_voice_list").html();
                    $("#tab_2 .row").append(html);
                    $("#wait_voice_list").empty();
                }

            }
        });
    });
</script>