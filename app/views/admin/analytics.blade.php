@extends ("main")
@section ('title')
<title>analytics Gutlo</title>
@section('content')
<?php
	$AnalyticsController = new AnalyticsController();
	$dimensions = "ga:date,ga:hour,ga:pagePath";
	$start_time = '7daysAgo';
	$end_time ='today';
	$metrics = 'ga:users,ga:newUsers,ga:pageviews';
	$stats = $AnalyticsController->analytics_Google($dimensions,$start_time,$end_time,$metrics);
	$count = 1;
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

</script>
<style type="text/css">
	.box-test{
		padding: 5px;
		margin-bottom: 5px;
		border-bottom: 1px solid;
	}
</style>
<div id="admin-page" class=" stream row">
	@include('adminTemplates/adminNavi')
	<div class="col-md-9 left-stream left-stream-media">
		<!-- <div id="chart_div" style="width: 100%; height: 500px;"></div> -->
	@foreach($stats as $n)
			@if($n[2]=='/')
			<div class="box-test" style="color: red;">
				<div> <b> {{$count}}</b> </div>
				<div>Date: {{$n[0]}}</div>
				<div>Hour: {{$n[1]}}</div>
				<div >Pagepath: {{$n[2]}}</div>
				<div>Users: {{$n[3]}}</div>
				<div>New Users: {{$n[4]}}</div>
				<div>Pageviews: {{$n[5]}}</div>
			</div>
				@else
			<div class="box-test">
				<div> <b> {{$count}}</b> </div>
				<div>Date: {{$n[0]}}</div>
				<div>Hour: {{$n[1]}}</div>
				<div>Pagepath: {{$n[2]}}</div>
				<div>Users: {{$n[3]}}</div>
				<div>New Users: {{$n[4]}}</div>
				<div>Pageviews: {{$n[5]}}</div>
			</div>
			@endif
			<?php $count = $count+ 1; ?>
		@endforeach
	</div>
	<ul class="col-md-3 right-stream right-stream-media display-item-col pull-right">
		@include('templates/top')
		@include('templates/trendRight')
		@include('templates/analytics')
		@include('templates/footer')
	</ul>
</div>
@endsection