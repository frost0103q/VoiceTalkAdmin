<button class="hidden" role="button" data-toggle="modal" data-target="#user_info_modal" id="btn_get_data"></button>
<div class="modal fade bs-modal-lg in" id="user_info_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><strong>회원정보</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label "
                                   style="text-align: right"><strong>이름</strong></label>
                            <div class="col-md-8">
                                <input class="form-control" name="nickname" id="nickname" disabled>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label "
                                   style="text-align: right"><strong>성별</strong></label>
                            <div class="col-md-2">
                                <input class="form-control" name="sex" id="sex" disabled>
                            </div>
                            <label class="col-md-4 control-label"
                                   style="text-align: right"><strong>나이</strong></label>
                            <div class="col-md-2">
                                <input class="form-control" name="age" id="age" disabled>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label"
                                   style="text-align: right"><strong>이메일</strong></label>
                            <div class="col-md-8">
                                <input class="form-control" name="email" id="email" disabled>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label "
                                   style="text-align: right"><strong>전화번호</strong></label>
                            <div class="col-md-8">
                                <input class="form-control" name="phone_number" id="phone_number" disabled>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label"
                                   style="text-align: right"><strong>장치형태</strong></label>
                            <div class="col-md-8">
                                <input class="form-control" name="device_type" id="device_type" disabled>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-4 control-label "
                                   style="text-align: right"><strong>주제</strong></label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="subject" id="subject" disabled></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group form-md-line-input">
                            <div class="profile-userinfo">
                                <img src="" alt="" class="img-responsive" id="profile_img" height="200" width="200">
                            </div>
                        </div>
                        <div class="form-group form-md-line-input" style="text-align: center;margin-bottom: 0">
                            <span class="badge badge-success hidden" id="verify" name="verify">상담인증</span>
                            <span class="badge badge-primary" id="status" name="status"></span>
                        </div>
                        <div class="form-group form-md-line-input" style="text-align: center">
                            <div class="form-group form-md-line-input">
                                <label class="control-label "
                                       style="text-align: right"><strong>적립포인트</strong>&nbsp;&nbsp;&nbsp;<span class="badge badge-danger" id="point"></span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">닫기</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
