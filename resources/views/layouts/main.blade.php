<!DOCTYPE html>

<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>Metronic | Page Layouts - Blank Page</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/uniform.default.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/toastr.min.css')}}"/>
    <link href="{{ asset('css/profile.css')}}" rel="stylesheet" type="text/css"/>

    <!-- END PAGE LEVEL STYLES -->

    <!-- BEGIN THEME STYLES -->
    <link href="{{ asset('css/components.css')}}" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/plugins.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/layout.css')}}" rel="stylesheet" type="text/css"/>
    <link id="style_color" href="{{ asset('css/darkblue.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/custom.css')}}" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>

    <style>
        .page-content{
            background-color: #F1F3FA;
        }

        .portlet > .portlet-title > .caption {
            float: left;
            display: inline-block;
            font-size: 16px;
            line-height: 21px;
            padding: 10px 0;
            font-weight: 600;
        }
    </style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed page-quick-sidebar-over-content">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="index.html">
                <img src="{{ asset('img/logo.png')}}" alt="logo" class="logo-default"
                     style="height: 33px;margin-top: 8px;"/>
            </a>
            <div class="menu-toggler sidebar-toggler hide">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
        </div>
        <!-- END LOGO -->

        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                       data-close-others="true">
                        <i class="icon-user"></i>
                        <span class="username username-hide-on-mobile">	Admin </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="/setting">
                                <i class="fa fa-cog"></i> 비밀번호변경 </a>
                        </li>
                        <li>
                            <a href="/logout">
                                <i class="fa fa-power-off"></i> 로그아웃 </a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
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
                <li menu_index="1" class="start">
                    <a href="/agree_photo">
                        <i class="fa fa-photo"></i>
                        <span class="title">사진승인</span>
                    </a>
                </li>
                <li menu_index="2">
                    <a href="#">
                        <i class="fa fa-microphone"></i>
                        <span class="title">Voice승인</span>
                    </a>
                </li>
            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content">
            <!-- BEGIN PAGE CONTENT-->
            @yield('content')
            <!-- END PAGE CONTENT-->
        </div>
    </div>
    <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner">
        2017 &copy; Voice Talk
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="{{ asset('scripts/respond.min.js')}}"></script>
<script src="{{ asset('scripts/excanvas.min.js')}}"></script>
<![endif]-->
<script src="{{ asset('scripts/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('scripts/jquery-migrate.min.js')}}" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="{{ asset('scripts/jquery-ui.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('scripts/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('scripts/bootstrap-hover-dropdown.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('scripts/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('scripts/jquery.blockui.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('scripts/jquery.cokie.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('scripts/jquery.uniform.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('scripts/bootstrap-switch.min.js')}}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('scripts/toastr.min.js')}}"></script>
<script src="{{ asset('scripts/ui-toastr.js')}}"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script src="{{ asset('scripts/metronic.js')}}" type="text/javascript"></script>
<script src="{{ asset('scripts/layout.js')}}" type="text/javascript"></script>
<script src="{{ asset('scripts/quick-sidebar.js')}}" type="text/javascript"></script>
<script src="{{ asset('scripts/demo.js')}}" type="text/javascript"></script>
<script>
    jQuery(document).ready(function () {
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
    });
</script>

<?php
if(isset($menu_index)){
    for($i = 1; $i <= 25;$i++){
        if ($i == $menu_index){
        ?>
        <script>
            $("li[menu_index='<?=$i?>']").addClass('active').addClass("open");
            $("li[menu_index='<?=$i?>'] a").append('<span class="selected" style="border-right: 12px solid #f1f3fa;"></span>');
        </script>
        <?php
        }
    }
}
?>

@yield('scripts')
        <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>