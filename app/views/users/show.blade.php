@extends ("main")
@section ('title')
<?php
    $GutloComment_count = new GutloComment();
	$activity = new ActivityController();
	$commentController = new CommentController();
	$comments = $commentController->getCommentFreshById($data->id);
	$count_comment = $GutloComment_count->get_count_comment($data->id,$_auth['auth_id']);
	$GutloReply = new GutloReply();
?>

<link rel="image_src" href="{{{ url('/'.$data->ava ) }}}">
<link rel="canonical" href="{{ URL::route('show_posts',array('id'=>$data->id)) }}">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- <meta name="robots" content="index, follow"> -->
<meta name="referrer" content="always">
<meta name="keywords" content="gutlo, gutlo.com, brick, comment, post">
<meta name="description" content="gutlo: relax space">

<meta itemprop="name" content="{{{ $data->title }}}">
<meta itemprop="description" content="{{{$data->content}}}">
<meta itemprop="image" content="{{{ url('/'.$data->ava ) }}}">

<meta name="twitter:card" content="photo">
<meta name="twitter:site" content="@gutlo">
<meta name="twitter:image" content="{{{ url('/'.$data->ava ) }}}">
<!-- <meta name="twitter:title" content="Page Title">
<meta name="twitter:description" content="Page description less than 200 characters">
<meta name="twitter:creator" content="@author_handle">
 -->
<meta property="og:title" content="{{{ $data->title }}}">
<meta property="og:site_name" content="Gutlo">
<meta property="og:url" content="{{ URL::route('show_posts',array('id'=>$data->id))  }}">
<meta property="og:description" content="{{{$data->content}}}">
<meta property="og:type" content="article">
<meta property="og:image" content="{{{ url('/'.$data->ava ) }}}">
<meta property="article:section" content="Article Section" />
<meta property="article:tag" content="Article Tag" />
<meta name="author" content="{{{$data->nickname}}}">
<meta property="article:published_time" content="{{{$data->created_time['time-ago']}}}" />
<meta property="fb:admins" content="10205272775250051" />
<title>{{{$data->nickname}}} on gutlo.com : "{{{ $data->title }}}" </title>
@endsection
@section('content')
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script type="text/javascript">
	var emoticons = {{$emoticons}};
	var emo_group = {{ $group_emoticon_js }};
	socket.on('data_{{ $data->id}}_{{ $_auth["auth_name"] }}', function (data) {
		//Do something with data
		callback_socket_show(data);
	});
	$(window).scroll(function() {
	   if($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
			var parent = $('.read-more').first();
	   		var load = parent.attr('data-load');
	   		if( load != 'true' ) return;
			$('.loading').removeClass('hide');
	   		parent.attr('data-load','false');
	       	var post_id = '{{ $data->id }}';
			var _url = url + 'loadMoreCommentFresh/'+post_id;
			var count = $('.ident-com-0').length;
			$.ajax({
			    type: "POST",
			    url: _url,
			    data: { count:count }
			}).done(function( data ) {
			    if(data.error == 'false'){
					$('.loading').addClass('hide');
					// $('.comment-'+ir).find('.reply').text(data.data.total_comment);
					// $('.stream').find('ul.post-stats').first().find('.comment').text(data.data.total_comment_post);
					var array = [];
					var _length = data.data.length;
					if(_length > 0 ){
						for( var i = 0 ; i < _length ; i ++ ){
							array.push( load_more_Comment(data.data[i]) );
							var load_more_rep = document.createElement('div');
							var _length_reply = data.data[i].replys.length;
							load_more_rep.className = 'load-more-rep';
								var a = document.createElement('a');
								a.href = 'javascript:void(0)';
								a.className="load-more-reply";
								a.setAttribute('data-ir',data.data[i].id);
								a.appendChild(document.createTextNode('Xem thêm '+( data.data[i].count_replys.count - ( $('.id-comment-'+ data.data[i].id).length + _length_reply)) +' trả lời.'));
							load_more_rep.appendChild(a);

							if(data.data[i].count_replys.count > $('.id-comment-'+ data.data[i].id).length + _length_reply){
								array.push( load_more_rep );
							}
							var ul = document.createElement('ul');
							ul.className = 'stream-reply';
							ul.setAttribute('data-ir',data.data[i].id);
							for( var j = 0 ; j < _length_reply ; j ++ ){
								ul.appendChild( load_more_Reply(data.data[i].replys[j]));
							}

							array.push( ul );

						if(data.count.count >= $('.ident-com-0').length ) parent.addClass('hide');

						}
						setTimeout(function() {
						    for(var i = 0 ; i < array.length; i ++ ){
						    	parent.before( array[i] );
						    }
						    var count_comment = $('li.ident-com-0').length;
							var page = parent.attr('data-page');
							var a = page * 60;
							var b = count_comment - a ;
							if( b%6 == 0 ){
								parent.removeClass('hide');
							}else {
								parent.attr('data-load','true');
							}
						}, 550)
					}else {
						parent.attr('data-load','false');
						$('.loading').addClass('hide');
						parent.addClass('hide');
					}

			    }else {
			    	alert(data.msg);
			    }
			});
	   }
	});
