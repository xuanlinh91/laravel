@extends ("main")
@section ('title')
<title>Shaphira</title>
@endsection
@section('content')
	<script type="text/javascript" 	src	="{{ URL::asset('assets/js/typeahead.bundle.min.js') }}"></script>
	<script type="text/javascript" 	src	="{{ URL::asset('assets/js/moment.min.js') }}"></script>
	<script type="text/javascript" 	src	="{{ URL::asset('assets/js/bootstrap-datetimepicker.js') }}"></script>
	<link   rel="stylesheet"        href="{{ URL::Asset('assets/css/bootstrap-datetimepicker.css') }}">
	<script type="text/javascript" 	src	="{{ URL::asset('assets/js/function_admin.js') }}"></script>
	<script type="text/javascript" 	src	="{{ URL::asset('assets/js/main_admin.js') }}"></script>

<div id="admin-page" class=" stream row">
	@include('adminTemplates/adminNavi')
	<div class="col-md-9 left-stream left-stream-media">
		<div class="col-md-12 left-stream left-stream-box">
			<li class="simple-post">
				<form id="bane_User" method="post" action="{{ URL::route('baneUser') }}" enctype="application/x-www-form-urlencoded" autocomplete="off" class="form-horizontal col-md-12 ">
					<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
					<div class="form-group">
					    <label for="inputTitle" class="col-sm-2 control-label">userName:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control key typeahead" name="username" id="inputKey" placeholder="search̀">
					    </div>
					</div>
					<div class="form-group">
				   		<label for="reason" class="col-sm-2 control-label">Lý do :</label>
					    <div class="col-sm-10">
					      <textarea class="form-control" name="reason" id="reason" placeholder="Lý do"></textarea>
					    </div>
					</div>
					<div class="form-group" style="position:relative;">
				   		<label for="reason" class="col-sm-2 control-label">ngày giờ kết thúc :</label>
					    <div class="col-sm-10">
					    	<input type="text" class="form-control" name='end_time' id="end_time" />
					    </div>
					</div>
					<div class="form-group">
				    	<div class="col-sm-offset-2 col-sm-10">
				      		<button type="submit" id="submit-search" data-loading-text="Loading..."class="btn btn-default submit-post" autocomplete="off">Bane</button>
				    	</div>
				  	</div>
				</form>
			</li>
		</div>
	</div>
	<ul class="col-md-3 right-stream right-stream-media display-item-col pull-right">
		@include('templates/top')
		@include('templates/trendRight')
		@include('templates/analytics')
		@include('templates/footer')
	</ul>
</div>
@endsection

