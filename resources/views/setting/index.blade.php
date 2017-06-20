@extends('layouts.main')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box green" style="border: none">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-cog"></i>비밀번호변경
					</div>
				</div>
				<div class="portlet-body form">
					<form role="form" class="form-horizontal" id="changePwdForm">
						<div class="form-body" style="padding-top: 30px">
							<div class="form-group form-md-line-input">
								<label class="col-md-2 control-label" for="old_password">이전 비밀번호</label>
								<div class="col-md-4">
									<input type="password" class="form-control" id="old_password">
									<div class="form-control-focus">
									</div>
								</div>
							</div>
							<div class="form-group form-md-line-input">
								<label class="col-md-2 control-label" for="new_password">새 비밀번호</label>
								<div class="col-md-4">
									<input type="password" class="form-control" id="new_password">
									<div class="form-control-focus">
									</div>
								</div>
							</div>
							<div class="form-group form-md-line-input">
								<label class="col-md-2 control-label" for="confirm_password">비밀번호 확인</label>
								<div class="col-md-4">
									<input type="password" class="form-control" id="confirm_password">
									<div class="form-control-focus">
									</div>
								</div>
							</div>
							<div class="form-group form-md-line-input">
								<div class="col-md-offset-2 col-md-10">
									<button type="button" class="btn blue" id="btn_store">비밀번호 변경</button>
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
				toastr["error"]("이전 비밀번호를 입력하세요.", "알림");
				$("#old_password").focus();
				return;
			}
			if($("#new_password").val()==''){
				toastr["error"]("새 비밀번호를 입력하세요.", "알림");
				$("#new_password").focus();
				return;
			}
			if($("#confirm_password").val()==''){
				toastr["error"]("비밀번호 확인을 하세요.", "알림");
				$("#confirm_password").focus();
				return;
			}
			if($("#new_password").val()!=$("#confirm_password").val()){
				toastr["error"]("비밀번호 확인을 다시하세요.", "알림");
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
						toastr["error"]("이전비밀번호가 정확	하지 않습니다.", "알림");
					if(result=='{{config('constants.FAIL')}}')
						toastr["error"]("변경이 실해하였습니다.", "알림");
					if(result=='{{config('constants.SUCCESS')}}')
						toastr["success"]("정확히 변경되었습니다.", "알림");
				}
			});

		})
	</script>
@stop


