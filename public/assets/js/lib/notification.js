

function event_scroll(id_post) {
	var parent = $('.read-more').first();
   		var load = parent.attr('data-load');
   		if( load != 'true' ) return;
		$('.loading').removeClass('hide');
   		parent.attr('data-load','false');
       	var ir = id_post;
		var _url = url + 'loadMoreCommentFresh/'+ir;
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
							a.appendChild(document.createTextNode('Load more '+( data.data[i].count_replys.count - $('.id-comment-'+ data.data[i].id).length)+' replies'));
						load_more_rep.appendChild(a);

						if(data.data[i].count_replys.count > $('.id-comment-'+ data.data[i].id).length){
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
					    	var item = array[i];
					    	parent.before( item );
					    	lazy_load($(item).find('img'));
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
		    	message_error(data.msg);
		    }
		});
}

function event_click_reply_onload (Elm,avatar_user) {
	var username = Elm.attr('data-user');
	var content_id = 0,comment_id = 0;
	if(Elm.hasClass('ident-com-0')){
		content_id = Elm.attr('data-content-id');
		comment_id = Elm.attr('data-content-id');
	}
	else {
		content_id = Elm.attr('data-content-id');
		comment_id = Elm.attr('data-comment-id');
	}
	var ul = Elm.parents('ul.item-stream');
	var title = Elm.attr('data-original-title');
	var li ;
	if($('.id-comment-'+content_id).length > 0 ) {
		if( $( 'li.box-reply-'+content_id ).length > 0 ){
			li = $( 'li.box-reply-'+content_id ).last();
		} else li = $('.id-comment-'+content_id).last();
	}
	else li = Elm.parents('li');
	var box_reply = ul.find('li.box-reply-'+content_id).first();
	var value =  box_reply.find('textarea').first().val();
	if(value == '' || value == " "){
		box_reply.remove();
	}
	var box_comment = $('li.box-reply-'+content_id);
	var lenght_box_comment = box_comment.length;
	var is_createElement = true;
	if(lenght_box_comment > 0) {
		box_comment.each(function( index,obj ) {
			if( $(obj).attr('data-original-title') == title){
				is_createElement = false;
				return;
			}
		});
	}
	if(is_createElement) {
		create_box_reply(content_id,title,username,comment_id,Elm);
	} else {
		var textarea = box_comment.find('.content').first().val();
		linkSelection = {
            start: textarea.length,
            end: textarea.length
        };
        doRestore(box_comment.find('.contenteditable').first().children().last()[0], linkSelection);
		box_comment.find('.contenteditable').first().focus();
		box_comment.find('.contenteditable').first().goTo();
	}
	$('body').on('click',event_click_content);
}


