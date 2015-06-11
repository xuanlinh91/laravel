<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="_token" content="{{ csrf_token() }}" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="shortcut icon" href="{{ URL::Asset('assets/image/logo/g-mobile.png') }}">
	@yield('title')
	<!-- He thong truyen dan link cho thu vien Public -->
	<link 	rel="stylesheet" 		href="{{ URL::Asset('assets/css/bootstrap.min.css') }}">
	<link 	rel="stylesheet" 		href="{{ URL::Asset('assets/css/home.css') }}">
	<link   rel="stylesheet"        href="{{ URL::Asset('assets/css/font.css') }}">
	<link   rel="stylesheet"        href="{{ URL::Asset('assets/css/media-max-min.css') }}">
	<link   rel="stylesheet"        href="{{ URL::Asset('assets/css/iframe.css') }}">

	<script type="text/javascript" 	src ="{{ URL::Asset('assets/js/jquery-1.11.2.min.js') }}"></script>
	<script type="text/javascript" 	src ="{{ URL::Asset('assets/js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" 	src ="{{ URL::Asset('assets/js/jquery.scrollbar.min.js') }}"></script>
	<script type="text/javascript" 	src	="{{ URL::asset('assets/js/selection.js') }}"></script>
	<!--<script type="text/javascript" 	src	="{{ URL::asset('assets/js/rangy-core.js') }}"></script>
	<script type="text/javascript" 	src	="{{ URL::asset('assets/js/rangy-textrange.js') }}"></script>-->
	<script type="text/javascript" 	src	="{{ URL::asset('assets/js/createElement.js') }}"></script>
	<script type="text/javascript" 	src	="{{ URL::asset('assets/js/function.js') }}"></script>
	<script type="text/javascript" 	src	="{{ URL::asset('assets/js/lib.js') }}"></script>
	<script type="text/javascript" 	src	="{{ URL::asset('assets/js/main.js') }}"></script>
	<script type="text/javascript" 	src	="{{ URL::asset('assets/js/socket.io-1.2.1.js') }}"></script>
	<script type="text/javascript" 	src	="{{ URL::asset('assets/js/jquery.lazyload.min.js') }}"></script>


<script type="text/javascript" >
// function detectmob() { 
//  if( navigator.userAgent.match(/Android/i)
//  || navigator.userAgent.match(/webOS/i)
//  || navigator.userAgent.match(/iPhone/i)
//  || navigator.userAgent.match(/iPad/i)
//  || navigator.userAgent.match(/iPod/i)
//  || navigator.userAgent.match(/BlackBerry/i)
//  || navigator.userAgent.match(/Windows Phone/i)
//  ){
//     return true;
//   }
//  else {
//     return false;
//   }
// }
// function detectmob_2() {
//    if(window.innerWidth <= 800 && window.innerHeight <= 600) {
//      return true;
//    } else {
//      return false;
//    }
// }
		$(document).ready(function(){
			$("img.video-thumb,img.media-thumb").lazyload({
			    effect : "fadeIn"
			});
		});
		var AuthName = '{{ $_auth["auth_name"]}}';
		var Auth_ava = '{{ $_auth["auth_ava"]}}';
		var Auth_userName = "{{ $_auth['auth_username']}}";
		@if($_auth["auth_permission"] > 1)
			var auth_permission = "{{ $_auth['auth_permission'] }}";
		@endif
       	var socket = io.connect(url_socket+':3000/');
       	socket.on('{{ $_auth["auth_id"] }}', function (data) {
       		if(data.msg == 'bonus'){
       			// var abc = confirm('bạn được hệ thống tặng cho n exp và n gold click yes để nhận');
       			// if(abc){
       			// 	console.log('abc');
       			// }

       		}else {
				//Do something with data
				// console.log(data);
				var count = $('.count-inner').first().text();
				count = parseInt(count);
				$('.count-inner').first().text(count+1);
				// console.log(data.notifi_type);
				show_notification(data.id_notifi, data.ava, data.msg, data.user,data.nickname,data.notifi_type,data.content,data.post_id);
				playSound("{{ URL::asset('assets/sound_notifi/affirmative') }}");
			}
		});
		socket.on('connect', function() {
			socket.send({ChannelName:'{{ $_auth["auth_id"]}}',Message:{username:'{{ $_auth["auth_name"]}}', ava:'{{ $_auth["auth_ava"]}}', nickname:'{{ $_auth["auth_nickname"]}}'}});
		});
		socket.on('message',function(data){
			console.log(data);
		});
		socket.on('list member online', function(data) {
			update_user_online(data);
		});
		socket.on('member offline', function(data) {
			update_user_offline(data);
			socket.send({ChannelName:'{{ $_auth["auth_id"]}}',Message:{username:'{{ $_auth["auth_name"]}}', ava:'{{ $_auth["auth_ava"]}}', nickname:'{{ $_auth["auth_nickname"]}}'}});
		});