$(document).ready(function(){
	lazy_load($('img'));
	var parent = $($('ul.item-stream').children()[0]);
	var post_id = {{{ $data->id }}};
	var _url = url + 'fresh-comment/' + post_id;;
	load_data_comment (_url,post_id,parent) ;
});
</script>

<div id="show-page" class="stream row">
	<div id=""class="col-md-9 left-stream left-stream-media" >
		<div class="PostBox permalink" data-ir = "">
			<div class="PostBoxContent">
				<div class="BoxContent">
					<div class="post-header">
						<div class="ava42-only">
							<a href="{{{ url( '/'.$data->username ) }}}" class="ava-user">
								<img alt="ava of {{{$data->username}}}"class="img-rounded" src="{{{ url('/'.$data->ava ) }}}">
							</a>
						</div>
					    <div class="header-analytics-only">
					        <a class="full-name" href="{{{ url( '/'.$data->username ) }}}">
					        	<div class="nick-name">{{{ $data->nickname }}}</div>
					        	<small class="username">{{{ '@'.$data->username }}}</small>
					        </a>
					    </div>
					    <span class="span-space">-</span>
					    <div class="time-post time-top">
					    	<small>{{ $data->created_time['time-ago'] }}</small>
					    </div>
					    <div class="post-nav">
						  	<a class="" data-entry-key="aE10GMo" href=""></a>
						  	<a class="next" data-entry-key="aE10GMo" href="{{$data->next_post}}">
						  		<span class="next-label">Tiếp</span>
						  		<span class="arrow"></span>
						  	</a>
						</div>
					</div>
					<div class="title-post">
						@foreach($data->Categories as $category)
						<h1 class="title-show-page"><a class="cate-post" href="{{ url('/g/'.$category->cate_code ) }}"><span>{{{ $category->name.': ' }}}</span></a>
							{{{ $data->title }}}
						</h1>
						@endforeach

					</div>
					<div role="group" class="button-action-line" data-ir="{{ $data->id }}" data-title="post-icon">
						<div class="pull-left button-margin">
						    <button class="button-action icons-brick @if($data->brick_content) hide @endif" autocomplete="off" type="button" data-content-id="{{ $data->id }}" data-loading-text="<span class='icons-bricks icon-brick'></span>" data-action="brick-post" data-original-title="Brick">
						      <span class="icons-bricks icon-brick"></span>
						    </button>
						    <button class="button-action icons--brick @if(!$data->brick_content) hide @endif" autocomplete="off"  type="button" data-content-id="{{ $data->id }}" data-loading-text="<span class='icons--bricks icon-brick'></span>" data-action="brick-post" data-original-title="Brick">
						      <span class="icons--bricks icon-brick"></span>
						    </button>
						</div>
						<div class="pull-left button-margin">
						    <button class="button-action icon-like @if($data->like_content) hide @endif " type="button" autocomplete="off" data-loading-text="<span class='icons-like icon-emo-thumbsup'></span>" data-content-id="{{ $data->id }}" data-action="like-post" data-original-title="Like">
						     	<span class="icons-like icon-emo-thumbsup"></span>
						    </button>
						    <button class="button-action icon--like @if(!$data->like_content) hide @endif" type="button" autocomplete="off" data-loading-text="<span class='icons-like icon-emo-thumbsup'></span>" data-content-id="{{ $data->id }}" data-action="like-post" data-original-title="Like">
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

					<ul class="post-stats" data-post-id="{{ $data->id }}">
						<li class="brick-count">
							<a href="javascript:void(0)">Gạch
								<strong class="brick">{{ $data->total_brick }}</strong>
							</a>
						</li>
						<li class="like-count">
							<a href="javascript:void(0)">Thích
								<strong class="like">{{ $data->total_like }}</strong>
							</a>
						</li>
						<li class="comment-count">
							<a href="javascript:void(0)">Bình luận
									<strong class="comment">{{ $GutloComment_count->get_total_comment($data->id); }}</strong>
							</a>
						</li>
						<li class="point-count">
							<a href="javascript:void(0)">Vàng
									<strong class="point">{{ $data->total_point }}</strong>
							</a>
						</li>
						<div class="avatar-row pull-left">
							@if(!empty($data->users_action))
								@foreach($data->users_action as $user_action)
								<a class="ava-rela" href="{{ url( '/'.$user_action->username )}}" data-toggle="tooltip" data-placement="top" title="{{{$user_action->username}}}">
									<img class="img-rounded ava25" src="{{ url( '/'.$user_action->ava )}}">
								</a>
								@endforeach
							@endif
						</div>
					</ul>
					@if (!empty($data->link))
					<div class="link-show-page"><a class="link-to-web text-nowrap-full" href="{{$data->link}}">{{{$data->link}}}</a></div>
					@endif
					<div class="post-content">{{ $data->new_content }}
						@if( $data->image != null)
							@if($data->type_media == 0)
						<!-- <a class="link-to-web text-nowrap-full" href="{{$data->link}}"> -->
							<img src="{{url($data->image_thumb)}}" data-src="{{$data->image}}" class="media-thumb">
							@else
							<div id="player">
								@if($data->embed_id == 1)
									<iframe class="video-play" width="800" height="400" src="https://www.youtube.com/embed/{{ $data->media_api_id }}?feature=oembed&autoplay=1&wmode=opaque&rel=0&showinfo=0&modestbranding=0" frameborder="0" allowfullscreen=""></iframe>
								@else
									<div class="fb-video" data-href="{{ $data->link }}" data-allowfullscreen="true" data-width="800"></div>
								@endif
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
				        	<a class="share-facebook share" href="javascript:void(0)" id="share-fb" data-ir="{{ $data->id }}" data-width="700" data-height="300" data-share="{{ url('/posts/') }}">Share on Facebook</a>
				        </li>
				        <li class="share-after-post-t col-md-6 ">
				        	<a class="share-twitter share" href="https://twitter.com/home?status={{ $data->title }}" data-track="" data-evt="" data-title="" data-share="">Share on Twitter</a>
				        </li>
				<div class="clear"></div>

				    </ul>
				</div>
				<div class="clear"></div>
			</div>
			<div class="info-after-post">
				<div class="info-top">
					<div class="pull-right">
						@if($_auth["auth_permission"] > 1)
							<i class="icon-alert  mod-block" data-action="block-post" data-content-id="{{ $data->id }}" data-toggle="tooltip" data-placement="top" data-original-title="Block!!"></i>
						@endif
						<span class="current-time" data-time="{{ $data->created_time['seconds'] }}"></span>
						<span class="badge-only">-</span>
						@if($data->username == $_auth['auth_name'])
						<a href="javascript:void(0)" class="delete" data-post-id="{{ $data->id }}" data-action="delete-post"><i class="icon-trash"></i>Xóa</a>
						@else
						<a href="javascript:void(0)" class="report" data-post-id="{{ $data->id }}" data-action="report-post"><i class="icon-flag-filled"></i>Vi Phạm</a>
						@endif
					</div>
						<div class="clear"></div>

				</div>
				<div class="info-bottom">
					<div>
						<h1 class="info-comment pull-left" ><span class="comment">{{ $GutloComment_count->get_total_comment($data->id); }}</span> Bình luận</h1>
					</div>
					<div class="pull-right">
						<a class="fresh-nav pull-right hot-fresh-active" data-action="fresh-comment" data-post-id="{{ $data->id }}" href="javascript:void(0)">Mới</a>
						<a class="hot-nav pull-right" data-action="hot-comment" data-post-id="{{ $data->id }}" href="javascript:void(0)">Đang Hot</a>
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
				<form id="comment_post" method="post" action="{{ url('/'.'comment/'.$data->id ) }}" enctype="application/x-www-form-urlencoded" autocomplete="off" class="form-horizontal">
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
				    	<div class="col-sm-12 col-right-none comment-uti">
				      		<button type="submit" id="submit-newComment" data-loading-text="Loading..." class="btn btn-primary pull-right" autocomplete="off">
				      			<span class="submit-cmm">Đăng</span>
				      		</button>
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
				  		<div class="col-md-12 col-right-none">
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