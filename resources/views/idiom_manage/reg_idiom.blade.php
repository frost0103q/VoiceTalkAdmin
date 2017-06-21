<div class="row" style="padding-left: 17px;padding-right: 17px;">
    <div class="col-md-12" style="margin-top: 30px;padding-bottom: 30px">
        <div class="col-md-2" style="text-align: right">
            <label class="control-label" style="padding-top: 8px">{{trans('lang.reg_interdict_idiom')}}</label>
        </div>
        <div class="col-md-10">
            <div class="col-md-9">
                <input type="text" class="form-control" placeholder="{{trans('lang.multi_reg_enable_comma')}}" id="txt_add_idiom">
            </div>
            <div class="col-md-3">
                <a class="btn blue" onclick="reg_idiom(this)">{{trans('lang.register')}}</a>
            </div>
        </div>
    </div>
    <div class="col-md-12" style="margin-bottom: 30px">
        <div class="col-md-2" style="text-align: right">
            <label class="control-label" style="padding-top: 8px">{{trans('lang.registered_idiom')}}</label>
        </div>
        <div class="col-md-10">
            <div class="col-md-9" id="idiom_txt_pad">
                <textarea class="form-control" id="txt_idiom_content" rows="15" disabled style="background: white">{{$content}}</textarea>
            </div>
        </div>
    </div>
</div>

<script>
    $("#txt_idiom_content").val('{{$content}}');
    $("#txt_add_idiom").val('');
</script>