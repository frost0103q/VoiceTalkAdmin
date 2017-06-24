<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <form action="#" class="form-horizontal">
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <label class="control-label">{{trans('lang.period_search')}}</label>
                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control" name="from_date" id="e_from_date">
                        <span class="input-group-addon"> to </span>
                        <input type="text" class="form-control" name="to_date" id="e_to_date">
                    </div>
                </div>
                <div class="col-md-1">
                    <label class="control-label">{{trans('lang.sex')}}</label>
                    <select class="form-control select2me" id="e_sex" name="sex">
                        <option value="">{{trans('lang.man')}}</option>
                        <option value="">{{trans('lang.woman')}}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.user_no')}}</label>
                    <input class="form-control" placeholder="" type="text" id="e_user_no" name="user_no">
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.nickname')}}</label>
                    <input class="form-control" placeholder="" type="text" id="e_user_nickname" name="user_nickname">
                </div>
                <div class="col-md-1" style="padding-top: 7px">
                    <br>
                    <a class="btn blue" id="btn_e_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12">
        <label class="control-label" style="padding: 20px"><strong>{{trans('lang.period_sum')}} : 12,500{{trans('lang.won')}}</strong></label>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_edwards" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.order')}}</th>
                <th>{{trans('lang.user_no')}}</th>
                <th>Nickname</th>
                <th>{{trans('lang.profile')}}</th>
                <th>{{trans('lang.earn')}}</th>
                <th>{{trans('lang.jehusa')}}</th>
                <th>{{trans('lang.ad_name')}}</th>
                <th>{{trans('lang.process_date')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    var tbl_edwards;
    $(document).ready(function () {
        var start_index;
        tbl_edwards=$("#tbl_edwards").DataTable({
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