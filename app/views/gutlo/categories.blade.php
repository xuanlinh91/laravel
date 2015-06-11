@extends("main")

@section('title')
<title>Categories</title>
@endsection
@section('content')
<div id="cate-page" class="stream row">
	<div id="media-max-min" class="col-md-12 middle-stream">
		<div class="trendsbox-wrapper">
			<div class=" top-box-page">
				<div class="cate-top"><span >Categories</span></div>
				@if(!empty ($categories))
				<div class="divider"></div>
				<ul class="box-item-trend dd-notification" style="max-height:330px;">
					@foreach($categories as $item)
						<li class="col-md-4"><a href="{{url('/g/'.$item->cate_code) }}">{{$item->name}}</a></li>
					@endforeach
				<div class="clear"></div>
				</ul>
				@endif
			</div>
		</div>
	</div>
	<ul id="media-max-min"class="col-md-9 left-stream-cate left-stream-media" >
		@foreach($data as $item)
		<li class="list-trend-cate col-md-6">
			<div class="cate-page-list">
				<div class="list-of-trend">
					<div class="header-cate-page-list">{{{$item->name}}}</div>
					@foreach($item->trend as $trend)
						<div>
							<a href="{{url('/hashtag/'.$trend->hashtag)}}"><span>{{ '#'.$trend->hashtag }}</span></a>
						</div>
					@endforeach
				</div>
			</div>
		</li>
		@endforeach
		<div class="clear"></div>
	</ul>
	@include('templates/navRight2')
	<div class="clear"></div>
</div>
@endsection
