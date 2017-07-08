@extends('layouts.main')

@section('content')
    <link href="../assets/global/plugins/icheck/skins/all.css" rel="stylesheet" type="text/css" />
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
                    <div class="col-md-4" style="margin-top: 6px;">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="icheck-inline" id="div_radio">
                                    <label>
                                        <input type="radio" id="rb_content" name="radio2" checked class="icheck" data-radio="iradio_flat-grey" value="false"> {{trans('lang.content')}} </label>
                                    <label>
                                        <input type="radio" id="rb_url" name="radio2" class="icheck" data-radio="iradio_flat-grey"  value="true">{{trans('lang.url')}} </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <textarea class="form-control" rows="16" id="ta_content"></textarea>

                    <div class="row margin-top-20">
                        <div class="col-md-offset-2 col-md-8 text-center">
                            <button class="btn btn-primary" id="btn_save"> {{trans('lang.save')}}</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="btn_preview">{{trans('lang.preview')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/global/plugins/icheck/icheck.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/icheck/icheck.min.js" type="text/javascript"></script>
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

        $("#btn_preview").click(function () {

            var content=$("#ta_content").val();
            if(content==''){
                toastr["error"]("{{trans('lang.input_content')}}", "{{trans('lang.notice')}}");
                return;
            }


        })
    </script>
@stop


