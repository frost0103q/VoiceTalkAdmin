@extends('layouts.auth')

@section('content')
<div class="content">
		<!-- BEGIN LOGIN FORM -->
		{!! Form::open(['action' => 'Admin\AdminLoginController@doLogin', 'method' => 'post', 'id'=>'loginForm']) !!}
			<h3 class="form-title" style="text-align: center"><strong>관리자 로그인</strong></h3>
			<div class="alert alert-danger display-hide">
				<button class="close" data-close="alert"></button>
			<span id="error_content">
			이메일 혹은 비밀번호를 정확히 입력하세요.</span>
			</div>
			<div class="form-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">이메일</label>
				<div class="input-icon">
					<i class="fa fa-user"></i>
					<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="이메일을 입력하세요." name="email" id="email"/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">비밀번호</label>
				<div class="input-icon">
					<i class="fa fa-lock"></i>
					<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="비밀번호를 입력하세요." name="password" id="password"/>
				</div>
			</div>
			<div class="form-actions">
				<label class="checkbox">
					<input type="checkbox" name="remember" value="1"/> 자동 로그인</label>
				<button type="submit" class="btn green-haze pull-right" id="btn_login">
					로그인 <i class="m-icon-swapright m-icon-white"></i>
				</button>
			</div>
			<div class="create-account" style="border-top: none">
				<p>
					관리자만 리용하는 관리페이지입니다.
				</p>
			</div>
		{!! Form::close() !!}
		<!-- END LOGIN FORM -->
	</div>
@stop

@section('scripts')
<script>

	$(document).ready(function() {

		var error = '<?php if(isset($error)==false){ echo 0;} else {echo $error;}; ?>';

		if(error == '{{config('constants.INVALID_EMAIL')}}') {
			$("#error_content").text('이메일이 정확하지않습니다.');
			$('.alert-danger', $('#loginForm')).show();
		}
		else if(error =='{{config('constants.INVALID_PASSWORD')}}') {
			$("#error_content").text('비밀번호가 정확하지않습니다.');
			$('.alert-danger', $('#loginForm')).show();
		}

		$('#loginForm').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: true, // do not focus the last invalid input
			rules: {
				email: {
					required: true
				},
				password: {
					required: true
				},
				remember: {
					required: false
				}
			},

			messages: {
				email: {
					required: "이메일을 입력하세요."
				},
				password: {
					required: "비밀번호를 입력하세요."
				}
			},

			invalidHandler: function(event, validator) { //display error alert on form submit
				/*$('.alert-danger', $('.login-form')).show();*/
			},

			highlight: function(element) { // hightlight error inputs
				$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
			},

			success: function(label) {
				label.closest('.form-group').removeClass('has-error');
				label.remove();
			},

			errorPlacement: function(error, element) {
				error.insertAfter(element.closest('.input-icon'));
			},

			submitHandler: function(form) {
				form.submit(); // form validation success, call ajax form submit
			}
		});
	});

</script>
@stop
