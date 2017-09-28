<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <form action="#" class="form-horizontal">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <label class="control-label">{{trans('lang.period_search')}}</label>
                        <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control" name="from_date" id="w_from_date">
                            <span class="input-group-addon"> to </span>
                            <input type="text" class="form-control" name="to_date" id="w_to_date">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">{{trans('lang.withdraw_status')}}</label>
                        <select class="form-control select2me" id="w_status" name="status">
                            <option value="-1">{{trans('lang.all')}}</option>
                            <option value="{{config('constants.WITHDRAW_WAIT')}}">{{trans('lang.wait')}}</option>
                            <option value="{{config('constants.WITHDRAW_FINISH')}}">{{trans('lang.finish')}}</option>
                            <option value="{{config('constants.WITHDRAW_ERROR')}}">{{trans('lang.error')}}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">{{trans('lang.verify_status')}}</label>
                        <select class="form-control select2me" id="w_verified_status" name="status">
                            <option value="-1">{{trans('lang.all')}}</option>
                            <option value="{{config('constants.UNVERIFIED')}}">{{trans('lang.no_auth')}}</option>
                            <option value="{{config('constants.VERIFIED')}}">{{trans('lang.is_verified')}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
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
                <th>Nickname</th>
                <th>{{trans('lang.req_amount')}}</th>
                <th>{{trans('lang.cash_owner')}}</th>
                <th>{{trans('lang.account_number')}}</th>
                <th>{{trans('lang.bank_name')}}</th>
                <th>{{trans('lang.withdraw_status')}}</th>
                <th>{{trans('lang.verify_status')}}</th>
                <th>{{trans('lang.req_date')}}</th>
                <th>{{trans('lang.withdraw_amount')}}</th>
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
        <div class=" col-md-2">
            <a class="btn blue" id="btn_withdraw_change">{{trans('lang.status_change')}}</a>
        </div>
        <div class=" col-md-2">
            <a class="btn blue" id="btn_withdraw_delete">{{trans('lang.delete')}}</a>
        </div>
    </div>
</div>


<button class="hidden" role="button" data-toggle="modal" data-target="#push_del_modal" id="btn_push_del_modal"></button>
<div class="modal fade in" id="push_del_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><strong>{{trans('lang.notice')}}</strong></h4>
            </div>
            <div class="modal-body">
                {{trans('lang.really_delete')}}
            </div>
            <div class="modal-footer">
                <a class="btn blue" id="btn_push_del_confirm">&nbsp;{{trans('lang.confirm')}}</a>
                <a class="btn default" data-dismiss="modal" id="btn_push_del_cancel">&nbsp;{{trans('lang.cancel')}}</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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
                            d.verified = $("#w_verified_status").val();
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
                        'targets': [0, 1, 2,3,4,5,6,7,8]
                    },
                        {
                            'orderable': true,
                            'targets': [9]
                        }],

                    "order": [
                        [9, "desc"]
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
                    toastr["error"]("{{trans('lang.fail_status_change')}}", "{{trans('lang.notice')}}");
                    return;
                }
                else {
                    tbl_withdraw.draw(false);
                    toastr["success"]("{{trans('lang.success_status_change')}}", "{{trans('lang.notice')}}");
                }
            }
        });
    })

    $("#btn_withdraw_delete").click(function () {
        $("#btn_push_del_modal").trigger('click');
    })

    $("#btn_push_del_confirm").click(function () {
        delete_withdraw();
    })

    function delete_withdraw() {

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
                _token: "{{csrf_token()}}"
            },
            url: 'selected_withdraw_delete',
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}'){
                    toastr["error"]("{{trans('lang.delete_fail')}}", "{{trans('lang.notice')}}");
                    return;
                }
                else {
                    tbl_withdraw.draw(false);
                    toastr["success"]("{{trans('lang.delete_success')}}", "{{trans('lang.notice')}}");
                }
                $("#btn_push_del_cancel").trigger('click');
            }
        });
    }
</script>