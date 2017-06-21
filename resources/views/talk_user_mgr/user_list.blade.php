<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <div class="col-md-1">
            <label class="control-label">{{trans('lang.sex')}}</label>
            <select class="form-control select2me" id="user_sex">
                <option value="">{{trans('lang.all')}}</option>
                <option value="{{config('constants.MALE')}}">{{trans('lang.man')}}</option>
                <option value="{{config('constants.FEMALE')}}">{{trans('lang.woman')}}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.user_no')}}</label>
            <input class="form-control" placeholder="" type="text" id="user_no">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.nickname')}}</label>
            <input class="form-control" placeholder="" type="text" id="user_nickname">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.telnum')}}</label>
            <input class="form-control" placeholder="" type="text" id="user_phone_number">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.email')}}</label>
            <input class="form-control" placeholder="" type="text" id="user_email">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.chat_content')}}</label>
            <input class="form-control" placeholder="" type="text" id="user_chat_content">
        </div>
        <div class="col-md-1" style="padding-top: 7px">
            <br>
            <a class="btn blue" id="btn_user_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_user" style="width: 100%">
            <thead>
            <tr>
                <th></th>
                <th>{{trans('lang.user_no')}}</th>
                <th>{{trans('lang.photo')}}</th>
                <th>Nickname</th>
                <th>{{trans('lang.content')}}</th>
                <th>{{trans('lang.edit_time')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="col-md-12"  style="padding-top: 30px">
        <div class=" col-md-2">
            <a class="btn blue" id="btn_warning_user">{{trans('lang.warning')}}</a>
            <a class="btn blue" id="btn_del_user_photo">{{trans('lang.del_only_photo')}}</a>
        </div>
        <div class="col-md-2">
            <select class="form-control select2me" id="user_sex">
                <option value="">{{trans('lang.all')}}</option>
                <option value="{{config('constants.MALE')}}">{{trans('lang.man')}}</option>
                <option value="{{config('constants.FEMALE')}}">{{trans('lang.woman')}}</option>
            </select>
        </div>
        <label class="control-label col-md-1" for="admin_memo" style="text-align: right">{{trans('lang.admin_memo')}}</label>
        <div class="col-md-3">
            <input class="form-control" placeholder="" type="text" id="admin_memo">
        </div>
    </div>
</div>

<script>
    var tbl_user;
    $(document).ready(function () {
        var start_index;
        tbl_user=$("#tbl_user").DataTable({
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
            "processing":false,
            "serverSide": true,
            "ajax": {
                "url": 	"ajax_user_table",
                "type":	"POST",
                "data":   function ( d ) {
                    start_index=d.start;
                    d._token= "{{csrf_token()}}";
                    d.sex=$("#user_sex").val();
                    d.user_no=$("#user_no").val();
                    d.nickname=$("#user_nickname").val();
                    d.phone_number=$("#user_phone_number").val();
                    d.email=$("#user_email").val();
                    d.chat_content=$("#user_chat_content").val();
                }
            },
            "createdRow": function (row, data, dataIndex) {
                $('td:eq(2)', row).html('<img src="'+data[2]+'" height="50px">');
            },
            "lengthMenu": [
                [5, 10, 20, -1],
                [5, 10, 20, "{{trans('lang.all')}}"] // change per page values here
            ],
            // set the initial value
            "pageLength": 5,
            "pagingType": "bootstrap_full_number",
            "columnDefs": [{  // set default column settings
                'orderable': false,
                'targets': [0,1,2,3,4]
            },
                {  // set default column settings
                    'orderable': true,
                    'targets': [5]
                }],

            "order": [
                [5, "desc"]
            ] // set first column as a default sort by asc
        });
    });

    $("#btn_user_search").click(function () {
        tbl_user.draw(false);
    });

</script>