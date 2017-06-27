<div class="row" style="padding-left: 17px;padding-right: 17px;">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.sender_mgr')}}</label>
            <select class="form-control select2me" id="message_sender_type_search" name="message_sender_type_search">
                <option value="0">{{trans('lang.all')}}</option>
                <option value="1">{{trans('lang.admin')}}</option>
                <option value="2">{{trans('lang.talk_policy')}}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.send_to')}}</label>
            <select class="form-control select2me" id="message_receive_type_search" name="message_receive_type_search">
                <option value="0">{{trans('lang.all')}}</option>
                <option value="1">{{trans('lang.special_user')}}</option>
                <option value="2">{{trans('lang.common_user')}}</option>
                <option value="3">{{trans('lang.talk_user')}}</option>
                <option value="4">{{trans('lang.all_user')}}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.user_id')}}</label>
            <input type="text" id="message_user_id_search" name="message_user_id_search" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.sentence_to')}}</label>
            <select class="form-control select2me" id="message_sentence_type_search" name="message_sentence_type_search">
                <option value="0">{{trans('lang.all')}}</option>
                <option value="1">{{trans('lang.no_passbook_guide')}}</option>
                <option value="2">{{trans('lang.lost_pw')}}</option>
                <option value="3">{{trans('lang.declare_recep')}}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.content')}}</label>
            <input class="form-control" placeholder="" type="text" id="message_content_search" name="message_content_search">
        </div>
        <div class="col-md-1" style="padding-top: 7px">
            <br>
            <a class="btn blue" id="btn_message_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
        </div>
        <div class="col-md-1" style="padding-top: 7px">
            <br>
            <a class="btn blue" id="btn_message_add"><i class="fa fa-plus"></i> {{trans('lang.add')}}</a>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_message" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.number')}}</th>
                <th>{{trans('lang.edit_time')}}</th>
                <th>{{trans('lang.sender_mgr')}}</th>
                <th>{{trans('lang.send_to')}}</th>
                <th>{{trans('lang.user_id')}}</th>
                <th>{{trans('lang.sentence_to')}}</th>
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

