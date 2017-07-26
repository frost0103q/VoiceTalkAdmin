<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <li class="sidebar-toggler-wrapper" style="margin-bottom: 15px">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler">
                </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>
            <li menu_index="0" class="start">
                <a href="home">
                    <i class="fa fa-home"></i>
                    <span class="title">{{trans('lang.homepage')}}</span>
                </a>
            </li>
            <li menu_index="1" class="start">
                <a href="agree_photo">
                    <i class="fa fa-photo"></i>
                    <span class="title">{{trans('lang.photo_agree')}}</span>
                </a>
            </li>
            <li menu_index="2">
                <a href="agree_voice">
                    <i class="fa fa-microphone"></i>
                    <span class="title">{{trans('lang.voice_agree')}}</span>
                </a>
            </li>
            <li menu_index="3">
                <a href="cash_question">
                    <i class="fa fa-credit-card"></i>
                    <span class="title">{{trans('lang.cash_question')}}</span>
                </a>
            </li>
            <li menu_index="4">
                <a href="talk_user_mgr">
                    <i class="fa fa-list"></i>
                    <span class="title">{{trans('lang.talk')}}/{{trans('lang.user_manage')}}</span>
                </a>
            </li>
            <li menu_index="5">
                <a href="notice_mgr">
                    <i class="fa fa-edit"></i>
                    <span class="title">{{trans('lang.notice_manage')}}</span>
                </a>
            </li>
            <li menu_index="6">
                <a href="withdraw">
                    <i class="fa fa-bank"></i>
                    <span class="title">{{trans('lang.cach_manage')}}</span>
                </a>
            </li>
            <li menu_index="7">
                <a href="statistic">
                    <i class="fa fa-line-chart"></i>
                    <span class="title">{{trans('lang.statistic_manage')}}</span>
                </a>
            </li>
            <li menu_index="8">
                <a href="admin_notice">
                    <i class="fa fa-file-text-o"></i>
                    <span class="title">{{trans('lang.admin_notice')}}</span>
                </a>
            </li>
            <li menu_index="9">
                <a href="interdict_idiom_reg">
                    <i class="fa fa-file-word-o"></i>
                    <span class="title">{{trans('lang.interdict_manage')}}</span>
                </a>
            </li>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>