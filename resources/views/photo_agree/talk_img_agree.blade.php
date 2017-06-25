<div class="row">
    <div class="col-md-12">
        <div class="note note-success">
            <p>{{trans('lang.agree')}}</p>
        </div>
    </div>
    @foreach($talk_img as $img_model)
        @if($img_model->checked==config('constants.AGREE'))
            <?php
            $type='talk';
            $all_flag=false;
            ?>
            @include('photo_agree.img')
        @endif
    @endforeach
</div>



