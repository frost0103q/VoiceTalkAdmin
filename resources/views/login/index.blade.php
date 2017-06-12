@extends('layouts.auth')

@section('scripts')
@parent

@stop

@section('content')

<script type="text/javascript">
var error = '<?php if(isset($error)==false){ echo 0;} else {echo $error['error'];}; ?>'; 

if(error == {!!config('constants.ERROR_NO_MATCH_INFORMATION')['error']!!}) {
	alert("Account No Exist!");
}
else if(error == {!!config('constants.ERROR_NO_MATCH_PASSWORD')['error']!!}) {
	alert("Password No Match!");
}

</script>


<div class="main-container">
	<div class="main-content">
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">
				<div class="login-container">
					<div class="center">
						<h1>
							<i class="icon-leaf green"></i>
							<span class="red">{!!config('app.name')!!}</span>
						</h1>
					</div>

					<div class="space-6"></div>

					<div class="position-relative">
						<div id="login-box" class="login-box visible widget-box no-border">
							<div class="widget-body">
								<div class="widget-main">
									<h4 class="header blue lighter bigger">
										<!--<i class="icon-coffee green"></i>-->
										Please Enter Your Information
									</h4>

									<div class="space-6"></div>

									<!--  <form id="signupForm" method="post" action="login.act.php"> -->
									{!! Form::open(['action' => 'Admin\AdminLoginController@doLogin', 'method' => 'post', 'id'=>'signupForm']) !!}
										<fieldset>
											<label class="block clearfix">
												<span class="block input-icon input-icon-right">
													<input type="text" id="email" name="email" class="form-control" placeholder="eMail" maxlength="30" />
													<i class="icon-user"></i>
												</span>
											</label>

											<label class="block clearfix">
												<span class="block input-icon input-icon-right">
													<input type="password" id="password" name="password" class="form-control" placeholder="Password" maxlength="20" />
													<i class="icon-lock"></i>
												</span>
											</label>

											<div class="space"></div>

											<div class="clearfix align-center ">
												<!--<label class="inline">
													<input type="checkbox" class="ace" />
													<span class="lbl"> Remember Me</span>
												</label>-->

												<button type="submit" id="btnLogin" class="width-35 btn btn-sm btn-primary">
													<i class="icon-key"></i>
													Login
												</button>
											</div>

											<div class="space-4"></div>
										</fieldset>
									<!-- </form> -->
									{!! Form::close() !!}

									
								</div><!-- /widget-main -->

								<div class="toolbar clearfix">
									<!--<div>
										<a href="#" onclick="show_box('forgot-box'); return false;" class="forgot-password-link">
											<i class="icon-arrow-left"></i>
											I forgot my password
										</a>
									</div>

									<div>
										<a href="#" onclick="show_box('signup-box'); return false;" class="user-signup-link">
											I want to register
											<i class="icon-arrow-right"></i>
										</a>
									</div>-->
								</div>
							</div><!-- /widget-body -->
						</div><!-- /login-box -->

					</div><!-- /position-relative -->
				</div>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div>
</div><!-- /.main-container -->

<script type="text/javascript">
	$(function() {

		$("#signupForm").validate({
			rules: {
				email: {
					required: true,
					email: true
				},
				password: {
					required: true,
					minlength: 3
				}
			},
			messages: {
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 3 characters long"
				},
				email: "Please enter a valid email address"
			}
		});

	});
</script>

@stop