<li class="simple-post-list">
	<div class="list-permalink">
		<div class="BXH-TOP">
			<div class="top-box top-trends">
				<div class="cate-top"><span >#Từ_khóa Hot</span></div>
				@if(!empty ($TopTrends))
					<div class="divider"></div>
					<ul>
						@foreach($TopTrends as $item)
							<li class=""><a href="{{url('/hashtag/'.$item->hashtag) }}">#{{$item->hashtag}}</a></li>
						@endforeach
					</ul>
				@endif
			</div>
		</div>
	</div>
</li>