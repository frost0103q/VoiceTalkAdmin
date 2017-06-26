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
                        <option value="">{{trans('lang.payment_finish')}}</option>
                        <option value="">{{trans('lang.no_payment')}}</option>
                        <option value="">{{trans('lang.wait_payment')}}</option>
                        <option value="">{{trans('lang.no_auth')}}</option>
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
        <label class="control-label" style="padding: 20px"><strong>{{trans('lang.total_cash_by_condition')}} : 12,500{{trans('lang.won')}}</strong></label>
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
            "pageLength": 5,
            "pagingType": "bootstrap_full_number"
        });
    });
</script>