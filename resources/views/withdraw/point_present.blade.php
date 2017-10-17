<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <form action="#" class="form-horizontal">
            <div class="form-group">
                <div class="col-md-1">
                    <label class="control-label">{{trans('lang.sex')}}</label>
                    <select class="form-control select2me" id="pp_sex" name="sex">
                        <option value="-1">{{trans('lang.all')}}</option>
                        <option value="{{config('constants.MALE')}}">{{trans('lang.man')}}</option>
                        <option value="{{config('constants.FEMALE')}}">{{trans('lang.woman')}}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.user_no')}}</label>
                    <input class="form-control" placeholder="" type="text" id="pp_user_no" name="user_no">
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.nickname')}}</label>
                    <input class="form-control" placeholder="" type="text" id="pp_user_nickname" name="user_nickname">
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.telnum')}}</label>
                    <input class="form-control" placeholder="" type="text" id="pp_phone_number" name="phone_number">
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.email')}}</label>
                    <input class="form-control" placeholder="" type="text" id="pp_email" name="email">
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.chat_content')}}</label>
                    <input class="form-control" placeholder="" type="text" id="pp_chat_content" name="chat_content">
                </div>
                <div class="col-md-1" style="padding-top: 7px">
                    <br>
                    <a class="btn blue" id="btn_pp_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_point_present" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.number')}}</th>
                <th>{{trans('lang.send_user')}}</th>
                <th>{{trans('lang.send_point')}}</th>
                <th>{{trans('lang.send_date')}}</th>
                <th>{{trans('lang.received_user')}}</th>
                <th>{{trans('lang.received_point')}}</th>
                <th>{{trans('lang.received_date')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    var start_index;
    var tbl_point_present;
    var init_tbl_point_present;
    $(document).ready(function () {
        init_tbl_point_present=function () {
            if(!$("#tbl_point_present").hasClass("dataTable")){
                tbl_point_present=$("#tbl_point_present").DataTable({
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
                    "lengthMenu": [
                        [5, 10, 20, -1],
                        [5, 10, 20, "{{trans('lang.all')}}"] // change per page values here
                    ],
                    // set the initial value
                    "pageLength": 10,
                    "pagingType": "bootstrap_full_number",
                    "processing": false,
                    "serverSide": true,
                    "ajax": {
                        "url": "ajax_present_table",
                        "type": "POST",
                        "data": function (d) {
                            start_index = d.start;
                            d._token = "{{csrf_token()}}";
                            d.sex=$("#pp_sex").val();
                            d.user_no=$("#pp_user_no").val();
                            d.nickname=$("#pp_user_nickname").val();
                            d.phone_number=$("#pp_phone_number").val();
                            d.email=$("#pp_email").val();
                            d.chat_content=$("#pp_chat_content").val();
                        }
                    },
                    "createdRow": function (row, data, dataIndex) {
                        $('td:eq(0)', row).html(dataIndex + start_index + 1);
                        $("#total_withdraw_amount").text(data[10]);
                    },
                    "columnDefs": [{
                        'orderable': false,
                        'targets': [0, 1, 2,4,5,6]
                    },
                        {
                            'orderable': true,
                            'targets': [3]
                        }],

                    "order": [
                        [3, "desc"]
                    ]
                });
            }
        }
    });

    $("#btn_pp_search").click(function () {
        tbl_point_present.draw(false);
    })
</script>