$(document).on('click','.loadmore-next-reply, .loadmore-previous-reply',function(e){
	e.preventDefault();
	var _this = $(this);
	var id_comment = _this.attr('data-comment-id');
	var parent = _this.parent().first();
	var _url =  _this.attr('data-url');;
	var id = 0 ;
	var id_min_new = 0;
	if(_this.hasClass('loadmore-next-reply')){
		id = _this.attr('data-id-max-load');
		id_min_new = _this.attr('data-id-min-new');
	}  else  id = $('.id-comment-'+id_comment+':not(.ident-com-0)').first().attr('data-content-id');
	$.ajax({
        type: "POST",
        url: _url,
        data: { id:id,ir:id_comment,id_min_new:id_min_new }
    }).done(function( data ) {
        if(data.error == 'false'){
        	var replies = data.data.replies;
			var _length = replies.length;
			if(_length > 0 ){
				// var count_reply = data.count.count - $('li.id-comment-' + data.data[0].comment_id).length - _length;
				// _this.text('Load more '+count_reply+' replies');
				var count_replys = parseInt(_this.parent().find('span.count-replies').first().text()) + _length;
				var total_replies = parseInt(_this.parent().find('span.total_teplies').first().text());
				_this.parents('ul.stream-reply').first().find('span.count-replies').text(count_replys);
				if(_this.hasClass('loadmore-next-reply')){
					for( var i = 0 ; i < _length ; i ++ ){
						var length_rep = $('li.id-comment-' + replies[i].comment_id).length;
						if(length_rep > 0 ){
							var is_add_reply = false;
							for(var j = 0 ; j < length_rep; j++){

								var ir = parseInt($($('li.id-comment-' + replies[i].comment_id)[j]).attr('data-content-id'));
								if(ir == parseInt(replies[i].id)) {
									is_add_reply = true;
									break;
								}else if(ir > parseInt(replies[i].id)){
									var item = load_more_Reply(replies[i]);
									parent.before( item );
									lazy_load($(item).find('img'));
									is_add_reply = true;
									break;
								}
							}
							if(!is_add_reply){
								var item = load_more_Reply(replies[i]);
								parent.before( item );
								lazy_load($(item).find('img'));
							}

						} else{
							var item = load_more_Reply(replies[i]);
							parent.before( item );
							lazy_load($(item).find('img'));
						} 
					}
					var a_max = 0,a_min = 0 ,b_max = 0 , b_min = 0;
		            if(replies[0] != undefined){
		                a_max = data.data.max_id;
		                b_max = replies[0].max_id_all;
		            }
					_this.attr('data-id-max-load',a_max);
					if(a_max == b_max ) parent.remove();
				}else {
					for( var i = 0 ; i < _length ; i ++ ){
						var item = load_more_Reply(replies[i]);
						$('.id-comment-'+id_comment+':not(.ident-com-0)').first().before(item);
						lazy_load($(item).find('img'));
					}
					var a_max = 0,a_min = 0 ,b_max = 0 , b_min = 0;
		            if(replies[0] != undefined){
		                a_min = data.data.min_id;
		                b_min = replies[0].min_id_all;
		            }

					if(a_min == b_min ) parent.remove();
				}
			}else {
				parent.remove();
			}

        }else {
        	message_error(data.msg);
        }
    });
});

