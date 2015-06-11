<link 	rel="stylesheet" 		href="{{ URL::Asset('assets/css/admin-css.css') }}">
<div id="" class="media-max-min col-md-12 middle-stream">
	<div class="trendsbox-wrapper">
		<div class="top-box-page">
			<div class="cate-top"><span >List Tool</span></div>
			<div class="divider"></div>
			<ul class="box-item-trend dd-notification" style="max-height:330px;">
					<li class="col-md-4">
						<span>
							<a href="{{URL::route('manage-categories')}}">Manage Categories</a>
						</span>
					</li>
					<li class="col-md-4">
						<span>
							<a href="{{URL::route('manage-staff')}}">Manage Staff</a>
						</span>
					</li>
					<li class="col-md-4">
						<span>
							<a href="{{URL::route('analytics')}}">Analytics</a>
						</span>
					</li>
					<li class="col-md-4">
						<span>
							<a href="{{URL::route('analytics-tested')}}">Analytics Tested</a>
						</span>
					</li>
					<li class="col-md-4">
						<span>
							<a href="{{URL::route('hot100')}}">Hot 100</a>
						</span>
					</li>
					<li class="col-md-4">
						<span>
							<a href="{{URL::route('adminemoticon')}}">new Emoticon</a>
						</span>
					</li>
					<li class="col-md-4">
						<span>
							<a href="{{URL::route('rating-post')}}">Rate Post</a>
						</span>
					</li>
					<li class="col-md-4">
						<span>
							<a href="{{URL::route('blocking-user')}}">Blocking User</a>
						</span>
					</li>
					<li class="col-md-4">
						<span>
							<a href="{{URL::route('blocking-content')}}">Blocking Content</a>
						</span>
					</li>
					<li class="col-md-4">
						<span>
							<a href="{{URL::route('manage-report')}}">Manage Report</a>
						</span>
					</li>
			<div class="clear"></div>
			</ul>
		</div>
	</div>
</div>