@extends("main")
@section('title')
<title>Đăng Nhập</title>
@endsection
@section('content')
<style type="text/css">
	.wrap-login{
		padding-top: 66px;
	}
	#form-login{
		background: #fff;
		padding: 1px 15px 10px 15px !important;
		webkit-border-radius: 3px;
		moz-border-radius: 3px;
		border-radius: 3px;
		-moz-box-shadow: 0 0 1px 0 rgba(0,0,0,0.1),0 1px 2px 0 rgba(0,0,0,0.2);
		-webkit-box-shadow: 0 0 1px 0 rgba(0,0,0,0.1),0 1px 2px 0 rgba(0,0,0,0.2);
		box-shadow: 0 0 1px 0 rgba(0,0,0,0.1),0 1px 2px 0 rgba(0,0,0,0.2);
	}
	.no-float{
		float: none;
	}
	.item-singup{
		margin: 0px 0px 8px 0px;
	}
	.btn-facbook,.btn-google{
		border-color: rgba(36,61,89,0.25) !important;
		border-radius: 4px;
		font-weight: bold;
	}
	.btn-g-signup{
	}
	.btn-g-signup-btom{
		border-radius: 5px;
	}
	.btn-facbook:hover,.btn-google:hover,.btn-facbook:focus,.btn-google:focus{
		border-color: #fff;
		border-radius: 5px;
	}
	.btn-google{
		background-color: #d14836;
	}
	.btn-google:hover,.btn-google:focus{
		background-color: #c53727;
	}
	.no-padding{
		padding-right: 0px;
		padding-left: 0px;
	}
</style>
<div class="col-md-5 center-block no-float wrap-login">
	<form method="post" action="{{Asset('login')}}" id="form-login" >
		<h2>Đăng nhập</h2>
		<div class="form-group">
			<div class="col-md-6 no-padding">
				{{ HTML::linkRoute('loginFacebook','Login With Facebook',array(),array('class'=>' btn btn-lg btn-primary item-singup btn-facbook btn-g-signup col-xs-12 no-padding')) }}
				<div class="clearfix"></div>
			</div>
			<div class="col-md-6 no-padding">
				{{ HTML::linkRoute('indexGoogle','Login With Google',array(),array('class'=>' btn btn-lg btn-primary col-xs-12 item-singup btn-google pull-right btn-g-signup no-padding')) }}
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<!-- <input type="text" name="user_input" id="user_input" placeholder="Username or email" class="form-control"/> -->
		<input type="text" name="user_type" id="user_type" placeholder="Username or email" class="form-control item-singup" autocomplete = "off"/>
		<input type="password" name="password" id="password" placeholder="Password" class="form-control item-singup" autocomplete = "off"/>
		@if(isset($error_message))
		<label class="error">{{$error_message}}</label>
		@endif
		<?php // thông báo lỗi đăng nhập?>
		<button class="btn btn-lg btn-primary col-md-12 item-singup btn-g-signup-btom "><b>Đăng nhập</b></button>
		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		<p> Bạn chưa có tài khoản hãy <b><a href="{{Asset('login')}}">Đăng ký</a></b></p>
		<div class="clearfix"></div>
	</form>
</div>
<div class="clearfix"></div>

@endsection