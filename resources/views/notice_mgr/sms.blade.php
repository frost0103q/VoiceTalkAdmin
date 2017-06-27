<div class="row" style="padding-left: 17px;padding-right: 17px;">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.receive_number')}}</label>
            <input class="form-control" placeholder="" type="text" id="sms_receive_number_search" name="sms_receive_number_search">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.content')}}</label>
            <input class="form-control" placeholder="" type="text" id="sms_content_search" name="sms_content_search">
        </div>
        <div class="col-md-1" style="padding-top: 7px">
            <br>
            <a class="btn blue" id="btn_sms_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
        </div>
        <div class="col-md-1" style="padding-top: 7px">
            <br>
            <a class="btn blue" id="btn_sms_add"><i class="fa fa-plus"></i> {{trans('lang.add')}}</a>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_sms" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.number')}}</th>
                <th>{{trans('lang.edit_time')}}</th>
                <th>{{trans('lang.receive_number')}}</th>
                <th>{{trans('lang.content')}}</th>
                <th>{{trans('lang.function')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>

</script>

<button class="hidden" role="button" data-toggle="modal" data-target="#sms_edit_modal" id="btn_sms_open_modal"></button>
<div class="modal fade bs-modal-lg in" id="sms_edit_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-receive_number"><strong>{{trans('lang.sms_sending')}}</strong></h4>
            </div>
            <div class="modal-body">
                <form id="sms_edit_form" class="form-horizontal" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="control-label col-md-2">{{trans('lang.sender_number')}}</label>
                        <label class="control-label col-md-2" style="text-align: left"><strong>070-123-580</strong></label>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">{{trans('lang.receive_number')}}</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="" id="sms_receive_number" name="sms_receive_number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">{{trans('lang.content')}}</label>
                        <div class="col-md-8">
                            <textarea class="form-control" id="sms_content" name="sms_content" rows="15" style="background: white"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="sms_flag" id="sms_flag">
                    <input type="hidden" name="sms_edit_id" id="sms_edit_id">
                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn blue" id="btn_sms_save"><i class="fa fa-floppy-o"></i>&nbsp;{{trans('lang.edit_finish')}}</a>
                <a class="btn default" data-dismiss="modal" id="btn_sms_cancel"><i class="fa fa-rotate-left"></i>&nbsp;{{trans('lang.close')}}</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<button class="hidden" role="button" data-toggle="modal" data-target="#sms_del_modal" id="btn_sms_del_modal"></button>
<div class="modal fade in" id="sms_del_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-receive_number"><strong>{{trans('lang.notice')}}</strong></h4>
            </div>
            <div class="modal-body">
                {{trans('lang.really_delete')}}
            </div>
            <div class="modal-footer">
                <a class="btn blue" id="btn_sms_del_confirm">&nbsp;{{trans('lang.confirm')}}</a>
                <a class="btn default" data-dismiss="modal" id="btn_sms_del_cancel">&nbsp;{{trans('lang.cancel')}}</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    var tbl_sms;
    $(document).ready(function () {
        var start_index;
        tbl_sms=$("#tbl_sms").DataTable({
            "dom": '<"top"i><"toolbar pull-left">rtlp',
            "language": {
                "emptyTable": "{{trans('lang.no_display_data')}}",
                "lengthMenu": "{{trans('lang.display_cnt')}} _MENU_",
                "sInfo": "{{trans('lang.all_cnt')}} _TOTAL_ {{trans('lang.unit')}}",
                "infoFiltered": "",
                "sInfoEmpty": "",
                "zeroRecords": "{{trans('lang.no_display_data')}}",
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
                "url": 	"ajax_sms_table",
                "type":	"POST",
                "data":   function ( d ) {
                    start_index=d.start;
                    d._token= "{{csrf_token()}}";
                    d.receive_number_search = $("#sms_receive_number_search").val();
                    d.content_search = $("#sms_content_search").val();
                }
            },
            "createdRow": function (row, data, dataIndex) {
                $('td:eq(0)', row).html(dataIndex + start_index + 1);
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
                'targets': [0,2,3,4]
            },
                {  // set default column settings
                    'orderable': true,
                    'targets': [1]
                }],

            "order": [
                [1, "desc"]
            ] // set first column as a default sort by asc
        });
    });

    function sms_edit(no) {
        $("#sms_edit_id").val(no);
        $("#sms_flag").val("{{config('constants.SAVE_FLAG_EDIT')}}");
        $.ajax({
            url: "get_sms_content",
            type: "POST",
            data: {
                no: no,
                _token: "{{csrf_token()}}"
            },
            success: function (result) {
                if (result == "{{config('constants.FAIL')}}") {
                    toastr["error"]("{{trans('lang.no_display_data')}}", "{{trans('lang.notice')}}");
                }
                else {
                    $("#sms_receive_number").val(result.receive_number);
                    $("#sms_content").val(result.content);
                    $("#btn_sms_open_modal").trigger('click');
                }
            }
        })
    }

    function sms_del(no) {
        $("#sms_edit_id").val(no);
        $("#btn_sms_del_modal").trigger('click');
    }

    $("#btn_sms_del_confirm").click(function () {
        $.ajax({
            url: "remove_sms",
            type: "POST",
            data: {
                no: $("#sms_edit_id").val(),
                _token: "{{csrf_token()}}"
            },
            success: function (result) {
                if (result == "{{config('constants.SUCCESS')}}") {
                    toastr["success"]("{{trans('lang.delete_success')}}", "{{trans('lang.notice')}}");
                    $("#btn_sms_del_cancel").trigger('click');
                    tbl_sms.draw();
                }
                else {
                    toastr["error"]("{{trans('lang.delete_fail')}}", "{{trans('lang.notice')}}");
                }
            }
        })
    })

    $("#btn_sms_add").click (function () {
        $("#sms_flag").val("{{config('constants.SAVE_FLAG_ADD')}}");
        $("#sms_receive_number").val("");
        $("#sms_content").val("");
        $("#btn_sms_open_modal").trigger('click');
    })

    $("#btn_sms_save").click(function () {
        $.ajax({
            url: "add_sms",
            type: "POST",
            data: $("#sms_edit_form").serialize(),
            success: function (result) {
                if (result == "{{config('constants.SUCCESS')}}") {
                    toastr["success"]("{{trans('lang.save_success')}}", "{{trans('lang.notice')}}");
                    $("#btn_sms_cancel").trigger('click');
                    tbl_sms.draw();
                }
                else {
                    toastr["error"]("{{trans('lang.save_fail')}}", "{{trans('lang.notice')}}");
                }
            }
        })
    })

    $("#btn_sms_search").click(function () {
        tbl_sms.draw();
    })
</script>
