@extends("main")
@section('title')
<title>#Trending</title>
@endsection
@section('content')
<?php
	$activity = new ActivityController();
	$GutloComment_count = new GutloComment();
?>
<script> 
	var url_loadmore = 'trend'; var page_index='trend';
	$(document).ready(function(){
		load_more_post();
	});
</script>
<div id="trend-page" class="stream row">
	<div id="" class="media-max-min col-md-12 middle-stream">
		<div class="trendsbox-wrapper">
			<div class="top-box-page">
				<div class="cate-top"><span >#Từ_Khóa Hot</span></div>
				@if(!empty ($trends))
				<div class="divider"></div>
				<ul class="box-item-trend dd-notification" style="max-height:330px;">
					@foreach($trends as $item)
						<li class="col-md-4">
							<span class="hashtag-item">
								<a class="ajax-trend-post" data-name="{{$item->hashtag}}" href="{{url('/hashtag/'.$item->hashtag) }}">#{{$item->hashtag}}</a>
							</span>
						</li>
					@endforeach
				<div class="clear"></div>
				</ul>
				@endif
			</div>
		</div>
	</div>
	<ul id=""class =" col-md-9 left-stream-media left-stream-wrap ">
		<li class="col-md-12 left-stream left-stream-box only-one ">
			<div class="cate-top only-one-top">
				<span >Toàn bộ  từ khóa</span>
				<a class="favor-button favor-hashtag hide" data-name=""><i class="icon-star " data-toggle="tooltip" data-placement="right" title="" data-original-title="Yêu thích" title=""></i></a>
				@if($_auth["auth_permission"] > 2)
					<i class="icon-alert  mod-block hide" data-action="block-hashtag" data-content-id="" data-toggle="tooltip" data-placement="top" data-original-title="Block!!"></i>
				@endif

			</div>
		</li>
		<li class="new-post-bar simple-post hide">Có <span class="count-new-post">0</span> bài viết mới</li>
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
	@include('templates/navRight2')
	<div class="clear"></div>
</div>
@endsection