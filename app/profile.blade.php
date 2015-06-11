@extends ("main")
@section ('title')
<title>Guto Profile</title>
@endsection
@section('content')
<style type="text/css">
.nav-profile-top{
	background: #fff;
	float: left;
	height: 40px;
	width: 100%;
}
.cover-stream{
	box-shadow: 0 1px 1px rgba(0,0,0,0.15)
}
.block-menu{
	height: 40px;
	float: none !important;
}
.block-menu>.nav_menu{
	width: 100%;
	height: 100%;
	text-align: center;
	border-collapse: collapse;
	border-spacing: 0;
}
.nav_menu>.tab-link{
	color: #000;
	text-decoration: none;
	display: inline-block;
	height: 40px;
	margin: 0 auto;
	line-height: 38px;
	white-space: nowrap;
	overflow: hidden;
	-webkit-transition: all 0.2s;
	-moz-transition: all 0.2s;
	transition: all 0.2s;
}
.nav_menu>.tab-link:first-child{
	margin-right: 5%;
}
.nav_menu>.tab-link:last-child{
	margin-left: 5%;
}
.t-link{
	padding: 0 7px;
	font-size: 14px;
}
.tab-link:hover,.tab-link:focus{
	border-bottom: 2px solid #eb7350;
}
.tlink-active{
	font-weight: bold;
	border-bottom: 2px solid #eb7350;
}
</style>
<script type="text/javascript">
	var url_loadmore = 'post_of_users'; var id = '{{$data->id}}';var page_index = 'profile';

$(document).ready(function(){
	load_post_of_user();
});
</script>
<div id="profile-page" class="stream row">
	<div id="" class="media-max-min scol-md-12 middle-stream cover-stream">
		<div>
			<div class="img-profile-page">
				<div class="header-profile-page">
					<img class="col-md-12 row cover-profile-page" src="/assets/image/default/cover/cover-gutlo.jpg">
					<img class="avatar-profile-page" src="{{$data->ava}}">
					<div class="clear"></div>
				</div>
				<div class=" nav-profile-top">
						<div class="center-block block-menu col-md-7">
							<div class="nav_menu">
								<a href="javascript:void(0)" class="tab-link tlink-active timeline">
									<span class="t-link ">Dòng thời gian</span>
								</a>
								<a href="javascript:void(0)" class="tab-link profile">
									<span class="t-link">Hồ sơ</span>
								</a>
							</div>
						</div>
					</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="col-md-9 left-stream-wrap left-stream-media">
		<ul id="media-max-min" class="">
			<div class="loading-post">
				<div class ="col-md-12 left-stream-box wrap-loading-post ">
					<div class="animated-background">
						<div class="background-masker header-ss-top"></div>
						<div class="background-masker header-s-right"></div>
						<div class="background-masker header-s-top"></div>
						<div class="background-masker header-s-left"></div>
						<div class="background-masker header-top"></div>
						<div class="background-masker header-left"></div>
						<div class="background-masker header-right"></div>
						<div class="background-masker header-bottom"></div>
						<div class="background-masker subheader-left"></div>
						<div class="background-masker subheader-right"></div>
						<div class="background-masker subheader-bottom"></div>
						<div class="background-masker content-top"></div>
						<div class="background-masker content-first-end"></div>
						<div class="background-masker content-second-line"></div>
						<div class="background-masker content-second-end"></div>
						<div class="background-masker content-third-line"></div>
						<div class="background-masker content-third-end"></div>
						<div class="background-masker content-s-third-end"></div>
					</div>
				</div>
				<div class ="col-md-12 left-stream-box wrap-loading-post ">
					<div class="animated-background">
						<div class="background-masker header-ss-top"></div>
						<div class="background-masker header-s-right"></div>
						<div class="background-masker header-s-top"></div>
						<div class="background-masker header-s-left"></div>
						<div class="background-masker header-top"></div>
						<div class="background-masker header-left"></div>
						<div class="background-masker header-right"></div>
						<div class="background-masker header-bottom"></div>
						<div class="background-masker subheader-left"></div>
						<div class="background-masker subheader-right"></div>
						<div class="background-masker subheader-bottom"></div>
						<div class="background-masker content-top"></div>
						<div class="background-masker content-first-end"></div>
						<div class="background-masker content-second-line"></div>
						<div class="background-masker content-second-end"></div>
						<div class="background-masker content-third-line"></div>
						<div class="background-masker content-third-end"></div>
						<div class="background-masker content-s-third-end"></div>
					</div>
				</div>
			</div>
			<div class="read-more col-md-12 hide">
				<a href="javascript:void(0)" class="load-more-post-fresh" > Xem thêm bài viết khác </a>
			</div>
		</ul>
	</div>
	@include('templates/navRight2')
<div class="clear"></div>
</div>
@endsection