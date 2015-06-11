@extends ("main")
@section ('title')
<title>Shaphira</title>
@endsection
@section('content')
<div id="admin-page" class=" stream row">
	@include('adminTemplates/adminNavi')
	<div class="col-md-9 left-stream left-stream-media">
		Đây là trang quản trị blocking Nội dung  
		(0 = hashtag
		1 = post
		2 = comment
		3 = reply)
	</div>
	<ul class="col-md-3 right-stream right-stream-media display-item-col pull-right">
		@include('templates/top')
		@include('templates/trendRight')
		@include('templates/analytics')
		@include('templates/footer')
	</ul>
</div>
@endsection

