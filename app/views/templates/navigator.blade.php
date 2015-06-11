<?php
	$notification = new NotificationController();
	$count = $notification->count_new_notification($_auth['auth_id']);
?>
<style type="text/css">
.open .caret{
	border-bottom: 4px dashed;
	border-top: 0px;
}
.caret{
	margin-left: 4px;
}
.open > .dropdown-menu {
  -webkit-transform: scale(1, 1);
  -moz-transform: scale(1, 1);
  transform: scale(1, 1);
  opacity:1;
}
.dropdown-menu {
  opacity:.3;
  display: block;
  -webkit-transform-origin: top;
  -moz-transform-origin: top;
  transform-origin: top;
  -webkit-animation-fill-mode: forwards;
  -moz-animation-fill-mode: forwards;
  animation-fill-mode: forwards;
  -webkit-transform: scale(1, 0);
  -moz-transform: scale(1, 0);
  transform: scale(1, 0);
  transition: all 111ms linear;
  -webkit-transition: all 111ms linear;
  -moz-transition: all 111ms linear;
}
.login-link{
  padding: 0 8px !important;
}
.nav-label>i{
	font-size: 19px;
}
.nav-action-item>li:first-child>a>span>i{
	margin-right: 3px;
}
.nav-action-item>li:nth-child(2)>a>span>i{
	margin-right: 3px;
}
.notifi-icon{
	position: relative;
 	font-size: 19px;
}
.count-inner {
	border-radius: :2px;
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
	box-shadow: 0 1px 1px rgba(0, 0, 0, .7);
	-moz-box-shadow: 0 1px 1px rgba(0, 0, 0, .7);
	-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .7);
	background-color: #dc0d17;
	background-image: linear-gradient(linear, center top, center bottom, from(#fa3c45), to(#dc0d17));
	background-image: -o-gradient(linear, center top, center bottom, from(#fa3c45), to(#dc0d17));
	background-image: -moz-gradient(linear, center top, center bottom, from(#fa3c45), to(#dc0d17));
	background-image: -webkit-gradient(linear, center top, center bottom, from(#fa3c45), to(#dc0d17));
	background-image: -webkit-linear-gradient(#fa3c45, #dc0d17);
	background-image: -moz-linear-gradient(#fa3c45, #dc0d17);
	background-image: -o-linear-gradient(#fa3c45, #dc0d17);
	background-image: linear-gradient(#fa3c45, #dc0d17);
	color: #fff;
	padding: 1px 3px;
	text-shadow: 0 -1px 0 rgba(0, 0, 0, .4);
	-webkit-background-clip: padding-box;
	-moz-background-clip: padding-box;
	-o-background-clip: padding-box;
	background-clip: padding-box;
	display: inline-block;
	font-size: 11px;
	-webkit-font-smoothing: subpixel-antialiased;
	-moz-font-smoothing: subpixel-antialiased;
	-o-font-smoothing: subpixel-antialiased;
	font-smoothing: subpixel-antialiased;
	line-height: normal;
	min-height: 13px;
}
.count-inner {
  position: absolute;
  right: -1px;
  top: -2px;
}

#dd-cate .dropdown-menu::before {
  position: absolute;
  top: -9px;
  left: 21px;
  display: inline-block;
  border-right: 9px solid transparent;
  border-bottom: 9px solid #CCC;
  border-left: 9px solid transparent;
  border-bottom-color: rgba(0, 0, 0, 0.2);
  content: '';
}

#dd-cate .dropdown-menu::after {
  position: absolute;
  top: -8px;
  left: 22px;
  display: inline-block;
  border-right: 8px solid transparent;
  border-bottom: 8px solid white;
  border-left: 8px solid transparent;
  content: '';
}

#dd-setting .dropdown-menu::before {
  position: absolute;
  top: -9px;
  left: 298px;
  display: inline-block;
  border-right: 9px solid transparent;
  border-bottom: 9px solid #CCC;
  border-left: 9px solid transparent;
  border-bottom-color: rgba(0, 0, 0, 0.2);
  content: '';
}

#dd-setting .dropdown-menu::after {
  position: absolute;
  top: -8px;
  left: 299px;
  display: inline-block;
  border-right: 8px solid transparent;
  border-bottom: 8px solid white;
  border-left: 8px solid transparent;
  content: '';
}
#dd-notifi .dropdown-menu::before {
  position: absolute;
  top: -9px;
  left: 261px;
  display: inline-block;
  border-right: 9px solid transparent;
  border-bottom: 9px solid #CCC;
  border-left: 9px solid transparent;
  border-bottom-color: rgba(0, 0, 0, 0.2);
  content: '';
}

#dd-notifi .dropdown-menu::after {
  position: absolute;
  top: -8px;
  left: 262px;
  display: inline-block;
  border-right: 8px solid transparent;
  border-bottom: 8px solid white;
  border-left: 8px solid transparent;
  content: '';
}
</style>
<div id="navigator">
	<div id="" class="media-max-min container container-media top-nav">
		<!-- <div class="nav-logo nav-logo-mobile">
			<a href="{{ URL::route('/') }}">
				<img src="{{ URL::Asset('assets/image/logo/g-mobile.png') }}">
			</a>
		</div> -->
		<!-- <div class="nav-logo display-item-logo">
			<a href="{{ URL::route('/') }}">
				<img src="{{ URL::Asset('assets/image/logo/aotusuma.png') }}">
			</a>
		</div> -->
		<ul class="nav-action-item ">
			<li class="">
				<a href="{{ URL::route('/') }}" class="">
					<span class="nav-label @if (Request::is('/')) nav-action-item-active @endif"><i class="icon-home-outline"></i><span class="display-item ">Đang Hot</span></span>
				</a>
			</li>
			<li class="">
				<a href="{{{ URL::route('fresh') }}}" class="">
					<span class="nav-label @if (Request::is('fresh')) nav-action-item-active @endif"><i class=" icon-users-3"></i><span class="display-item ">Mới</span></span>
				</a>
			</li>
			<li class=" ">
				<a href="{{ URL::route('trend')}}" class="">
					<span class="nav-label @if (Request::is('trend'))  nav-action-item-active @elseif (Request::is('hashtag/*')) nav-action-item-active  @else abc @endif "><i class=" icon-hash"></i><span class="display-item ">KhámPhá</span></span>
				</a>
			</li>
			<li id="dd-cate" class="active dropdown" data-global-action="connect" class="">
				<a href="#" data-toggle="dropdown" class="nav-label dropdown-toggle"> 
					<span class="nav-label @if (Request::is('categories')) nav-action-item-active @elseif (Request::is('g/*')) nav-action-item-active @endif"><i class=" icon-menu"></i><span class="display-item ">Mục</span><span class="caret"></span></span>
				</a>
				<div class="dropdown-menu dropdown-cate">
                    <div class="dd-cate">
                    	<ul class="nav-cate-list">
		                	@foreach($categories as $item )
		                	<li><a href="{{ url('/g/'.$item->cate_code )}}"><span>{{ $item->name }}</span></a></li>
							@endforeach
		                	<li><a href="{{ URL::route('categories') }}"><span><b>Tất cả chuyên mục</b></span></a></li>
						</ul>
                    </div>
                </div>
			</li>
		</ul>
		<div class="nav-search"></div>
		<ul class="nav-profile">
			@if(Auth::check())
				<li id="dd-setting" class="active dropdown" data-global-action="connect">
	                <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                        <span class="nav-label-ava" >
		                	<img class="ava32 img-rounded" src="{{ $_auth['auth_ava'] }}">
						</span>
	                </a>
	                <div class="dropdown-menu dropdown-setting">
	                	<div class="title-notifi">
	                		<b>Tùy chỉnh </b><b class="pull-right">Còn {{{ $_auth['auth_has_brick']}}}<i class="icon-has-brick icon-brick "></i></b>
	                	</div>
		                <div class="dd-notification dd-setting ">
	                    	<div class="header-set-noti">
								<div class="nav-analys-pro">
									<a href="{{{ URL::to($_auth['auth_username'])  }}}">
										<img alt="ava of {{{ $_auth['auth_nickname'] }}}" class="nav-ava81 " src="{{{ $_auth['auth_ava'] }}}">
									</a>
									<div class="analys-detail">
										<div class="nav-fullname">
								        	<div><b class="nav-nickname">{{{ $_auth['auth_nickname'] }}}</b></div>
								        	<div><b class="nav-username">{{{ '@'.$_auth['auth_username'] }}}</b></div>
								        	<div><b class="nav-email hide">{{{ $_auth['auth_email'] }}}</b></div>
							        	</div>
						        	</div>
									<a href="{{{ URL::to($_auth['auth_username']) }}}" class="btn btn-view-pro">Trang cá nhân</a>
							    <div class="clear"></div>
								</div>
								@if ($_auth['auth_permission'] >= 1)
								<a href="{{URL::route('admin-dashboard')}}" style="width: 100%;border-radius: 0px; background: #337ab7;" class="btn btn-view-pro">Admin</a>
								@endif
								<div class="nav-set-profile">
								    <div class="nav-rank-name text-center ">{{{ $_auth['auth_medal_name'] }}}</div>
							        <div class="analys-line-1">
							        	<div class="pull-left icon-comment-1"><span> Đăng {{{ $_auth['auth_total_post'] }}} bài</span></div>
							        	<div class="pull-right icon-reply "><span> Được trả lời {{{ $_auth['auth_total_comment'] }}} lần</span></div>
							        	<div class="clear"></div>
							        </div>
						        	<div class="analys-line-2">
							        	<div class="pull-left icon-brick"><span> Bị ném {{{ $_auth['auth_total_brick'] }}} lần</span></div>
							        	<div class="pull-right icon-emo-thumbsup"><span> Được thích {{{ $_auth['auth_total_like'] }}} lần</span></div>
							        	<div class="clear"></div>
						        	</div>
							        <div class="nav- text-center">Tổng số: {{{ $_auth['auth_real_point'] }}} Vàng</div>
							    </div>
							    <div class="nav-medals">
					        		<img class="img-rounded" src="{{{ URL::to($_auth['auth_medal_icon_url']) }}}">
					        	</div>
							</div>
		                </div>
	                    <div class="divider"></div>
	                    <a href="{{URL::route('logout')}}">
	                    	<div class="text-center nav-logout">Thoát</div>
	                    </a>
                	</div>
				</li>
				<li id="dd-notifi" class="people notifications active dropdown" data-global-action="connect">
	                <a href="#" data-toggle="dropdown" class="dropdown-toggle show-notifi">
	                    <div class="icon icon-notifications icon-large"></div>
                        <span class="nav-label-noti">
                        	<span class="notifi-icon icon-bell-alt">
                        		<span class="count-inner">{{ $count }}</span>
                        	</span>
                        </span>
	                </a>
	                <div class="dropdown-menu">
	                	<div class="title-notifi"><b>Thông báo</b></div>
	                    	<input type="hidden" name="page" id="page" value="0" >
	                    <div class="dd-notification scrollbar-noti" data-content="true">
	                        <div class="dum">
		                    	<div class="text-center loadding-notifi"><i class="icon-spin4 animate-spin"></i></div>
	                        </div>
	                    </div>
	                    <div class="divider"></div>
	                    <a href="#">
	                    	<div class="text-center">Xem thêm</div>
	                    </a>
	                </div>
	            </li>
				<li class=" ">
					<a href="{{ URL::route('new-post')}}">
						<span class="nav-label @if (Request::is('new-post')) nav-action-item-active @endif"><i class=" icon-edit-alt"></i><span class="display-item ">Thớt mới</span></span>
					</a>
				</li>
				<li class="display-item">
					<a href="{{{ URL::to($_auth['auth_username']) }}}">
						<span class="nav-label name" >
							{{ $_auth['auth_nickname'] }}
						</span>
	                </a>
				</li>
			@else
				<li>
					<a class="login-link" href="#">
						<span class="nav-label" title="Đăng nhập">Đăng nhập</span>
					</a>
				</li>
       		@endif
        <div class="clear"></div>
		</ul>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('.scrollbar-noti').scrollbar({
        "onScroll": function(y, x) {
            if (y.scroll == y.maxScroll || y.maxScroll - 17 <= y.scroll) {
            	if($('.row-notifi').length > 0 ){
            		loadMoreNotification($('.row-notifi').length);
            	}
            }
        }
    });

});
</script>
