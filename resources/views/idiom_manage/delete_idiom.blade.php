<div class="row" style="padding-left: 17px;padding-right: 17px;">
    <div class="col-md-12" style="margin-top: 30px;padding-bottom: 30px">
        <div class="col-md-2" style="text-align: right">
            <label class="control-label" style="padding-top: 8px">{{trans('lang.reg_interdict_idiom')}}</label>
        </div>
        <div class="col-md-10">
            <div class="col-md-9">
                <input type="text" class="form-control" placeholder="{{trans('lang.multi_reg_enable_comma')}}">
            </div>
            <div class="col-md-3">
                <a class="btn blue" onclick="reg_idiom(this)">{{trans('lang.register')}}</a>
            </div>
        </div>
    </div>
    <div class="col-md-12" style="margin-bottom: 30px">
        <div class="col-md-2" style="text-align: right">
            <label class="control-label" style="padding-top: 8px">{{trans('lang.registered_idiom')}}</label>
        </div>
        <div class="col-md-10">
            <div class="col-md-9" style="padding-top: 10px" id="idiom_select_pad">
                @include('idiom_manage.select_idiom')
            </div>
            <div class="col-md-3">
                <a class="btn blue" onclick="del_selected_idiom()">{{trans('lang.select_del')}}</a>
            </div>
        </div>
    </div>

</div>

<script>
    function del_selected_idiom() {
        var unselected_idiom_str='';
        var del_flag=false;
        $(".selected_idiom").each(function () {
            if(!$(this).is(':checked')){
                unselected_idiom_str+=$(this).val()+',';
            }
            else
                del_flag=true;
        });

        unselected_idiom_str=unselected_idiom_str.substr(0,unselected_idiom_str.length-1);

        if(del_flag==false){
            toastr["error"]("{{trans('lang.select_idiom_del')}}", "{{trans('lang.notice')}}");
            return;
        }

        $.ajax({
            type: "POST",
            data: {
                idiom_str: unselected_idiom_str,
                _token: "{{csrf_token()}}"
            },
            url: 'del_selected_idiom',
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}'){
                    toastr["error"]("{{trans('lang.delete_fail')}}", "{{trans('lang.notice')}}");
                    return;
                }
                else {
                    $("#idiom_select_pad").html(result);
                    $("#txt_idiom_content").val($("#idiom_content_hidden").val());

                    toastr["success"]("{{trans('lang.delete_success')}}", "{{trans('lang.notice')}}");
                }
            }
        });
    }
</script>