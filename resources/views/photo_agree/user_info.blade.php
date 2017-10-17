<button class="hidden" role="button" data-toggle="modal" data-target="#user_info_modal" id="btn_get_data"></button>
<div class="modal fade bs-modal-lg in" id="user_info_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><strong>{{trans('lang.user_info')}}</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label "
                                   style="text-align: right"><strong>{{trans('lang.name')}}</strong></label>
                            <div class="col-md-8">
                                <input class="form-control" name="nickname" id="nickname" disabled>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label "
                                   style="text-align: right"><strong>{{trans('lang.sex')}}</strong></label>
                            <div class="col-md-2">
                                <input class="form-control" name="sex" id="sex" disabled>
                            </div>
                            <label class="col-md-4 control-label"
                                   style="text-align: right"><strong>{{trans('lang.age')}}</strong></label>
                            <div class="col-md-2">
                                <input class="form-control" name="age" id="age" disabled>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label"
                                   style="text-align: right"><strong>{{trans('lang.email')}}</strong></label>
                            <div class="col-md-8">
                                <input class="form-control" name="email" id="email" disabled>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label "
                                   style="text-align: right"><strong>{{trans('lang.telnum')}}</strong></label>
                            <div class="col-md-8">
                                <input class="form-control" name="phone_number" id="phone_number" disabled>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input" style="text-align: center;padding-top: 25px">
                            <div class="form-group form-md-line-input">
                                <label class="control-label col-md-4" style="text-align: right"><strong>{{trans('lang.earn_point')}}</strong></label>
                                <label class="control-label col-md-8" style="text-align: left"><span class="badge badge-danger" id="point"></span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group form-md-line-input">
                            <div class="profile-userinfo">
                                <a id="profile_img_fancy" href="" class="fancybox-button" data-rel="fancybox-button">
                                    <img src="" alt="" class="img-responsive thumbnail" id="profile_img" height="200" width="200">
                                </a>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input" style="text-align: center;margin-bottom: 0">
                            <span class="badge badge-success hidden" id="verify" name="verify">{{trans('lang.talk_insure')}}</span>
                            <span class="badge badge-primary" id="status" name="status"></span>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn default" data-dismiss="modal"><i class="fa fa-rotate-right"></i>&nbsp;{{trans('lang.close')}}</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
