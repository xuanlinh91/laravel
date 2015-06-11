@extends ("main")
@section ('title')
<title>analytics Gutlo</title>
@section('content')
<?php
	$AnalyticsController = new AnalyticsController();
	$dimensions = "ga:date,ga:hour,ga:previousPagePath,ga:pagePath";
	$dimensions2 = "ga:date,ga:hour,ga:pagePathLevel2";
	$dimensions3 = "ga:date,ga:hour,ga:pagePath";
	$dimensions4 = "ga:date,ga:hour,ga:pagePathLevel2,ga:pagePathLevel1";

	$metrics = 'ga:users,ga:newUsers,ga:pageviews';
	$stats = $AnalyticsController->run_data_analytic_view_all();
	$stats_new = $AnalyticsController->analytics_Google_view_all($dimensions,'today','today',$metrics);
	$stats_new2 = $AnalyticsController->analytics_Google_view_all($dimensions2,'today','today',$metrics);
	$stats_new3 = $AnalyticsController->analytics_Google_view_all($dimensions3,'today','today',$metrics);
	$stats_new4 = $AnalyticsController->analytics_Google_view_all($dimensions4,'today','today',$metrics);

	$count = 1;$count_user = 1;

	$data_cache = Cache::get('gua');

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
	<div class="col-md-9 left-stream left-stream-media abcde">
		<div class="col-md-9 pull-left first-col">
			<div class="box-test" >
				<div> <b> User & pageView All page </b> </div>
			
			</div>
			<div class="box-test" >

			<?php $count_view_path = 0;?>
			@if(isset($data_cache['viewPath']))
				@foreach($data_cache['viewPath'] as $n)
				<div>
					
				</div>
				<?php $count_view_path = $count_view_path+ 1; ?>
				@endforeach
			@endif
			</div>

			<?php $count_analytic_post = 0;?>
			@if(isset($data_cache['analytic_post']))
			@foreach($data_cache['analytic_post']['analytics_post'] as $log)
			<div class="box-test">
				<div> <b> Thống kê các hành động trong 1 post</b> </div>
				<div>post_id: {{ $log['post_id'] }}</div>
				<div>total_like: {{ $log['total_like'] }}</div>
				<div>total_brick : {{ $log['total_brick'] }}</div>
				<div>total_comment : {{ $log['total_comment'] }}</div>
				<div>total_reply : {{ $log['total_reply'] }}</div>
			</div>
			<?php $count_analytic_post = $count_analytic_post +1;?>
			@endforeach
			@endif
		</div>
		<div class="clear"></div>
	</div>
