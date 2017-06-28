<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <div class="col-md-1">
            <label class="control-label">{{trans('lang.sex')}}</label>
            <select class="form-control select2me" id="user_sex">
                <option value="">{{trans('lang.all')}}</option>
                <option value="{{config('constants.MALE')}}">{{trans('lang.man')}}</option>
                <option value="{{config('constants.FEMALE')}}">{{trans('lang.woman')}}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.user_no')}}</label>
            <input class="form-control" placeholder="" type="text" id="user_no">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.nickname')}}</label>
            <input class="form-control" placeholder="" type="text" id="user_nickname">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.telnum')}}</label>
            <input class="form-control" placeholder="" type="text" id="user_phone_number">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.email')}}</label>
            <input class="form-control" placeholder="" type="text" id="user_email">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.chat_content')}}</label>
            <input class="form-control" placeholder="" type="text" id="user_chat_content">
        </div>
        <div class="col-md-1" style="padding-top: 7px">
            <br>
            <a class="btn blue" id="btn_user_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_user" style="width: 100%">
            <thead>
            <tr>
                <th></th>
                <th>{{trans('lang.user_no')}}</th>
                <th>{{trans('lang.photo')}}</th>
                <th>Nickname</th>
                <th>{{trans('lang.subject')}}</th>
                <th>{{trans('lang.edit_time')}}</th>
                <th>{{trans('lang.warn')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="col-md-12"  style="padding-top: 30px">
        <div class=" col-md-3">
            <a class="btn blue" id="btn_warning_user">{{trans('lang.warning')}}</a>
            <a class="btn blue" id="btn_del_user_photo">{{trans('lang.del_only_photo')}}</a>
        </div>
        <label class="control-label col-md-1" style="text-align: right;padding-top: 7px">{{trans('lang.warning_sentence')}}</label>
        <div class="col-md-2">
            <select class="form-control select2me" id="warning_reason_user">
                <option value=""></option>
                <option value="{{config('constants.SALE_SCREEN')}}">{{trans('lang.sale_screen')}}</option>
                <option value="{{config('constants.SALE_SEX')}}">{{trans('lang.sale_sex')}}</option>
                <option value="{{config('constants.HONGBO_TARGET')}}">{{trans('lang.hongbo_target')}}</option>
                <option value="{{config('constants.ABUSIBE')}}">{{trans('lang.abusive')}}</option>
                <option value="{{config('constants.OUTER_PICTURE')}}">{{trans('lang.outer_picture')}}</option>
                <option value="{{config('constants.OTHER_USER_CALUMNY')}}">{{trans('lang.other_user_calumny')}}</option>
                <option value="{{config('constants.POINT_LEAD')}}">{{trans('lang.point_lead')}}</option>
                <option value="{{config('constants.PHOTO_STEAL')}}">{{trans('lang.photo_steal')}}</option>
                <option value="{{config('constants.UNLAWFULNESS_TRADE')}}">{{trans('lang.unlawfulness_trade')}}</option>
                <option value="{{config('constants.LIE_THING')}}">{{trans('lang.lie_thing')}}</option>
                <option value="{{config('constants.LIE_DECLARE')}}">{{trans('lang.lie_declare')}}</option>
                <option value="{{config('constants.USER_REPRESENT')}}">{{trans('lang.user_represent')}}</option>
                <option value="{{config('constants.AD_OTHER_APP')}}">{{trans('lang.ad_other_app')}}</option>
                <option value="{{config('constants.OTHER')}}">{{trans('lang.other')}}</option>
                <option value="{{config('constants.WRONG_WORD')}}">{{trans('lang.wrong_word')}}</option>
                <option value="{{config('constants.SEX_BANTER')}}">{{trans('lang.sex_banter')}}</option>
                <option value="{{config('constants.THREAT')}}">{{trans('lang.threat')}}</option>
            </select>
        </div>
        <label class="control-label col-md-1" for="admin_memo" style="text-align: right;padding-top: 7px">{{trans('lang.admin_memo')}}</label>
        <div class="col-md-3">
            <input class="form-control" placeholder="" type="text" id="admin_memo_user">
        </div>
    </div>
</div>

<script>
    var tbl_user;
    $(document).ready(function () {
        var start_index;
        tbl_user=$("#tbl_user").DataTable({
            "dom": '<"top"i><"toolbar pull-left">rtlp',
            "language": {
                "emptyTable": "{{trans('lang.no_display_data')}}",
                "lengthMenu": "{{trans('lang.display_cnt')}} _MENU_",
                "sInfo": "{{trans('lang.all_cnt')}} _TOTAL_ {{trans('lang.unit')}}",
                "infoFiltered": "",
                "sInfoEmpty": "",
                "paginate": {
                    "previous": "Prev",
                    "next": "Next",
                    "last": "Last",
                    "first": "First"
                }
            },
            "autowidth": true,
            "processing":false,
            "serverSide": true,
            "ajax": {
                "url": 	"ajax_user_table",
                "type":	"POST",
                "data":   function ( d ) {
                    start_index=d.start;
                    d._token= "{{csrf_token()}}";
                    d.sex=$("#user_sex").val();
                    d.user_no=$("#user_no").val();
                    d.nickname=$("#user_nickname").val();
                    d.phone_number=$("#user_phone_number").val();
                    d.email=$("#user_email").val();
                    d.chat_content=$("#user_chat_content").val();
                }
            },
            "createdRow": function (row, data, dataIndex) {
                if(data[2]!=null)
                    $('td:eq(2)', row).html('<img src="'+data[2]+'" height="50px">');
            },
            "lengthMenu": [
                [5, 10, 20, -1],
                [5, 10, 20, "{{trans('lang.all')}}"] // change per page values here
            ],
            // set the initial value
            "pageLength": 5,
            "pagingType": "bootstrap_full_number",
            "columnDefs": [{  // set default column settings
                'orderable': false,
                'targets': [0,1,2,3,4,6]
            },
                {  // set default column settings
                    'orderable': true,
                    'targets': [5]
                }],

            "order": [
                [5, "desc"]
            ] // set first column as a default sort by asc
        });
    });

    $("#btn_user_search").click(function () {
        tbl_user.draw(false);
    });
    
    $("#btn_del_user_photo").click(function () {

        var selected_user_str='';
        $("#tbl_user .user_no").each(function () {
            if($(this).is(':checked'))
                selected_user_str+=$(this).val()+',';
        });

        selected_user_str=selected_user_str.substr(0,selected_user_str.length-1);

        if(selected_user_str==''){
            toastr["error"]("{{trans('lang.select_user_to_delete')}}", "{{trans('lang.notice')}}");
            return;
        }

        $.ajax({
            type: "POST",
            data: {
                selected_user_str: selected_user_str,
                _token: "{{csrf_token()}}"
            },
            url: 'del_selected_profile',
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}'){
                    toastr["error"]("{{trans('lang.delete_fail')}}", "{{trans('lang.notice')}}");
                    return;
                }
                else {
                    tbl_user.draw(false);
                    toastr["success"]("{{trans('lang.delete_success')}}", "{{trans('lang.notice')}}");
                }
            }
        });
    });

    $("#btn_warning_user").click(function () {

        var selected_user_str='';
        $("#tbl_user .user_no").each(function () {
            if($(this).is(':checked'))
                selected_user_str+=$(this).val()+',';
        });

        selected_user_str=selected_user_str.substr(0,selected_user_str.length-1);

        if(selected_user_str==''){
            toastr["error"]("{{trans('lang.select_user_to_declare')}}", "{{trans('lang.notice')}}");
            return;
        }

        var warning_reason=$("#warning_reason_user").val();
        if(warning_reason==''){
            toastr["error"]("{{trans('lang.input_warning_sentence')}}", "{{trans('lang.notice')}}");
            return;
        }

        var admin_memo=$("#admin_memo_user").val();
        if(admin_memo==''){
            toastr["error"]("{{trans('lang.input_admin_memo')}}", "{{trans('lang.notice')}}");
            $("#admin_memo_user").focus();
            return;
        }

        $.ajax({
            type: "POST",
            data: {
                selected_user_str: selected_user_str,
                warning_reason:warning_reason,
                admin_memo:admin_memo,
                _token: "{{csrf_token()}}"
            },
            url: 'selected_user_warning',
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}'){
                    toastr["error"]("{{trans('lang.fail_warning')}}", "{{trans('lang.notice')}}");
                    return;
                }
                else {
                    tbl_user.draw(false);
                    toastr["success"]("{{trans('lang.success_warning')}}", "{{trans('lang.notice')}}");
                }
            }
        });
    })

</script>