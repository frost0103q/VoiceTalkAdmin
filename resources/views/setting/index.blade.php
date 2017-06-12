@extends('layouts.main')

@section('scripts')
@parent

@stop

@section('content')

<script type="text/javascript">
var error = '<?php if(isset($error)==false){ echo 0;} else {echo $error['error'];}; ?>'; 
var success = '<?php if(isset($success)==false){ echo 0;} else {echo 1;}; ?>';

if(error == {!!config('constants.ERROR_NO_MATCH_INFORMATION')['error']!!}) {
	alert("Account No Exist!");
}
else if(error == {!!config('constants.ERROR_NO_MATCH_PASSWORD')['error']!!}) {
	alert("Password No Match!");
}
else if(success == 1) {
	alert("Successfully saved!");
}

</script>

<div class="main-content">
	<div class="breadcrumbs" id="breadcrumbs">
		<script type="text/javascript">
			try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
		</script>

		<ul class="breadcrumb">
			<li>
				<i class="icon-home home-icon"></i>
				<a href="#">Home</a>
			</li>

			<li>
				<a href="#">Setting</a>
			</li>

		</ul><!-- .breadcrumb -->

		<!--<div class="nav-search" id="nav-search">
			<form class="form-search">
				<span class="input-icon">
					<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
					<i class="icon-search nav-search-icon"></i>
				</span>
			</form>
		</div>-->
	</div>

	<div class="page-content">
		<div class="page-header">
			<h1>
				Setting
			</h1>
		</div><!-- /.page-header -->

		<div class="row">
			<div class="col-xs-12">
				<!-- PAGE CONTENT BEGINS -->

				<!-- <form class="form-horizontal" role="form" id="frmSetting" method="post" action="./setting.act.php">  -->
				{!! Form::open(['action' => 'Admin\AdminSettingController@doSetting', 'method' => 'post', 'id'=>'frmSetting', 'class'=>'form-horizontal', 'role'=>'form']) !!}
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="old_password"> Old Password  </label>

						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<input type="password" name="old_password" id="old_password" class="col-xs-12 col-sm-4" />
							</div>
						</div>
					</div>

					<div class="space-2"></div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="new_password"> New Password  </label>

						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<input type="password" name="new_password" id="new_password" class="col-xs-12 col-sm-4" />
							</div>
						</div>
					</div>

					<div class="space-2"></div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="new_password_confirm"> New Password Confirm  </label>

						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<input type="password" name="new_password_confirm" id="new_password_confirm" class="col-xs-12 col-sm-4" />
							</div>
						</div>
					</div>

					<div class="space-4"><br><br></div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-info" type="submit">
								<i class="icon-ok bigger-110"></i>
								Submit
							</button>

							&nbsp; &nbsp; &nbsp;
							<button class="btn" type="reset">
								<i class="icon-undo bigger-110"></i>
								Reset
							</button>
						</div>
					</div>

				<!-- </form>  -->
				{!! Form::close() !!}
				<!-- /row -->

				<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.page-content -->
</div><!-- /.main-content -->

<script type="text/javascript">
	$(function () {
		$(document).ready(function () {
			$("div#sidebar.sidebar ul.nav.nav-list li").each(function () {
				$(this).removeClass("active");
			});

			$("div#sidebar.sidebar ul.nav.nav-list li:eq(4)").addClass("active");
		});

		$("#frmSetting").validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: true,
			rules: {
				old_password: {
					required: true
				},
				new_password: {
					required: true
				},
				new_password_confirm: {
					required: true,
					equalTo: '#new_password'
				}
			},
			messages: {
				old_password: {
					required: "Old Password cannot be blank"
				},
				new_password: {
					required: "New Password cannot be blank"
				},
				new_password_confirm: {
					required: "New Password Confirm cannot be blank",
					equalTo: "Specify Password Confirm Again"
				}
			},
			highlight: function (e) {
				$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
			},
			success: function (e) {
				$(e).closest('.form-group').removeClass('has-error').addClass('has-info');
				$(e).remove();
			},
			errorPlacement: function (error, element) {
				error.insertAfter(element.parent());
			},
			submitHandler: function (form) {
				form.preventDefault();
			},
			invalidHandler: function (form) {
			}
		});
	});
</script>

@stop