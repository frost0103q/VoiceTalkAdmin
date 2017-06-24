<div class="row" style="padding-left: 17px;padding-right: 17px;">
    <div class="col-md-12">
        <form id="frm_push_reg" class="form-horizontal" method="post" enctype="multipart/form-data">
            <div class="form-group" style="margin-top: 30px">
                <label class="control-label col-md-2">{{trans('lang.title')}}</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" placeholder="" id="b_title" name="title">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">{{trans('lang.content')}}</label>
                <div class="col-md-8">
                    <textarea class="form-control" id="b_content" name="content" rows="15" style="background: white"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">{{trans('lang.img_url')}}</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" placeholder="" id="b_img_url" name="img_url">
                </div>
                <div class="col-md-2">
                    <a class="btn blue" onclick="image_reg(this)">{{trans('lang.img_reg')}}</a>
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