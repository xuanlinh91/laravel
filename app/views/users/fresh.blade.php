@extends ("main")
@section ('title')
<title>Bài đăng mới trên Gutlo</title>
@endsection
@section('content')
<?php
	$activity = new ActivityController();
?>
<script type="text/javascript">
	var url_loadmore = 'loadMorePostFresh'; var page_index='post-fresh';
	socket.on('post_fresh', function (data) {
		var count = parseInt($('.count-new-post').text());
		$('.count-new-post').text(count + 1);
		$('.new-post-bar').removeClass('hide');
		//Do something with data
	});
	$(document).ready(function(){
		load_more_post();
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
	#fresh-page .box-header-farvorite{
		padding: 10px 0px !important;
	}
	.header-farvorite{
		padding-bottom: 10px;
		border-bottom: 1px solid #ccc;
	}
	.item-header-farvorite{
		padding: 15px 15px 5px 15px;
	}
	.line-header-farvorite{

	}
	.line-header-farvorite>span{
		font-weight: bold;
	}
</style>
<div id="fresh-page" class=" first-stream stream row">
	@include('templates/header')
	@include('templates/highlight')
	<div id="naviLeft" class="col-md-2 display-item-col-second pull-left ">
		<div class="fixed">
			<div class="nav-left-group">
				<div class="nav-left-box">
					<h3>
						<a class="posts-fresh nav-left-selected" href="javascript:void(0)">
							<span>Có gì mới ?</span>
						</a>
					</h3>
					<h3>
						<a class="posts-of-user" href="javascript:void(0)">
							<span>Bài viết của bạn</span>
						</a>
					</h3>
					<h3>
						<a class="posts-related-user" href="javascript:void(0)">
							<span>Có liên quan</span>
						</a>
					</h3>
					<h3>
						<a href="javascript:void(0)" class="link-favor-hashtag">
							<span># Yêu Thích #</span>
						</a>
					</h3>
					<h3>
						<a href="javascript:void(0)" class="link-favor-cate">
							<span>Mục Yêu Thích</span>
						</a>
					</h3>
				</div>
			</div>
		</div>
	</div>
	<ul id="media-max-min"class ="col-md-7 left-stream-media left-stream-wrap">
		
		@if(Auth::check())
			<!-- <li class="col-md-12 left-stream left-stream-box box-f-post">
				<form id="comment_post" method="post" action="" enctype="application/x-www-form-urlencoded" autocomplete="off" class="form-horizontal">
					<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
					<div class="form-group">
					    <div class="col-sm-12">
							<div id="div_content" class="form-control contenteditable" contenteditable="false" data-content="false"></div>
							<input class="typeahead" type="hidden">
							<textarea class="content hide" name="content" value=""  rows="4" cols="50"></textarea>
						</div>
					</div>
				    @if ($errors->first('content'))
				    <span class="help-block">{{ $errors->first('content') }}</span>
				    @endif
					<div class="form-group f-f-post">
				    	<div class="col-sm-12">
				      		<button type="submit" id="submit-newComment" data-loading-text="Loading..." class="btn btn-primary pull-right" autocomplete="off">
				      			<span class="submit-cmm">Đăng</span>
				      		</button>
				      		<span id="" data-loading-text="Loading..." class=" add-emoticon pull-left button-margin" autocomplete="off">
				      			<span class="add-emoticons icon-emo-sunglasses">
				      				<span>Emo</span>
				      			</span>
				      		</span>
				    		<span class="count_text">500</span>
				    		<div class="clearfix"></div>
				    	</div>
				  	</div>
				  	<style type="text/css">
				  	</style>
				</form>
			</li> -->
		@endif
		<li class="new-post-bar left-stream simple-post hide">Có <span class="count-new-post">0</span> bài viết mới</li>
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
		<li class="no-post col-md-12 left-stream left-stream-box box-f-post  hide">
			<center><h2 class="icon-emo-unhappy"></h2></center>
			<center><h2> Xin lỗi hiện chưa có bài viết nào được tạo trong mục này !</h2></center>
		</li>
		<div class="read-more col-md-12 hide">
			<a href="javascript:void(0)" class="load-more-post-fresh" > Xem thêm bài viết khác </a>
		</div>
	</ul>
	@include('templates/navRight')
<div class="clear"></div>
</div>
@endsection