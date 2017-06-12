<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" type="image/png"
	href="{!! asset('/images/logo.png')!!}">
<title>{!!env('APP_NAME')!!}</title>


<!-- basic styles -->

<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css')}}" />


<!-- page specific plugin styles -->

<link rel="stylesheet" href="{{ asset('css/jquery-ui-1.10.3.full.min.css')}}" />
<link rel="stylesheet" href="{{ asset('css/datepicker.css')}}" />
<link rel="stylesheet" href="{{ asset('css/ui.jqgrid.css')}}" />

<!-- fonts  -->

<link rel="stylesheet" href="{{ asset('css/ace-fonts.css')}}" />

<!-- ace styles -->

<link rel="stylesheet" href="{{ asset('css/ace.min.css')}}" />
<link rel="stylesheet" href="{{ asset('css/ace-rtl.min.css')}}" />
<link rel="stylesheet" href="{{ asset('css/ace-skins.min.css')}}" />


<!-- ace settings handler -->

<script src="{{ asset('js/ace-extra.min.js')}}"></script>

<script src="{{ asset('js/jquery-2.0.3.min.js')}}"></script>
<script src="{{ asset('js/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/jquery.form.js')}}"></script>



<script src="{{ asset('js/jquery.mobile.custom.min.js')}}"></script>
<script src="{{ asset('js/bootstrap.min.js')}}"></script>
<script src="{{ asset('js/typeahead-bs2.min.js')}}"></script>

<script src="{{ asset('js/jqGrid/jquery.jqGrid.min.js')}}"></script>
<script src="{{ asset('js/jqGrid/i18n/grid.locale-en.js')}}"></script>

<script src="{{ asset('js/excanvas.min.js')}}"></script>
<script src="{{ asset('js/jquery-ui-1.10.3.custom.min.js')}}"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js')}}"></script>
<script src="{{ asset('js/ace-elements.min.js')}}"></script>
<script src="{{ asset('js/ace.min.js')}}"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
	
