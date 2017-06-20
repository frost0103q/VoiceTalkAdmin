
<button class="hidden" role="button" data-toggle="modal" data-target="#talk_confirm_modal" id="btn_talk_confirm"></button>
<div class="modal fade bs-modal-lg in" id="talk_confirm_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><strong>{{trans('lang.A_talk')}}</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div  class="col-md-12">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label "
                                   style="text-align: right"><strong>{{trans('lang.talk_img')}}</strong></label>
                            <div class="col-md-9" style="margin-bottom: 25px;">
                                <img id="talk_img" class="img-responsive" src="{{asset('uploads/profile-img.jpg')}}" style="max-height: 300px">
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label "
                                   style="text-align: right"><strong>{{trans('lang.subject')}}</strong></label>
                            <div class="col-md-9">
                                <input class="form-control" name="talk_subject" id="talk_subject" disabled>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label "
                                   style="text-align: right"><strong>{{trans('lang.greeting')}}</strong></label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="greeting" id="greeting" disabled></textarea>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label "
                                   style="text-align: right"><strong>{{trans('lang.voice')}}</strong></label>
                            <div class="col-md-9">
                                <input class="form-control" name="voice_type" id="voice_type" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12" style="margin-top: 10px;padding-left: 0;padding-right: 0">
                                <label class="col-md-3 control-label"
                                       style="text-align: right"><strong>{{trans('lang.audio')}}</strong></label>
                                <div class="col-md-9">
                                    <audio id="voice_path" src="{{ asset('uploads/1.mp3')}}" controls></audio>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label "
                                   style="text-align: right"><strong>{{trans('lang.nickname')}}</strong></label>
                            <div class="col-md-9">
                                <input class="form-control" name="talk_nickname" id="talk_nickname" disabled>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-3 control-label "
                                   style="text-align: right"><strong>{{trans('lang.age')}}</strong></label>
                            <div class="col-md-9">
                                <input class="form-control" name="talk_age" id="talk_age" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal"><i class="fa fa-rotate-right"></i>&nbsp;{{trans('lang.close')}} </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
