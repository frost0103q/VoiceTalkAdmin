<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <form action="#" class="form-horizontal">
            <div class="form-group">
                <div class="col-md-4">
                    <label class="control-label">{{trans('lang.period_search')}}</label>
                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control" name="from_date" id="w_from_date">
                        <span class="input-group-addon"> to </span>
                        <input type="text" class="form-control" name="to_date" id="w_to_date">
                    </div>
                </div>
                <div class="col-md-1">
                    <label class="control-label">{{trans('lang.withdraw_status')}}</label>
                    <select class="form-control select2me" id="w_status" name="status">
                        <option value="-1">{{trans('lang.all')}}</option>
                        <option value="{{config('constants.WITHDRAW_WAIT')}}">{{trans('lang.wait')}}</option>
                        <option value="{{config('constants.WITHDRAW_FINISH')}}">{{trans('lang.finish')}}</option>
                        <option value="{{config('constants.WITHDRAW_ERROR')}}">{{trans('lang.error')}}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.user_no')}}</label>
                    <input class="form-control" placeholder="" type="text" id="w_user_no" name="user_no">
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.cash_owner')}}</label>
                    <input class="form-control" placeholder="" type="text" id="account_name" name="account_name">
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.cash_account')}}</label>
                    <input class="form-control" placeholder="" type="text" id="bank_name" name="bank_name">
                </div>
                <div class="col-md-1" style="padding-top: 7px">
                    <br>
                    <a class="btn blue" id="btn_w_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12">
        <label class="control-label" style="padding: 20px"><strong>{{trans('lang.total_req_amount_by_condition')}} : <span id="total_withdraw_amount"></span>{{trans('lang.won')}}</strong></label>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_withdraw" style="width: 100%">
            <thead>
            <tr>
                <th></th>
                <th>{{trans('lang.number')}}</th>
                <th>{{trans('lang.user_no')}}</th>
                <th>Nickname</th>
                <th>{{trans('lang.req_amount')}}</th>
                <th>{{trans('lang.wait_amount')}}</th>
                <th>{{trans('lang.cash_owner')}}</th>
                <th>{{trans('lang.birthday')}}</th>
                <th>{{trans('lang.withdraw_status')}}</th>
                <th>{{trans('lang.verify_status')}}</th>
                <th>{{trans('lang.req_date')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="col-md-12" style="padding-top: 30px">
        <div class="col-md-2">
            <select class="form-control select2me" id="sel_withdraw_status">
                <option value="{{config('constants.WITHDRAW_WAIT')}}">{{trans('lang.wait')}}</option>
                <option value="{{config('constants.WITHDRAW_FINISH')}}">{{trans('lang.finish')}}</option>
                <option value="{{config('constants.WITHDRAW_ERROR')}}">{{trans('lang.error')}}</option>
            </select>
        </div>
        <div class=" col-md-5">
            <a class="btn blue" id="btn_withdraw_change">{{trans('lang.status_change')}}</a>
        </div>
    </div>
</div>

<script>
    var start_index;
    var tbl_withdraw;
    var init_tbl_withdraw;
    $(document).ready(function () {
        init_tbl_withdraw=function () {
            if(!$("#tbl_withdraw").hasClass("dataTable")){
                tbl_withdraw=$("#tbl_withdraw").DataTable({
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
                        "url": "ajax_withdraw_table",
                        "type": "POST",
                        "data": function (d) {
                            start_index = d.start;
                            d._token = "{{csrf_token()}}";
                            d.start_dt=$("#w_from_date").val();
                            d.end_dt=$("#w_to_date").val();
                            d.status=$("#w_status").val();
                            d.user_no=$("#w_user_no").val();
                            d.account_name=$("#account_name").val();
                            d.bank_name=$("#bank_name").val();
                        }
                    },
                    "createdRow": function (row, data, dataIndex) {
                        $('td:eq(1)', row).html(dataIndex + start_index + 1);
                        $("#total_withdraw_amount").text(data[11]);
                    },
                    "columnDefs": [{
                        'orderable': false,
                        'targets': [0, 1, 2,3,4,5,6,7,8,9]
                    },
                        {
                            'orderable': true,
                            'targets': [10]
                        }],

                    "order": [
                        [10, "desc"]
                    ]
                });
            }
        }
    });

    $("#btn_w_search").click(function () {
       tbl_withdraw.draw(false);
    });


    $("#btn_withdraw_change").click(function () {

        var selected_withdraw_str='';
        $("#tbl_withdraw .withdraw_no").each(function () {
            if($(this).is(':checked'))
                selected_withdraw_str+=$(this).val()+',';
        });

        selected_withdraw_str=selected_withdraw_str.substr(0,selected_withdraw_str.length-1);

        if(selected_withdraw_str==''){
            toastr["error"]("{{trans('lang.select_row_to_change_status')}}", "{{trans('lang.notice')}}");
            return;
        }


        $.ajax({
            type: "POST",
            data: {
                selected_withdraw_str: selected_withdraw_str,
                status: $("#sel_withdraw_status").val(),
                _token: "{{csrf_token()}}"
            },
            url: 'selected_withdraw_change_status',
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}'){
                    toastr["error"]("{{trans('lang.success_status_change')}}", "{{trans('lang.notice')}}");
                    return;
                }
                else {
                    tbl_withdraw.draw(false);
                    toastr["success"]("{{trans('lang.fail_status_change')}}", "{{trans('lang.notice')}}");
                }
            }
        });
    })
</script>