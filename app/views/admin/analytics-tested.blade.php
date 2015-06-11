@extends ("main")
@section ('title')
<title>analytics Gutlo</title>
@section('content')
<?php
	$AnalyticsController = new AnalyticsController();
	$dimensions = "ga:date,ga:hour,ga:pagePath,ga:source,ga:sourceMedium";
	$metrics = 'ga:users,ga:newUsers,ga:pageviews,ga:organicSearches';
	$stats_new = $AnalyticsController->analytics_Google_view_all($dimensions,'yesterday','today',$metrics);
	$dimensions_new = "ga:date,ga:hour,ga:eventAction,ga:eventCategory";
	$metrics_new = 'ga:totalEvents,ga:uniqueEvents,ga:avgEventValue';
	$stats = $AnalyticsController->analytics_Google_view_all($dimensions_new,'yesterday','today',$metrics_new);
	$count = 1;
	$count_user = 1;
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
	.first-col{
		border-right: 1px solid #ccc;
	}
	.abcde {
		margin-bottom: 30px;
	}
</style>
<div id="admin-page" class="stream row">
	@include('adminTemplates/adminNavi')
	<div class="col-md-9 left-stream left-stream-media">
		<div class="col-md-6 first-col ">
			<?php $count = 1; ?>
			@foreach($stats_new as $n)
			<div class="box-test">
				<div> <b> {{$count}}</b> </div>
				<div>Date: {{$n[0]}}</div>
				<div>Hour: {{$n[1]}}</div>
				<div>Path: {{$n[2]}}</div>
				<div>source: {{$n[3]}}</div>
				<div>keyword: {{$n[4]}}</div>
				<div>Users: {{$n[6]}}</div>
				<div>New Users: {{$n[6]}}</div>
				<div>Pageviews: {{$n[7]}}</div>
				<div>organicSearches: {{$n[8]}}</div>
			</div>
			<?php $count = $count+ 1; ?>
			@endforeach
		</div>
		<div class="col-md-6 first-col ">
			<?php $count_new = 1; ?>
			@foreach($stats_new as $n)
			<div class="box-test">
				<div> <b> {{$count_new}}</b> </div>
				<div>Date: {{$n[0]}}</div>
				<div>Hour: {{$n[1]}}</div>
				<div>eventAction: {{$n[2]}}</div>
				<div>eventCategory: {{$n[3]}}</div>
				<div>totalEvents: {{$n[4]}}</div>
				<div>uniqueEvents: {{$n[5]}}</div>
				<div>avgEventValue: {{$n[6]}}</div>
			</div>
			<?php $count_new = $count_new+ 1; ?>
			@endforeach
		</div>
	</div>
	<ul class="col-md-3 right-stream right-stream-media display-item-col pull-right">
		@include('templates/top')
		@include('templates/trendRight')
		@include('templates/analytics')
		@include('templates/footer')
	</ul>
<div class="clear"></div>
</div>
<script>
$(document).ready(function(){
	var time_item = $('.current-time');
	time_item.each(function(i, obj) {
	    var time_ago = parseInt( $(obj).attr('data-time') );

		time = new Date(time_ago);
		console.log(fomat_time_Datetime(time));
		$(obj).text( fomat_time_Datetime(time) );
	});
});
</script>
@endsection