<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <form action="#" class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-md-1" style="text-align: right">{{trans('lang.period_search')}}</label>
                <div class="col-md-3">
                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control" name="from_date" id="c_from_date">
                        <span class="input-group-addon"> to </span>
                        <input type="text" class="form-control" name="to_date" id="c_to_date">
                    </div>
                </div>
                <label class="control-label col-md-1" style="text-align: right">{{trans('lang.sex')}}</label>
                <div class="col-md-1">
                    <select class="form-control select2me" id="c_sex" name="sex">
                        <option value="">{{trans('lang.man')}}</option>
                        <option value="">{{trans('lang.woman')}}</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <a class="btn blue" id="btn_c_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_connect" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.year_month_day')}}</th>
                <th>{{trans('lang.connect_cnt')}}</th>
                <th>{{trans('lang.cash_cnt')}}</th>
                <th>{{trans('lang.sale')}}(+)</th>
                <th>{{trans('lang.ad')}}(+)</th>
                <th>{{trans('lang.cupon')}}(-)</th>
                <th>{{trans('lang.benefit')}}</th>
                <th>{{trans('lang.log_in')}}({{trans('lang.male')}}+{{trans('lang.female')}}-{{trans('lang.exit')}}={{trans('lang.sum')}})</th>
                <th>{{trans('lang.presnet')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    var tbl_connect;
    $(document).ready(function () {
        var start_index;
        tbl_connect=$("#tbl_connect").DataTable({
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