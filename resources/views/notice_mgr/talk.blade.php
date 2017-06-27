<div class="row" style="padding-left: 17px;padding-right: 17px;">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.sender_mgr')}}</label>
            <select class="form-control select2me" id="talk_send_type_search" name="talk_send_type_search">
                <option value="0">{{trans('lang.all')}}</option>
                <option value="1">{{trans('lang.admin')}}</option>
                <option value="2">{{trans('lang.talk_policy')}}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.content')}}</label>
            <input class="form-control" placeholder="" type="text" id="talk_content_search" name="talk_content_search">
        </div>
        <div class="col-md-1" style="padding-top: 7px">
            <br>
            <a class="btn blue" id="btn_talk_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
        </div>
        <div class="col-md-1" style="padding-top: 7px">
            <br>
            <a class="btn blue" id="btn_talk_add"><i class="fa fa-plus"></i> {{trans('lang.add')}}</a>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_talk" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.number')}}</th>
                <th>{{trans('lang.edit_time')}}</th>
                <th>{{trans('lang.sender_mgr')}}</th>
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

<button class="hidden" role="button" data-toggle="modal" data-target="#talk_edit_modal" id="btn_talk_open_modal"></button>
<div class="modal fade bs-modal-lg in" id="talk_edit_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><strong>{{trans('lang.sender_mgr')}}</strong></h4>
            </div>
            <div class="modal-body">
                <form id="talk_edit_form" class="form-horizontal" method="post" enctype="multipart/form-data">
                    <div class="form-group" style="margin-top: 30px;">
                        <label class="control-label col-md-2">{{trans('lang.sender_mgr')}}</label>
                        <div class="col-md-2">
                            <select class="form-control select2me" id="talk_send_type" name="talk_send_type">
                                <option value="1">{{trans('lang.admin')}}</option>
                                <option value="2">{{trans('lang.talk_policy')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">{{trans('lang.content')}}</label>
                        <div class="col-md-8">
                            <textarea class="form-control" id="talk_content" name="talk_content" rows="15" style="background: white"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="talk_flag" id="talk_flag">
                    <input type="hidden" name="talk_edit_id" id="talk_edit_id">
                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn blue" id="btn_talk_save"><i class="fa fa-floppy-o"></i>&nbsp;{{trans('lang.edit_finish')}}</a>
                <a class="btn default" data-dismiss="modal" id="btn_talk_cancel"><i class="fa fa-rotate-left"></i>&nbsp;{{trans('lang.close')}}</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<button class="hidden" role="button" data-toggle="modal" data-target="#talk_del_modal" id="btn_talk_del_modal"></button>
<div class="modal fade in" id="talk_del_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><strong>{{trans('lang.notice')}}</strong></h4>
            </div>
            <div class="modal-body">
                {{trans('lang.really_delete')}}
            </div>
            <div class="modal-footer">
                <a class="btn blue" id="btn_talk_del_confirm">&nbsp;{{trans('lang.confirm')}}</a>
                <a class="btn default" data-dismiss="modal" id="btn_talk_del_cancel">&nbsp;{{trans('lang.cancel')}}</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    var tbl_talk;
    $(document).ready(function () {
        var start_index;
        tbl_talk=$("#tbl_talk").DataTable({
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
                "url": 	"ajax_talk_notice_table",
                "type":	"POST",
                "data":   function ( d ) {
                    start_index=d.start;
                    d._token= "{{csrf_token()}}";
                    d.send_type_search = $("#talk_send_type_search").val();
                    d.content_search = $("#talk_content_search").val();
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

    function talk_edit(no) {
        $("#talk_edit_id").val(no);
        $("#talk_flag").val("{{config('constants.SAVE_FLAG_EDIT')}}");
        $.ajax({
            url: "get_talk_content",
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
                    $("#talk_content").val(result.content);
                    $("#talk_send_type").val(result.sender_type);
                    $("#btn_talk_open_modal").trigger('click');
                }
            }
        })
    }

    function talk_del(no) {
        $("#talk_edit_id").val(no);
        $("#btn_talk_del_modal").trigger('click');
    }

    $("#btn_talk_del_confirm").click(function () {
        $.ajax({
            url: "remove_talk",
            type: "POST",
            data: {
                no: $("#talk_edit_id").val(),
                _token: "{{csrf_token()}}"
            },
            success: function (result) {
                if (result == "{{config('constants.SUCCESS')}}") {
                    toastr["success"]("{{trans('lang.delete_success')}}", "{{trans('lang.notice')}}");
                    $("#btn_talk_del_cancel").trigger('click');
                    tbl_talk.draw();
                }
                else {
                    toastr["error"]("{{trans('lang.delete_fail')}}", "{{trans('lang.notice')}}");
                }
            }
        })
    })

    $("#btn_talk_add").click (function () {
        $("#talk_flag").val("{{config('constants.SAVE_FLAG_ADD')}}");
        $("#talk_content").val("");
        $("#talk_send_type").val("1");
        $("#btn_talk_open_modal").trigger('click');
    })

    $("#btn_talk_save").click(function () {
        $.ajax({
            url: "add_talk",
            type: "POST",
            data: $("#talk_edit_form").serialize(),
            success: function (result) {
                if (result == "{{config('constants.SUCCESS')}}") {
                    toastr["success"]("{{trans('lang.save_success')}}", "{{trans('lang.notice')}}");
                    $("#btn_talk_cancel").trigger('click');
                    tbl_talk.draw();
                }
                else {
                    toastr["error"]("{{trans('lang.save_fail')}}", "{{trans('lang.notice')}}");
                }
            }
        })
    })

    $("#btn_talk_search").click(function () {
        tbl_talk.draw();
    })
</script>
