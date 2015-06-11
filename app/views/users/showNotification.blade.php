@extends ("main")
@section ('title')


<link rel="image_src" href="{{{ url('/'.$data['post'][0]->ava ) }}}">
<link rel="canonical" href="{{ URL::route('show_posts',array('id'=>$data['post'][0]->id)) }}">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- <meta name="robots" content="index, follow"> -->
<meta name="referrer" content="always">
<meta name="keywords" content="gutlo, gutlo.com, brick, comment, post">
<meta name="description" content="gutlo: relax space">

<meta itemprop="name" content="{{{ $data['post'][0]->title }}}">
<meta itemprop="description" content="{{{$data['post'][0]->content}}}">
<meta itemprop="image" content="{{{ url('/'.$data['post'][0]->ava ) }}}">

<meta name="twitter:card" content="photo">
<meta name="twitter:site" content="@gutlo">
<meta name="twitter:image" content="{{{ url('/'.$data['post'][0]->ava ) }}}">
<!-- <meta name="twitter:title" content="Page Title">
<meta name="twitter:description" content="Page description less than 200 characters">
<meta name="twitter:creator" content="@author_handle">
 -->
<meta property="og:title" content="{{{ $data['post'][0]->title }}}">
<meta property="og:site_name" content="Gutlo">
<meta property="og:url" content="{{ URL::route('show_posts',array('id'=>$data['post'][0]->id)) }}">
<meta property="og:description" content="{{{$data['post'][0]->content}}}">
<meta property="og:type" content="article">
<meta property="og:image" content="{{{ url('/'.$data['post'][0]->ava ) }}}">
<meta property="article:section" content="Article Section" />
<meta property="article:tag" content="Article Tag" />
<meta name="author" content="{{{$data['post'][0]->nickname}}}">
<meta property="article:published_time" content="{{{$data['post'][0]->created_time['time-ago']}}}" />
<meta property="fb:admins" content="10205272775250051" />
<title>{{{$data['post'][0]->nickname}}} on gutlo.com : "{{{ $data['post'][0]->title }}}" </title>
@endsection
@section('content')
<?php
    $GutloComment_count = new GutloComment();
	$activity = new ActivityController();
	$commentController = new CommentController();
	$GutloReply = new GutloReply();
	$total_comment = $GutloComment_count->get_count_comment($data['post'][0]->id,$_auth['auth_id'])->count;
	$comments = null;
	$comment_id = 0;
	switch ($data['content_type']) {
		case Config::get('Common.content_type.comment'):
			$comments = $commentController->get_comment_by_notifi_comment($data['post'][0]->id,$data['id_content']);
			break;
		case Config::get('Common.content_type.reply'):
			$comments = $commentController->get_comment_by_notifi_reply($data['post'][0]->id,$data['id_content']);
			$comment_id =  DB::table('gutlo_reply')->select('comment_id')->where('id','=',$data['id_content'])->first()->comment_id;
			break;
		case Config::get('Common.content_type.post'):
			$comments = $commentController->getCommentFreshById($data['post'][0]->id);
			break;
	}
	$total_comment_all = $GutloComment_count->get_total_comment($data['post'][0]->id);


?>
<script type="text/javascript">
	var emoticons = {{$emoticons}};
	var emo_group = {{ $group_emoticon_js }};

	$(document).ready(function(){
		load_data_comment_notification('{{ Config::get("Common.content_type.comment")}}','{{ Config::get("Common.content_type.reply")}}',{{ $data['post'][0]->id }},{{ $_auth['auth_id'] }},{{ $data['id_content'] }},{{ $data['content_type'] }},'{{ url("/loadMoreNextComment")}}','{{ url("/loadMorePreviousComment")}}','{{ url("/loadMorePreviousReply") }}','{{ url("/loadMoreNextReply") }}');
		lazy_load($('img'));

	});
	// socket.on('data_{{ $data['post'][0]->id}}_{{ $_auth["auth_name"] }}', function (data) {
	// 	//Do something with data
	// 	callback_socket_show(data);
	// });
	$(window).scroll(function() {
	   if($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
			event_scroll('{{ $data['post'][0]->id }}') ;
	   }
	});

