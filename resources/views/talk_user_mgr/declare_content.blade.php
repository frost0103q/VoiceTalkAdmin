<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <div class="col-md-1">
            <label class="control-label">{{trans('lang.sex')}}</label>
            <select class="form-control select2me" id="declare_user_sex">
                <option value="">{{trans('lang.all')}}</option>
                <option value="{{config('constants.MALE')}}">{{trans('lang.man')}}</option>
                <option value="{{config('constants.FEMALE')}}">{{trans('lang.woman')}}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.user_no')}}</label>
            <input class="form-control" placeholder="" type="text" id="declare_user_no">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.nickname')}}</label>
            <input class="form-control" placeholder="" type="text" id="declare_user_nickname">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.telnum')}}</label>
            <input class="form-control" placeholder="" type="text" id="declare_user_phone_number">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.email')}}</label>
            <input class="form-control" placeholder="" type="text" id="declare_user_email">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.chat_content')}}</label>
            <input class="form-control" placeholder="" type="text" id="declare_user_chat_content">
        </div>
        <div class="col-md-1" style="padding-top: 7px">
            <br>
            <a class="btn blue" id="btn_declare_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_declare" style="width: 100%">
            <thead>
            <tr>
                <th></th>
                <th>{{trans('lang.from_user_declare')}}</th>
                <th>{{trans('lang.from_user_photo')}}</th>
                <th>{{trans('lang.to_user_declare')}}</th>
                <th>{{trans('lang.declare_content')}}</th>
                <th>{{trans('lang.declare_time')}}</th>
                <th>{{trans('lang.process_time')}}</th>
                <th>{{trans('lang.processing')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    var tbl_declare;
    $(document).ready(function () {
        var start_index;
        tbl_declare=$("#tbl_declare").DataTable({
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
                "url": 	"ajax_declare_table",
                "type":	"POST",
                "data":   function ( d ) {
                    start_index=d.start;
                    d._token= "{{csrf_token()}}";
                    d.sex=$("#declare_user_sex").val();
                    d.user_no=$("#declare_user_no").val();
                    d.nickname=$("#declare_user_nickname").val();
                    d.phone_number=$("#declare_user_phone_number").val();
                    d.email=$("#declare_user_email").val();
                    d.chat_content=$("#declare_user_chat_content").val();
                }
            },
            "createdRow": function (row, data, dataIndex) {
                $('td:eq(2)', row).html('<img src="'+data[2]+'" height="50px">');
            },
            "lengthMenu": [
                [5, 10, 20, -1],
                [5, 10, 20, "전체"] // change per page values here
            ],
            // set the initial value
            "pageLength": 5,
            "pagingType": "bootstrap_full_number",
            "columnDefs": [{  // set default column settings
                'orderable': false,
                'targets': [0,1,2,3,4,6,7]
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

    $("#btn_declare_search").click(function () {
        tbl_declare.draw(false);
    });

</script>