<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <form action="#" class="form-horizontal">
            <div class="form-group">
                <div class="col-md-3">
                    <label class="control-label">{{trans('lang.period_search')}}</label>
                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control" name="from_date" id="pr_from_date">
                        <span class="input-group-addon"> to </span>
                        <input type="text" class="form-control" name="to_date" id="pr_to_date">
                    </div>
                </div>
                <div class="col-md-1">
                    <label class="control-label">{{trans('lang.sex')}}</label>
                    <select class="form-control select2me" id="pr_sex" name="sex">
                        <option value="">{{trans('lang.man')}}</option>
                        <option value="">{{trans('lang.woman')}}</option>
                    </select>
                </div>
                <div class="col-md-1" style="padding-top: 7px">
                    <br>
                    <a class="btn blue" id="btn_c_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_point_rank" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.rank')}}</th>
                <th>{{trans('lang.user_no')}}</th>
                <th>{{trans('lang.photo')}}</th>
                <th>{{trans('lang.writer')}}/{{trans('lang.age')}}/{{trans('lang.point')}}</th>
                <th>{{trans('lang.last_connect')}}/{{trans('lang.reg_time')}}</th>
                <th>{{trans('lang.warn')}}</th>
                <th>{{trans('lang.receive_point')}}</th>
                <th>{{trans('lang.out_point')}}</th>
                <th>{{trans('lang.receive_present')}}</th>
                <th>{{trans('lang.send_present')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    var tbl_point_rank;
    $(document).ready(function () {
        var start_index;
        tbl_point_rank=$("#tbl_point_rank").DataTable({
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