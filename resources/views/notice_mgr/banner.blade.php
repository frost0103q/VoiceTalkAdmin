<div class="row" style="padding-left: 17px;padding-right: 17px;">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.title')}}</label>
            <input class="form-control" placeholder="" type="text" id="banner_title_search" name="banner_title_search">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.content')}}</label>
            <input class="form-control" placeholder="" type="text" id="banner_content_search" name="banner_content_search">
        </div>
        <div class="col-md-1" style="padding-top: 7px">
            <br>
            <a class="btn blue" id="btn_banner_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
        </div>
        <div class="col-md-1" style="padding-top: 7px">
            <br>
            <a class="btn blue" id="btn_banner_add"><i class="fa fa-plus"></i> {{trans('lang.add')}}</a>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_banner" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.number')}}</th>
                <th>{{trans('lang.edit_time')}}</th>
                <th>{{trans('lang.title')}}</th>
                <th>{{trans('lang.content')}}</th>
                <th>{{trans('lang.img_url')}}</th>
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

<button class="hidden" role="button" data-toggle="modal" data-target="#banner_edit_modal" id="btn_banner_open_modal"></button>
<div class="modal fade bs-modal-lg in" id="banner_edit_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><strong>{{trans('lang.banner_reg')}}</strong></h4>
            </div>
            <div class="modal-body">
                <form id="banner_edit_form" class="form-horizontal" method="post" enctype="multipart/form-data">
                    <div class="form-group" style="margin-top: 30px">
                        <label class="control-label col-md-2">{{trans('lang.title')}}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" placeholder="" id="banner_title" name="banner_title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">{{trans('lang.content')}}</label>
                        <div class="col-md-9">
                            <textarea class="form-control" id="banner_content" name="banner_content" rows="15" style="background: white"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-2">
                            <a class="btn blue start" id="btn_banner_img_upload">
                                <i class="fa fa-upload"></i>
								        <span>
								        {{trans('lang.file_upload')}} </span>
                            </a>
                        </div>
                        <div class="col-md-7">
                            <div class="input-group" style="text-align:left">
                                <input type="text" class="form-control" name="banner_img_url"
                                       id="banner_img_url" readonly style="background: white">
												<span class="input-group-btn">
												<a onclick="remove_banner_img_url(this)" class="btn green"
                                                   id="username1_checker">
                                                    <i class="fa fa-times"></i> {{trans('lang.cancel')}} </a>
												</span>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="banner_flag" id="banner_flag">
                    <input type="hidden" name="banner_edit_id" id="banner_edit_id">
                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn blue" id="btn_banner_save"><i class="fa fa-floppy-o"></i>&nbsp;{{trans('lang.edit_finish')}}</a>
                <a class="btn default" data-dismiss="modal" id="btn_banner_cancel"><i class="fa fa-rotate-left"></i>&nbsp;{{trans('lang.close')}}</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<button class="hidden" role="button" data-toggle="modal" data-target="#banner_del_modal" id="btn_banner_del_modal"></button>
<div class="modal fade in" id="banner_del_modal" tabindex="-1" aria-hidden="true">
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
                <a class="btn blue" id="btn_banner_del_confirm">&nbsp;{{trans('lang.confirm')}}</a>
                <a class="btn default" data-dismiss="modal" id="btn_banner_del_cancel">&nbsp;{{trans('lang.cancel')}}</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    var start_index;
    var tbl_banner;
    var init_tbl_banner;
    $(document).ready(function () {
        init_tbl_banner=function () {
            if(!$("#tbl_banner").hasClass("dataTable")){
                tbl_banner=$("#tbl_banner").DataTable({
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
                        "url": 	"ajax_banner_table",
                        "type":	"POST",
                        "data":   function ( d ) {
                            start_index=d.start;
                            d._token= "{{csrf_token()}}";
                            d.send_type_search = $("#banner_send_type_search").val();
                            d.title_search = $("#banner_title_search").val();
                            d.content_search = $("#banner_content_search").val();
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
                        'targets': [0,2,3,4,5]
                    },
                        {  // set default column settings
                            'orderable': true,
                            'targets': [1]
                        }],

                    "order": [
                        [1, "desc"]
                    ] // set first column as a default sort by asc
                });
            }
        }
    });

    function banner_edit(no) {
        $("#banner_edit_id").val(no);
        $("#banner_flag").val("{{config('constants.SAVE_FLAG_EDIT')}}");
        $.ajax({
            url: "get_banner_content",
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
                    $("#banner_title").val(result.title);
                    $("#banner_content").val(result.content);
                    $("#banner_img_url").val(result.img_url);
                    $("#btn_banner_open_modal").trigger('click');
                }
            }
        })
    }

    function banner_del(no) {
        $("#banner_edit_id").val(no);
        $("#btn_banner_del_modal").trigger('click');
    }

    $("#btn_banner_del_confirm").click(function () {
        $.ajax({
            url: "remove_banner",
            type: "POST",
            data: {
                no: $("#banner_edit_id").val(),
                _token: "{{csrf_token()}}"
            },
            success: function (result) {
                if (result == "{{config('constants.SUCCESS')}}") {
                    toastr["success"]("{{trans('lang.delete_success')}}", "{{trans('lang.notice')}}");
                    $("#btn_banner_del_cancel").trigger('click');
                    tbl_banner.draw();
                }
                else {
                    toastr["error"]("{{trans('lang.delete_fail')}}", "{{trans('lang.notice')}}");
                }
            }
        })
    })

    $("#btn_banner_add").click (function () {
        $("#banner_flag").val("{{config('constants.SAVE_FLAG_ADD')}}");
        $("#banner_title").val("");
        $("#banner_content").val("");
        $("#banner_img_url").val("");
        $("#banner_send_type").val("1");
        $("#btn_banner_open_modal").trigger('click');
    })

    $("#btn_banner_save").click(function () {
        if ($("#banner_title").val() == '') {
            toastr["error"]("{{trans('lang.input_title')}}", "{{trans('lang.notice')}}");
            $("#banner_title").focus();
            return;
        }
        if ($("#banner_content").val() == '') {
            toastr["error"]("{{trans('lang.input_content')}}", "{{trans('lang.notice')}}");
            $("#banner_content").focus();
            return;
        }
        $.ajax({
            url: "add_banner",
            type: "POST",
            data: $("#banner_edit_form").serialize(),
            success: function (result) {
                if (result == "{{config('constants.SUCCESS')}}") {
                    toastr["success"]("{{trans('lang.save_success')}}", "{{trans('lang.notice')}}");
                    $("#btn_banner_cancel").trigger('click');
                    tbl_banner.draw();
                }
                else {
                    toastr["error"]("{{trans('lang.save_fail')}}", "{{trans('lang.notice')}}");
                }
            }
        })
    })

    $("#btn_banner_search").click(function () {
        tbl_banner.draw();
    })

    function file_download(file_name) {
        window.location.href = 'file_download?file_name=' + file_name;
    }

    function remove_banner_img_url(obj) {
        $(obj).closest('div').find('input').val('');
    }

    $(function () {
        try {
            new AjaxUpload($("#btn_banner_img_upload"), {
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
                        $("#banner_img_url").val(jsonData.filename);
                    }
                }
            })
        } catch (e) {
            alert(e);
        }
    });

</script>
