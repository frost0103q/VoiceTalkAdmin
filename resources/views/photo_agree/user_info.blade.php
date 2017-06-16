<?php
/**
 * Created by PhpStorm.
 * User: Hrs
 * Date: 6/16/2017
 * Time: 6:21 PM
 */
?>

<button class="hidden" role="button" data-toggle="modal" data-target="#user_info_modal" id="btn_get_data"></button>
<div class="modal fade bs-modal-lg in" id="user_info_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{trans('photo_agree.user_info')}}</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" id="user_data_form">
                    <div class="form-group form-md-line-input">
                        <label class="col-md-2 control-label "
                               style="text-align: right"><strong>{{trans('photo_agree.nickname')}}</strong></label>
                        <div class="col-md-3">
                            <input class="form-control" name="nickname" id="nickname" disabled>
                        </div>
                        <label class="col-md-2 control-label"
                               style="text-align: right"><strong>{{trans('photo_agree.email')}}</strong></label>
                        <div class="col-md-3">
                            <input class="form-control" name="email" id="email" disabled>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <div class="col-md-6">
                            <div class="form-group form-md-line-input">
                                <label class="col-md-4 control-label "
                                       style="text-align: right"><strong>{{trans('photo_agree.profile_img')}}</strong></label>
                                <div class="col-md-6">
                                    <div class="profile-userinfo">
                                        <img src="" alt="" class="img-responsive" id="profile_img">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-md-line-input">
                                <div class="col-md-1"></div>
                                <span class="badge badge-primary" id="status" name="status"></span>
                                <span class="badge badge-success hidden" id="verify" name="verify">{{trans('photo_agree.verify')}}</span>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label class="col-md-2 control-label "
                                       style="text-align: right"><strong>{{trans('photo_agree.sex')}}</strong></label>
                                <div class="col-md-3">
                                    <input class="form-control" name="sex" id="sex" disabled>
                                </div>
                                <label class="col-md-2 control-label"
                                       style="text-align: right"><strong>{{trans('photo_agree.age')}}</strong></label>
                                <div class="col-md-3">
                                    <input class="form-control" name="age" id="age" disabled>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label class="col-md-2 control-label "
                                       style="text-align: right"><strong>{{trans('photo_agree.subject')}}</strong></label>
                                <div class="col-md-8">
                                    <input class="form-control" name="subject" id="subject" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-2 control-label "
                               style="text-align: right; padding-right: 0px;"><strong>{{trans('photo_agree.deposite_time')}}</strong></label>
                        <div class="col-md-2">
                            <input class="form-control" name="deposite_time" id="deposite_time" disabled>
                        </div>
                        <div class="col-md-offset-2 col-md-3">
                            <a class="icon-btn">
                                <i class="fa fa-star"></i>
                                <div>
                                    {{trans('photo_agree.point')}}
                                </div>
                                <span class="badge badge-danger" id="point"></span>
                            </a>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-2 control-label "
                               style="text-align: right"><strong>{{trans('photo_agree.phone_number')}}</strong></label>
                        <div class="col-md-3">
                            <input class="form-control" name="phone_number" id="phone_number" disabled>
                        </div>
                        <label class="col-md-2 control-label"
                               style="text-align: right"><strong>{{trans('photo_agree.device_type')}}</strong></label>
                        <div class="col-md-3">
                            <input class="form-control" name="device_type" id="device_type" disabled>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">{{trans('photo_agree.close')}}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
