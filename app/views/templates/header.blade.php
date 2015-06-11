<div id="" class="media-max-min col-md-12 middle-stream">
	<div class="trendsbox-wrapper">
		<div class="top-box-page">
			<div class="cate-top"><span >#Từ_Khóa Hot</span></div>
			@if(!empty ($trends))
			<div class="divider"></div>
			<ul class="box-item-trend dd-notification" style="max-height:330px;">
				@foreach($trends as $item)
					<li class="col-md-3">
						<span class="hashtag-item">
							<a href="{{url('/hashtag/'.$item->hashtag) }}">#{{$item->hashtag}}</a>
							<!-- <span class="icon-count">
								<span class="icon-chat-3"></span> {{$item->total_post}}
								<span class="icon-chat-3"></span> {{$item->total_comment}}
								<span class="icons-like icon-emo-thumbsup"></span> {{$item->total_like}}
								<span class="icons-bricks icon-brick"></span> {{$item->total_brick}}
								<span class="icon-ellipsis"></span> {{$item->total_point}}
							</span> -->
						</span>
					</li>
				@endforeach
			<div class="clear"></div>
			</ul>
			@endif
		</div>
	</div>
</div>