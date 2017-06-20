<div class="row">
    <div class="col-md-12">
        <div class="note note-success">
            <p>{{trans('lang.agree_voice')}}</p>
        </div>
    </div>
    @foreach($talk_voice as $voice_model)
        @if($voice_model->checked==config('constants.AGREE'))
            @include('voice_agree.voice')
        @endif
    @endforeach
</div>



