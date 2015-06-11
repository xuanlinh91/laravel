<!DOCTYPE html>
@extends ("main")
@section ('title')
<title>dasboad Gutlo</title>
@section('content')
<div id="admin-page" class=" stream row">
	@include('adminTemplates/adminNavi')
	<div class="col-md-9 left-stream left-stream-media">
		dashboad
	</div>
	<ul class="col-md-3 right-stream right-stream-media display-item-col pull-right">
		@include('templates/top')
		@include('templates/trendRight')
		@include('templates/analytics')
		@include('templates/footer')
	</ul>
</div>
@endsection