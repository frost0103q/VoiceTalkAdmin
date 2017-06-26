<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <form action="#" class="form-horizontal">
            <div class="form-group">
                <div class="col-md-1">
                    <label class="control-label">{{trans('lang.sex')}}</label>
                    <select class="form-control select2me" id="pp_sex" name="sex">
                        <option value="">{{trans('lang.man')}}</option>
                        <option value="">{{trans('lang.woman')}}</option>
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
    var tbl_point_present;
    $(document).ready(function () {
        var start_index;
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
            "pageLength": 5,
            "pagingType": "bootstrap_full_number"
        });
    });
</script>