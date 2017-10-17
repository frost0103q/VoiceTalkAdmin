<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <div class="col-md-1">
            <label class="control-label">{{trans('lang.sex')}}</label>
            <select class="form-control select2me" id="er_user_sex">
                <option value="">{{trans('lang.all')}}</option>
                <option value="{{config('constants.MALE')}}">{{trans('lang.man')}}</option>
                <option value="{{config('constants.FEMALE')}}">{{trans('lang.woman')}}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.nickname')}}</label>
            <input class="form-control" placeholder="" type="text" id="er_user_nickname">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.telnum')}}</label>
            <input class="form-control" placeholder="" type="text" id="er_user_phone_number">
        </div>
        <div class="col-md-1" style="padding-top: 7px">
            <br>
            <a class="btn blue" id="btn_er_user_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_er_user" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.exit_request_user')}}</th>
                <th>{{trans('lang.photo')}}</th>
                <th>{{trans('lang.point')}}</th>
                <th>{{trans('lang.exit_request_time')}}</th>
                <th>{{trans('lang.processing')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="col-md-12" style="text-align: center">
        <a class="btn red" id="btn_user_exit">{{trans('lang.exit_process')}}</a>
    </div>
</div>

<script>
    var start_index;
    var tbl_er_user;
    var init_tbl_er_user;
    $(document).ready(function () {
        init_tbl_er_user=function () {
            if(!$("#tbl_er_user").hasClass("dataTable")){
                tbl_er_user=$("#tbl_er_user").DataTable({
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
                        "url": 	"ajax_er_user_table",
                        "type":	"POST",
                        "data":   function ( d ) {
                            start_index=d.start;
                            d._token= "{{csrf_token()}}";
                            d.sex=$("#er_user_sex").val();
                            d.nickname=$("#er_user_nickname").val();
                            d.phone_number=$("#er_user_phone_number").val();
                        }
                    },
                    "createdRow": function (row, data, dataIndex) {
                        if(data[2]!=null)
                            $('td:eq(1)', row).html('<img src="'+data[1]+'" height="50px">');
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
                        'targets': [0,1,2,4]
                    },
                        {  // set default column settings
                            'orderable': true,
                            'targets': [3]
                        }],

                    "order": [
                        [3, "desc"]
                    ] // set first column as a default sort by asc
                });
            }
        }
    });

    $("#btn_er_user_search").click(function () {
        tbl_er_user.draw(false);
    });


    $("#btn_user_exit").click(function () {

        var selected_user_str='';
        $("#tbl_er_user .user_no").each(function () {
            if($(this).is(':checked'))
                selected_user_str+=$(this).val()+',';
        });

        selected_user_str=selected_user_str.substr(0,selected_user_str.length-1);

        if(selected_user_str==''){
            toastr["error"]("{{trans('lang.select_user_to_exit')}}", "{{trans('lang.notice')}}");
            return;
        }


        $.ajax({
            type: "POST",
            data: {
                selected_user_str: selected_user_str,
                _token: "{{csrf_token()}}"
            },
            url: 'selected_user_exit',
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}'){
                    toastr["error"]("{{trans('lang.exit_fail')}}", "{{trans('lang.notice')}}");
                    return;
                }
                else {
                    tbl_er_user.draw(false);
                    tbl_e_user.draw(false);
                    toastr["success"]("{{trans('lang.success_warning')}}", "{{trans('lang.notice')}}");
                }
            }
        });
    })
</script>