<button class="hidden" role="button" data-toggle="modal" data-target="#message_edit_modal" id="btn_message_open_modal"></button>
<div class="modal fade bs-modal-lg in" id="message_edit_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><strong>{{trans('lang.message_sending')}}</strong></h4>
            </div>
            <div class="modal-body">
                <form id="message_edit_form" class="form-horizontal" method="post" enctype="multipart/form-data">
                    <div class="form-group" style="margin-top: 30px;">
                        <label class="control-label col-md-2">{{trans('lang.sender_mgr')}}</label>
                        <div class="col-md-2">
                            <select class="form-control select2me" id="message_sender_type" name="message_sender_type">
                                <option value="1">{{trans('lang.admin')}}</option>
                                <option value="2">{{trans('lang.talk_policy')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">{{trans('lang.send_to')}}</label>
                        <div class="col-md-2">
                            <select class="form-control select2me" id="message_receive_type" name="message_receive_type">
                                <option value="1">{{trans('lang.special_user')}}</option>
                                <option value="2">{{trans('lang.common_user')}}</option>
                                <option value="3">{{trans('lang.talk_user')}}</option>
                                <option value="4">{{trans('lang.all_user')}}</option>
                            </select>
                        </div>
                        <label class="control-label col-md-1">{{trans('lang.user_id')}}</label>
                        <div class="col-md-2">
                            <input type="text" id="message_user_id" name="message_user_id" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">{{trans('lang.sentence_to')}}</label>
                        <div class="col-md-2">
                            <select class="form-control select2me" id="message_sentence_type" name="message_sentence_type">
                                <option value="1">{{trans('lang.no_passbook_guide')}}</option>
                                <option value="2">{{trans('lang.lost_pw')}}</option>
                                <option value="3">{{trans('lang.declare_recep')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">{{trans('lang.content')}}</label>
                        <div class="col-md-8">
                            <textarea class="form-control" id="message_content" name="message_content" rows="15" style="background: white"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="message_flag" id="message_flag">
                    <input type="hidden" name="message_edit_id" id="message_edit_id">
                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn blue" id="btn_message_save"><i class="fa fa-floppy-o"></i>&nbsp;{{trans('lang.edit_finish')}}</a>
                <a class="btn default" data-dismiss="modal" id="btn_message_cancel"><i class="fa fa-rotate-left"></i>&nbsp;{{trans('lang.close')}}</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<button class="hidden" role="button" data-toggle="modal" data-target="#message_del_modal" id="btn_message_del_modal"></button>
<div class="modal fade in" id="message_del_modal" tabindex="-1" aria-hidden="true">
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
                <a class="btn blue" id="btn_message_del_confirm">&nbsp;{{trans('lang.confirm')}}</a>
                <a class="btn default" data-dismiss="modal" id="btn_message_del_cancel">&nbsp;{{trans('lang.cancel')}}</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    var tbl_message;
    $(document).ready(function () {
        var start_index;
        tbl_message = $("#tbl_message").DataTable({
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
                "url": 	"ajax_message_table",
                "type":	"POST",
                "data":   function ( d ) {
                    start_index=d.start;
                    d._token= "{{csrf_token()}}";
                    d.sender_type_search = $("#message_sender_type_search").val();
                    d.receive_type_search = $("#message_receive_type_search").val();
                    d.user_id_search = $("#message_user_id_search").val();
                    d.sentence_type_search = $("#message_sentence_type_search").val();
                    d.content_search = $("#message_content_search").val();
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
                'targets': [0,2,3,4,5,6,7]
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

    function message_edit(no) {
        $("#message_edit_id").val(no);
        $("#message_flag").val("{{config('constants.SAVE_FLAG_EDIT')}}");
        $.ajax({
            url: "get_message_content",
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
                    $("#message_sender_type").val(result.sender_type);
                    $("#message_receive_type").val(result.receive_type);
                    $("#message_user_id").val(result.user_id);
                    $("#message_sentence_type").val(result.sentence_type);
                    $("#message_content").val(result.content);
                    $("#btn_message_open_modal").trigger('click');
                }
            }
        })
    }

    function message_del(no) {
        $("#message_edit_id").val(no);
        $("#btn_message_del_modal").trigger('click');
    }

    $("#btn_message_del_confirm").click(function () {
        $.ajax({
            url: "remove_message",
            type: "POST",
            data: {
                no: $("#message_edit_id").val(),
                _token: "{{csrf_token()}}"
            },
            success: function (result) {
                if (result == "{{config('constants.SUCCESS')}}") {
                    toastr["success"]("{{trans('lang.delete_success')}}", "{{trans('lang.notice')}}");
                    $("#btn_message_del_cancel").trigger('click');
                    tbl_message.draw();
                }
                else {
                    toastr["error"]("{{trans('lang.delete_fail')}}", "{{trans('lang.notice')}}");
                }
            }
        })
    })

    $("#btn_message_add").click (function () {
        $("#message_flag").val("{{config('constants.SAVE_FLAG_ADD')}}");
        $("#message_sender_type").val("1");
        $("#message_receive_type").val("1");
        $("#message_user_id").val("");
        $("#message_sentence_type").val("1");
        $("#message_content").val("");
        $("#btn_message_open_modal").trigger('click');
    })

    $("#btn_message_save").click(function () {
        if ($("#message_sender_type").val() == '') {
            toastr["error"]("{{trans('lang.input_sender_type')}}", "{{trans('lang.notice')}}");
            $("#message_sender_type").focus();
            return;
        }
        if ($("#message_receiver_type").val() == '') {
            toastr["error"]("{{trans('lang.input_receiver_type')}}", "{{trans('lang.notice')}}");
            $("#message_receiver_type").focus();
            return;
        }
        if ($("#message_user_id").val() == '') {
            toastr["error"]("{{trans('lang.input_user_id')}}", "{{trans('lang.notice')}}");
            $("#message_user_id").focus();
            return;
        }
        if ($("#message_sentence_type").val() == '') {
            toastr["error"]("{{trans('lang.input_sentence_type')}}", "{{trans('lang.notice')}}");
            $("#message_sentence_type").focus();
            return;
        }
        if ($("#message_content").val() == '') {
            toastr["error"]("{{trans('lang.input_content')}}", "{{trans('lang.notice')}}");
            $("#message_content").focus();
            return;
        }
        $.ajax({
            url: "add_message",
            type: "POST",
            data: $("#message_edit_form").serialize(),
            success: function (result) {
                if (result == "{{config('constants.SUCCESS')}}") {
                    toastr["success"]("{{trans('lang.save_success')}}", "{{trans('lang.notice')}}");
                    $("#btn_message_cancel").trigger('click');
                    tbl_message.draw();
                }
                else {
                    toastr["error"]("{{trans('lang.save_fail')}}", "{{trans('lang.notice')}}");
                }
            }
        })
    })

    $("#btn_message_search").click(function () {
        tbl_message.draw();
    })
</script>