</script>
	@include('google/analyticsAPI')
</head>
<body >
	<!-- <div id="fb-root"></div> -->
	<script>
	// (function(d, s, id) {
	//   var js, fjs = d.getElementsByTagName(s)[0];
	//   if (d.getElementById(id)) return;
	//   js = d.createElement(s); js.id = id;
	//   js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.3&appId=1394141534212482";
	//   fjs.parentNode.insertBefore(js, fjs);
	// }(document, 'script', 'facebook-jssdk'));
</script>
	<div class="@if (Request::is('trend')) seconde-page @elseif  (Request::is('/') || Request::is('fresh'))  mini-page @else third-page @endif ">
	@include('templates/navigator')
	@include('templates/secondeNavi')
		<div class="container container-media">
			@yield("content")
		</div>
	</div>
	<div id="sound">
		<audio id="audio_sound" preload="auto">
		</audio>
	</div>
	<div class="new-notification"></div>
	@if(!Auth::check())	
	<script type="text/javascript">
		$(document).ready(function(){
			$(document).on('click','.login-link',function(e){
				e.preventDefault();
				$('#login_modal').modal();
			});
		});
		$(document).on('click','#login_modal>.modal-body',function(e) {
			if(e.target.nodeName == 'DIV'){
				$('#login_modal').modal('hide');
			}
		});

	</script>
	<style type="text/css">
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
		#login_modal>.modal-body {
			background: none;
		}
	</style>
		<div id="login_modal" class="modal fade">
		  <div class="modal-body container">
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
		  </div>
		</div>
	@endif
	<div id="message" class="modal fade ">
		<div class="modal-body container">
			<span class="message"></span>
		</div>
		<div class="modal-footer container">
			<button type="button" data-dismiss="modal" class="btn">close</button>
		</div>
	</div>
	<div id="confirm-report" class="modal fade">
		<div class="modal-body">
			Bạn chác chắn muốn report bài viết này chứ ???
		</div>
		<div class="modal-footer">
			<button type="button" data-dismiss="modal" class="btn btn-primary" id="report">Đồng ý</button>
			<button type="button" data-dismiss="modal" class="btn">Hủy</button>
		</div>
	</div>
	<div id="confirm-delete" class="modal fade">
	  	<div class="modal-body">
	    	Bạn chác chắn muốn xóa bài viết này chứ ??? Khi xóa toàn bộ point liên quan đến bài viết sẽ bị xóa
	  	</div>
	  	<div class="modal-footer">
		    <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Đồng ý</button>
		    <button type="button" data-dismiss="modal" class="btn">Hủy</button>
	  	</div>
	</div>
	@if($_auth['auth_permission'] > 1)
	<div id="confirm-block" class="modal fade">
	  	<div class="modal-body">
	    	<form id="block_Content" method="post" action="{{ URL::route('baneUser') }}" enctype="application/x-www-form-urlencoded" autocomplete="off" class="form-horizontal col-md-12 ">
				<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				<div class="form-group">
			   		<label for="reason" class="col-sm-2 control-label">Lý do :</label>
				    <div class="col-sm-10">
				      <textarea class="form-control" name="reason" id="reason" placeholder="Lý do"></textarea>
				    </div>
				</div>
				<div class="form-group">
			    	<div class="col-sm-offset-2 col-sm-10">
			      		<button type="submit" id="submit-block" data-loading-text="Loading..." class="btn btn-default submit-block-Content" autocomplete="off">block</button>
			      		<button type="button" data-dismiss="modal" class="btn">Hủy</button>
			      		
			    	</div>
			  	</div>
			</form>
	  	</div>
	</div>
	@endif
</body>
</html>
