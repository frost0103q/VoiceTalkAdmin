<div class="row">
    <div class="col-md-12">
        <div class="note note-success">
            <p>거부</p>
        </div>
    </div>
    @foreach($user_profile_img as $img_model)
        @if($img_model->checked==config('constants.DISAGREE'))
            <?php
            $type='profile';
            $all_flag=false;
            ?>
            @include('photo_agree.img')
        @endif
    @endforeach
</div>