<!-- 	<div class="col-md-9 left-stream left-stream-media abcde">
		<div class="col-md-6 pull-left first-col">
			@foreach($stats['google'] as $n)
			@if($n[2]=='/')
			<div class="box-test" style="color: red;">
				<div> <b> {{$count}}</b> </div>
				<div>Date: {{$n[0]}}</div>
				<div>Hour: {{$n[1]}}</div>
				<div>Users: {{$n[2]}}</div>
				<div>New Users: {{$n[3]}}</div>
				<div>Pageviews: {{$n[4]}}</div>
			</div>
				@else
			<div class="box-test">
				<div> <b> {{$count}}</b> </div>
				<div>Date: {{$n[0]}}</div>
				<div>Hour: {{$n[1]}}</div>
				<div>Users: {{$n[2]}}</div>
				<div>New Users: {{$n[3]}}</div>
				<div>Pageviews: {{$n[4]}}</div>
			</div>
			@endif
			<?php $count = $count+ 1; ?>
			@endforeach
			<?php $count_view_path = 0;?>
			@if(isset($stats['path-view']['viewPath']))
			@foreach($stats['path-view']['viewPath'] as $n)
			<div class="box-test" style="color: red;">
				<div> <b> {{$count_view_path}}</b> </div>
				<div>Path: {{$n['path']}}</div>
				<div>Users: {{$n['User']}}</div>
				<div>Pageviews: {{$n['PageView']}}</div>
			</div>
			<?php $count_view_path = $count_view_path+ 1; ?>
			@endforeach
			@endif
		</div>
		<div class="col-md-6 pull-left seconde-col">
			@foreach($stats['gutlo'] as $user)
			<div class="box-test">
				<div> <b> {{$count_user}}</b> </div>
				<div>start time: <span class="current-time" data-time="{{$user['start_time']}}"></span></div>
				<div>end time: <span class="current-time" data-time="{{$user['end_time']}}"></span></div>
				<div>count: {{$user['count']}}</div>
			</div>
			<?php $count_user = $count_user +1;?>
			@endforeach
			<?php $count_analytic_post = 0;?>
			@if(isset($stats['data_analytics_post']['analytics_post']))
			<div class="box-test">
				<div> <b> All post</b> </div>
				<div>total post: {{ $stats['data_analytics_post']['total_post'] }}</div>
				<div>total comment: {{ $stats['data_analytics_post']['total_comment_all_post'] }}</div>
				<div>total reply: {{ $stats['data_analytics_post']['total_reply_all_post'] }}</div>
				<div>total like: {{ $stats['data_analytics_post']['total_like_all_post'] }}</div>
				<div>total brick: {{ $stats['data_analytics_post']['total_brick_all_post'] }}</div>
			</div>
			@foreach($stats['data_analytics_post']['analytics_post'] as $log)
			<div class="box-test">
				<div> <b> {{$count_analytic_post}}</b> </div>
				<div>post_id: {{ $log['post_id'] }}</div>
				<div>total_like: {{ $log['total_like'] }}</div>
				<div>total_brick : {{ $log['total_brick'] }}</div>
				<div>total_comment : {{ $log['total_comment'] }}</div>
				<div>total_reply : {{ $log['total_reply'] }}</div>
			</div>
			<?php $count_analytic_post = $count_analytic_post +1;?>
			@endforeach
			@endif
		</div>
		<div class="clear"></div>
	</div> -->


	<div class="col-md-9 left-stream left-stream-media abcde">
		<div class="col-md-6 pull-left first-col">
			<?php $count = 1;$count_user = 1; ?>
			@if(!empty($stats_new))
			@foreach($stats_new as $n)
			<div class="box-test">
				<div> <b> {{$count}}</b> </div>
				<div>Date: {{$n[0]}}</div>
				<div>Hour: {{$n[1]}}</div>
				<div>PrePath: {{$n[2]}}</div>
				<div>Path: {{$n[3]}}</div>
				<div>Users: {{$n[4]}}</div>
				<div>New Users: {{$n[5]}}</div>
				<div>Pageviews: {{$n[6]}}</div>
			</div>
			<?php $count = $count+ 1; ?>
			@endforeach
			@endif
		</div>
		<div class="col-md-6 pull-right seconde-col">
			<?php $count = 1;$count_user = 1; ?>
			@if(!empty($stats_new2))
			@foreach($stats_new2 as $n)
			<div class="box-test">
				<div> <b> {{$count}}</b> </div>
				<div>Date: {{$n[0]}}</div>
				<div>Hour: {{$n[1]}}</div>
				<div>Path: {{$n[2]}}</div>
				<div>Users: {{$n[3]}}</div>
				<div>New Users: {{$n[4]}}</div>
				<div>Pageviews: {{$n[5]}}</div>
			</div>
			<?php $count = $count+ 1; ?>
			@endforeach
			@endif

		</div>
		<div class="clearfix"></div>
	</div>

	<div class="col-md-9 left-stream left-stream-media abcde">
		<div class="col-md-6 pull-left first-col">
			<?php $count = 1;$count_user = 1; ?>
			@if(!empty($stats_new3))
			@foreach($stats_new3 as $n)
			<div class="box-test">
				<div> <b> {{$count}}</b> </div>
				<div>Date: {{$n[0]}}</div>
				<div>Hour: {{$n[1]}}</div>
				<div>Path: {{$n[2]}}</div>
				<div>Users: {{$n[3]}}</div>
				<div>New Users: {{$n[4]}}</div>
				<div>Pageviews: {{$n[5]}}</div>
			</div>
			<?php $count = $count+ 1; ?>
			@endforeach
			@endif

		</div>
		<div class="col-md-6 pull-left seconde-col">
			<?php $count = 1;$count_user = 1; ?>
			@if(!empty($stats_new4))
			@foreach($stats_new4 as $n)
			<div class="box-test">
				<div> <b> {{$count}}</b> </div>
				<div>Date: {{$n[0]}}</div>
				<div>Hour: {{$n[1]}}</div>
				<div>Path1: {{$n[3]}}</div>
				<div>Path2: {{$n[2]}}</div>
				<div>Users: {{$n[4]}}</div>
				<div>New Users: {{$n[5]}}</div>
				<div>Pageviews: {{$n[6]}}</div>
			</div>
			<?php $count = $count+ 1; ?>
			@endforeach
			@endif

		</div>
		<div class="clearfix"></div>
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