<?php
/**
 * Created by PhpStorm.
 * User: Hrs
 * Date: 6/16/2017
 * Time: 6:23 PM
 */
?>

<button class="hidden" role="button" data-toggle="modal" data-target="#talk_confirm_modal" id="btn_talk_confirm"></button>
<div class="modal fade bs-modal-lg in" id="talk_confirm_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{trans('talk_confirm')}}</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" id="user_data_form">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">{{trans('photo_agree.close')}}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
