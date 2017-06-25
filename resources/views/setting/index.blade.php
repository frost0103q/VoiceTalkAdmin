@extends('layouts.main')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box green" style="border: none">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-cog"></i>{{trans('lang.change_password')}}
					</div>
				</div>
				<div class="portlet-body form">
					<form role="form" class="form-horizontal" id="changePwdForm">
						<div class="form-body" style="padding-top: 30px">
							<div class="form-group form-md-line-input">
								<label class="col-md-2 control-label" for="old_password">{{trans('lang.old_password')}}</label>
								<div class="col-md-4">
									<input type="password" class="form-control" id="old_password">
									<div class="form-control-focus">
									</div>
								</div>
							</div>
							<div class="form-group form-md-line-input">
								<label class="col-md-2 control-label" for="new_password">{{trans('lang.new_password')}}</label>
								<div class="col-md-4">
									<input type="password" class="form-control" id="new_password">
									<div class="form-control-focus">
									</div>
								</div>
							</div>
							<div class="form-group form-md-line-input">
								<label class="col-md-2 control-label" for="confirm_password">{{trans('lang.confirm_password')}}</label>
								<div class="col-md-4">
									<input type="password" class="form-control" id="confirm_password">
									<div class="form-control-focus">
									</div>
								</div>
							</div>
							<div class="form-group form-md-line-input">
								<div class="col-md-offset-2 col-md-10">
									<button type="button" class="btn blue" id="btn_store">{{trans('lang.change_password')}}</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script>
		$("#btn_store").click(function () {
			if($("#old_password").val()==''){
				toastr["error"]("{{trans('lang.input_old_password')}}", "{{trans('lang.notice')}}");
				$("#old_password").focus();
				return;
			}
			if($("#new_password").val()==''){
				toastr["error"]("{{trans('lang.input_new_password')}}", "{{trans('lang.notice')}}");
				$("#new_password").focus();
				return;
			}
			if($("#confirm_password").val()==''){
				toastr["error"]("{{trans('lang.confirm_password_')}}", "{{trans('lang.notice')}}");
				$("#confirm_password").focus();
				return;
			}
			if($("#new_password").val()!=$("#confirm_password").val()){
				toastr["error"]("{{trans('lang.retry_confirm_password')}}", "{{trans('lang.notice')}}");
				$("#confirm_password").focus();
				return;
			}

			$.ajax({
				url: "do_setting",
				type: "get",
				data: {
					old_password : $("#old_password").val(),
					new_password : $("#new_password").val()
				},
				success: function (result) {
					if(result=='{{config('constants.INVALID_PASSWORD')}}')
						toastr["error"]("{{trans('lang.incorrect_old_password')}}", "{{trans('lang.notice')}}");
					if(result=='{{config('constants.FAIL')}}')
						toastr["error"]("{{trans('lang.fail_change_password')}}", "{{trans('lang.notice')}}");
					if(result=='{{config('constants.SUCCESS')}}')
						toastr["success"]("{{trans('lang.success_change_password')}}", "{{trans('lang.notice')}}");
				}
			});

		})
	</script>
@stop


