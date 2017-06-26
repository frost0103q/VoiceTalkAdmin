<div class="row">
    <div class="col-md-12" style="padding: 30px">
        <a class="btn blue" id="btn_add_opinion"><i class="fa fa-plus"></i> {{trans('lang.add')}}</a>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_opinion" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.number')}}</th>
                <th>{{trans('lang.title')}}</th>
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



<button class="hidden" role="button" data-toggle="modal" data-target="#opinion_modal" id="btn_opinion_modal"></button>
<div class="modal fade bs-modal-lg in" id="opinion_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><strong id="opinion_modal_title"><i class="fa fa-edit"></i> {{trans('lang.edit')}}</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div  class="col-md-12">
                        <form class="form-horizontal" id="frm_opinion">
                            <div class="form-group">
                                <label for="opinion_title" class="col-md-2 control-label">{{trans('lang.title')}}</label>
                                <div class="col-md-9">
                                    <input class="form-control" id="opinion_title" placeholder="" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="opinion_content" class="col-md-2 control-label">{{trans('lang.content')}}</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" id="opinion_content" placeholder="" rows="15"></textarea>
                                </div>
                            </div>
                            <input type="hidden" id="opinion_no">
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn blue" id="btn_opinion_save"><i class="fa fa-save"></i>&nbsp;{{trans('lang.save')}} </button>
                <button type="button" class="btn default" data-dismiss="modal"><i class="fa fa-rotate-right"></i>&nbsp;{{trans('lang.close')}} </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<button class="hidden" role="button" data-toggle="modal" data-target="#delete_opinion_modal" id="btn_delete_opinion_modal"></button>
<div class="modal fade" id="delete_opinion_modal" tabindex="-1" role="basic" aria-hidden="true">
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
                <button type="button" class="btn blue" id="btn_delete_opinion">{{trans('lang.delete')}}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<input type="hidden" id="delete_opinion_no">

<script>
    var tbl_opinion;
    $(document).ready(function () {
        var start_index;
        tbl_opinion=$("#tbl_opinion").DataTable({
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
            "processing":false,
            "serverSide": true,
            "ajax": {
                "url": 	"ajax_opinion_table",
                "type":	"POST",
                "data":   function ( d ) {
                    start_index=d.start;
                    d._token= "{{csrf_token()}}";
                }
            },
            "createdRow": function (row, data, dataIndex) {
                $('td:eq(0)', row).html(dataIndex + start_index + 1);
                $('td:eq(5)', row).html('<a><i class="fa fa-edit" onclick="opinion_edit('+data[5]+',\''+data[1]+'\',\''+data[6]+'\')"></i> <i class="fa fa-remove" onclick="opinion_delete('+data[5]+')"></i></a>');
            },
            "autowidth": true,
            "lengthMenu": [
                [5, 10, 20, -1],
                [5, 10, 20, "{{trans('lang.all')}}"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,
            "pagingType": "bootstrap_full_number",
            "columnDefs": [{  // set default column settings
                'orderable': false,
                'targets': [0,1,2,4,5]
            },
                {  // set default column settings
                    'orderable': true,
                    'targets': [3]
                }],

            "order": [
                [3, "desc"]
            ] // set first column as a default sort by asc
        });
    });

    $("#btn_add_opinion").click(function () {
        $("#opinion_no").val('');
        $("#opinion_title").val('');
        $("#opinion_content").val('');

        $("#opinion_modal_title").html('<i class="fa fa-plus"></i> {{trans('lang.add')}}');

        $("#btn_opinion_modal").trigger('click');
    });

    function opinion_edit(opinion_no,title,content) {

        $("#opinion_no").val(opinion_no);
        $("#opinion_title").val(title);
        $("#opinion_content").val(content);

        $("#opinion_modal_title").html('<i class="fa fa-edit"></i> {{trans('lang.edit')}}');

        $("#btn_opinion_modal").trigger('click');
    }

    function opinion_delete(opinion_no) {
        $("#delete_opinion_no").val(opinion_no);
        $("#btn_delete_opinion_modal").trigger('click');
    }

    $("#btn_delete_opinion").click(function () {
        $.ajax({
            url: "delete_opinion",
            type: "post",
            data:{
                opinion_no:$("#delete_opinion_no").val(),
                _token: "{{csrf_token()}}"
            },
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}')
                    toastr["error"]("{{trans('lang.delete_fail')}}", "{{trans('lang.notice')}}");
                if(result=='{{config('constants.SUCCESS')}}'){
                    toastr["success"]("{{trans('lang.delete_success')}}", "{{trans('lang.notice')}}");
                    tbl_opinion.draw(false);
                }

                $("button[data-dismiss='modal']").trigger('click');
            }
        });
    });

    $("#btn_opinion_save").click(function () {
       if($("#opinion_title").val()==''){
            toastr["error"]("{{trans('lang.input_title')}}", "{{trans('lang.notice')}}");
           $("#opinion_title").focus();
            return;
       }
       if($("#opinion_content").val()==''){
           toastr["error"]("{{trans('lang.input_content')}}", "{{trans('lang.notice')}}");
           $("#opinion_content").focus();
           return;
       }

        $.ajax({
            url: "save_opinion",
            type: "post",
            data:{
                opinion_no:$("#opinion_no").val(),
                opinion_title:$("#opinion_title").val(),
                opinion_content:$("#opinion_content").val(),
                _token: "{{csrf_token()}}"
            },
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}')
                    toastr["error"]("{{trans('lang.save_fail')}}", "{{trans('lang.notice')}}");
                if(result=='{{config('constants.SUCCESS')}}'){
                    toastr["success"]("{{trans('lang.save_success')}}", "{{trans('lang.notice')}}");
                    tbl_opinion.draw(false);
                }

                $("button[data-dismiss='modal']").trigger('click');
            }
        });


    });
</script>