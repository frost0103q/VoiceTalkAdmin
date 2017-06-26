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
                <h4 class="modal-title"><strong>{{trans('lang.banner_sending')}}</strong></h4>
            </div>
            <div class="modal-body">
                <form id="banner_edit_form" class="form-horizontal" method="post" enctype="multipart/form-data">
                    <div class="form-group" style="margin-top: 30px">
                        <label class="control-label col-md-2">{{trans('lang.title')}}</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="" id="banner_title" name="banner_title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">{{trans('lang.content')}}</label>
                        <div class="col-md-8">
                            <textarea class="form-control" id="banner_content" name="banner_content" rows="15" style="background: white"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">{{trans('lang.img_url')}}</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="" id="banner_img_url" name="banner_img_url">
                        </div>
                        <div class="col-md-2">
                            <a class="btn blue" onclick="banner_image_reg(this)">{{trans('lang.img_reg')}}</a>
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

<script>
    var tbl_banner;
    $(document).ready(function () {
        var start_index;
        tbl_banner=$("#tbl_banner").DataTable({
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

    $("#btn_banner_add").click (function () {
        $("#banner_flag").val("{{config('constants.SAVE_FLAG_ADD')}}");
        $("#banner_title").val("");
        $("#banner_content").val("");
        $("#banner_img_url").val("");
        $("#banner_send_type").val("1");
        $("#btn_banner_open_modal").trigger('click');
    })

    $("#btn_banner_save").click(function () {
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
</script>
