@extends("main")
@section('title')
<title>{{ $data->name }}</title>
@endsection
@section('content')

<?php
	$activity = new ActivityController();
	$GutloComment_count = new GutloComment();
?>
<script type="text/javascript">
	socket.on('post_fresh_{{ $data->cate_code }}', function (data) {
		var count = parseInt($('.count-new-post').text());
		$('.count-new-post').text(count + 1);
		$('.new-post-bar').removeClass('hide');
		//Do something with data
	});
</script>
<script> 
	var url_loadmore = 'g/{{ $data->cate_code }}'; var page_index='show-cate';
	$(document).ready(function(){
		load_more_post_on_category();
	});

</script>
<div id="fresh-page" class="stream row">
	<li class="col-md-12 left-stream left-stream-box only-one ">
		<div class="cate-top only-one-top">
			<span >{{ $data->name }}</span>
			<a href="javascript:void(0)" data-id="{{ $data->id }}" class="favor-button favor-cate"><i class="icon-star @if($data->favorite == 1 || $data->favorite == '1' ) favorited @endif" data-toggle="tooltip" data-placement="right" title="" data-original-title="Yêu thích" title=""></i></a>
		</div>
	</li>
	<ul id=""class =" col-md-9 left-stream-media left-stream-wrap">
		@if(!empty ($trends_category))
		<div id="media-max-min" class="col-md-12 middle-stream">
			<div class="trendsbox-wrapper">
				<div class="top-box-page">
					<div class="cate-top"><span >#Từ_Khóa trong chủ đề </span></div>
					<div class="divider"></div>
					<ul class="box-item-trend dd-notification" style="max-height:330px;">
						@foreach($trends_category as $item)
							<li class="col-md-4"><a href="{{url('/hashtag/'.$item->hashtag) }}">#{{$item->hashtag}}</a></li>
						@endforeach
					<div class="clear"></div>
					</ul>
				</div>
			</div>
		</div>
		@endif
		<li class="new-post-bar simple-post hide">Có <span class="count-new-post">0</span> bài viết mới</li>
		<li class="no-post left-stream-no-post hide">
			<center><h2 class="icon-emo-unhappy"></h2></center>
			<center><h2> Xin lỗi hiện chưa có bài viết nào được tạo trong chuyên mục này !</h2></center>
		</li>
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
			<a href="javascript:void(0)" class="load-more-post-category" > Xem thêm bài viết khác </a>
		</div>
		<div class="clearfix"></div>
	</ul>
	@include('templates/navRight2')
	<div class="clear"></div>
</div>

@endsection