</script>
<script type="text/javascript" 	src ="{{ URL::Asset('assets/js/lib/notification.js') }}"></script>

<div id="confirm-report" class="modal fade">
  <div class="modal-body">
    bạn chác chắn muốn report bài viết này chứ ???
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn btn-primary" id="report">Yes</button>
    <button type="button" data-dismiss="modal" class="btn">No</button>
  </div>
</div>
<div id="confirm-delete" class="modal fade">
  <div class="modal-body">
    bạn chác chắn muốn xóa bài viết này chứ ??? Khi xóa toàn bộ point liên quan đến bài viết sẽ bị xóa
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Yes</button>
    <button type="button" data-dismiss="modal" class="btn">No</button>
  </div>
</div>
<div id="show-page" class="stream row">
	<div id="media-max-min"class="col-md-9 left-stream left-stream-media" >
		<div class="PostBox permalink">
			<div class="PostBoxContent">
				<div class="BoxContent">
					<div class="post-header">
						<div class="ava42-only">
							<a href="{{{ url( '/'.$data['post'][0]->username ) }}}" class="ava-user">
								<img alt="ava of {{{$data['post'][0]->username}}}"class="img-rounded" src="{{{ url('/'.$data['post'][0]->ava ) }}}">
							</a>
						</div>
					    <div class="header-analytics-only">
					        <a class="full-name" href="{{{ url( '/'.$data['post'][0]->username ) }}}">
					        	<div class="nick-name">{{{ $data['post'][0]->nickname }}}</div>
					        	<small class="username">{{{ '@'.$data['post'][0]->username }}}</small>
					        </a>
					    </div>
					    <span class="span-space">-</span>
					    <div class="time-post time-top">
					    	<small>{{ $data['post'][0]->created_time['time-ago'] }}</small>
					    </div>
					    <div class="post-nav">
						  	<a class="" data-entry-key="aE10GMo" href=""></a>
						  	<a class="next" data-entry-key="aE10GMo" href="{{$data['post'][0]->next_post}}">
						  		<span class="next-label">Tiếp</span>
						  		<span class="arrow"></span>
						  	</a>
						</div>
					</div>
					<div class="title-post">
						@foreach($data['post'][0]->Categories as $category)
						<h1 class="title-show-page"><a class="cate-post" href="{{ url('/g/'.$category->cate_code ) }}"><span>{{{ $category->name.': ' }}}</span></a>
							{{{ $data['post'][0]->title }}}
						</h1>
						@endforeach
					</div>
					<div role="group" class="button-action-line" data-post-id="{{ $data['post'][0]->id }}" data-title="post-icon">
						<div class="pull-left button-margin">
						    <button class="button-action icons-brick @if($data['post'][0]->brick_content) hide @endif" autocomplete="off" type="button" data-content-id="{{ $data['post'][0]->id }}" data-loading-text="<span class='icons-bricks icon-brick'></span>" data-action="brick-post" data-original-title="Brick">
						      <span class="icons-bricks icon-brick"></span>
						    </button>
						    <button class="button-action icons--brick @if(!$data['post'][0]->brick_content) hide @endif" autocomplete="off"  type="button" data-content-id="{{ $data['post'][0]->id }}" data-loading-text="<span class='icons--bricks icon-brick'></span>" data-action="brick-post" data-original-title="Brick">
						      <span class="icons--bricks icon-brick"></span>
						    </button>
						</div>
						<div class="pull-left button-margin">
						    <button class="button-action icon-like @if($data['post'][0]->like_content) hide @endif " type="button" autocomplete="off" data-loading-text="<span class='icons-like icon-emo-thumbsup'></span>" data-content-id="{{ $data['post'][0]->id }}" data-action="like-post" data-original-title="Like">
						     	<span class="icons-like icon-emo-thumbsup"></span>
						    </button>
						    <button class="button-action icon--like @if(!$data['post'][0]->like_content) hide @endif" type="button" autocomplete="off" data-loading-text="<span class='icons-like icon-emo-thumbsup'></span>" data-content-id="{{ $data['post'][0]->id }}" data-action="like-post" data-original-title="Like">
						    	<span class="icons--like icon-emo-thumbsup"></span>
						    </button>
						</div>

						<div class="pull-left button-margin">
						    <button class="button-action comment-post" type="button" data-original-title="Comment">
						      <span class="icon-chat-3 "></span>
						    </button>
						</div>
						<div class="pull-left button-margin">
						    <div class="dropdown">
								<button class="button-action" type="button" data-original-title="More">
								      <span class="icon-ellipsis"></span>
								</button>
							</div>
	    				</div>
	    				<div class="clear"></div>
					</div>

					<ul class="post-stats" data-post-id="{{ $data['post'][0]->id }}">
						<li class="brick-count">
							<a href="javascript:void(0)">Gạch
								<strong class="brick">{{ $data['post'][0]->total_brick }}</strong>
							</a>
						</li>
						<li class="like-count">
							<a href="javascript:void(0)">Thích
								<strong class="like">{{ $data['post'][0]->total_like }}</strong>
							</a>
						</li>
						<li class="comment-count">
							<a href="javascript:void(0)">Bình luận
									<strong class="comment">{{ $total_comment_all }}</strong>
							</a>
						</li>
						<li class="point-count">
							<a href="javascript:void(0)">Vàng
									<strong class="point">{{ $data['post'][0]->total_point }}</strong>
							</a>
						</li>
						<div class="avatar-row pull-left">
							@if(!empty($data['post'][0]->users_action))
								@foreach($data['post'][0]->users_action as $user_action)
								<a class="ava-rela" href="{{ url( '/'.$user_action->username )}}" data-toggle="tooltip" data-placement="top" title="{{{$user_action->username}}}">
									<img class="img-rounded ava25" src="{{ url( '/'.$user_action->ava )}}">
								</a>
								@endforeach
							@endif
						</div>
					</ul>
					@if (!empty($data['post'][0]->link))
					<div class="link-show-page"><a class="link-to-web text-nowrap-full" href="{{$data['post'][0]->link}}">{{{$data['post'][0]->link}}}</a></div>
					@endif
					<div class="post-content">{{ $data['post'][0]->new_content }}
						@if( $data['post'][0]->image != null)
							@if($data['post'][0]->type_media == 0)
						<!-- <a class="link-to-web text-nowrap-full" href="{{$data->link}}"> -->
							<img src="{{url($data['post'][0]->image_thumb)}}" data-src="{{$data['post'][0]->image}}" class="media-thumb">
							@else
							<div id="player">
								<iframe class="video-play" width="800" height="400" src="https://www.youtube.com/embed/{{ $data['post'][0]->media_api_id }}?feature=oembed&autoplay=1&wmode=opaque&rel=0&showinfo=0&modestbranding=0" frameborder="0" allowfullscreen=""></iframe>
							</div>
							@endif
						<!-- </a> -->
						@endif
					</div>
				</div>
			</div>
			<div class="after-post">
				<div class="share-bar">
				    <ul>
				        <li class="share-after-post-f col-md-6 ">
				        	<a class="share-facebook share" href="javascript:void(0)" id="share-fb" data-ir="{{ $data['post'][0]->id }}" data-width="700" data-height="300" data-share="{{ url('/posts/') }}">Share on Facebook</a>
				        </li>
				        <li class="share-after-post-t col-md-6 ">
				        	<a class="share-twitter share" href="https://twitter.com/home?status={{ $data['post'][0]->title }}" data-track="" data-evt="" data-title="" data-share="">Share on Twitter</a>
				        </li>
				<div class="clear"></div>

				    </ul>
				</div>
				<div class="clear"></div>
			</div>
			<div class="info-after-post">
				<div class="info-top">
					<div class="pull-right">
						<span class="current-time" data-time="{{ $data['post'][0]->created_time['seconds'] }}"></span>
						<span class="badge-only">-</span>
						@if($data['post'][0]->username == $_auth['auth_name'])
						<a href="javascript:void(0)" class="delete" data-post-id="{{ $data['post'][0]->id }}" data-action="delete-post"><i class="icon-trash"></i>Xóa</a>
						@else
						<a href="javascript:void(0)" class="report" data-post-id="{{ $data['post'][0]->id }}" data-action="report-post"><i class="icon-flag-filled"></i>Vi Phạm</a>
						@endif
					</div>
						<div class="clear"></div>

				</div>
				<div class="info-bottom">
					<div>
						<h1 class="info-comment pull-left" ><span class="comment">{{ $total_comment_all }}</span> Bình luận</h1>
					</div>
					<div class="pull-right">
						
					</div>
					<div class="clear"></div>
				</div>
			</div>

			<div class="post-reply-box">
				<div class="avatar-reply-box">
						@if( $_auth['auth_nickname'] != '' )
					<a href="{{{ url( '/'.$_auth['auth_name'] ) }}}" class="ava-user">
						<img class=" img-rounded" src="{{{ $_auth['auth_ava'] }}}">
						@else
					<a href="#" class="ava-user">
						<div class="img-rounded ava_guest" ></div>
						@endif
					</a>
				</div>
				<form id="comment_post" method="post" action="{{ url('/'.'comment/'.$data['post'][0]->id ) }}" enctype="application/x-www-form-urlencoded" autocomplete="off" class="form-horizontal">
					<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
					<div class="form-group submit-comment">
					    <div class="col-sm-12 col-right-none">
							<div id="div_content" class="form-control contenteditable" contenteditable="false" data-content="false"></div>
							<input class="typeahead" type="hidden">
							<textarea class="content hide" name="content" value=""  rows="4" cols="50"></textarea>
						</div>
					</div>
				    @if ($errors->first('content'))
				    <span class="help-block">{{ $errors->first('content') }}</span>
				    @endif
					<div class="form-group submit-comment comment-uti">
				    	<div class="col-sm-12 col-right-none">
				      		<button type="submit" id="submit-newComment" data-loading-text="Loading..." class="btn btn-primary pull-right" autocomplete="off"><span class="submit-cmm">Đăng</span></button>
				      		<a id="" class=" pull-left button-margin emo-cmm add-emoticon" autocomplete="off">
				      			<span class="add-emoticons icon-emo-sunglasses">
				      				<span>Emo</span>
				      			</span>
				      		</a>
				    		<span class="count_text">500</span>
				    		<div class="clearfix"></div>
				    	</div>
				  	</div>
				  	<div class="form-group submit-emo hide">
				  		<div class="col-sm-12 col-right-none">
				  			<div class="facebox ">
				  				<div class="f-header col-md-12">
				  					<a class="group-emo col-md-1 selected" data-group="Hot">Hot</a>
				  					@foreach($group_emoticon as $group)
										<a class="group-emo col-md-1" data-group="{{$group->emo_group}}">{{$group->emo_group}}</a>
									@endforeach
				  					<div class="clearfix"></div>
				  				</div>
				  				<div class="f-content col-md-12"></div>
				  				<div class="clearfix"></div>
				  			</div>
				  		</div>
				  	</div>
				</form>
			</div>
			<div class="col-md-12 link-view-full">Đây là bản rút gọn dành cho Notifications. Click <a class="view-full" href="{{ URL::route('show_posts',array('id'=>$data['post'][0]->id)) }}">vào đây</a> để xem thêm <b>{{ $total_comment_all }}</b> comments khác !</div>
			<div class="stream-permalink-container">
				<ul class="item-stream">

					<li class="loading text-center hide">
						<span class="icon-spin5 animate-spin size"></span>
					</li>
				</ul>
			<div class="clear"></div>
			</div>
		<div class="clear"></div>
		</div>
	</div>
	@include('templates/navRight2')
	<div class="clear"></div>
</div>
@endsection