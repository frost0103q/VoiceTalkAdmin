<div class="row" style="padding-left: 17px;padding-right: 17px;">
    <div class="col-md-12">
        <form id="frm_push_reg" class="form-horizontal" method="post" enctype="multipart/form-data">
            <div class="form-group" style="margin-top: 30px;">
                <label class="control-label col-md-2">{{trans('lang.sender_mgr')}}</label>
                <div class="col-md-2">
                    <select class="form-control select2me" id="t_sender_type" name="sender_type">
                        <option value="">{{trans('lang.admin')}}</option>
                        <option value="">{{trans('lang.talk_policy')}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">{{trans('lang.content')}}</label>
                <div class="col-md-8">
                    <textarea class="form-control" id="t_content" name="content" rows="15" style="background: white"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-2">
                    <a class="btn blue" id="talk_notice_save">{{trans('lang.edit_finish')}}</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

</script>