
<button class="hidden" role="button" data-toggle="modal" data-target="#voice_confirm_modal" id="btn_voice_confirm"></button>
<div class="modal fade bs-modal-lg in" id="voice_confirm_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><strong>{{trans('lang.hear_voice')}}</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div  class="col-md-12">
                        <div class="form-group" style="padding-top: 20px">
                            <label class="col-md-3 control-label"
                                   style="text-align: right"><strong>{{trans('lang.audio')}}</strong></label>
                            <div class="col-md-9">
                                <audio id="talk_voice_path" src="" controls></audio>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-12 control-label "
                                   style="text-align: center;padding-top: 15px"><strong>{{trans('lang.confirm_voice_for_test')}}</strong></label>
                        </div>
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