</head>
<body ng-app='MyApp'>

	@if (env('APP_DEBUG') == true)
        <div class="alert alert-danger text-center no-mg-b">
            You are on the development server! If you are here by mistake, please
            let me know at <strong>admin@gmail.com</strong>
        </div>
	@endif

	<div class="navbar navbar-default" id="navbar">
		<script type="text/javascript">
        try {
            ace.settings.check('navbar', 'fixed')
        } catch (e) {
        }
    	</script>

		<div class="navbar-container" id="navbar-container">
			<div class="navbar-header pull-left">
				<a href="./main.php" class="navbar-brand"> <small> <img
						src="{{ asset('images/logo.png')}}"
						style="display: inline; width: 18px; height: 18px;" />
						{!!config('app.name')!!}
				</small>
				</a>
				<!-- /.brand -->
			</div>
			<!-- /.navbar-header -->

			<div class="navbar-header pull-right" role="navigation">
				<ul class="nav ace-nav">

					<li class="light-blue"><a data-toggle="dropdown" href="#"
						class="dropdown-toggle"> <img class="nav-user-photo"
							src="{{ asset('avatars/avatar2.png')}}" alt="Admin's Photo" /> <span
							class="user-info"> <small>Welcome,</small> Admin
						</span> <i class="icon-caret-down"></i>
					</a>

						<ul
							class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

							<li><a href="/logout"> <i class="icon-off"></i> Logout
							</a></li>

						</ul></li>
				</ul>
				<!-- /.ace-nav -->
			</div>
			<!-- /.navbar-header -->
		</div>
		<!-- /.container -->
	</div>

	<div class="main-container" id="main-container">
		<script type="text/javascript">
        try {
            ace.settings.check('main-container', 'fixed')
        } catch (e) {
        }
    </script>

		<div class="main-container-inner">
			<a class="menu-toggler" id="menu-toggler" href="#"> <span
				class="menu-text"></span>
			</a>

			<div class="sidebar" id="sidebar">
				<script type="text/javascript">
                try {
                    ace.settings.check('sidebar', 'fixed')
                } catch (e) {
                }
            </script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">

					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span> <span class="btn btn-info"></span>

						<span class="btn btn-warning"></span> <span class="btn btn-danger"></span>
					</div>
				</div>
				<!-- #sidebar-shortcuts -->

				<ul class="nav nav-list">
					<li><a href="/home"> <i class="icon-list"></i> <span
							class="menu-text"> User List </span>
					</a></li>

					<li><a href="/rants"> <i class="icon-flag"></i> <span
							class="menu-text"> Rant List </span>
					</a></li>
					
					<li><a href="/rantcomments"> <i class="icon-heart"></i> <span
							class="menu-text"> Comment List </span>
					</a></li>
					
					<li><a href="/notifications"> <i class="icon-music"></i> <span
							class="menu-text"> Notification List </span>
					</a></li>
					
					<li><a href="/setting"> <i class="icon-cogs"></i> <span
							class="menu-text"> Setting </span>
					</a></li>


				</ul>
				<!-- /.nav-list -->

				<div class="sidebar-collapse" id="sidebar-collapse">
					<i class="icon-double-angle-left"
						data-icon1="icon-double-angle-left"
						data-icon2="icon-double-angle-right"></i>
				</div>

				<script type="text/javascript">
                try {
                    ace.settings.check('sidebar', 'collapsed')
                } catch (e) {
                }
            </script>
			</div>

			@yield('content')

			<div class="ace-settings-container" id="ace-settings-container">
				<div class="btn btn-app btn-xs btn-warning ace-settings-btn"
					id="ace-settings-btn">
					<i class="icon-cog bigger-150"></i>
				</div>

				<div class="ace-settings-box" id="ace-settings-box">
					<div>
						<div class="pull-left">
							<select id="skin-colorpicker" class="hide">
								<option data-skin="default" value="#438EB9">#438EB9</option>
								<option data-skin="skin-1" value="#222A2D">#222A2D</option>
								<option data-skin="skin-2" value="#C6487E">#C6487E</option>
								<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
							</select>
						</div>
						<span>&nbsp; Choose Skin</span>
					</div>

					<div>
						<input type="checkbox" class="ace ace-checkbox-2"
							id="ace-settings-navbar" /> <label class="lbl"
							for="ace-settings-navbar"> Fixed Navbar</label>
					</div>

					<div>
						<input type="checkbox" class="ace ace-checkbox-2"
							id="ace-settings-sidebar" /> <label class="lbl"
							for="ace-settings-sidebar"> Fixed Sidebar</label>
					</div>

					<div>
						<input type="checkbox" class="ace ace-checkbox-2"
							id="ace-settings-breadcrumbs" /> <label class="lbl"
							for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
					</div>

					<div>
						<input type="checkbox" class="ace ace-checkbox-2"
							id="ace-settings-rtl" /> <label class="lbl"
							for="ace-settings-rtl"> Right To Left (rtl)</label>
					</div>

					<div>
						<input type="checkbox" class="ace ace-checkbox-2"
							id="ace-settings-add-container" /> <label class="lbl"
							for="ace-settings-add-container"> Inside <b>.container</b>
						</label>
					</div>
				</div>
			</div>
			<!-- /#ace-settings-container -->
		</div>
		<!-- /.main-container-inner -->

		<a href="#" id="btn-scroll-up"
			class="btn-scroll-up btn btn-sm btn-inverse"> <i
			class="icon-double-angle-up icon-only bigger-110"></i>
		</a>
	</div>
	<!-- /.main-container -->

	<!-- 
	<script src="{{ asset('js/jquery-2.0.3.min.js')}}"></script>
	<script src="{{ asset('js/jquery.validate.min.js')}}"></script>
	<script src="{{ asset('js/jquery.form.js')}}"></script>
	
	
	
	<script src="{{ asset('js/jquery.mobile.custom.min.js')}}"></script>
	<script src="{{ asset('js/bootstrap.min.js')}}"></script>
	<script src="{{ asset('js/typeahead-bs2.min.js')}}"></script>
	
	<script src="{{ asset('js/jqGrid/jquery.jqGrid.min.js')}}"></script>
	<script src="{{ asset('js/jqGrid/i18n/grid.locale-en.js')}}"></script>
	
	<script src="{{ asset('js/excanvas.min.js')}}"></script>
	<script src="{{ asset('js/jquery-ui-1.10.3.custom.min.js')}}"></script>
	<script src="{{ asset('js/jquery.slimscroll.min.js')}}"></script>
	<script src="{{ asset('js/ace-elements.min.js')}}"></script>
	<script src="{{ asset('js/ace.min.js')}}"></script>
	 -->
	@yield('scripts')
</body>
</html>