<?php
if($content != ""){
$idiom_array = explode(',', $content);
$index = 0;
foreach ($idiom_array as $idiom){
$index++;
?>
<div class="col-md-3">
    <div class="md-checkbox">
        <input type="checkbox" id="idiom{{$index}}" value="{{$idiom}}" class="md-check selected_idiom">
        <label for="idiom{{$index}}">
            <span></span>
            <span class="check"></span>
            <span class="box"></span>
            {{$idiom}}</label>
    </div>
</div>
<?php
}
}
?>
<input type="hidden" id="idiom_content_hidden" value="{{$content}}">