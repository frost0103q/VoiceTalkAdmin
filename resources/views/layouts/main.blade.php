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

    <script src="{{ asset('scripts/jquery.min.js')}}" type="text/javascript"></script>

    <script>
        jQuery(document).ready(function () {
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            QuickSidebar.init(); // init quick sidebar
            Demo.init(); // init demo features
        });
    </script>

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
@include('layouts.header')
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    @include('layouts.menu')
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
@include('layouts.footer')
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="{{ asset('scripts/respond.min.js')}}"></script>
<script src="{{ asset('scripts/excanvas.min.js')}}"></script>
<![endif]-->
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


<?php
if(isset($menu_index)){
    for($i = 1; $i <= 10;$i++){
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

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>