$(document).on('click','.loadmore-next-comment, .loadmore-previous-comment',function(e){
	e.preventDefault();
		var _this = $(this);
		var id_post = _this.attr('data-post-id');
		var parent = _this.parent().first();
		var _url =  _this.attr('data-url');;
		var id = 0 ;
		if(_this.hasClass('loadmore-next-comment')){
			if($('.ident-com-0').length > 0){
				id = $('.ident-com-0').first().attr('data-content-id');
			}
		}  else  id = $('.ident-com-0').last().attr('data-content-id');
		$.ajax({
            type: "POST",
            url: _url,
            data: { id:id,ir:id_post }
        }).done(function( data ) {
            if(data.error == 'false'){
				var _length = data.data.length;
				if(_length > 0 ){
					_this.parents('ul.item-stream').find('span.count-comments').text(parseInt(_this.parent().find('span.count-comments').first().text()) + _length);
					if(!_this.hasClass('loadmore-next-comment')){
						for( var i = 0 ; i < _length ; i ++ ){
							var item = load_more_Comment(data.data[i])
							item.setAttribute('data-content-id',data.data[i].id);
							$(parent).before( item );
							lazy_load($(item).find('img'));
							var ul = document.createElement('ul');
							ul.className = 'stream-reply';
							ul.setAttribute('data-id-comment',data.data[i].id);
								var load_more_rep = document.createElement('div');
								load_more_rep.className = 'load-more-rep';
									var span_count_total_rep = document.createElement('span');
									span_count_total_rep.className = 'count-total-rep';
										var span_count_replies = document.createElement('span');
										span_count_replies.className = 'count-replies';
										span_count_replies.appendChild(document.createTextNode('0'));
									span_count_total_rep.appendChild(span_count_replies);
									var span_total_teplies = document.createElement('span');
									span_total_teplies.className = 'total_teplies';
									span_total_teplies.appendChild(document.createTextNode(data.data[i].count_replys.count));

									span_count_total_rep.appendChild(document.createTextNode(' trong số '));
									span_count_total_rep.appendChild(span_total_teplies);
								load_more_rep.appendChild(span_count_total_rep);
									var a = document.createElement('a');
									a.href = 'javascript:void(0)';
									a.className="loadmore-next-reply";
									a.setAttribute('data-comment-id',data.data[i].id);
									a.setAttribute('data-url',url+"loadMoreNextReply")
									a.appendChild(document.createTextNode('Xem trả lời mới'));
								load_more_rep.appendChild(a);
							if(data.data[i].count_replys.count > $('.id-comment-'+ data.data[i].id).length){
								ul.appendChild(load_more_rep);
							}
							var _length_reply = data.data[i].replys.length;
							for( var j = 0 ; j < _length_reply ; j ++ ){
								if($(ul).find('.load-more-rep').length == 0 ){
									var item = load_more_Reply(data.data[i].replys[j]);
									ul.appendChild( item);
									lazy_load($(item).find('img'));
								}else {
									var item = load_more_Reply(data.data[i].replys[j]);
									$(load_more_rep).before(item);
									lazy_load($(item).find('img'));
								}
							}
							$(parent).before(ul);
							lazy_load($(ul).find('img'));

							if(data.count.count >= $('.ident-com-0').length ) parent.addClass('hide');
						}
					}else {
						for( var i = 0 ; i < _length ; i ++ ){
							var item = load_more_Comment(data.data[i])
							item.setAttribute('data-content-id',data.data[i].id);
							$('.ident-com-0').first().before( item );
							lazy_load($(item).find('img'));
							var ul = document.createElement('ul');
							ul.className = 'stream-reply';
							ul.setAttribute('data-id-comment',data.data[i].id);
								var load_more_rep = document.createElement('div');
								load_more_rep.className = 'load-more-rep';
									var span_count_total_rep = document.createElement('span');
									span_count_total_rep.className = 'count-total-rep';
										var span_count_replies = document.createElement('span');
										span_count_replies.className = 'count-replies';
										span_count_replies.appendChild(document.createTextNode('0'));
									span_count_total_rep.appendChild(span_count_replies);
									var span_total_teplies = document.createElement('span');
									span_total_teplies.className = 'total_teplies';
									span_total_teplies.appendChild(document.createTextNode(data.data[i].count_replys.count));

									span_count_total_rep.appendChild(document.createTextNode(' trong số '));
									span_count_total_rep.appendChild(span_total_teplies);
								load_more_rep.appendChild(span_count_total_rep);
									var a = document.createElement('a');
									a.href = 'javascript:void(0)';
									a.className="loadmore-next-reply";
									a.setAttribute('data-comment-id',data.data[i].id);
									a.setAttribute('data-url',url+"loadMoreNextReply")
									a.appendChild(document.createTextNode('Xem trả lời mới'));
								load_more_rep.appendChild(a);
							if(data.data[i].count_replys.count > $('.id-comment-'+ data.data[i].id).length){

								ul.appendChild( load_more_rep );
							}
							var _length_reply = data.data[i].replys.length;
							for( var j = 0 ; j < _length_reply ; j ++ ){
								if($(ul).find('.load-more-rep').length == 0 ){
									var item = load_more_Reply(data.data[i].replys[j]);
									ul.appendChild( item );
									lazy_load($(item).find('img'));
								}else {
									var item = load_more_Reply(data.data[i].replys[j]);
									$(load_more_rep).before();
									lazy_load($(item).find('img'));
								}
							}
							$('.ident-com-0').first().after(ul);

							if(data.count.count >= $('.ident-com-0').length ) parent.addClass('hide');
						}
					}
				}else {
					parent.addClass('hide');
				}

            }else {
            	message_error(data.msg);
            }
        });
});