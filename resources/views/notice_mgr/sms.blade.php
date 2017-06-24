<div class="row" style="padding-left: 17px;padding-right: 17px;">
    <div class="col-md-12">
        <form id="frm_push_reg" class="form-horizontal" method="post" enctype="multipart/form-data">
            <div class="form-group" style="margin-top: 30px">
                <label class="control-label col-md-2">{{trans('lang.sender_number')}}</label>
                <label class="control-label col-md-2" style="text-align: left"><strong>070-123-580</strong></label>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">{{trans('lang.receive_number')}}</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="" id="s_receive_number" name="receive_number">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">{{trans('lang.content')}}</label>
                <div class="col-md-8">
                    <textarea class="form-control" id="s_content" name="content" rows="15" style="background: white"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-2">
                    <a class="btn blue" id="banner_save">{{trans('lang.edit_finish')}}</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

</script>