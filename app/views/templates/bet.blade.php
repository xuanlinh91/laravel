<style type="text/css">
li.simple-hero>.icon-user-pro{
	padding-top: 0px;
	margin-top: -3px;
}
.WB_right_expand{
	margin-top: 2px;
}
.W_arrow_bor{
	height: 9px;
	overflow: hidden;
}
.S_bg1_br{
	border-color: #f2f2f5;
}
.W_arrow_bor i{
	border-top-color: transparent;
	border-right-color: transparent;
	border-left-color: transparent;
	border-width: 10px;
	margin: -5px 0 0 13px;
	display: inline-block;
	width: 0;
	height: 0;
	border-width: 7px;
	border-style: solid;
	overflow: hidden;
	font-size: 0;
	line-height: 0;
	vertical-align: top;
}
.S_bg1{
	background-color: #f2f2f5;
}
.WB_right_expand .expand{
	padding: 6px;
	line-height: 16px;
}
.S_txt2{
	color: #808080;
	text-decoration: none;
	margin: 0px;
}
.S_txt3{
	color: #808080;
	text-decoration: none;
	margin: 0px;
	font-size: 11px;
}
.total-score{
	background: #fff;
	padding: 3px 5px !important;
	webkit-border-radius: 1px;
	moz-border-radius: 1px;
	border-radius: 1px;
	-moz-box-shadow: 0 0 1px 0 rgba(0,0,0,0.1),0 1px 2px 0 rgba(0,0,0,0.2);
	-webkit-box-shadow: 0 0 1px 0 rgba(0,0,0,0.1),0 1px 2px 0 rgba(0,0,0,0.2);
	box-shadow: 0 0 1px 0 rgba(0,0,0,0.1),0 1px 2px 0 rgba(0,0,0,0.2);
}
.hero-static {
	margin-top: -1px;
	float: left;
}
.hero-static>a {
	padding-top: 1px;
	display: block;
	color: #292f33;
	text-decoration: none;
	height: auto;
	width: auto;
}
.hero-static>a:hover {
	color: red;
}
.gold{
	font-weight: bold;
	color: #eb7350!important;
}
.hero-nick{
  margin-right: 2px;
  font-size: 12px;
  display: block;
  font-weight: 600;
  max-width: 99px !important;
  text-align: left;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
.total-score{
  font-weight: normal;
  font-size: 11px;
}
.ava-hero{
  display: block;
  float: left;
  height: auto;
  width: auto;
  margin-right: 5px;
}
.simple-hero>.icon-user-pro>i{
	margin-left: 0px;
}
</style>
<li class="simple-post-list">
	<div class="list-permalink">
		<div class="BXH-TOP">
			<div class="top-box top-nole">
				<div class="cate-top"><span >Thảm họa tháng 5</span></div>
				@if(!empty ($TopsNL))
				<div class="divider"></div>
				<ul>
					@foreach($TopsNL as $top)
					<li class="simple-hero">
						<a class="ava-hero" href="{{{ url( '/'.$top->username ) }}}">
							<img class="ava42 img-rounded" src="{{ url( '/'.$top->ava ) }}">
						</a>
						<div class="hero-static">
							<a class"hero-info" href="{{{ url( '/'.$top->username ) }}}">
								<span class="hero-nick">{{{ $top->nickname }}}</span>
							</a>
							<a class"hero-score" href="{{{ url( '/'.$top->username ) }}}">
								 <span class="total-score">{{{ $top->real_point }}}<span class="gold"> Vàng</span></span>
							</a>
						</div>
						<div class="icon-user-pro @if ($top->user_level <= 9) A_level_icon level_icon_c1 @elseif ($top->user_level <= 19 && $top->user_level > 9) A_level_icon level_icon_c2 @else A_level_icon level_icon_c3 @endif">
							<span data-toggle="tooltip" data-placement="top" title="" data-original-title="Cấp {{$top->user_level}}">Lv.{{$top->user_level}}</span>
						</div>
						@if ($top->gender == 1)
						<a href="" class="icon-user-pro">
							<i data-toggle="tooltip" data-placement="top" title="" data-original-title="Nam nhân" class="A_icon icon_male" ></i>
						</a>
						@elseif ($top->gender == 2)
						<a href="" class="icon-user-pro">
							<i data-toggle="tooltip" data-placement="top" title="" data-original-title="Nữ nhân" class="A_icon icon_female" ></i>
						</a>
						@else
						@endif
						@if ($top->confirmed == true)
						<a href="" class="icon-user-pro">
							<i data-toggle="tooltip" data-placement="top" title="" data-original-title="Xác thực cấp 1" class="A_icon icon_approve_co"></i>
						</a>
						@elseif ($top->confirmed == true && $top->shaphira_verified == true)
						<a href="" class="icon-user-pro">
							<i data-toggle="tooltip" data-placement="top" title="" data-original-title="8xuhuong verify" class="A_icon icon_approve" ></i>
						</a>
						@else
						@endif
						<?php
						switch ($top->blogger_level) {
							case '1':
								# code...
								echo '<a class="icon-user-pro">';
								echo '<i class="A_icon icon_member1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Blogger Cấp 1"></i>';
								echo '</a>';
								break;
							case '2':
								# code...
								echo '<a class="icon-user-pro">';
								echo '<i class="A_icon icon_member2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Blogger Cấp 2"></i>';
								echo '</a>';
								break;
							case '3':
								# code...
								echo '<a class="icon-user-pro">';
								echo '<i class="A_icon icon_member3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Blogger Cấp 3"></i>';
								echo '</a>';
								break;
							case '4':
								# code...
								echo '<a class="icon-user-pro">';
								echo '<i class="A_icon icon_member4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Blogger Cấp 4"></i>';
								echo '</a>';
								break;
							case '5':
								# code...
								echo '<a class="icon-user-pro">';
								echo '<i class="A_icon icon_member5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Blogger Cấp 5"></i>';
								echo '</a>';
								break;
							case '6':
								# code...
								echo '<a class="icon-user-pro">';
								echo '<i class="A_icon icon_member6" data-toggle="tooltip" data-placement="top" title="" data-original-title="Blogger Cấp 5"></i>';
								echo '</a>';
								break;
						}
						?>
						<div class="clear"></div>
						<div class="WB_right_expand">
							<div class="W_arrow_bor W_arrow_bor_t">
								<i class="S_bg1_br"></i>
							</div>
							<div class="expand S_bg1">
								<p class="S_txt2">
									<span title="#{{ $top->hashtag }}"><a href="{{ url('/hashtag/'.$top->hashtag)}}">#{{ $top->hashtag }} ...</a></span>
								</p>
								<p class="S_txt3">24 giờ qua</p>
							</div>
						</div>
					</li>
					@endforeach
				</ul>
				@endif
			</div>
		</div>
	</div>
</li>
