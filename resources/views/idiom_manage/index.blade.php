@extends('layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green" style="border: none">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-file-word-o"></i>{{trans('lang.interdict_manage')}}
                    </div>
                </div>
                <div class="portlet-body">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab">{{trans('lang.interdict_manage')}}</a>
                        </li>
                        <li>
                            <a href="#tab_2" data-toggle="tab">{{trans('lang.registered_idiom')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_1">
                            @include('idiom_manage.reg_idiom')
                        </div>
                        <div class="tab-pane fade" id="tab_2">
                            @include('idiom_manage.delete_idiom')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function reg_idiom(obj) {
            var idiom_str=$(obj).parent('div').parent('div').find('input').val();
            if(idiom_str==''){
                toastr["error"]("{{trans('lang.input_idiom')}}", "{{trans('lang.notice')}}");
                $(obj).parent('div').parent('div').find('input').focus();
                return;
            }
            $.ajax({
                type: "POST",
                data: {
                    idiom_str: idiom_str,
                    _token: "{{csrf_token()}}"
                },
                url: 'save_interdict_idiom',
                success: function (result) {
                    if(result=='{{config('constants.FAIL')}}'){
                        toastr["error"]("{{trans('lang.save_fail')}}", "{{trans('lang.notice')}}");
                        return;
                    }
                    else {
                        $("#idiom_select_pad").html(result);
                        $("#txt_idiom_content").val($("#idiom_content_hidden").val());

                        toastr["success"]("{{trans('lang.save_success')}}", "{{trans('lang.notice')}}");
                        $(obj).parent('div').parent('div').find('input').val('');
                    }
                }
            });
        }
    </script>
@stop


