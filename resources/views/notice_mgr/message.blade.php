<div class="row" style="padding-left: 17px;padding-right: 17px;">
    <div class="col-md-12">
        <form id="frm_push_reg" class="form-horizontal" method="post" enctype="multipart/form-data">
            <div class="form-group" style="margin-top: 30px;">
                <label class="control-label col-md-2">{{trans('lang.sender_mgr')}}</label>
                <div class="col-md-2">
                    <select class="form-control select2me" id="m_sender_type" name="sender_type">
                        <option value="">{{trans('lang.admin')}}</option>
                        <option value="">{{trans('lang.talk_policy')}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">{{trans('lang.send_to')}}</label>
                <div class="col-md-2">
                    <select class="form-control select2me" id="m_receive_type" name="receive_type">
                        <option value="">{{trans('lang.special_user')}}</option>
                        <option value="">{{trans('lang.common_user')}}</option>
                        <option value="">{{trans('lang.talk_user')}}</option>
                        <option value="">{{trans('lang.all_user')}}</option>
                    </select>
                </div>
                <label class="control-label col-md-1">{{trans('lang.user_id')}}</label>
                <div class="col-md-2">
                    <input type="text" id="m_user_id" name="user_id" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">{{trans('lang.sentecen_to')}}</label>
                <div class="col-md-2">
                    <select class="form-control select2me" id="m_sentence_type" name="sentence_type">
                        <option value="">{{trans('lang.no_passbook_guide')}}</option>
                        <option value="">{{trans('lang.lost_pw')}}</option>
                        <option value="">{{trans('lang.declare_recep')}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">{{trans('lang.title')}}</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" placeholder="" id="m_title" name="title">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">{{trans('lang.content')}}</label>
                <div class="col-md-8">
                    <textarea class="form-control" id="m_content" name="content" rows="15" style="background: white"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">{{trans('lang.img_url')}}</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" placeholder="" id="m_img_url" name="img_url">
                </div>
                <div class="col-md-2">
                    <a class="btn blue" onclick="image_reg(this)">{{trans('lang.img_reg')}}</a>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-2">
                    <a class="btn blue" id="message_save">{{trans('lang.edit_finish')}}</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

</script>