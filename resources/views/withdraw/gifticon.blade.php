<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <form action="#" class="form-horizontal">
            <div class="form-group">
                <div class="col-md-3">
                    <label class="control-label">{{trans('lang.period_search')}}</label>
                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control" name="from_date" id="g_from_date">
                        <span class="input-group-addon"> to </span>
                        <input type="text" class="form-control" name="to_date" id="g_to_date">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.status')}}</label>
                    <select class="form-control select2me" id="g_status" name="status">
                        <option value="-1">{{trans('lang.all')}}</option>
                        <option value="{{config('constants.GIFTICON_NOMAL')}}">{{trans('lang.nomal')}}</option>
                        <option value="{{config('constants.GIFTICON_CANCEL')}}">{{trans('lang.cancel')}}</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="control-label">{{trans('lang.user_no')}}</label>
                    <input class="form-control" placeholder="" type="text" id="g_user_no" name="user_no">
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.nickname')}}</label>
                    <input class="form-control" placeholder="" type="text" id="g_user_nickname" name="user_nickname">
                </div>
                <div class="col-md-1">
                    <label class="control-label">{{trans('lang.manage_number')}}</label>
                    <input class="form-control" placeholder="" type="text" id="g_mgr_number" name="mgr_number">
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.cupon_number')}}</label>
                    <input class="form-control" placeholder="" type="text" id="g_cupon_number" name="cupon_code">
                </div>
                <div class="col-md-1" style="padding-top: 7px">
                    <br>
                    <a class="btn blue" id="btn_g_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12">
        <label class="control-label" style="padding: 20px"><strong>{{trans('lang.total_nomal_amount_by_condition')}} : <span id="gif_total_nomal_price"></span>{{trans('lang.won')}}</strong></label>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_gifticon" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.number')}}</th>
                <th>{{trans('lang.cupon_number')}}</th>
                <th>{{trans('lang.product_name')}}</th>
                <th>Nickname</th>
                <th>{{trans('lang.real_price')}}/{{trans('lang.sale_price')}}</th>
                <th>{{trans('lang.cur_price')}}/{{trans('lang.benefit')}}</th>
                <th>{{trans('lang.status')}}</th>
                <th>{{trans('lang.send_time')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    var tbl_gifticon;
    $(document).ready(function () {
        var start_index;
        tbl_gifticon=$("#tbl_gifticon").DataTable({
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
                "url": "ajax_gifticon_table",
                "type": "POST",
                "data": function (d) {
                    start_index = d.start;
                    d._token = "{{csrf_token()}}";
                    d.start_dt=$("#g_from_date").val();
                    d.end_dt=$("#g_to_date").val();
                    d.status=$("#g_status").val();
                    d.user_no=$("#g_user_no").val();
                    d.nickname=$("#g_user_nickname").val();
                    d.mgr_number=$("#g_mgr_number").val();
                    d.cupon_number=$("#g_cupon_number").val();
                }
            },
            "createdRow": function (row, data, dataIndex) {
                $('td:eq(0)', row).html(dataIndex + start_index + 1);
                $("#gif_total_nomal_price").text(data[8]);
            },
            "columnDefs": [{
                'orderable': false,
                'targets': [0, 1, 2,3,4,5,6]
            },
                {
                    'orderable': true,
                    'targets': [7]
                }],

            "order": [
                [7, "desc"]
            ]
        });
    });

    $("#btn_g_search").click(function () {
        tbl_gifticon.draw(false);
    })
</script>