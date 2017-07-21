@extends('layouts.auth')

@section('content')
<div class="content">
		<!-- BEGIN LOGIN FORM -->
		{!! Form::open(['action' => 'Admin\AdminLoginController@doLogin', 'method' => 'post', 'id'=>'loginForm']) !!}
			<h3 class="form-title" style="text-align: center"><strong>{{trans('lang.admin_login')}}</strong></h3>
			<div class="alert alert-danger display-hide">
				<button class="close" data-close="alert"></button>
			<span id="error_content">{{trans('lang.input_correct_email_and_password')}}</span>
			</div>
			<div class="form-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">{{trans('lang.email')}}</label>
				<div class="input-icon">
					<i class="fa fa-user"></i>
					<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="{{trans('lang.input_email')}}" name="email" id="email"/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">{{trans('lang.password')}}</label>
				<div class="input-icon">
					<i class="fa fa-lock"></i>
					<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="{{trans('lang.input_password')}}" name="password" id="password"/>
				</div>
			</div>
			<div class="form-actions">
				<label class="checkbox">
					<input type="checkbox" id="chk_auto_login"/> {{trans('lang.auto_login')}}</label>
				<button type="submit" class="btn green-haze pull-right" id="btn_login">
					{{trans('lang.login')}} <i class="m-icon-swapright m-icon-white"></i>
				</button>
			</div>
			<div class="create-account" style="border-top: none">
				<p>
					{{trans('lang.admin_using_page')}}
				</p>
			</div>
		{!! Form::close() !!}
		<!-- END LOGIN FORM -->
	</div>
@stop

@section('scripts')
<script>

	$(document).ready(function() {

		if($("#chk_auto_login").is(':checked'))
				$(this).trigger('click');

		var error = '<?php if(isset($error)==false){ echo 0;} else {echo $error;}; ?>';

		if(error == '{{config('constants.INVALID_EMAIL')}}') {
			$("#error_content").text('{{trans('lang.incorrect_email')}}');
			$('.alert-danger', $('#loginForm')).show();
		}
		else if(error =='{{config('constants.INVALID_PASSWORD')}}') {
			$("#error_content").text('{{trans('lang.incorrect_password')}}');
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
					required: "{{trans('lang.input_email')}}"
				},
				password: {
					required: "{{trans('lang.input_password')}}"
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

	$("#chk_auto_login").click(function () {
		if($(this).is(':checked')){
			$.ajax({
				url: "auto_login",
				type: "POST",
				data: {
					temp: '',
					_token: "{{csrf_token()}}"
				},
				success: function (result) {
					if(result=="success"){
						window.location.href='home';
					}
				}
			})
		}
	})

</script>
@stop
