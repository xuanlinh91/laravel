<!DOCTYPE html>
@extends ("main")
@section ('title')
<title>manage user Gutlo</title>
@section('content')
<div id="post-page" class=" stream row">
	<div class="col-md-9 left-stream-media ">
		manage user
	</div>
	<ul class="col-md-3 right-stream right-stream-media display-item-col pull-right">
		@include('templates/top')
		@include('templates/trendRight')
		@include('templates/analytics')
		@include('templates/footer')
	</ul>
</div>
@endsection