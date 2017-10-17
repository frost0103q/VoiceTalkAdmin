<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <form action="#" class="form-horizontal">
            <div class="form-group">

                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.nickname')}}</label>
                    <input class="form-control" placeholder="" type="text" id="f_user_nickname" name="user_nickname">
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.received_point')}}</label>
                    <input class="form-control" placeholder="" type="text" id="f_received_point" name="received_point">
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{trans('lang.free_charge_store')}}</label>
                    <select class="form-control select2me" id="f_charge_type" name="charge_type">
                        <option value="-1">{{trans('lang.all')}}</option>
                        <option value="{{config('constants.FREE_CHARGE_ADSYNC')}}">Adsync</option>
                        <option value="{{config('constants.FREE_CHARGE_NAS')}}">NAS</option>
                        <option value="{{config('constants.FREE_CHARGE_IGAWORKS')}}">IGAWorks</option>
                    </select>
                </div>
                <div class="col-md-1" style="padding-top: 7px">
                    <br>
                    <a class="btn blue" id="btn_f_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_free_charge" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.number')}}</th>
                <th>Nickname</th>
                <th>{{trans('lang.received_point')}}</th>
                <th>{{trans('lang.free_charge_store')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    var start_index;
    var tbl_free_charge;
    var init_tbl_free_charge;
    $(document).ready(function () {
        init_tbl_free_charge=function () {
            if(!$("#tbl_free_charge").hasClass("dataTable")){
                tbl_free_charge=$("#tbl_free_charge").DataTable({
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
                        "url": "ajax_free_charge_table",
                        "type": "POST",
                        "data": function (d) {
                            start_index = d.start;
                            d._token = "{{csrf_token()}}";
                            d.nickname=$("#f_user_nickname").val();
                            d.received_point=$("#f_received_point").val();
                            d.charge_type=$("#f_charge_type").val();
                        }
                    },
                    "createdRow": function (row, data, dataIndex) {
                        $('td:eq(0)', row).html(dataIndex + start_index + 1);
                    },
                    "columnDefs": [{
                        'orderable': false,
                        'targets': [0, 1, 3]
                    },
                        {
                            'orderable': true,
                            'targets': [2]
                        }],

                    "order": [
                        [2, "desc"]
                    ]
                });
            }
        }
    });

    $("#btn_f_search").click(function () {
        tbl_free_charge.draw(false);
    })
</script>