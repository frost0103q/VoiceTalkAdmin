<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <div class="col-md-1">
            <label class="control-label">{{trans('lang.sex')}}</label>
            <select class="form-control select2me" id="declare_user_sex">
                <option value="">{{trans('lang.all')}}</option>
                <option value="{{config('constants.MALE')}}">{{trans('lang.man')}}</option>
                <option value="{{config('constants.FEMALE')}}">{{trans('lang.woman')}}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.user_no')}}</label>
            <input class="form-control" placeholder="" type="text" id="declare_user_no">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.nickname')}}</label>
            <input class="form-control" placeholder="" type="text" id="declare_user_nickname">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.telnum')}}</label>
            <input class="form-control" placeholder="" type="text" id="declare_user_phone_number">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.email')}}</label>
            <input class="form-control" placeholder="" type="text" id="declare_user_email">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.chat_content')}}</label>
            <input class="form-control" placeholder="" type="text" id="declare_user_chat_content">
        </div>
        <div class="col-md-1" style="padding-top: 7px">
            <br>
            <a class="btn blue" id="btn_declare_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_declare" style="width: 100%">
            <thead>
            <tr>
                <th></th>
                <th>{{trans('lang.from_user_declare')}}</th>
                <th>{{trans('lang.from_user_photo')}}</th>
                <th>{{trans('lang.to_user_declare')}}</th>
                <th>{{trans('lang.to_user_declare')}}</th>
                <th>{{trans('lang.declare_content')}}</th>
                <th>{{trans('lang.declare_time')}}</th>
                <th>{{trans('lang.process_time')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="col-md-12"  style="padding-top: 30px">
        <div class=" col-md-4">
            <a class="btn blue" id="btn_del_talk">{{trans('lang.del_talk')}}</a>
            <a class="btn blue" id="btn_stop_user_declare">{{trans('lang.force_stop')}}</a>
            <a class="btn blue" id="btn_del_user_photo_declare">{{trans('lang.del_only_photo')}}</a>
        </div>
        <div class="col-md-2">
            <select class="form-control select2me" id="user_sex">
                <option value="">{{trans('lang.all')}}</option>
                <option value="{{config('constants.MALE')}}">{{trans('lang.man')}}</option>
                <option value="{{config('constants.FEMALE')}}">{{trans('lang.woman')}}</option>
            </select>
        </div>
        <label class="control-label col-md-1" style="text-align: right">{{trans('lang.admin_memo')}}</label>
        <div class="col-md-3">
            <input class="form-control" placeholder="" type="text" id="admin_memo_declare">
        </div>
    </div>
</div>

<script>
    var tbl_declare;
    $(document).ready(function () {
        var start_index;
        tbl_declare=$("#tbl_declare").DataTable({
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
                "url": 	"ajax_declare_table",
                "type":	"POST",
                "data":   function ( d ) {
                    start_index=d.start;
                    d._token= "{{csrf_token()}}";
                    d.sex=$("#declare_user_sex").val();
                    d.user_no=$("#declare_user_no").val();
                    d.nickname=$("#declare_user_nickname").val();
                    d.phone_number=$("#declare_user_phone_number").val();
                    d.email=$("#declare_user_email").val();
                    d.chat_content=$("#declare_user_chat_content").val();
                }
            },
            "createdRow": function (row, data, dataIndex) {
                if(data[2]!=null)
                    $('td:eq(2)', row).html('<img src="'+data[2]+'" height="50px">');
                if(data[4]!=null)
                    $('td:eq(4)', row).html('<img src="'+data[4]+'" height="50px">');
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
                'targets': [0,1,2,3,4,5,7]
            },
                {  // set default column settings
                    'orderable': true,
                    'targets': [6]
                }],

            "order": [
                [6, "desc"]
            ] // set first column as a default sort by asc
        });
    });

    $("#btn_declare_search").click(function () {
        tbl_declare.draw(false);
    });


    $("#btn_del_user_photo_declare").click(function () {
        var selected_user_str='';
        $("#tbl_declare .declare_no").each(function () {
            if($(this).is(':checked'))
                selected_user_str+=$(this).attr('to_user_no')+',';
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
                    tbl_declare.draw(false);
                    toastr["success"]("{{trans('lang.delete_success')}}", "{{trans('lang.notice')}}");
                }
            }
        });
    });

    $("#btn_del_talk").click(function () {
        var selected_user_str='';
        $("#tbl_declare .declare_no").each(function () {
            if($(this).is(':checked'))
                selected_user_str+=$(this).attr('to_user_no')+',';
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
            url: 'del_selected_user_talk',
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}'){
                    toastr["error"]("{{trans('lang.delete_fail')}}", "{{trans('lang.notice')}}");
                    return;
                }
                else {
                    tbl_talk.draw(false);
                    toastr["success"]("{{trans('lang.delete_success')}}", "{{trans('lang.notice')}}");
                }
            }
        });
    });

    $("#btn_stop_user_declare").click(function () {
        var selected_user_str='';
        $("#tbl_declare .declare_no").each(function () {
            if($(this).is(':checked'))
                selected_user_str+=$(this).attr('to_user_no')+',';
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
            url: 'user_force_stop',
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}'){
                    toastr["error"]("{{trans('lang.force_stop_failed')}}", "{{trans('lang.notice')}}");
                    return;
                }
                else {
                    toastr["success"]("{{trans('lang.success_warning')}}", "{{trans('lang.notice')}}");
                }
            }
        });
    });
</script>