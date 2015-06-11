<!DOCTYPE html>
@extends ("main")
@section ('title')
<title>Bài đăng đang nóng trên Gutlo</title>
@section('content')
<?php
	$activity = new ActivityController();
	$GutloComment_count = new GutloComment();
?>
<script>
	var url_loadmore = 'loadMorePostHot'; var page_index='post-hot';
	$(document).ready(function(){
		load_more_post_hot();
	});
</script>
<style type="text/css">
.first-stream{
		padding-left: 10px;
		padding-right: 10px;
	}
	.favorites-title{
		color: #fff;
		font-size: 13px;
		line-height: 36px;
	}
	.favorites-title:hover{
		background-color: rgba(255,255,255,0.2)
	}
	.favorites-title>a{
		padding-left: 15px;
		color: #fff;
		text-decoration: none;
	}
	.favorites-title>a>b{
		width: 100%;
		text-align: right;
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	#fresh-page .box-f-post{
		padding: 15px !important;
	}
	.f-f-post{
		margin-bottom: 5px;
	}
	.facebox{
		height: 100px;
		border: 3px solid #ccc;
		padding: 6px 12px;
		border-radius: 3px;
	}
	.submit-emo{
		margin-left: -70px !important;
		margin-right: -25px !important;
		margin-bottom: 10px;
	}
	#naviLeft{
		padding: 0px;
	}
	.nav-left-group{
		width: 105%;
		margin-left: -10px;
	}
	.nav-left-box h3{
		display: block;
		height: 34px;
		line-height: 34px;
		font-size: 14px;
		font-weight: bold;
		text-decoration: none;
		overflow: hidden;
		margin: 0px;
	}
	.nav-left-box h3>a{
		color: #fff;
		font-size: 13px;
		font-weight: bold;
		height: 34px;
		line-height: 34px;
		padding: 0 0 0 15px;
		display: block;
		text-decoration: none;
		overflow: hidden;
		position: relative;
	}
	.nav-left-box h3>a:hover{
		background-color: rgba(255,255,255,0.2)
	}
	.nav-left-box h3>a>span{
		display: inline-block;
		max-width: 100%;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		cursor: pointer;
	}
	.nav-left-selected{
		background-color: rgba(255,255,255,0.2)
	}
</style>
<div id="hot-page" class="stream row first-stream ">
	@include('templates/header')
	@include('templates/highlight')
	<ul id=""class =" col-md-9 left-stream-media left-stream-wrap ">
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
			<a href="javascript:void(0)" class="load-more-post-hot" > Xem thêm bài viết khác </a>
		</div>
	</ul>
	@include('templates/navRight')
<div class="clear"></div>
</div>
@endsection