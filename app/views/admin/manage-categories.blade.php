@extends ("main")
@section ('title')
<title>dasboad Gutlo</title>
@section('content')
<div id="admin-page" class=" stream row">
	@include('adminTemplates/adminNavi')
	<div class="col-md-9 left-stream left-stream-media">
		<form method="POST" action="{{ url('/')}}/admin/category" enctype="application/x-www-form-urlencoded" autocomplete="off" class="form-horizontal col-md-8 ">
		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		<input type='text' name="name" class="category_name"></input>
		<input type="submit" class="submit"></input>
		@if ($errors->first('name'))
		<span class="help-block">{{ $errors->first('name') }}</span>
		@endif
		</form>
		@if(isset($data))
		<pre>
			<?php print_r($data);?>
		</pre>
		@endif
	</div>
	<ul class="col-md-3 right-stream right-stream-media display-item-col pull-right">
		@include('templates/top')
		@include('templates/trendRight')
		@include('templates/analytics')
		@include('templates/footer')
	</ul>
</div>
@endsection
