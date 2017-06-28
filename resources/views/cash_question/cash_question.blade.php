<div class="row">
    <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
        <div class="col-md-1">
            <label class="control-label">{{trans('lang.sex')}}</label>
            <select class="form-control select2me" id="user_sex">
                <option value="-1">{{trans('lang.all')}}</option>
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
            <a class="btn blue" id="btn_cash_question_search"><i class="fa fa-search"></i> {{trans('lang.search')}}</a>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="tbl_cash_question" style="width: 100%">
            <thead>
            <tr>
                <th>{{trans('lang.no')}}</th>
                <th>{{trans('lang.user_no')}}</th>
                <th>{{trans('lang.photo')}}</th>
                <th>Nickname</th>
                <th>{{trans('lang.question_content')}}</th>
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

<button class="hidden" role="button" data-toggle="modal" data-target="#anwer_modal" id="btn_anwer_modal"></button>
<div class="modal fade bs-modal-lg in" id="anwer_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><strong id="opinion_modal_title"><i class="fa fa-edit"></i> {{trans('lang.edit')}}</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div  class="col-md-12">
                        <form class="form-horizontal" id="frm_cash_question">
                            <div class="form-group">
                                <label for="opinion_content" class="col-md-2 control-label">{{trans('lang.question_content')}}</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" id="cash_question_content" placeholder="" rows="10" disabled style="background: white"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="opinion_content" class="col-md-2 control-label">{{trans('lang.answer_content')}}</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" id="cash_question_answer" placeholder="" rows="10"></textarea>
                                </div>
                            </div>
                            <input type="hidden" id="cash_question_no">
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn blue" id="btn_cash_question_save"><i class="fa fa-save"></i>&nbsp;{{trans('lang.save')}} </button>
                <button type="button" class="btn default" data-dismiss="modal"><i class="fa fa-rotate-right"></i>&nbsp;{{trans('lang.close')}} </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<button class="hidden" role="button" data-toggle="modal" data-target="#delete_cash_question_modal" id="btn_delete_cash_question_modal"></button>
<div class="modal fade" id="delete_cash_question_modal" tabindex="-1" role="basic" aria-hidden="true">
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
                <button type="button" class="btn blue" id="btn_delete_cash_question">{{trans('lang.delete')}}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<input type="hidden" id="delete_cash_question_no">

<script>
    var tbl_cash_question;
    $(document).ready(function () {
        var start_index;
        tbl_cash_question=$("#tbl_cash_question").DataTable({
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
                "url": "ajax_cash_question_table",
                "type": "POST",
                "data": function (d) {
                    start_index = d.start;
                    d._token = "{{csrf_token()}}";
                    d.user_sex=$("#user_sex").val();
                    d.user_no=$("#user_no").val();
                    d.user_nickname=$("#user_nickname").val();
                    d.user_phone_number=$("#user_phone_number").val();
                    d.user_email=$("#user_email").val();
                    d.user_chat_content=$("#user_chat_content").val();
                }
            },
            "createdRow": function (row, data, dataIndex) {
                $('td:eq(0)', row).html(dataIndex + start_index + 1);
                if(data[2]!='')
                    $('td:eq(2)', row).html('<img src="'+data[2]+'" height="50px">');
                if(data[6]!='' && data[6]!=null)
                    $('td:eq(6)', row).html('{{trans('lang.answer')}}');
                else
                    $('td:eq(6)', row).html('{{trans('lang.uncertain')}}');
                var option_html = '<a><i class="fa fa-edit" onclick="cash_question_edit(' + data[0] + ',\'' + data[4] + '\',\'' + data[6] + '\')"></i>' +
                        ' <i class="fa fa-remove" onclick="cash_question_delete(' + data[0] + ')"></i></a>';
                $('td:eq(8)', row).html(option_html);
            },
            "columnDefs": [{
                'orderable': false,
                'targets': [0, 1, 2, 3, 4,6,7,8]
            },
                {
                    'orderable': true,
                    'targets': [5]
                }],

            "order": [
                [5, "desc"]
            ]
        });
    });

    function cash_question_edit(no,content,answer) {
        if(answer=='null')
                answer='';
        $("#cash_question_content").val(content);
        $("#cash_question_answer").val(answer);
        $("#cash_question_no").val(no);
        $("#btn_anwer_modal").trigger('click');
    }

    $("#btn_cash_question_save").click(function () {

        if($("#cash_question_answer").val()==''){
            toastr["error"]("{{trans('lang.input_answer_content')}}", "{{trans('lang.notice')}}");
            $("#cash_question_answer").focus();
            return;
        }

        $.ajax({
            url: "save_cash_question_opinion",
            type: "post",
            data:{
                no:$("#cash_question_no").val(),
                answer:$("#cash_question_answer").val(),
                _token: "{{csrf_token()}}"
            },
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}')
                    toastr["error"]("{{trans('lang.save_fail')}}", "{{trans('lang.notice')}}");
                if(result=='{{config('constants.SUCCESS')}}'){
                    toastr["success"]("{{trans('lang.save_success')}}", "{{trans('lang.notice')}}");
                    tbl_cash_question.draw(false);
                }

                $("button[data-dismiss='modal']").trigger('click');
            }
        });
    });

    function cash_question_delete(no) {
        $("#delete_cash_question_no").val(no);
        $("#btn_delete_cash_question_modal").trigger('click');
    }

    $("#btn_delete_cash_question").click(function () {
        $.ajax({
            url: "delete_cash_questin",
            type: "post",
            data:{
                no:$("#delete_cash_question_no").val(),
                _token: "{{csrf_token()}}"
            },
            success: function (result) {
                if(result=='{{config('constants.FAIL')}}')
                    toastr["error"]("{{trans('lang.delete_fail')}}", "{{trans('lang.notice')}}");
                if(result=='{{config('constants.SUCCESS')}}'){
                    toastr["success"]("{{trans('lang.delete_success')}}", "{{trans('lang.notice')}}");
                    tbl_cash_question.draw(false);
                }

                $("button[data-dismiss='modal']").trigger('click');
            }
        });
    });

    $("#btn_cash_question_search").click(function () {
        tbl_cash_question.draw(false);
    })

</script>