<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <div class="col-md-1">
            <label class="control-label">{{trans('lang.sex')}}</label>
            <select class="form-control select2me" id="d_user_sex">
                <option value="-1">{{trans('lang.all')}}</option>
                <option value="{{config('constants.MALE')}}">{{trans('lang.man')}}</option>
                <option value="{{config('constants.FEMALE')}}">{{trans('lang.woman')}}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.user_no')}}</label>
            <input class="form-control" placeholder="" type="text" id="d_user_no">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.nickname')}}</label>
            <input class="form-control" placeholder="" type="text" id="d_user_nickname">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.telnum')}}</label>
            <input class="form-control" placeholder="" type="text" id="d_user_phone_number">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.email')}}</label>
            <input class="form-control" placeholder="" type="text" id="d_user_email">
        </div>
        <div class="col-md-2">
            <label class="control-label">{{trans('lang.chat_content')}}</label>
            <input class="form-control" placeholder="" type="text" id="d_user_chat_content">
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
                <th>{{trans('lang.no')}}</th>
                <th>{{trans('lang.user_no')}}</th>
                <th>Nickname</th>
                <th>{{trans('lang.declare_content')}}</th>
                <th>{{trans('lang.edit_time')}}</th>
                <th>{{trans('lang.answer_status')}}</th>
                <th>{{trans('lang.answer_date')}}</th>
                <th>{{trans('lang.option')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<button class="hidden" role="button" data-toggle="modal" data-target="#declare_answer_modal" id="btn_delcare_answer"></button>
<div class="modal fade bs-modal-lg in" id="declare_answer_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><strong id="opinion_modal_title"><i class="fa fa-edit"></i> {{trans('lang.edit')}}</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div  class="col-md-12">
                        <form class="form-horizontal" id="frm_cash_declare">
                            <div class="form-group">
                                <label for="opinion_content" class="col-md-2 control-label">{{trans('lang.declare_content')}}</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" id="cash_declare_content" placeholder="" rows="10" disabled style="background: white"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="opinion_content" class="col-md-2 control-label">{{trans('lang.answer_content')}}</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" id="cash_declare_answer" placeholder="" rows="10"></textarea>
                                </div>
                            </div>
                            <input type="hidden" id="cash_declare_no">
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn blue" id="btn_cash_declare_save"><i class="fa fa-save"></i>&nbsp;{{trans('lang.save')}} </button>
                <button type="button" class="btn default" data-dismiss="modal"><i class="fa fa-rotate-right"></i>&nbsp;{{trans('lang.close')}} </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<button class="hidden" role="button" data-toggle="modal" data-target="#delete_cash_declare_modal" id="btn_delete_cash_declare_modal"></button>
<div class="modal fade" id="delete_cash_declare_modal" tabindex="-1" role="basic" aria-hidden="true">
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
                <button type="button" class="btn blue" id="btn_delete_cash_declare">{{trans('lang.delete')}}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<input type="hidden" id="delete_cash_declare_no">

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
            "lengthMenu": [
                [5, 10, 20, -1],
                [5, 10, 20, "{{trans('lang.all')}}"] // change per page values here
            ],
            // set the initial value
            "pageLength": 5,
            "pagingType": "bootstrap_full_number",
            "processing": false,
            "serverSide": true,
            "ajax": {
                "url": "ajax_cash_declare_table",
                "type": "POST",
                "data": function (d) {
                    start_index = d.start;
                    d._token = "{{csrf_token()}}";
                    d.user_sex=$("#d_user_sex").val();
                    d.user_no=$("#d_user_no").val();
                    d.user_nickname=$("#d_user_nickname").val();
                    d.user_phone_number=$("#d_user_phone_number").val();
                    d.user_email=$("#d_user_email").val();
                    d.user_chat_content=$("#d_user_chat_content").val();
                }
            },
            "createdRow": function (row, data, dataIndex) {
                $('td:eq(0)', row).html(dataIndex + start_index + 1);

                if(data[5]!='' && data[5]!=null)
                    $('td:eq(5)', row).html('{{trans('lang.answer')}}');
                else
                    $('td:eq(5)', row).html('{{trans('lang.uncertain')}}');
                var option_html = '<a><i class="fa fa-edit" onclick="cash_declare_edit(' + data[0] + ',\'' + data[3] + '\',\'' + data[5] + '\')"></i>' +
                        ' <i class="fa fa-remove" onclick="cash_declare_delete(' + data[0] + ')"></i></a>';
                $('td:eq(7)', row).html(option_html);
            },
            "columnDefs": [{
                'orderable': false,
                'targets': [0, 1, 2, 3, 5,6,7]
            },
                {
                    'orderable': true,
                    'targets': [4]
                }],

            "order": [
                [4, "desc"]
            ]
        });
    });

    function cash_declare_edit(no,content,answer) {
        if(answer=='null')
            answer='';
        $("#cash_declare_content").val(content);
        $("#cash_declare_answer").val(answer);
        $("#cash_declare_no").val(no);
        $("#btn_delcare_answer").trigger('click');
    }

    $("#btn_cash_declare_save").click(function () {

        if($("#cash_declare_answer").val()==''){
            toastr["error"]("{{trans('lang.input_answer_content')}}", "{{trans('lang.notice')}}");
            $("#cash_declare_answer").focus();
            return;
        }

        $.ajax({
            url: "save_cash_declare",
            type: "post",
            data:{
                no:$("#cash_declare_no").val(),
                answer:$("#cash_declare_answer").val(),
                _token: "{{csrf_token()}}"
            },
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}')
                    toastr["error"]("{{trans('lang.save_fail')}}", "{{trans('lang.notice')}}");
                if(result=='{{config('constants.SUCCESS')}}'){
                    toastr["success"]("{{trans('lang.save_success')}}", "{{trans('lang.notice')}}");
                    tbl_declare.draw(false);
                }

                $("button[data-dismiss='modal']").trigger('click');
            }
        });
    });

    function cash_declare_delete(no) {
        $("#delete_cash_declare_no").val(no);
        $("#btn_delete_cash_declare_modal").trigger('click');
    }

    $("#btn_delete_cash_declare").click(function () {
        $.ajax({
            url: "delete_cash_declare",
            type: "post",
            data:{
                no:$("#delete_cash_declare_no").val(),
                _token: "{{csrf_token()}}"
            },
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}')
                    toastr["error"]("{{trans('lang.delete_fail')}}", "{{trans('lang.notice')}}");
                if(result=='{{config('constants.SUCCESS')}}'){
                    toastr["success"]("{{trans('lang.delete_success')}}", "{{trans('lang.notice')}}");
                    tbl_declare.draw(false);
                }

                $("button[data-dismiss='modal']").trigger('click');
            }
        });
    });

    $("#btn_declare_search").click(function () {
        tbl_declare.draw(false);
    })

</script>