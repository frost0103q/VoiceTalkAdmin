@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green" style="border: none">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-phone"></i>{{trans('lang.mobile_page')}}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="margin-top: 30px;margin-bottom: 20px">
                    <div class="col-md-2">
                        <div class="col-md-12">
                            <select class="form-control select2me" id="select_page_type">
                                <option value="{{config('constants.MOBILE_SERVICE_PAGE')}}">{{trans('lang.service_page')}}</option>
                                <option value="{{config('constants.MOBILE_PRIVACY_PAGE')}}">{{trans('lang.privacy_page')}}</option>
                                <option value="{{config('constants.MOBILE_GPS_PAGE')}}">{{trans('lang.gps_page')}}</option>
                                <option value="{{config('constants.MOBILE_GOOGLE_PAY_PAGE')}}">{{trans('lang.google_card_page')}}</option>
                                <option value="{{config('constants.MOBILE_USE_GUIDE_PAGE')}}">{{trans('lang.use_guide_page')}}</option>
                                <option value="{{config('constants.MOBILE_NOTIFY_PAGE')}}">{{trans('lang.notify')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6" style="margin-top: 6px;">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="mt-radio-inline" id="div_radio">
                                    <label class="mt-radio"> {{trans('lang.content')}}
                                        <input type="radio" value="false" name="radio2" id="rb_content" checked/>
                                        <span></span>
                                    </label>
                                    <label class="mt-radio"> {{trans('lang.url')}}
                                        <input type="radio" value="true" name="radio2" id="rb_url"  />
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <textarea class="form-control" rows="16" id="ta_content" content="{{$content}}" url="{{$url}}">{{$content}}</textarea>
                    <div class="row margin-top-20">
                        <div class="col-md-offset-2 col-md-8 text-center">
                            <button class="btn btn-primary" id="btn_save"> {{trans('lang.save')}}</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="btn_preview">{{trans('lang.preview')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="invisible_form" action="new_window.php" method="get" target="_blank">
        <input id="new_window_parameter_1" name="content" type="hidden" value="default">
        <input id="new_window_parameter_2" name="_token" type="hidden" value="{{csrf_token()}}">
    </form>

    <script>
        $("#btn_save").click(function () {

            var content=$("#ta_content").val();
            if(content==''){
                toastr["error"]("{{trans('lang.input_content')}}", "{{trans('lang.notice')}}");
                return;
            }

            var type = $("#select_page_type").val()
            var isurl = $('input[name=radio2]:checked', '#div_radio').val();
            $.ajax({
                type: "POST",
                data: {
                    type: type,
                    content:content,
                    is_url:isurl,
                    _token: "{{csrf_token()}}"
                },
                url: 'save_mobile_page',
                success: function (result) {
                    if(result=='{{config('constants.FAIL')}}'){
                        toastr["error"]("{{trans('lang.save_fail')}}", "{{trans('lang.notice')}}");
                        return;
                    }
                    else {
                        toastr["success"]("{{trans('lang.save_success')}}", "{{trans('lang.notice')}}");
                    }
                }
            });
        })

        $("#rb_content").click(function () {
            var content=$("#ta_content").attr("content");
            $("#ta_content").val(content);
        })

        $("#rb_url").click(function () {
            var url=$("#ta_content").attr("url");
            $("#ta_content").val(url);
        })

        $( "#select_page_type" ).change(function() {
            var type = $("#select_page_type").val()
            $.ajax({
                type: "GET",
                data: {
                    type: type,
                    _token: "{{csrf_token()}}"
                },
                url: 'get_mobile_page',
                success: function (result) {
                    if(result=='{{config('constants.FAIL')}}'){
                        toastr["error"]("{{trans('lang.no_display_data')}}", "{{trans('lang.notice')}}");
                        return;
                    }
                    else {
                        var obj = JSON.parse(result);
                        $("#ta_content").attr("url", obj.url);
                        $("#ta_content").attr("content", obj.content);

                        var isurl = $('input[name=radio2]:checked', '#div_radio').val();
                        if(isurl == "true") {
                            $("#ta_content").val(obj.url);
                        }
                        else {
                            $("#ta_content").val(obj.content);
                        }
                    }
                }
            });
        });

        $("#btn_preview").click(function () {

            var content=$("#ta_content").val();
            if(content==''){
                toastr["error"]("{{trans('lang.input_content')}}", "{{trans('lang.notice')}}");
                return;
            }

            var type = $("#select_page_type").val()
            var isurl = $('input[name=radio2]:checked', '#div_radio').val();

            if(isurl == "true") {
                var win = window.open(content, '_blank');
                win.focus();
            }
            else {
                $.ajax({
                    type: "GET",
                    data: {
                        type: type,
                        _token: "{{csrf_token()}}"
                    },
                    url: 'get_mobile_page_url',
                    success: function (result) {
                        if(result=='{{config('constants.FAIL')}}'){
                            toastr["error"]("{{trans('lang.no_display_data')}}", "{{trans('lang.notice')}}");
                            return;
                        }
                        else {
                            $('#invisible_form').attr("action", result);
                            $('#new_window_parameter_1').val(content);
                            $('#invisible_form').submit();
                        }
                    }
                });
            }

        })
    </script>
@stop


