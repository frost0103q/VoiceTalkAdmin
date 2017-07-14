<div class="row">
    <div class="col-md-12" style="padding: 30px">
        <a class="btn blue" id="btn_add_manage_notice"><i class="fa fa-plus"></i> {{trans('lang.add')}}</a>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_manage_notice" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.number')}}</th>
                <th>{{trans('lang.title')}}</th>
                <th>{{trans('lang.ref_file')}}</th>
                <th>{{trans('lang.writer')}}</th>
                <th>{{trans('lang.date')}}</th>
                <th>{{trans('lang.inquire')}}</th>
                <th>{{trans('lang.option')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<button class="hidden" role="button" data-toggle="modal" data-target="#manage_notice_modal"
        id="btn_manage_notice_modal"></button>
<div class="modal fade bs-modal-lg in" id="manage_notice_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><strong id="manage_notice_modal_title"><i
                                class="fa fa-edit"></i> {{trans('lang.edit')}}</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" id="frm_opinion">
                            <div class="form-group">
                                <label for="manage_notice_title"
                                       class="col-md-2 control-label">{{trans('lang.title')}}</label>
                                <div class="col-md-9">
                                    <input class="form-control" id="manage_notice_title" placeholder="" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="manage_notice_content"
                                       class="col-md-2 control-label">{{trans('lang.content')}}</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" id="manage_notice_content" placeholder=""
                                              rows="15"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-2">
                                    <a class="btn blue start" id="btn_manage_notice_file_upload">
                                        <i class="fa fa-upload"></i>
								        <span>
								        {{trans('lang.file_upload')}} </span>
                                    </a>
                                </div>
                                <div class="col-md-7">
                                    <div class="input-group" style="text-align:left">
                                        <input type="text" class="form-control" name="file_url"
                                               id="manage_notice_file_url" disabled style="background: white">
												<span class="input-group-btn">
												<a onclick="remove_manage_notice_file_url(this)" class="btn green"
                                                   id="username1_checker">
                                                    <i class="fa fa-times"></i> {{trans('lang.cancel')}} </a>
												</span>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="manage_notice_no">
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn blue" id="btn_manage_notice_save"><i
                            class="fa fa-save"></i>&nbsp;{{trans('lang.save')}} </button>
                <button type="button" class="btn default" data-dismiss="modal"><i
                            class="fa fa-rotate-right"></i>&nbsp;{{trans('lang.close')}} </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<button class="hidden" role="button" data-toggle="modal" data-target="#delete_manage_notice_modal"
        id="btn_delete_manage_notice_modal"></button>
<div class="modal fade" id="delete_manage_notice_modal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{trans('lang.notice')}}</h4>
            </div>
            <div class="modal-body">
                {{trans('lang.really_delete')}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">{{trans('lang.cancel')}}</button>
                <button type="button" class="btn blue" id="btn_delete_manage_notice">{{trans('lang.delete')}}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<input type="hidden" id="delete_manage_notice_no">

<script>
    var start_index;
    var tbl_manage_notice;
    var init_tbl_manage_notice;
    $(document).ready(function () {
        init_tbl_manage_notice=function () {
            if(!$("#tbl_manage_notice").hasClass("dataTable")){
                tbl_manage_notice = $("#tbl_manage_notice").DataTable({
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
                    "processing": false,
                    "serverSide": true,
                    "ajax": {
                        "url": "ajax_manage_notice_table",
                        "type": "POST",
                        "data": function (d) {
                            start_index = d.start;
                            d._token = "{{csrf_token()}}";
                        }
                    },
                    "createdRow": function (row, data, dataIndex) {
                        $('td:eq(0)', row).html(dataIndex + start_index + 1);
                        if (data[2] != "" && data[2] != null)
                            $('td:eq(2)', row).html(data[2] + ' &nbsp;<a onclick="file_download(\'' + data[2] + '\')"><i class="fa fa-download"></i></a>');
                        var option_html = '<a><i class="fa fa-edit" onclick="manage_notice_edit(' + data[0] + ',\'' + data[1] + '\',\'' + data[2] + '\',\'' + data[7] + '\')"></i>' +
                                ' <i class="fa fa-remove" onclick="manage_notice_delete(' + data[0] + ')"></i></a>';
                        $('td:eq(6)', row).html(option_html);
                    },
                    "columnDefs": [{
                        'orderable': false,
                        'targets': [0, 1, 2, 3, 5]
                    },
                        {
                            'orderable': true,
                            'targets': [4]
                        }],

                    "order": [
                        [4, "desc"]
                    ],
                    "autowidth": true,
                    "lengthMenu": [
                        [5, 10, 20, -1],
                        [5, 10, 20, "{{trans('lang.all')}}"]
                    ],
                    "pageLength": 10,
                    "pagingType": "bootstrap_full_number"
                });
            }
        }
    });

    $(function () {
        try {
            new AjaxUpload($("#btn_manage_notice_file_upload"), {
                action: "ajax_upload",
                data: {
                    _token: "{{csrf_token()}}"
                },
                name: 'uploadfile',
                onComplete: function (file, response) {
                    if (response == '{{config('constants.FAIL')}}')
                        toastr["error"]("{{trans('lang.file_upload_fail')}}", "{{trans('lang.notice')}}");
                    else {
                        var jsonData = JSON.parse(response);
                        $("#manage_notice_file_url").val(jsonData.filename);
                    }
                }
            })
        } catch (e) {
            alert(e);
        }
    });

    $("#btn_add_manage_notice").click(function () {
        $("#manage_notice_modal_title").html('<i class="fa fa-plus"></i> {{trans('lang.add')}}');

        $("#manage_notice_no").val('');
        $("#manage_notice_title").val('');
        $("#manage_notice_content").val('');
        $("#manage_notice_file_url").val('');
        $("#btn_manage_notice_modal").trigger('click');
    });

    function manage_notice_edit(no, title, file_name, content) {
        $("#manage_notice_modal_title").html('<i class="fa fa-edit"></i> {{trans('lang.edit')}}');

        $("#manage_notice_no").val(no);
        $("#manage_notice_title").val(title);
        $("#manage_notice_content").val(content);

        if (file_name != 'null')
            $("#manage_notice_file_url").val(file_name);
        else
            $("#manage_notice_file_url").val('');
        $("#btn_manage_notice_modal").trigger('click');
    }

    function manage_notice_delete(no) {
        $("#delete_manage_notice_no").val(no);
        $("#btn_delete_manage_notice_modal").trigger('click');
    }

    $("#btn_delete_manage_notice").click(function () {
        $.ajax({
            url: "delete_manage_notice",
            type: "post",
            data: {
                manage_notice_no: $("#delete_manage_notice_no").val(),
                _token: "{{csrf_token()}}"
            },
            success: function (result) {
                if (result == '{{config('constants.FAIL')}}')
                    toastr["error"]("{{trans('lang.delete_fail')}}", "{{trans('lang.notice')}}");
                if (result == '{{config('constants.SUCCESS')}}') {
                    toastr["success"]("{{trans('lang.delete_success')}}", "{{trans('lang.notice')}}");
                    tbl_manage_notice.draw(false);
                }

                $("button[data-dismiss='modal']").trigger('click');
            }
        });
    });

    function file_download(file_name) {
        window.location.href = 'file_download?file_name=' + file_name;
    }

    function remove_manage_notice_file_url(obj) {
        $(obj).closest('div').find('input').val('');
    }

    $("#btn_manage_notice_save").click(function () {
        if ($("#manage_notice_title").val() == '') {
            toastr["error"]("{{trans('lang.input_title')}}", "{{trans('lang.notice')}}");
            $("#manage_notice_title").focus();
            return;
        }
        if ($("#manage_notice_content").val() == '') {
            toastr["error"]("{{trans('lang.input_content')}}", "{{trans('lang.notice')}}");
            $("#manage_notice_content").focus();
            return;
        }

        $.ajax({
            url: "save_manage_notice",
            type: "post",
            data: {
                no: $("#manage_notice_no").val(),
                title: $("#manage_notice_title").val(),
                content: $("#manage_notice_content").val(),
                file_url: $("#manage_notice_file_url").val(),
                _token: "{{csrf_token()}}"
            },
            success: function (result) {
                if (result == '{{config('constants.FAIL')}}')
                    toastr["error"]("{{trans('lang.save_fail')}}", "{{trans('lang.notice')}}");
                if (result == '{{config('constants.SUCCESS')}}') {
                    toastr["success"]("{{trans('lang.save_success')}}", "{{trans('lang.notice')}}");
                    tbl_manage_notice.draw(false);
                }

                $("button[data-dismiss='modal']").trigger('click');
            }
        });

    })
</script>