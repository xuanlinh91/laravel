@extends("main")
@section('title')
<title>Error</title>
@endsection
@section('content')

<div id="hot-page" class="stream row">
	<ul id=""class ="media-max-min col-md-9 left-stream left-stream-media">
		<li class="new-post-bar simple-post hide">Có <span class="count-new-post">0</span> bài viết mới</li>
		<li class="no-post">
			<center><h2 class="icon-emo-unhappy"></h2></center>
			<center><h2> Xin lỗi bài viết này đã bị xóa vui lòng xem bài viết khác !</h2></center>
		</li>
		<div class="read-more hide">
			<a href="javascript:void(0)" class="load-more-post-category" > Xem thêm bài viết khác </a>
		</div>
	</ul>
	@include('templates/top')
</div>
<div class="clear"></div>
@endsection
