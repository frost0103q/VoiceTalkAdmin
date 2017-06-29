<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <form action="#" class="form-horizontal">
            <div class="form-group">
                <div class="col-md-3">
                    <label class="control-label">{{trans('lang.period_search')}}</label>
                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control" name="from_date" id="c_from_date">
                        <span class="input-group-addon"> to </span>
                        <input type="text" class="form-control" name="to_date" id="c_to_date">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.status')}}</label>
                    <select class="form-control select2me" id="c_status" name="status">
                        <option value="-1">{{trans('lang.all')}}</option>
                        <option value="{{config('constants.CASH_FINISH')}}">{{trans('lang.cash_finish')}}</option>
                        <option value="{{config('constants.CASH_STOP')}}">{{trans('lang.cash_stop')}}</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="control-label">{{trans('lang.user_no')}}</label>
                    <input class="form-control" placeholder="" type="text" id="c_user_no" name="user_no">
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.nickname')}}</label>
                    <input class="form-control" placeholder="" type="text" id="c_user_nickname" name="user_nickname">
                </div>
                <div class="col-md-1">
                    <label class="control-label">{{trans('lang.order_number')}}</label>
                    <input class="form-control" placeholder="" type="text" id="c_order_number" name="order_number">
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.cash_code')}}</label>
                    <input class="form-control" placeholder="" type="text" id="c_cash_code" name="cach_code">
                </div>
                <div class="col-md-1" style="padding-top: 7px">
                    <br>
                    <a class="btn blue" id="btn_c_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12">
        <label class="control-label" style="padding: 20px"><strong>{{trans('lang.total_cash_amount_by_condition')}} : <span id="total_cash_amount">12,500</span>{{trans('lang.won')}}</strong></label>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_cash" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.number')}}</th>
                <th>{{trans('lang.order_number')}}</th>
                <th>Nickname</th>
                <th>{{trans('lang.realtime_point')}}</th>
                <th>{{trans('lang.date')}}</th>
                <th>{{trans('lang.status')}}</th>
                <th>{{trans('lang.total_cash')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    var tbl_cash;
    $(document).ready(function () {
        var start_index;
        tbl_cash=$("#tbl_cash").DataTable({
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
                "url": "ajax_cash_table",
                "type": "POST",
                "data": function (d) {
                    start_index = d.start;
                    d._token = "{{csrf_token()}}";
                    d.start_dt=$("#c_from_date").val();
                    d.end_dt=$("#c_to_date").val();
                    d.status=$("#c_status").val();
                    d.user_no=$("#c_user_no").val();
                    d.nickname=$("#c_user_nickname").val();
                    d.order_number=$("#c_order_number").val();
                    d.cash_code=$("#c_cash_code").val();
                }
            },
            "createdRow": function (row, data, dataIndex) {
                $('td:eq(0)', row).html(dataIndex + start_index + 1);
                $("#total_cash_amount").text(data[7]);
            },
            "columnDefs": [{
                'orderable': false,
                'targets': [0, 1, 2, 3,5,6]
            },
                {
                    'orderable': true,
                    'targets': [4]
                }],

            "order": [
                [4, "desc"]
            ]
        });
    });

    $("#btn_c_search").click(function () {
        tbl_cash.draw(false);
    })
</script>