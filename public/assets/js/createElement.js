function new_Comment(data) {
	var li = document.createElement('li');
	li.className = 'ident-com-0 comment-'+data.comment_id;
	li.setAttribute('data-user',data.user.username);
	li.setAttribute('data-content-id',data.comment_id);
	li.setAttribute('data-comment-id',data.comment_id);
		var div_PostBoxContent = document.createElement('div');
		div_PostBoxContent.className = 'PostBoxContent';
			var div_BoxContent_com = document.createElement('div');
			div_BoxContent_com.className = 'BoxContent-com';
				var div_ava50_com = document.createElement('div');
				div_ava50_com.className = 'ava40-com';
					var a_ava50_com = document.createElement('a');
					a_ava50_com.className = 'ava-user';
					a_ava50_com.href = url + data.user.username;
						var img_ava50_com = document.createElement('img');
						img_ava50_com.className = "img-rounded";
						img_ava50_com.src = data.user.ava;
						// img_ava50_com.setAttribute('data-original',data.user.ava);
					a_ava50_com.appendChild(img_ava50_com);
				div_ava50_com.appendChild(a_ava50_com);
			div_BoxContent_com.appendChild(div_ava50_com);

				var div_post_header_com = document.createElement('div');
				div_post_header_com.className = 'post-header-com';
					var div_header_analytics = document.createElement('div');
					div_header_analytics.className = 'header-analytics';
						var a_full_name = document.createElement('a');
						a_full_name.className = 'full-name';
						a_full_name.href = '/'+data.user.username;
							var div_username = document.createElement('div');
							div_username.className = 'username inline-block ident-com';
							div_username.appendChild(document.createTextNode(data.user.username));
						a_full_name.appendChild(div_username);
					div_header_analytics.appendChild(a_full_name);
				div_post_header_com.appendChild(div_header_analytics);

					var div_header_point = document.createElement('div');
					div_header_point.className = 'header-point';
						var small_header_point = document.createElement('small');
							var span_header_point = document.createElement('span');
							span_header_point.className = 'point';
							span_header_point.appendChild(document.createTextNode('0'));
						small_header_point.appendChild(span_header_point);
						small_header_point.appendChild(document.createTextNode(' vàng'));
					div_header_point.appendChild(small_header_point);
				div_post_header_com.appendChild(div_header_point);

					var div_time_post = document.createElement('div');
					div_time_post.className = 'time-post';
						var small_time_post = document.createElement('small');
						small_time_post.appendChild(document.createTextNode(' - '+data.time['time-ago']));
					div_time_post.appendChild(small_time_post);
				div_post_header_com.appendChild(div_time_post);

				if(data.user.username != AuthName) {
					var div_report_action = document.createElement('div');
					div_report_action.className = 'pull-right none-space';
						var button_report = document.createElement('button');
						button_report.className = 'button-action button-report none-space';
						button_report.type = 'button';
						button_report.setAttribute('data-original-title','report-comment');
						button_report.setAttribute('data-action','report-comment');
							var strong_report = document.createElement('strong');
							strong_report.className = 'strong-com delete-report-ico';
							strong_report.setAttribute('data-toggle','tooltip');
							strong_report.setAttribute('data-placement','top');
							strong_report.setAttribute('data-original-title','Vi phạm');
						button_report.appendChild(strong_report);
					div_report_action.appendChild(button_report);
				div_post_header_com.appendChild(div_report_action);
				} else {
					var div_delete_action = document.createElement('div');
					div_delete_action.className = 'pull-right none-space';
						var button_delete = document.createElement('button');
						button_delete.className = 'button-action button-delete none-space';
						button_delete.type = 'button';
						button_delete.setAttribute('data-original-title','delete-comment');
						button_delete.setAttribute('data-action','delete-comment');
							var strong_delete = document.createElement('strong');
							strong_delete.className = 'strong-com delete-report-ico';
							strong_delete.setAttribute('data-toggle','tooltip');
							strong_delete.setAttribute('data-placement','top');
							strong_delete.setAttribute('data-original-title','Xóa');
						button_delete.appendChild(strong_delete);
					div_delete_action.appendChild(button_delete);
				div_post_header_com.appendChild(div_delete_action);
				}
			div_BoxContent_com.appendChild(div_post_header_com);

				var div_post_content_com = document.createElement('div');
				div_post_content_com.className = 'post-content-com';
				div_post_content_com.innerHTML =  data.new_content;
			div_BoxContent_com.appendChild(div_post_content_com);

				var div_button_action_line = document.createElement('div');
				div_button_action_line.className = 'button-action-line button-action-line-com';
				div_button_action_line.setAttribute('role','group');
				div_button_action_line.setAttribute('data-title','post-icon');

				var div_pull_left_Reply = document.createElement('div');
					div_pull_left_Reply.className = 'pull-left btn-relative';
						var button_action_Reply = document.createElement('button');
						button_action_Reply.className = 'button-action button-reply';
						button_action_Reply.setAttribute('type','button');
						button_action_Reply.setAttribute('data-original-title','Comment');
							var strong_reply = document.createElement('strong');
							strong_reply.className = 'strong-com reply';
							strong_reply.innerHTML = 'Trả lời';
						button_action_Reply.appendChild(strong_reply);
					div_pull_left_Reply.appendChild(button_action_Reply);

				div_button_action_line.appendChild(div_pull_left_Reply);
					var div_pull_left_brick = document.createElement('div');
					div_pull_left_brick.className = 'pull-left btn-relative';
						var button_action_brick_true = document.createElement('button');
						button_action_brick_true.className = 'button-action icons-brick';
						button_action_brick_true.setAttribute('type','button');
						button_action_brick_true.setAttribute('autocomplete','off');
						button_action_brick_true.setAttribute('data-loading-text',"<span class='icons-bricks icon-brick'></span>");
						button_action_brick_true.setAttribute('data-action','brick-comment');
						button_action_brick_true.setAttribute('data-original-title','Brick');
							var span_action_brick_true = document.createElement('span');
							span_action_brick_true.className = 'icons-bricks icon-brick';
						button_action_brick_true.appendChild(span_action_brick_true);
					div_pull_left_brick.appendChild(button_action_brick_true);

						var button_action_brick_false = document.createElement('button');
						button_action_brick_false.className = 'button-action icons--brick hide';
						button_action_brick_false.setAttribute('autocomplete','off');
						button_action_brick_false.setAttribute('data-loading-text',"<span class='icons-bricks icon-brick'></span>");
						button_action_brick_false.setAttribute('type','button');
						button_action_brick_false.setAttribute('data-action','brick-comment');
						button_action_brick_false.setAttribute('data-original-title','Brick');
							var span_action_brick_false = document.createElement('span');
							span_action_brick_false.className = 'icons--bricks icon-brick';
						button_action_brick_false.appendChild(span_action_brick_false);
					div_pull_left_brick.appendChild(button_action_brick_false);
						var strong_brick = document.createElement('strong');
						strong_brick.className = 'strong-com brick';
						strong_brick.innerHTML = '0';
						div_pull_left_brick.appendChild(document.createTextNode(' '));
					div_pull_left_brick.appendChild(strong_brick);
				div_button_action_line.appendChild(div_pull_left_brick);

					var div_pull_left_like = document.createElement('div');
					div_pull_left_like.className = 'pull-left btn-relative';
						var button_action_like_true = document.createElement('button');
						button_action_like_true.className = 'button-action icon-like';
						button_action_like_true.setAttribute('type','button');
						button_action_like_true.setAttribute('autocomplete','off');
						button_action_like_true.setAttribute('data-loading-text',"<span class=' icons-like icon-emo-thumbsup'></span>");
						button_action_like_true.setAttribute('data-action','like-comment');
						button_action_like_true.setAttribute('data-original-title','Like');
						var span_action_like_true = document.createElement('span');
							span_action_like_true.className = 'icons-like icon-emo-thumbsup';
						button_action_like_true.appendChild(span_action_like_true);
					div_pull_left_like.appendChild(button_action_like_true);

						var button_action_like_false = document.createElement('button');
						button_action_like_false.className = 'button-action icon--like hide';
						button_action_like_false.setAttribute('autocomplete','off');
						button_action_like_false.setAttribute('data-loading-text',"<span class=' icons-like icon-emo-thumbsup'></span>");
						button_action_like_false.setAttribute('type','button');
						button_action_like_false.setAttribute('data-action','like-comment');
						button_action_like_false.setAttribute('data-original-title','Like');
						var span_action_like_false = document.createElement('span');
							span_action_like_false.className = 'icons--like icon-emo-thumbsup';
						button_action_like_false.appendChild(span_action_like_false);
					div_pull_left_like.appendChild(button_action_like_false);
						var strong_like = document.createElement('strong');
						strong_like.className = 'strong-com like';
						strong_like.innerHTML = '0';

					div_pull_left_like.appendChild(document.createTextNode(' '));
					div_pull_left_like.appendChild(strong_like);
					if(typeof auth_permission !== 'undefined'){
							var i = document.createElement('i');
							i.className = 'icon-alert icon-mod-block-com-rep mod-block';
							i.setAttribute('data-action','block-comment');
							i.setAttribute('data-content-id',data.comment_id);
							i.setAttribute('data-toggle','tooltip');
							i.setAttribute('data-placement','top');
							i.setAttribute('data-original-title','Block!!');
						div_button_action_line.appendChild(i);
					}
				div_button_action_line.appendChild(div_pull_left_like);


					var div_clear_button_action_line = document.createElement('div');
					div_clear_button_action_line.className = 'clear';
				div_button_action_line.appendChild(div_clear_button_action_line);
			div_BoxContent_com.appendChild(div_button_action_line);
		div_PostBoxContent.appendChild(div_BoxContent_com);
	li.appendChild(div_PostBoxContent);
	return li;
}

function load_more_Comment(data) {
	var comment_id = data.id;
	var li = document.createElement('li');
	li.className = 'ident-com-0 id-comment-'+comment_id;
	li.setAttribute('data-user',data.username);
	li.setAttribute('data-content-id',comment_id);
	li.setAttribute('data-comment-id',comment_id);
		var div_PostBoxContent = document.createElement('div');
		div_PostBoxContent.className = 'PostBoxContent';
			var div_BoxContent_com = document.createElement('div');
			div_BoxContent_com.className = 'BoxContent-com';
				var div_ava50_com = document.createElement('div');
				div_ava50_com.className = 'ava40-com';
					var a_ava50_com = document.createElement('a');
					a_ava50_com.className = 'ava-user';
					a_ava50_com.href = url + data.username;
						var img_ava50_com = document.createElement('img');
						img_ava50_com.className = "img-rounded";
						img_ava50_com.src = url+data.ava;
						//img_ava50_com.setAttribute('data-original',url+data.ava);
					a_ava50_com.appendChild(img_ava50_com);
				div_ava50_com.appendChild(a_ava50_com);
			div_BoxContent_com.appendChild(div_ava50_com);

				var div_post_header_com = document.createElement('div');
				div_post_header_com.className = 'post-header-com';
					var div_header_analytics = document.createElement('div');
					div_header_analytics.className = 'header-analytics';
						var a_full_name = document.createElement('a');
						a_full_name.className = 'full-name';
						a_full_name.href = '/'+data.username;
							var div_username = document.createElement('div');
							div_username.className = 'username inline-block ident-com';
							div_username.appendChild(document.createTextNode(data.username));
						a_full_name.appendChild(div_username);
					div_header_analytics.appendChild(a_full_name);
				div_post_header_com.appendChild(div_header_analytics);

					var div_header_point = document.createElement('div');
					div_header_point.className = 'header-point';
						var small_header_point = document.createElement('small');
						var span_header_point = document.createElement('span');
							span_header_point.className = 'point';
							var point = data.total_point_com;
							if(point == null) point = 0;
							span_header_point.appendChild(document.createTextNode(point));
						small_header_point.appendChild(span_header_point);
						small_header_point.appendChild(document.createTextNode(' vàng'));
					div_header_point.appendChild(small_header_point);
				div_post_header_com.appendChild(div_header_point);

					var div_time_post = document.createElement('div');
					div_time_post.className = 'time-post';
						var small_time_post = document.createElement('small');
						small_time_post.appendChild(document.createTextNode(' - '+ data.created_time['time-ago']));
					div_time_post.appendChild(small_time_post);
				div_post_header_com.appendChild(div_time_post);

				if(data.username != AuthName) {
					var div_report_action = document.createElement('div');
					div_report_action.className = 'pull-right none-space';
						var button_report = document.createElement('button');
						button_report.className = 'button-action button-report none-space';
						button_report.type = 'button';
						button_report.setAttribute('data-original-title','report-comment');
						button_report.setAttribute('data-action','report-comment');
							var strong_report = document.createElement('strong');
							strong_report.className = 'strong-com delete-report-ico';
							strong_report.setAttribute('data-toggle','tooltip');
							strong_report.setAttribute('data-placement','top');
							strong_report.setAttribute('data-original-title','Vi phạm');
						button_report.appendChild(strong_report);
					div_report_action.appendChild(button_report);
				div_post_header_com.appendChild(div_report_action);
				} else {
					var div_delete_action = document.createElement('div');
					div_delete_action.className = 'pull-right none-space';
						var button_delete = document.createElement('button');
						button_delete.className = 'button-action button-delete none-space';
						button_delete.type = 'button';
						button_delete.setAttribute('data-original-title','delete-comment');
						button_delete.setAttribute('data-action','delete-comment');
							var strong_delete = document.createElement('strong');
							strong_delete.className = 'strong-com delete-report-ico';
							strong_delete.setAttribute('data-toggle','tooltip');
							strong_delete.setAttribute('data-placement','top');
							strong_delete.setAttribute('data-original-title','Xóa');
						button_delete.appendChild(strong_delete);
					div_delete_action.appendChild(button_delete);
				div_post_header_com.appendChild(div_delete_action);
				}
			div_BoxContent_com.appendChild(div_post_header_com);

				var div_post_content_com = document.createElement('div');
				div_post_content_com.className = 'post-content-com';
				div_post_content_com.innerHTML  = data.new_content;
			div_BoxContent_com.appendChild(div_post_content_com);

				var div_button_action_line = document.createElement('div');
				div_button_action_line.className = 'button-action-line button-action-line-com';
				div_button_action_line.setAttribute('role','group');
				div_button_action_line.setAttribute('data-title','post-icon');
					var div_pull_left_Reply = document.createElement('div');
					div_pull_left_Reply.className = 'pull-left btn-relative';
						var button_action_Reply = document.createElement('button');
						button_action_Reply.className = 'button-action button-reply';
						button_action_Reply.setAttribute('type','button');
						button_action_Reply.setAttribute('data-original-title','Comment');
							var strong_reply = document.createElement('strong');
							strong_reply.className = 'strong-com reply';
							strong_reply.innerHTML = 'Trả lời';
						button_action_Reply.appendChild(strong_reply);
					div_pull_left_Reply.appendChild(button_action_Reply);
				div_button_action_line.appendChild(div_pull_left_Reply);
					var div_pull_left_brick = document.createElement('div');
					div_pull_left_brick.className = 'pull-left btn-relative';
						var button_action_brick_true = document.createElement('button');
						if(data.brick_content == null || data.brick_content == "0"){
							button_action_brick_true.className = 'button-action icons-brick';
						}else button_action_brick_true.className = 'button-action icons-brick hide';
						button_action_brick_true.setAttribute('autocomplete','off');
						button_action_brick_true.setAttribute('data-loading-text',"<span class='icons-bricks icon-brick'></span>");
						button_action_brick_true.setAttribute('type','button');
						button_action_brick_true.setAttribute('data-action','brick-comment');
						button_action_brick_true.setAttribute('data-original-title','Brick');
							var span_action_brick_true = document.createElement('span');
							span_action_brick_true.className = 'icons-bricks icon-brick';
						button_action_brick_true.appendChild(span_action_brick_true);
					div_pull_left_brick.appendChild(button_action_brick_true);

						var button_action_brick_false = document.createElement('button');
						if(data.brick_content == null || data.brick_content == "0"){
							button_action_brick_false.className = 'button-action icons--brick hide';
						}else button_action_brick_false.className = 'button-action icons--brick ';
						button_action_brick_false.setAttribute('autocomplete','off');
						button_action_brick_false.setAttribute('data-loading-text',"<span class='icons-bricks icon-brick'></span>");
						button_action_brick_false.setAttribute('type','button');
						button_action_brick_false.setAttribute('data-action','brick-comment');
						button_action_brick_false.setAttribute('data-original-title','Brick');
							var span_action_brick_false = document.createElement('span');
							span_action_brick_false.className = 'icons--bricks icon-brick';
						button_action_brick_false.appendChild(span_action_brick_false);
					div_pull_left_brick.appendChild(button_action_brick_false);
						var strong_brick = document.createElement('strong');
						strong_brick.className = 'strong-com brick';
						strong_brick.innerHTML = data.total_brick;
					div_pull_left_brick.appendChild(document.createTextNode(' '));
					div_pull_left_brick.appendChild(strong_brick);
				div_button_action_line.appendChild(div_pull_left_brick);

					var div_pull_left_like = document.createElement('div');
					div_pull_left_like.className = 'pull-left btn-relative';
						var button_action_like_true = document.createElement('button');
						if(data.like_content == null || data.like_content == "0"){
							button_action_like_true.className = 'button-action icon-like';
						} else button_action_like_true.className = 'button-action icon-like hide';
						button_action_like_true.setAttribute('autocomplete','off');
						button_action_like_true.setAttribute('data-loading-text',"<span class=' icons-like icon-emo-thumbsup'></span>");
						button_action_like_true.setAttribute('type','button');
						button_action_like_true.setAttribute('data-action','like-comment');
						button_action_like_true.setAttribute('data-original-title','Like');
						var span_action_like_true = document.createElement('span');
							span_action_like_true.className = 'icons-like icon-emo-thumbsup';
						button_action_like_true.appendChild(span_action_like_true);
					div_pull_left_like.appendChild(button_action_like_true);

						var button_action_like_false = document.createElement('button');
						if(data.like_content == null || data.like_content == "0"){
							button_action_like_false.className = 'button-action icon--like hide';
						} else button_action_like_false.className = 'button-action icon--like ';
						button_action_like_false.setAttribute('autocomplete','off');
						button_action_like_false.setAttribute('data-loading-text',"<span class=' icons-like icon-emo-thumbsup'></span>");
						button_action_like_false.setAttribute('type','button');
						button_action_like_false.setAttribute('data-action','like-comment');
						button_action_like_false.setAttribute('data-original-title','Like');
						var span_action_like_false = document.createElement('span');
							span_action_like_false.className = 'icons--like icon-emo-thumbsup';
						button_action_like_false.appendChild(span_action_like_false);
					div_pull_left_like.appendChild(button_action_like_false);
						var strong_like = document.createElement('strong');
						strong_like.className = 'strong-com like';
						strong_like.innerHTML = data.total_like;
					div_pull_left_like.appendChild(document.createTextNode(' '));
					div_pull_left_like.appendChild(strong_like);
					if(typeof auth_permission !== 'undefined'){
							var i = document.createElement('i');
							i.className = 'icon-alert icon-mod-block-com-rep mod-block';
							i.setAttribute('data-action','block-comment');
							i.setAttribute('data-content-id',data.id);
							i.setAttribute('data-toggle','tooltip');
							i.setAttribute('data-placement','top');
							i.setAttribute('data-original-title','Block!!');
						div_button_action_line.appendChild(i);
					}
				div_button_action_line.appendChild(div_pull_left_like);


					var div_clear_button_action_line = document.createElement('div');
					div_clear_button_action_line.className = 'clear';
				div_button_action_line.appendChild(div_clear_button_action_line);
			div_BoxContent_com.appendChild(div_button_action_line);
		div_PostBoxContent.appendChild(div_BoxContent_com);
	li.appendChild(div_PostBoxContent);
	return li;
}

function new_Reply(data) {
	var li = document.createElement('li');
	li.className = 'ident-com-1 id-comment-'+ data.comment_id;
	li.setAttribute('data-original-title',"Reply");
	li.setAttribute('data-user',data.user.username);
	li.setAttribute('data-content-id',data.ir);
	li.setAttribute('data-comment-id',data.comment_id);
		var div_PostBoxContent = document.createElement('div');
		div_PostBoxContent.className = 'PostBoxContent';
			var div_BoxContent_com = document.createElement('div');
			div_BoxContent_com.className = 'BoxContent-com';
				var div_ava50_com = document.createElement('div');
				div_ava50_com.className = 'ava32-com';
					var a_ava50_com = document.createElement('a');
					a_ava50_com.className = 'ava-user';
					a_ava50_com.href = url + data.user.username;
						var img_ava50_com = document.createElement('img');
						img_ava50_com.className = "img-rounded";
						img_ava50_com.src = data.user.ava;
						// img_ava50_com.setAttribute('data-original',data.user.ava);
					a_ava50_com.appendChild(img_ava50_com);
				div_ava50_com.appendChild(a_ava50_com);
			div_BoxContent_com.appendChild(div_ava50_com);

				var div_post_header_com = document.createElement('div');
				div_post_header_com.className = 'post-header-com';
					var div_header_analytics = document.createElement('div');
					div_header_analytics.className = 'header-analytics';
						var a_full_name = document.createElement('a');
						a_full_name.className = 'full-name';
						a_full_name.href = '/'+data.user.username;
							var div_username = document.createElement('div');
							div_username.className = 'username inline-block ident-com';
							div_username.appendChild(document.createTextNode(data.user.username));
						a_full_name.appendChild(div_username);
					div_header_analytics.appendChild(a_full_name);
				div_post_header_com.appendChild(div_header_analytics);

					var div_header_point = document.createElement('div');
					div_header_point.className = 'header-point';
						var small_header_point = document.createElement('small');
						var span_header_point = document.createElement('span');
							span_header_point.className = 'point';
							span_header_point.appendChild(document.createTextNode('0'));
						small_header_point.appendChild(span_header_point);
						small_header_point.appendChild(document.createTextNode(' vàng'));
					div_header_point.appendChild(small_header_point);
				div_post_header_com.appendChild(div_header_point);

					var div_time_post = document.createElement('div');
					div_time_post.className = 'time-post';
						var small_time_post = document.createElement('small');
						small_time_post.appendChild(document.createTextNode(' - '+data.time['time-ago']));
					div_time_post.appendChild(small_time_post);
				div_post_header_com.appendChild(div_time_post);

				if(data.user.username != AuthName) {
					var div_report_action = document.createElement('div');
					div_report_action.className = 'pull-right none-space';
						var button_report = document.createElement('button');
						button_report.className = 'button-action button-delete none-space';
						button_report.type = 'button';
						button_report.setAttribute('data-original-title','report-reply');
						button_report.setAttribute('data-action','report-reply');
							var strong_report = document.createElement('strong');
							strong_report.className = 'strong-com delete-report-ico';
							strong_report.setAttribute('data-toggle','tooltip');
							strong_report.setAttribute('data-placement','top');
							strong_report.setAttribute('data-original-title','Vi phạm');
						button_report.appendChild(strong_report);
					div_report_action.appendChild(button_report);
				div_post_header_com.appendChild(div_report_action);
				} else {
					var div_delete_action = document.createElement('div');
					div_delete_action.className = 'pull-right none-space';
						var button_delete = document.createElement('button');
						button_delete.className = 'button-action button-delete none-space';
						button_delete.type = 'button';
						button_delete.setAttribute('data-original-title','delete-reply');
						button_delete.setAttribute('data-action','delete-reply');
							var strong_delete = document.createElement('strong');
							strong_delete.className = 'strong-com delete-report-ico';
							strong_delete.setAttribute('data-toggle','tooltip');
							strong_delete.setAttribute('data-placement','top');
							strong_delete.setAttribute('data-original-title','Xóa');
						button_delete.appendChild(strong_delete);
					div_delete_action.appendChild(button_delete);
				div_post_header_com.appendChild(div_delete_action);
				}
			div_BoxContent_com.appendChild(div_post_header_com);

				var div_post_content_com = document.createElement('div');
				div_post_content_com.className = 'post-content-com';
				div_post_content_com.innerHTML  = data.new_content;;
			div_BoxContent_com.appendChild(div_post_content_com);

				var div_button_action_line = document.createElement('div');
				div_button_action_line.className = 'button-action-line button-action-line-com';
				div_button_action_line.setAttribute('role','group');
				div_button_action_line.setAttribute('data-title','post-icon');

					var div_pull_left_Reply = document.createElement('div');
					div_pull_left_Reply.className = 'pull-left btn-relative';
						var button_action_Reply = document.createElement('button');
						button_action_Reply.className = 'button-action button-reply';
						button_action_Reply.setAttribute('type','button');
						button_action_Reply.setAttribute('data-original-title','Comment');
							var strong_reply = document.createElement('strong');
							strong_reply.className = 'strong-com reply';
							strong_reply.innerHTML = 'Trả lời';
						button_action_Reply.appendChild(strong_reply);
					div_pull_left_Reply.appendChild(button_action_Reply);
				div_button_action_line.appendChild(div_pull_left_Reply);

					var div_pull_left_brick = document.createElement('div');
					div_pull_left_brick.className = 'pull-left btn-relative';
						var button_action_brick_true = document.createElement('button');
						button_action_brick_true.className = 'button-action icons-brick';
						button_action_brick_true.setAttribute('autocomplete','off');
						button_action_brick_true.setAttribute('data-loading-text',"<span class='icons-bricks icon-brick'></span>");
						button_action_brick_true.setAttribute('type','button');
						button_action_brick_true.setAttribute('data-action','brick-reply');
						button_action_brick_true.setAttribute('data-original-title','Brick');
							var span_action_brick_true = document.createElement('span');
							span_action_brick_true.className = 'icons-bricks icon-brick';
						button_action_brick_true.appendChild(span_action_brick_true);
					div_pull_left_brick.appendChild(button_action_brick_true);

						var button_action_brick_false = document.createElement('button');
						button_action_brick_false.className = 'button-action icons--brick hide';
						button_action_brick_false.setAttribute('autocomplete','off');
						button_action_brick_false.setAttribute('data-loading-text',"<span class='icons-bricks icon-brick'></span>");
						button_action_brick_false.setAttribute('type','button');
						button_action_brick_false.setAttribute('data-action','brick-reply');
						button_action_brick_false.setAttribute('data-original-title','Brick');
							var span_action_brick_false = document.createElement('span');
							span_action_brick_false.className = 'icons--bricks icon-brick';
						button_action_brick_false.appendChild(span_action_brick_false);
					div_pull_left_brick.appendChild(button_action_brick_false);
						var strong_brick = document.createElement('strong');
						strong_brick.className = 'strong-com brick';
						strong_brick.innerHTML = '0';
					div_pull_left_brick.appendChild(document.createTextNode(' '));
					div_pull_left_brick.appendChild(strong_brick);
				div_button_action_line.appendChild(div_pull_left_brick);

					var div_pull_left_like = document.createElement('div');
					div_pull_left_like.className = 'pull-left btn-relative';
						var button_action_like_true = document.createElement('button');
						button_action_like_true.className = 'button-action icon-like';
						button_action_like_true.setAttribute('autocomplete','off');
						button_action_like_true.setAttribute('data-loading-text',"<span class=' icons-like icon-emo-thumbsup'></span>");
						button_action_like_true.setAttribute('type','button');
						button_action_like_true.setAttribute('data-action','like-reply');
						button_action_like_true.setAttribute('data-original-title','Like');
						var span_action_like_true = document.createElement('span');
							span_action_like_true.className = 'icons-like icon-emo-thumbsup';
						button_action_like_true.appendChild(span_action_like_true);
					div_pull_left_like.appendChild(button_action_like_true);

						var button_action_like_false = document.createElement('button');
						button_action_like_false.className = 'button-action icon--like hide';
						button_action_like_false.setAttribute('autocomplete','off');
						button_action_like_false.setAttribute('data-loading-text',"<span class=' icons-like icon-emo-thumbsup'></span>");
						button_action_like_false.setAttribute('type','button');
						button_action_like_false.setAttribute('data-action','like-reply');
						button_action_like_false.setAttribute('data-original-title','Like');
						var span_action_like_false = document.createElement('span');
							span_action_like_false.className = 'icons--like icon-emo-thumbsup';
						button_action_like_false.appendChild(span_action_like_false);
					div_pull_left_like.appendChild(button_action_like_false);
						var strong_like = document.createElement('strong');
						strong_like.className = 'strong-com like';
						strong_like.innerHTML = '0';
					div_pull_left_like.appendChild(document.createTextNode(' '));
					div_pull_left_like.appendChild(strong_like);
					if(typeof auth_permission !== 'undefined'){
						var i = document.createElement('i');
						i.className = 'icon-alert icon-mod-block-com-rep mod-block';
						i.setAttribute('data-action','block-reply');
						i.setAttribute('data-content-id',data.ir);
						i.setAttribute('data-toggle','tooltip');
						i.setAttribute('data-placement','top');
						i.setAttribute('data-original-title','Block!!');
						div_button_action_line.appendChild(i);
					}
				div_button_action_line.appendChild(div_pull_left_like);
					var div_clear_button_action_line = document.createElement('div');
					div_clear_button_action_line.className = 'clear';
				div_button_action_line.appendChild(div_clear_button_action_line);
			div_BoxContent_com.appendChild(div_button_action_line);

		div_PostBoxContent.appendChild(div_BoxContent_com);
	li.appendChild(div_PostBoxContent);
	return li;
}

function load_more_Reply(data) {
	var li = document.createElement('li');
	li.className = 'ident-com-1 id-comment-'+ data.comment_id;
	li.setAttribute('data-original-title',"Reply");
	li.setAttribute('data-user',data.username);
	li.setAttribute('data-content-id',data.id);
	li.setAttribute('data-comment-id',data.comment_id);
		var div_PostBoxContent = document.createElement('div');
		div_PostBoxContent.className = 'PostBoxContent';
			var div_BoxContent_com = document.createElement('div');
			div_BoxContent_com.className = 'BoxContent-com';
				var div_ava50_com = document.createElement('div');
				div_ava50_com.className = 'ava32-com';
					var a_ava50_com = document.createElement('a');
					a_ava50_com.className = 'ava-user';
					a_ava50_com.href = url + data.username;
						var img_ava50_com = document.createElement('img');
						img_ava50_com.className = "img-rounded";
						img_ava50_com.src = url+data.ava;
						// img_ava50_com.setAttribute('data-original',url+data.ava);
					a_ava50_com.appendChild(img_ava50_com);
				div_ava50_com.appendChild(a_ava50_com);
			div_BoxContent_com.appendChild(div_ava50_com);

				var div_post_header_com = document.createElement('div');
				div_post_header_com.className = 'post-header-com';
					var div_header_analytics = document.createElement('div');
					div_header_analytics.className = 'header-analytics';
						var a_full_name = document.createElement('a');
						a_full_name.className = 'full-name';
						a_full_name.href = '/'+data.username;
							var div_username = document.createElement('div');
							div_username.className = 'username inline-block ident-com';
							div_username.appendChild(document.createTextNode(data.username));
						a_full_name.appendChild(div_username);
					div_header_analytics.appendChild(a_full_name);
				div_post_header_com.appendChild(div_header_analytics);

					var div_header_point = document.createElement('div');
					div_header_point.className = 'header-point';
						var small_header_point = document.createElement('small');
						var span_header_point = document.createElement('span');
							span_header_point.className = 'point';
						var point = 0;
						if(data.total_point != null ) point = data.total_point;
						span_header_point.appendChild(document.createTextNode(point));
						small_header_point.appendChild(span_header_point);
						small_header_point.appendChild(document.createTextNode(' vàng'));
					div_header_point.appendChild(small_header_point);
				div_post_header_com.appendChild(div_header_point);

					var div_time_post = document.createElement('div');
					div_time_post.className = 'time-post';
						var small_time_post = document.createElement('small');
						small_time_post.appendChild(document.createTextNode(' - '+data.created_time['time-ago']));
					div_time_post.appendChild(small_time_post);
				div_post_header_com.appendChild(div_time_post);

				if(data.username != AuthName) {
					var div_report_action = document.createElement('div');
					div_report_action.className = 'pull-right none-space';
						var button_report = document.createElement('button');
						button_report.className = 'button-action button-delete none-space';
						button_report.type = 'button';
						button_report.setAttribute('data-original-title','report-reply');
						button_report.setAttribute('data-action','report-reply');
							var strong_report = document.createElement('strong');
							strong_report.className = 'strong-com delete-report-ico';
							strong_report.setAttribute('data-toggle','tooltip');
							strong_report.setAttribute('data-placement','top');
							strong_report.setAttribute('data-original-title','Vi phạm');
						button_report.appendChild(strong_report);
					div_report_action.appendChild(button_report);
				div_post_header_com.appendChild(div_report_action);
				} else {
					var div_delete_action = document.createElement('div');
					div_delete_action.className = 'pull-right none-space';
						var button_delete = document.createElement('button');
						button_delete.className = 'button-action button-delete none-space';
						button_delete.type = 'button';
						button_delete.setAttribute('data-original-title','delete-reply');
						button_delete.setAttribute('data-action','delete-reply');
							var strong_delete = document.createElement('strong');
							strong_delete.className = 'strong-com delete-report-ico';
							strong_delete.setAttribute('data-toggle','tooltip');
							strong_delete.setAttribute('data-placement','top');
							strong_delete.setAttribute('data-original-title','Xóa');
						button_delete.appendChild(strong_delete);
					div_delete_action.appendChild(button_delete);
				div_post_header_com.appendChild(div_delete_action);
				}
			div_BoxContent_com.appendChild(div_post_header_com);

				var div_post_content_com = document.createElement('div');
				div_post_content_com.className = 'post-content-com';
				div_post_content_com.innerHTML  = data.new_content;;
			div_BoxContent_com.appendChild(div_post_content_com);

				var div_button_action_line = document.createElement('div');
				div_button_action_line.className = 'button-action-line button-action-line-com';
				div_button_action_line.setAttribute('role','group');
				div_button_action_line.setAttribute('data-title','post-icon');

					var div_pull_left_Reply = document.createElement('div');
					div_pull_left_Reply.className = 'pull-left btn-relative';
						var button_action_Reply = document.createElement('button');
						button_action_Reply.className = 'button-action button-reply';
						button_action_Reply.setAttribute('type','button');
						button_action_Reply.setAttribute('data-original-title','Comment');
							var strong_reply = document.createElement('strong');
							strong_reply.className = 'strong-com reply';
							strong_reply.innerHTML = 'Trả lời';
						button_action_Reply.appendChild(strong_reply);
					div_pull_left_Reply.appendChild(button_action_Reply);
				div_button_action_line.appendChild(div_pull_left_Reply);

					var div_pull_left_brick = document.createElement('div');
					div_pull_left_brick.className = 'pull-left btn-relative';
						var button_action_brick_true = document.createElement('button');
						if(data.brick_content == null || data.brick_content == "0"){
							button_action_brick_true.className = 'button-action icons-brick';
						}else button_action_brick_true.className = 'button-action icons-brick hide';
						button_action_brick_true.setAttribute('type','button');
						button_action_brick_true.setAttribute('autocomplete','off');
						button_action_brick_true.setAttribute('data-loading-text',"<span class='icons-bricks icon-brick'></span>");
						button_action_brick_true.setAttribute('data-action','brick-reply');
						button_action_brick_true.setAttribute('data-original-title','Brick');
							var span_action_brick_true = document.createElement('span');
							span_action_brick_true.className = 'icons-bricks icon-brick';
						button_action_brick_true.appendChild(span_action_brick_true);
					div_pull_left_brick.appendChild(button_action_brick_true);

						var button_action_brick_false = document.createElement('button');
						if(data.brick_content == null || data.brick_content == "0"){
							button_action_brick_false.className = 'button-action icons--brick hide';
						}else button_action_brick_false.className = 'button-action icons--brick';
						button_action_brick_false.setAttribute('autocomplete','off');
						button_action_brick_false.setAttribute('data-loading-text',"<span class='icons-bricks icon-brick'></span>");
						button_action_brick_false.setAttribute('type','button');
						button_action_brick_false.setAttribute('data-action','brick-reply');
						button_action_brick_false.setAttribute('data-original-title','Brick');
							var span_action_brick_false = document.createElement('span');
							span_action_brick_false.className = 'icons--bricks icon-brick';
						button_action_brick_false.appendChild(span_action_brick_false);
					div_pull_left_brick.appendChild(button_action_brick_false);
						var strong_brick = document.createElement('strong');
						strong_brick.className = 'strong-com brick';
						strong_brick.innerHTML = data.total_brick;
					div_pull_left_brick.appendChild(document.createTextNode(' '));
					div_pull_left_brick.appendChild(strong_brick);
				div_button_action_line.appendChild(div_pull_left_brick);

					var div_pull_left_like = document.createElement('div');
					div_pull_left_like.className = 'pull-left btn-relative';
						var button_action_like_true = document.createElement('button');
						if(data.like_content == null || data.like_content == "0"){
							button_action_like_true.className = 'button-action icon-like';
						} else button_action_like_true.className = 'button-action icon-like hide';
						button_action_like_true.setAttribute('autocomplete','off');
						button_action_like_true.setAttribute('data-loading-text',"<span class=' icons-like icon-emo-thumbsup'></span>");
						button_action_like_true.setAttribute('type','button');
						button_action_like_true.setAttribute('data-action','like-reply');
						button_action_like_true.setAttribute('data-original-title','Like');
						var span_action_like_true = document.createElement('span');
							span_action_like_true.className = 'icons-like icon-emo-thumbsup';
						button_action_like_true.appendChild(span_action_like_true);
					div_pull_left_like.appendChild(button_action_like_true);

						var button_action_like_false = document.createElement('button');
						if(data.like_content == null || data.like_content == "0"){
							button_action_like_false.className = 'button-action icon--like hide';
						}else button_action_like_false.className = 'button-action icon--like';
						button_action_like_false.setAttribute('autocomplete','off');
						button_action_like_false.setAttribute('data-loading-text',"<span class=' icons-like icon-emo-thumbsup'></span>");
						button_action_like_false.setAttribute('type','button');
						button_action_like_false.setAttribute('data-action','like-reply');
						button_action_like_false.setAttribute('data-original-title','Like');
						var span_action_like_false = document.createElement('span');
							span_action_like_false.className = 'icons--like icon-emo-thumbsup';
						button_action_like_false.appendChild(span_action_like_false);
					div_pull_left_like.appendChild(button_action_like_false);
						var strong_like = document.createElement('strong');
						strong_like.className = 'strong-com like';
						strong_like.innerHTML = data.total_like;
					div_pull_left_like.appendChild(document.createTextNode(' '));
					div_pull_left_like.appendChild(strong_like);
					if(typeof auth_permission !== 'undefined'){
						var i = document.createElement('i');
						i.className = 'icon-alert icon-mod-block-com-rep mod-block';
						i.setAttribute('data-action','block-reply');
						i.setAttribute('data-content-id',data.id);
						i.setAttribute('data-toggle','tooltip');
						i.setAttribute('data-placement','top');
						i.setAttribute('data-original-title','Block!!');
						div_button_action_line.appendChild(i);
					}
				div_button_action_line.appendChild(div_pull_left_like);

					var div_clear_button_action_line = document.createElement('div');
					div_clear_button_action_line.className = 'clear';
				div_button_action_line.appendChild(div_clear_button_action_line);
			div_BoxContent_com.appendChild(div_button_action_line);

		div_PostBoxContent.appendChild(div_BoxContent_com);
	li.appendChild(div_PostBoxContent);
	return li;
}

function loadMorePost (data) {
	var div_left_stream_box = document.createElement('div');
	div_left_stream_box.className = 'col-md-12 left-stream left-stream-box';
		var li = document.createElement('li');
		li.className = 'simple-post';
			if(typeof auth_permission !== 'undefined'){
					var i = document.createElement('i');
					i.className = 'icon-alert icon-mod-block mod-block';
					i.setAttribute('data-content-id',data.id);
					i.setAttribute('data-action','block-post');
					i.setAttribute('data-toggle','tooltip');
					i.setAttribute('data-placement','top');
					i.setAttribute('data-original-title','Block!!');
				li.appendChild(i);
			}
			var i = document.createElement('i');
			if(data.username != Auth_userName){
				i.className = 'delete-report-ico-tl report';
				i.setAttribute('data-post-id',data.id);
				i.setAttribute('data-action','report-post');
				i.setAttribute('data-toggle','tooltip');
				i.setAttribute('data-placement','top');
				i.setAttribute('data-original-title','Vi phạm');
			}else {
				i.className = 'delete-report-ico-tl delete';
				i.setAttribute('data-post-id',data.id);
				i.setAttribute('data-action','delete-post');
				i.setAttribute('data-toggle','tooltip');
				i.setAttribute('data-placement','top');
				i.setAttribute('data-original-title','Xóa');
			}


		li.appendChild(i);
			var div_post_permalink = document.createElement('div');
			div_post_permalink.className = 'post-permalink per-post';
			// 	var div_user_post_rank = document.createElement('div');
			// 	div_user_post_rank.className = 'user-post-rank';
			// 		var div_rank_pull_left = document.createElement('div');
			// 		div_rank_pull_left.className = 'rank pull-left';
			// 		div_rank_pull_left.appendChild(document.createTextNode(data.medal_name));
			// 	div_user_post_rank.appendChild(div_rank_pull_left);

			// 		var a_medals = document.createElement('a');
			// 		a_medals.className = 'medals';
			// 		a_medals.href = 'javascript:void(0)';
			// 			var img_medals = document.createElement('img');
			// 			img_medals.src = url + data.medal_icon_url;
			// 		a_medals.appendChild(img_medals);
			// 	div_user_post_rank.appendChild(a_medals);

			// 		var div_clear_post_rank = document.createElement('div');
			// 		div_clear_post_rank.className = 'clear';
			// 	div_user_post_rank.appendChild(div_clear_post_rank);
			// div_post_permalink.appendChild(div_user_post_rank);

				var div_post_header = document.createElement('div');
				div_post_header.className = 'post-header';
					if(typeof auth_permission !== 'undefined'){
						var rate_point  = data.rate_point;
						if(rate_point == null) rate_point = 0;
						var persent_rate = (rate_point / 5) * 100;
						var star = Math.floor(rate_point);
						var	div = document.createElement('div');
							div.className = 'mod-rate';
							div.setAttribute('data-content-id',data.id);
							div.setAttribute('data-type','rate_post');
							var span = document.createElement('span');
								span.className = "star-rating";
									var	input = document.createElement('input');
									input.className = 'start-rate';
									input.setAttribute('type',"radio")
									input.setAttribute('name',"rating_"+data.id)
									input.setAttribute('value',"1")
								span.appendChild(input);
								var i = document.createElement('i');
								if(star == 1){
									input.setAttribute('checked','checked');
									i.setAttribute('style','width:'+persent_rate+'%');
								}
								i.className = 'start1';
								span.appendChild(i);
									var	input = document.createElement('input');
									input.className = 'start-rate';
									input.setAttribute('type',"radio")
									input.setAttribute('name',"rating_"+data.id)
									input.setAttribute('value',"2")
								span.appendChild(input);
								var i = document.createElement('i');
								if(star == 2){
									input.setAttribute('checked','checked');
									i.setAttribute('style','width:'+persent_rate+'%');
								}
								i.className = 'start2';
								span.appendChild(i);
									var	input = document.createElement('input');
									input.className = 'start-rate';
									input.setAttribute('type',"radio")
									input.setAttribute('name',"rating_"+data.id)
									input.setAttribute('value',"3")
								span.appendChild(input);
								var i = document.createElement('i');
								if(star == 3){
									input.setAttribute('checked','checked');
									i.setAttribute('style','width:'+persent_rate+'%');
								}
								i.className = 'start3';
								span.appendChild(i);
									var	input = document.createElement('input');
									input.className = 'start-rate';
									input.setAttribute('type',"radio");
									input.setAttribute('name',"rating_"+data.id);
									input.setAttribute('value',"4");
								span.appendChild(input);
								var i = document.createElement('i');
								if(star == 4){
									input.setAttribute('checked','checked');
									i.setAttribute('style','width:'+persent_rate+'%');
								}
								i.className = 'start4';
								span.appendChild(i);
									var	input = document.createElement('input');
									input.className = 'start-rate';
									input.setAttribute('type',"radio");
									input.setAttribute('name',"rating_"+data.id);
									input.setAttribute('value',"5");
								span.appendChild(input);
								var i = document.createElement('i');
								if(star == 5){
									input.setAttribute('checked','checked');
									i.setAttribute('style','width:'+persent_rate+'%');
								}
								i.className = 'start5';
								span.appendChild(i);
							div.appendChild(span);
							var span = document.createElement('span');
							span.className = 'count-mod-rate';
							span.appendChild(document.createTextNode((Math.round(rate_point * 1000)/1000)+' on '+data.count_rate));
							div.appendChild(span);
							var div_clear = document.createElement('div');
								div_clear.className = 'clearfix';
							div.appendChild(div_clear);
						div_post_header.appendChild(div);
					}
					var div_ava42_only = document.createElement('div');
					div_ava42_only.className = 'ava42-only';
						var a_ava42_only = document.createElement('a');
						a_ava42_only.className = 'ava-user';
						a_ava42_only.href = url + data.username;
							var img_rounded = document.createElement('img');
							img_rounded.className = 'img-rounded';
							img_rounded.src = url + data.ava;
							// img_rounded.setAttribute('data-original',url + data.ava);

						a_ava42_only.appendChild(img_rounded);
					div_ava42_only.appendChild(a_ava42_only);
				div_post_header.appendChild(div_ava42_only);

					var div_header_analytics_only = document.createElement('div');
					div_header_analytics_only.className = 'header-analytics-only';
						var a_full_name = document.createElement('a');
						a_full_name.className = 'full-name';
						a_full_name.href = url + data.username;
							var div_nick_name = document.createElement('div');
							div_nick_name.className = 'nick-name';
							div_nick_name.appendChild( document.createTextNode(data.nickname) );
						a_full_name.appendChild(div_nick_name);
							var div_time_post = document.createElement('div');
						div_time_post.className = 'time-post time-top';
							var small_time_post = document.createElement('small');
							small_time_post.appendChild( document.createTextNode(data.created_time['time-ago'] ) );
						div_time_post.appendChild(small_time_post);
						a_full_name.appendChild(div_time_post);
					div_header_analytics_only.appendChild(a_full_name);
				div_post_header.appendChild(div_header_analytics_only);
//Level
				var iframe = document.createElement('div');
					if (data.user_level <=9) {
					iframe.className = 'icon-user-pro A_level_icon level_icon_c1';
					}else if (data.user_level > 9 && data.user_level <= 19){
					iframe.className = 'icon-user-pro A_level_icon level_icon_c2';
					}else{
					iframe.className = 'icon-user-pro A_level_icon level_icon_c3';
					}
						var span = document.createElement('span');
						span.appendChild( document.createTextNode('Lv.'+ data.user_level) );
						span.setAttribute('data-toggle',"tooltip");
						span.setAttribute('data-placement',"top");
						span.setAttribute('title','Cấp '+ data.user_level);
					iframe.appendChild(span);
				div_post_header.appendChild(iframe);
//Gender
				var iframe = document.createElement('a');
					iframe.className = 'icon-user-pro';
					if (data.gender ==1) {
						var i_iframe = document.createElement('i');
						i_iframe.className = 'A_icon icon_male';
						i_iframe.setAttribute('data-toggle',"tooltip");
						i_iframe.setAttribute('data-placement',"top");
						i_iframe.setAttribute('title','Nam nhân');
						iframe.appendChild(i_iframe);
					} else if (data.gender == 2){
						var i_iframe = document.createElement('i');
						i_iframe.className = 'A_icon icon_female';
						i_iframe.setAttribute('data-toggle',"tooltip");
						i_iframe.setAttribute('data-placement',"top");
						i_iframe.setAttribute('title','Nữ nhân');
						iframe.appendChild(i_iframe);
					} else {

					}
				div_post_header.appendChild(iframe);
//Verify
				var iframe = document.createElement('a');
					iframe.className = 'icon-user-pro';
					if (data.confirmed == true) {
						var i_iframe = document.createElement('i');
						i_iframe.className = 'A_icon icon_approve_co';
						i_iframe.setAttribute('data-toggle',"tooltip");
						i_iframe.setAttribute('data-placement',"top");
						i_iframe.setAttribute('title','Xác thực cấp 1');
					} else if (data.confirmed == true && data.shaphira_verified == true) {
						var i_iframe = document.createElement('i');
						i_iframe.className = 'A_icon icon_approve';
						i_iframe.setAttribute('data-toggle',"tooltip");
						i_iframe.setAttribute('data-placement',"top");
						i_iframe.setAttribute('title','8xuhuong verify');
					} else {}
					iframe.appendChild(i_iframe);
				div_post_header.appendChild(iframe);
//Rank username
				var iframe = document.createElement('a');
					iframe.className = 'icon-user-pro';
						var i_iframe = document.createElement('i');
						switch (data.blogger_level) {
							case '1':
								i_iframe.className = 'A_icon icon_member1';
								i_iframe.setAttribute('data-toggle',"tooltip");
								i_iframe.setAttribute('data-placement',"top");
								i_iframe.setAttribute('title','Blogger Cấp 1');
								break;
							case '2':
								i_iframe.className = 'A_icon icon_member2';
								i_iframe.setAttribute('data-toggle',"tooltip");
								i_iframe.setAttribute('data-placement',"top");
								i_iframe.setAttribute('title','Blogger Cấp 2');
								break;
							case '3':
								i_iframe.className = 'A_icon icon_member3';
								i_iframe.setAttribute('data-toggle',"tooltip");
								i_iframe.setAttribute('data-placement',"top");
								i_iframe.setAttribute('title','Blogger Cấp 3');
								break;
							case '4':
								i_iframe.className = 'A_icon icon_member4';
								i_iframe.setAttribute('data-toggle',"tooltip");
								i_iframe.setAttribute('data-placement',"top");
								i_iframe.setAttribute('title','Blogger Cấp 4');
								break;
							case '5':
								i_iframe.className = 'A_icon icon_member5';
								i_iframe.setAttribute('data-toggle',"tooltip");
								i_iframe.setAttribute('data-placement',"top");
								i_iframe.setAttribute('title','Blogger Cấp 5');
								break;
							case '6':
								i_iframe.className = 'A_icon icon_member6';
								i_iframe.setAttribute('data-toggle',"tooltip");
								i_iframe.setAttribute('data-placement',"top");
								i_iframe.setAttribute('title','Blogger Cấp 6');
								break;
						}
					iframe.appendChild(i_iframe);
				div_post_header.appendChild(iframe);

			div_post_permalink.appendChild(div_post_header);

				var div_post_header_2 = document.createElement('div');
				div_post_header_2.className = 'post-header';
					var a_post_header_2 = document.createElement('a');
					a_post_header_2.className = 'title-header';
					a_post_header_2.href = url+'posts/' + data.id;
					a_post_header_2.appendChild( document.createTextNode(data.title) );
				div_post_header_2.appendChild(a_post_header_2);
			div_post_permalink.appendChild(div_post_header_2);

				var div_post_content_fresh_hot = document.createElement('div');
				div_post_content_fresh_hot.className = 'post-content-fresh-hot';

				var length_content = data.new_content.length;
				var a_see_more = document.createTextNode('');
				var span = document.createTextNode('');
				var content_collapse = data.new_content;
				if(length_content >= max_length_content_collapse){
					content_collapse = data.new_content.substring(0,max_length_content_collapse) ;
					var position_last_space = content_collapse.lastIndexOf(' ');
					content_collapse = content_collapse.substring(0,position_last_space);
					span = document.createElement('span');
					span.appendChild(document.createTextNode('... '));
					a_see_more = document.createElement('a');
					a_see_more.href = url+'posts/'+data.id;
					a_see_more.appendChild(document.createTextNode('Đọc tiếp'));
				}

				div_post_content_fresh_hot.innerHTML =  content_collapse;
				div_post_content_fresh_hot.appendChild(span);
				div_post_content_fresh_hot.appendChild(a_see_more);
				if(data.image != null && data.image != ''){
					if(data.type_media == 0){
						var ul_media = document.createElement('ul');
							ul_media.className = 'A_media_box';
							var li_media = document.createElement('li');
							li_media.className = 'A_media';
								var img = document.createElement('img');
								img.className = 'media-thumb';
								img.setAttribute('data-original',data.image_thumb);
								img.setAttribute('data-src',data.image);
							li_media.appendChild(img);
						ul_media.appendChild(li_media);
						div_post_content_fresh_hot.appendChild(ul_media);
					} else {
						var ul_video = document.createElement('ul');
							ul_video.className = 'A_media_box';
							var li_video = document.createElement('li');
							li_video.className = 'A_media A_video';
								var img_video = document.createElement('img');
								img_video.className = 'video-thumb';
								img_video.setAttribute('data-original',data.image);
								img_video.setAttribute('src',data.image);
								img_video.setAttribute('data-media-id',data.media_api_id);
								img_video.setAttribute('data-media-type',data.type_media);
							li_video.appendChild(img_video);
							var i_icon_video = document.createElement('i');
							i_icon_video.className = 'A_icon icon-play-video';
							li_video.appendChild(i_icon_video);
							var div_player = document.createElement('div');
							div_player.id = 'player_'+data.id;
							div_player.className = 'hide player-youtube';
							li_video.appendChild(div_player);
						ul_video.appendChild(li_video);
						div_post_content_fresh_hot.appendChild(ul_video);
					}
				}
			div_post_permalink.appendChild(div_post_content_fresh_hot);

				var div_static_header = document.createElement('div');
				div_static_header.className = 'static-header';
					var ul_tags = document.createElement('ul');
					ul_tags.className = 'tags';
						var length_cate = data.Categories.length;

						for (var i = length_cate - 1; i >= 0; i--) {
							var li_cate = document.createElement('li');
								var a_li_cate = document.createElement('a');
								a_li_cate.className = 'cate-post';
								a_li_cate.href = '/g/'+(data.Categories[i].cate_code);
								a_li_cate.appendChild(document.createTextNode(data.Categories[i].name));
							li_cate.appendChild(a_li_cate);
						ul_tags.appendChild(li_cate);
						};
						// ket thuc vong lap
						// if(data.link_tag != '' && data.link_tag != null && data.link_tag != undefined) {
						// 	var length_tag = data.link_tag.length;
						// 	for (var i = length_tag - 1; i >= 0; i--) {
						// 		var li_hastag = document.createElement('li');
						// 			var li_hastag_a = document.createElement('a');
						// 			li_hastag_a.className = 'hastag-tag';
						// 			li_hastag_a.href = '/hashtag/'+data.link_tag[i];
						// 			li_hastag_a.appendChild( document.createTextNode('#'+data.link_tag[i]) );
						// 		li_hastag.appendChild(li_hastag_a);
						// 	ul_tags.appendChild(li_hastag);
						// 	};
						// }
						if(data.link != '' && data.link != null && data.link != undefined) {
								var li_link = document.createElement('li');
								li_link.className = 'li-link-to-web';
									var a_link_web = document.createElement('a');
									a_link_web.className = 'link-to-web';
									a_link_web.href = data.link;
										var p_link = document.createElement('p');
										p_link.appendChild( document.createTextNode(data.link) );
									a_link_web.appendChild( p_link );
								li_link.appendChild(a_link_web);
							ul_tags.appendChild(li_link);
						}
						var div_clear_ul_tag = document.createElement('div');
						div_clear_ul_tag.className = 'clear';
					ul_tags.appendChild(div_clear_ul_tag);
				div_static_header.appendChild(ul_tags);
			div_post_permalink.appendChild(div_static_header);

				var ul_post_stats = document.createElement('ul');
				ul_post_stats.className = 'post-stats';
				ul_post_stats.setAttribute('data-content-id',data.id);
					var li_brick_count = document.createElement('li');
					li_brick_count.className = 'brick-count';
						var div_brick_link = document.createElement('div');
							var a_brick_count = document.createElement('a');
							if(data.brick_able == 1 ) a_brick_count.className = 'brick--post bold';
							else a_brick_count.className = 'brick-post';
							a_brick_count.href = 'javascript:void(0)';
							a_brick_count.setAttribute('data-action','brick-post');
								var span_text_brick = document.createElement('span');
								span_text_brick.appendChild(document.createTextNode('Gạch'));
							a_brick_count.appendChild( span_text_brick );
						div_brick_link.appendChild(a_brick_count);
						var div_brick_count = document.createElement('div');
							var strong_brick_count = document.createElement('strong');
								strong_brick_count.appendChild( document.createTextNode(data.total_brick) );
							div_brick_count.appendChild( strong_brick_count );
					li_brick_count.appendChild( div_brick_link );
					li_brick_count.appendChild( div_brick_count );
				ul_post_stats.appendChild( li_brick_count );

					var li_like_count = document.createElement('li');
					li_like_count.className = 'like-count';
						var div_like_link = document.createElement('div');
							var a_like_count = document.createElement('a');
							if(data.like_able == 1 ) a_like_count.className = 'like--post bold';
							else a_like_count.className = 'like-post';
							a_like_count.href = 'javascript:void(0)';
							a_like_count.setAttribute('data-action','like-post');
								var span_text_like = document.createElement('span');
								span_text_like.appendChild(document.createTextNode('Thích'));
							a_like_count.appendChild( span_text_like );
						div_like_link.appendChild(a_like_count);
						var div_like_count = document.createElement('div');
							var strong_like_count = document.createElement('strong');
							strong_like_count.appendChild( document.createTextNode(data.total_like) );
						div_like_count.appendChild( strong_like_count );
					li_like_count.appendChild( div_like_link );
					li_like_count.appendChild( div_like_count );
				ul_post_stats.appendChild( li_like_count );

					var li_comment_count = document.createElement('li');
					li_comment_count.className = 'comment-count';
						var div_comment_link = document.createElement('div');
							var a_comment_count = document.createElement('a');
							a_comment_count.className = 'cmm-post';
							a_comment_count.href = url+'posts/'+data.id;
							var span_text_comment = document.createElement('span');
								span_text_comment.appendChild(document.createTextNode('Bình luận'));
							a_comment_count.appendChild( span_text_comment );
						div_comment_link.appendChild(a_comment_count);
						var div_comment_count = document.createElement('div');
							var strong_comment_count = document.createElement('strong');
							strong_comment_count.appendChild( document.createTextNode(data.total_comment) );
						div_comment_count.appendChild( strong_comment_count );
					li_comment_count.appendChild( div_comment_link );
					li_comment_count.appendChild( div_comment_count );
				ul_post_stats.appendChild( li_comment_count );

					var li_point_count = document.createElement('li');
					li_point_count.className = 'point-count';
						var div_point_link = document.createElement('div');
							var a_point_count = document.createElement('a');
							a_point_count.href = 'javascript:void(0)';
								var span_text_point = document.createElement('span');
								span_text_point.appendChild(document.createTextNode('Vàng'));
							a_point_count.appendChild( span_text_point );
						div_point_link.appendChild(a_point_count);
						var div_point_count = document.createElement('div');
							var strong_point_count = document.createElement('strong');
							strong_point_count.appendChild( document.createTextNode(data.total_point) );
						div_point_count.appendChild( strong_point_count );
					li_point_count.appendChild( div_point_link );
					li_point_count.appendChild( div_point_count );
				ul_post_stats.appendChild( li_point_count );

					var div_avatar_row_pull_left = document.createElement('div');
					div_avatar_row_pull_left.className = 'avatar-row pull-left display-item-row';

						var length_data_user = data.users_action.length;
						for (var i = length_data_user - 1; i >= 0; i--) {
							var a_ava_rela = document.createElement('a');
								a_ava_rela.className = 'ava-rela';
								a_ava_rela.setAttribute('data-toggle',"tooltip");
								a_ava_rela.setAttribute('data-placement',"top");
								a_ava_rela.setAttribute('title',data.users_action[i].username);
								a_ava_rela.href = url + data.users_action[i].username;
									var img_ava_rela = document.createElement('img');
									img_ava_rela.className = 'img-rounded ava25';
									img_ava_rela.setAttribute('data-original',url + data.users_action[i].ava);
								a_ava_rela.appendChild(img_ava_rela);
							div_avatar_row_pull_left.appendChild(a_ava_rela);
						}

					// ket thuc lap
				ul_post_stats.appendChild(div_avatar_row_pull_left);
			div_post_permalink.appendChild(ul_post_stats);
		li.appendChild(div_post_permalink);
			var div_clear_li = document.createElement('div');
			div_clear_li.className = 'clear';
		li.appendChild(div_clear_li);
	div_left_stream_box.appendChild(li);
	return div_left_stream_box;
}

function loading() {
	var li = document.createElement('li');
	li.className = 'loading text-center';
		var span = document.createElement('span');
		span.className = 'icon-spin5 animate-spin size';
	li.appendChild(span);
	return li;
}

function create_element_notifi (value) {
	var li = document.createElement('div');
    if (value.reach_time != null) {
        li.className = 'row-notifi read';
    } else {
        li.className = 'row-notifi no-read';
    }
    var a_link_notification = document.createElement("a");
    a_link_notification.className = "link-notification";
    a_link_notification.setAttribute('data-id_Notifi', value.id);
    a_link_notification.href = url + 'posts/'+value.post_id+'/?notifi_id=' + value.id ;
        var div_avatar = document.createElement('div');
        div_avatar.className = "pull-left ava32-com";
            var img_ava = document.createElement('img');
            img_ava.src = url+ value.ava;

            img_ava.className = 'ava40-noti';
        div_avatar.appendChild(img_ava);
    a_link_notification.appendChild(div_avatar);

        var div_content = document.createElement('div');
        div_content.className = "col-md-10";
	        var div_box =document.createElement('div');
	        div_box.className = "div-box-noti";
	            var span_user = document.createElement('span');
	            span_user.className = 'username-post-noti ';
	            span_user.appendChild(document.createTextNode(value.nickname + ' '));
	        div_box.appendChild(span_user);

        if(value.count == 1){
            var message_post = document.createElement('span');
	            if(value.notifi_type == 1){
	            message_post.className = "message-post";
	                message_post.appendChild(document.createTextNode(value.message+': '+value.content));
	            div_box.appendChild(message_post);
	        div_content.appendChild(div_box);
	            } else{
	            message_post.className = "message-post";
	                message_post.appendChild(document.createTextNode(value.message));
	            div_box.appendChild(message_post);
	        div_content.appendChild(div_box);
	            }
            var time_post = document.createElement('span');
            time_post.className = "time-post-noti";
            	switch (value.notifi_type){
					case '0' :
					var	i = document.createElement('i');
					i.className = 'icon-comment-1 icon-noti';
					time_post.appendChild(i);
					break;
					case '1' :
					var	i = document.createElement('i');
					i.className = 'icon-emo-thumbsup icon-noti';
					time_post.appendChild(i);
					break;
					case '2' :
					var	i = document.createElement('i');
					i.className = 'icon-brick icon-noti';
					time_post.appendChild(i);
					break;
					case '3' :
					var	i = document.createElement('i');
					i.className = 'icon-at icon-noti';
					time_post.appendChild(i);
					break;
				}
                time_post.appendChild(document.createTextNode(value.created_time['time-ago']));
            div_content.appendChild(time_post);
        } else {
            var message_post = document.createElement('span');
            message_post.className = "message-post";
            if(value.notifi_type == 1){
                message_post.appendChild(document.createTextNode('và '+ value.count +' người khác ' +value.message + ': ' + value.content));
            }else {
                message_post.appendChild(document.createTextNode('và '+ value.count +' người khác ' +value.message));
            }
            div_content.appendChild(message_post);

            var time_post = document.createElement('span');
            time_post.className = "time-post-noti";
            	switch (value.notifi_type){
					case '0' :
					var	i = document.createElement('i');
					i.className = 'icon-comment-1 icon-noti';
					time_post.appendChild(i);
					break;
					case '1' :
					var	i = document.createElement('i');
					i.className = 'icon-emo-thumbsup icon-noti';
					time_post.appendChild(i);
					break;
					case '3' :
					var	i = document.createElement('i');
					i.className = 'icon-at icon-noti';
					time_post.appendChild(i);
					break;
				}
                time_post.appendChild(document.createTextNode(value.created_time['time-ago']));
            div_content.appendChild(time_post);
        }
    a_link_notification.appendChild(div_content);

        var div_clear = document.createElement('div');
        div_clear.className = 'clear';
    a_link_notification.appendChild(div_clear);
    li.appendChild(a_link_notification);
    return li;
}

function loadMorePostHot (data,hot) {
	var div_left_stream_box = document.createElement('div');
	div_left_stream_box.className = 'col-md-12 left-stream left-stream-box';
		var li = document.createElement('li');
		li.className = 'simple-post';
		if(typeof auth_permission !== 'undefined'){
				var i = document.createElement('i');
				i.className = 'icon-alert icon-mod-block mod-block';
				i.setAttribute('data-content-id',data.id);
				i.setAttribute('data-action','block-post');
				i.setAttribute('data-toggle','tooltip');
				i.setAttribute('data-placement','top');
				i.setAttribute('data-original-title','Block!!');
			li.appendChild(i);
		}
			var i = document.createElement('i');
			if(data.username != Auth_userName){
				i.className = 'delete-report-ico-tl report';
				i.setAttribute('data-post-id',data.id);
				i.setAttribute('data-action','report-post');
				i.setAttribute('data-toggle','tooltip');
				i.setAttribute('data-placement','top');
				i.setAttribute('data-original-title','Vi phạm');
			}else {
				i.className = 'delete-report-ico-tl delete';
				i.setAttribute('data-post-id',data.id);
				i.setAttribute('data-action','delete-post');
				i.setAttribute('data-toggle','tooltip');
				i.setAttribute('data-placement','top');
				i.setAttribute('data-original-title','Xóa');
			}
		li.appendChild(i);
			var div_rank_top = document.createElement('div');
			div_rank_top.className = 'rank-top';
				var div_hot_rank_top = document.createElement('div');
				div_hot_rank_top.className = 'hot-rank-top';
					var div_one = document.createElement('div');
					div_one.className = 'one';
					if(data.pre_rate >= data.now_rate){
						var span_icon_crown_plus_up = document.createElement('span');
							span_icon_crown_plus_up.className = 'icon-crown-plus plus-rank-up';
						div_one.appendChild(span_icon_crown_plus_up);
							var span_icon_crown_plus_down = document.createElement('span');
							span_icon_crown_plus_down.className = 'icon-crown-plus plus-rank-down hide';
						div_one.appendChild(span_icon_crown_plus_down);
							var span_rank_num = document.createElement('span');
							span_rank_num.className = 'rank-num inline-block';
							span_rank_num.appendChild(document.createTextNode(hot));
						div_one.appendChild(span_rank_num);
							var span_icon_crown_minus_down = document.createElement('span');
							span_icon_crown_minus_down.className = 'icon-crown-minus minus-rank-down ';
						div_one.appendChild(span_icon_crown_minus_down);
							var span_icon_crown_minus_up = document.createElement('span');
							span_icon_crown_minus_up.className = 'icon-crown-minus minus-rank-up hide';
						div_one.appendChild(span_icon_crown_minus_up);
					}else {
						var span_icon_crown_plus_up = document.createElement('span');
							span_icon_crown_plus_up.className = 'icon-crown-plus plus-rank-up hide';
						div_one.appendChild(span_icon_crown_plus_up);
							var span_icon_crown_plus_down = document.createElement('span');
							span_icon_crown_plus_down.className = 'icon-crown-plus plus-rank-down ';
						div_one.appendChild(span_icon_crown_plus_down);
							var span_rank_num = document.createElement('span');
							span_rank_num.className = 'rank-num inline-block';
							span_rank_num.appendChild(document.createTextNode(hot));
						div_one.appendChild(span_rank_num);
							var span_icon_crown_minus_down = document.createElement('span');
							span_icon_crown_minus_down.className = 'icon-crown-minus minus-rank-down hide';
						div_one.appendChild(span_icon_crown_minus_down);
							var span_icon_crown_minus_up = document.createElement('span');
							span_icon_crown_minus_up.className = 'icon-crown-minus minus-rank-up ';
						div_one.appendChild(span_icon_crown_minus_up);
					}
				div_hot_rank_top.appendChild(div_one);
			div_rank_top.appendChild(div_hot_rank_top);
			li.appendChild(div_rank_top);

			var div_hot_rank
			var div_post_permalink = document.createElement('div');
			div_post_permalink.className = 'post-permalink per-post';
			// 	var div_user_post_rank = document.createElement('div');
			// 	div_user_post_rank.className = 'user-post-rank';
			// 		var div_rank_pull_left = document.createElement('div');
			// 		div_rank_pull_left.className = 'rank pull-left';
			// 		div_rank_pull_left.appendChild(document.createTextNode(data.medal_name));
			// 	div_user_post_rank.appendChild(div_rank_pull_left);

			// 		var a_medals = document.createElement('a');
			// 		a_medals.className = 'medals';
			// 		a_medals.href = 'javascript:void(0)';
			// 			var img_medals = document.createElement('img');
			// 			img_medals.src = url + data.medal_icon_url;
			// 		a_medals.appendChild(img_medals);
			// 	div_user_post_rank.appendChild(a_medals);

			// 		var div_clear_post_rank = document.createElement('div');
			// 		div_clear_post_rank.className = 'clear';
			// 	div_user_post_rank.appendChild(div_clear_post_rank);
			// div_post_permalink.appendChild(div_user_post_rank);

				var div_post_header = document.createElement('div');
				div_post_header.className = 'post-header';

					var div_ava42_only = document.createElement('div');
					div_ava42_only.className = 'ava42-only';
						var a_ava42_only = document.createElement('a');
						a_ava42_only.className = 'ava-user';
						a_ava42_only.href = url + data.username;
							var img_rounded = document.createElement('img');
							img_rounded.className = 'img-rounded';
							img_rounded.src = url + data.ava;
						a_ava42_only.appendChild(img_rounded);
					div_ava42_only.appendChild(a_ava42_only);
				div_post_header.appendChild(div_ava42_only);

					var div_header_analytics_only = document.createElement('div');
					div_header_analytics_only.className = 'header-analytics-only';
						var a_full_name = document.createElement('a');
						a_full_name.className = 'full-name';
						a_full_name.href = url + data.username;
							var div_nick_name = document.createElement('div');
							div_nick_name.className = 'nick-name';
							div_nick_name.appendChild( document.createTextNode(data.nickname) );
						a_full_name.appendChild(div_nick_name);
						// 	var small_username = document.createElement('small');
						// 	small_username.className = 'username';
						// 	small_username.appendChild( document.createTextNode('@' + data.username) );
						// a_full_name.appendChild(small_username);
							var div_time_post = document.createElement('div');
						div_time_post.className = 'time-post time-top';
							var small_time_post = document.createElement('small');
							small_time_post.appendChild( document.createTextNode(data.created_time['time-ago'] ) );
						div_time_post.appendChild(small_time_post);
						a_full_name.appendChild(div_time_post);
					div_header_analytics_only.appendChild(a_full_name);
				div_post_header.appendChild(div_header_analytics_only);

//Level
				var iframe = document.createElement('div');
					iframe.className = 'icon-user-pro A_level_icon level_icon_c1';
						var span = document.createElement('span');
						span.appendChild( document.createTextNode('Lv.'+ data.user_level) );
						span.setAttribute('data-toggle',"tooltip");
						span.setAttribute('data-placement',"top");
						span.setAttribute('title','Cấp '+ data.user_level);
					iframe.appendChild(span);
				div_post_header.appendChild(iframe);
//Gender
				var iframe = document.createElement('a');
					iframe.className = 'icon-user-pro';
					if (data.gender ==1) {
						var i_iframe = document.createElement('i');
						i_iframe.className = 'A_icon icon_male';
						i_iframe.setAttribute('data-toggle',"tooltip");
						i_iframe.setAttribute('data-placement',"top");
						i_iframe.setAttribute('title','Nam nhân');
						iframe.appendChild(i_iframe);
					} else if (data.gender == 2){
						var i_iframe = document.createElement('i');
						i_iframe.className = 'A_icon icon_female';
						i_iframe.setAttribute('data-toggle',"tooltip");
						i_iframe.setAttribute('data-placement',"top");
						i_iframe.setAttribute('title','Nữ nhân');
						iframe.appendChild(i_iframe);
					} else {

					};
				div_post_header.appendChild(iframe);
//Verify
				var iframe = document.createElement('a');
					iframe.className = 'icon-user-pro';
					if (data.confirmed == true) {
						var i_iframe = document.createElement('i');
						i_iframe.className = 'A_icon icon_approve_co';
						i_iframe.setAttribute('data-toggle',"tooltip");
						i_iframe.setAttribute('data-placement',"top");
						i_iframe.setAttribute('title','Xác thực cấp 1');
					} else if (data.confirmed == true && data.shaphira_verified == true) {
						var i_iframe = document.createElement('i');
						i_iframe.className = 'A_icon icon_approve';
						i_iframe.setAttribute('data-toggle',"tooltip");
						i_iframe.setAttribute('data-placement',"top");
						i_iframe.setAttribute('title','8xuhuong verify');
					} else {}
					iframe.appendChild(i_iframe);
				div_post_header.appendChild(iframe);
//Rank
				var iframe = document.createElement('a');
					iframe.className = 'icon-user-pro';
						var i_iframe = document.createElement('i');
						switch (data.blogger_level) {
							case '1':
								i_iframe.className = 'A_icon icon_member1';
								i_iframe.setAttribute('data-toggle',"tooltip");
								i_iframe.setAttribute('data-placement',"top");
								i_iframe.setAttribute('title','Blogger Cấp 1');
								break;
							case '2':
								i_iframe.className = 'A_icon icon_member2';
								i_iframe.setAttribute('data-toggle',"tooltip");
								i_iframe.setAttribute('data-placement',"top");
								i_iframe.setAttribute('title','Blogger Cấp 2');
								break;
							case '3':
								i_iframe.className = 'A_icon icon_member3';
								i_iframe.setAttribute('data-toggle',"tooltip");
								i_iframe.setAttribute('data-placement',"top");
								i_iframe.setAttribute('title','Blogger Cấp 3');
								break;
							case '4':
								i_iframe.className = 'A_icon icon_member4';
								i_iframe.setAttribute('data-toggle',"tooltip");
								i_iframe.setAttribute('data-placement',"top");
								i_iframe.setAttribute('title','Blogger Cấp 4');
								break;
							case '5':
								i_iframe.className = 'A_icon icon_member5';
								i_iframe.setAttribute('data-toggle',"tooltip");
								i_iframe.setAttribute('data-placement',"top");
								i_iframe.setAttribute('title','Blogger Cấp 5');
								break;
							case '6':
								i_iframe.className = 'A_icon icon_member6';
								i_iframe.setAttribute('data-toggle',"tooltip");
								i_iframe.setAttribute('data-placement',"top");
								i_iframe.setAttribute('title','Blogger Cấp 6');
								break;
						}
						iframe.appendChild(i_iframe);
				div_post_header.appendChild(iframe);

			div_post_permalink.appendChild(div_post_header);

				var div_post_header_2 = document.createElement('div');
				div_post_header_2.className = 'post-header';
					var a_post_header_2 = document.createElement('a');
					a_post_header_2.className = 'title-header';
					a_post_header_2.href = url+'posts/' + data.id;
					a_post_header_2.appendChild( document.createTextNode(data.title) );
				div_post_header_2.appendChild(a_post_header_2);
			div_post_permalink.appendChild(div_post_header_2);

				var div_post_content_fresh_hot = document.createElement('div');
				div_post_content_fresh_hot.className = 'post-content-fresh-hot';

				var length_content = data.new_content.length;
				var a_see_more = document.createTextNode('');
				var span = document.createTextNode('');
				var content_collapse = data.new_content;

				if(length_content >= max_length_content_collapse){
					content_collapse = data.new_content.substring(0,max_length_content_collapse) ;
					var position_last_space = content_collapse.lastIndexOf(' ');
					content_collapse = content_collapse.substring(0,position_last_space);
					span = document.createElement('span');
					span.appendChild(document.createTextNode('... '));
					a_see_more = document.createElement('a');
					a_see_more.href = url+'posts/'+data.id;
					a_see_more.appendChild(document.createTextNode('Đọc tiếp'));
				}

				div_post_content_fresh_hot.innerHTML =  content_collapse;
				div_post_content_fresh_hot.appendChild(span);
				div_post_content_fresh_hot.appendChild(a_see_more);
				if(data.image != null && data.image != ''){
					if(data.type_media == 0){
						var ul_media = document.createElement('ul');
							ul_media.className = 'A_media_box';
							var li_media = document.createElement('li');
							li_media.className = 'A_media';
								var img = document.createElement('img');
								img.className = 'media-thumb';
								img.setAttribute('data-original',data.image_thumb);
								img.setAttribute('data-src',data.image);
							li_media.appendChild(img);
						ul_media.appendChild(li_media);
						div_post_content_fresh_hot.appendChild(ul_media);
					}else {
						var ul_video = document.createElement('ul');
							ul_video.className = 'A_media_box';
							var li_video = document.createElement('li');
							li_video.className = 'A_media A_video';
								var img_video = document.createElement('img');
								img_video.className = 'video-thumb';
								img_video.setAttribute('data-original',data.image);
								img_video.setAttribute('data-media-id',data.media_api_id);
								img_video.setAttribute('data-media-type',data.type_media);
							li_video.appendChild(img_video);
							var i_icon_video = document.createElement('i');
							i_icon_video.className = 'A_icon icon-play-video';
							li_video.appendChild(i_icon_video);
							var div_player = document.createElement('div');
							div_player.id = 'player_'+data.id;
							div_player.className = 'hide player-youtube';
							li_video.appendChild(div_player);
						ul_video.appendChild(li_video);
						div_post_content_fresh_hot.appendChild(ul_video);
					}
				}
			div_post_permalink.appendChild(div_post_content_fresh_hot);

				var div_static_header = document.createElement('div');
				div_static_header.className = 'static-header';
					var ul_tags = document.createElement('ul');
					ul_tags.className = 'tags';
						var length_cate = data.Categories.length;

						for (var i = length_cate - 1; i >= 0; i--) {
							var li_cate = document.createElement('li');
								var a_li_cate = document.createElement('a');
								a_li_cate.className = 'cate-post';
								a_li_cate.href = 'javascript:void(0)';
								a_li_cate.appendChild(document.createTextNode(data.Categories[i].name));
							li_cate.appendChild(a_li_cate);
						ul_tags.appendChild(li_cate);
						};
						// if(data.link_tag != '' && data.link_tag != null && data.link_tag != undefined) {
						// 		var li_hastag = document.createElement('li');
						// 			var li_hastag_a = document.createElement('a');
						// 			li_hastag_a.className = 'hastag-tag';
						// 			li_hastag_a.href = '/hashtag/'+data.link_tag;
						// 			li_hastag_a.appendChild( document.createTextNode('#'+data.link_tag) );
						// 		li_hastag.appendChild(li_hastag_a);
						// 	ul_tags.appendChild(li_hastag);
						// }
						// ket thuc vong lap
						if(data.link != '' && data.link != null && data.link != undefined) {
								var li_link = document.createElement('li');
								li_link.className = 'li-link-to-web';
									var a_link_web = document.createElement('a');
									a_link_web.className = 'link-to-web';
									a_link_web.href = data.link;
									var p_link = document.createElement('p');
										p_link.appendChild( document.createTextNode(data.link) );
									a_link_web.appendChild( p_link );
								li_link.appendChild(a_link_web);
							ul_tags.appendChild(li_link);
						}
						var div_clear_ul_tag = document.createElement('div');
						div_clear_ul_tag.className = 'clear';
					ul_tags.appendChild(div_clear_ul_tag);
				div_static_header.appendChild(ul_tags);
			div_post_permalink.appendChild(div_static_header);

				var ul_post_stats = document.createElement('ul');
				ul_post_stats.className = 'post-stats';
				ul_post_stats.setAttribute('data-content-id',data.id);
					var li_brick_count = document.createElement('li');
					li_brick_count.className = 'brick-count';
						var div_brick_link = document.createElement('div');
							var a_brick_count = document.createElement('a');
							if(data.brick_able == 1 ) a_brick_count.className = 'brick--post bold';
							else a_brick_count.className = 'brick-post';
							a_brick_count.href = 'javascript:void(0)';
							a_brick_count.setAttribute('data-action','brick-post');
								var span_text_brick = document.createElement('span');
								span_text_brick.appendChild(document.createTextNode('Gạch'));
							a_brick_count.appendChild( span_text_brick );
						div_brick_link.appendChild(a_brick_count);
						var div_brick_count = document.createElement('div');
							var strong_brick_count = document.createElement('strong');
								strong_brick_count.appendChild( document.createTextNode(data.total_brick) );
							div_brick_count.appendChild( strong_brick_count );
					li_brick_count.appendChild( div_brick_link );
					li_brick_count.appendChild( div_brick_count );
				ul_post_stats.appendChild( li_brick_count );

					var li_like_count = document.createElement('li');
					li_like_count.className = 'like-count';
						var div_like_link = document.createElement('div');
							var a_like_count = document.createElement('a');
							if(data.like_able == 1 ) a_like_count.className = 'like--post bold';
							else a_like_count.className = 'like-post';
							a_like_count.href = 'javascript:void(0)';
							a_like_count.setAttribute('data-action','like-post');
								var span_text_like = document.createElement('span');
								span_text_like.appendChild(document.createTextNode('Thích'));
							a_like_count.appendChild( span_text_like );
						div_like_link.appendChild(a_like_count);
						var div_like_count = document.createElement('div');
							var strong_like_count = document.createElement('strong');
							strong_like_count.appendChild( document.createTextNode(data.total_like) );
						div_like_count.appendChild( strong_like_count );
					li_like_count.appendChild( div_like_link );
					li_like_count.appendChild( div_like_count );
				ul_post_stats.appendChild( li_like_count );

					var li_comment_count = document.createElement('li');
					li_comment_count.className = 'comment-count';
						var div_comment_link = document.createElement('div');
							var a_comment_count = document.createElement('a');
							a_comment_count.className = 'cmm-post';
							a_comment_count.href = url+'posts/'+data.id;
							var span_text_comment = document.createElement('span');
								span_text_comment.appendChild(document.createTextNode('Bình luận'));
							a_comment_count.appendChild( span_text_comment );
						div_comment_link.appendChild(a_comment_count);
						var div_comment_count = document.createElement('div');
							var strong_comment_count = document.createElement('strong');
							strong_comment_count.appendChild( document.createTextNode(data.total_comment) );
						div_comment_count.appendChild( strong_comment_count );
					li_comment_count.appendChild( div_comment_link );
					li_comment_count.appendChild( div_comment_count );
				ul_post_stats.appendChild( li_comment_count );

					var li_point_count = document.createElement('li');
					li_point_count.className = 'point-count';
						var div_point_link = document.createElement('div');
							var a_point_count = document.createElement('a');
							a_point_count.href = 'javascript:void(0)';
								var span_text_point = document.createElement('span');
								span_text_point.appendChild(document.createTextNode('Vàng'));
							a_point_count.appendChild( span_text_point );
						div_point_link.appendChild(a_point_count);
						var div_point_count = document.createElement('div');
							var strong_point_count = document.createElement('strong');
							strong_point_count.appendChild( document.createTextNode(data.total_point) );
						div_point_count.appendChild( strong_point_count );
					li_point_count.appendChild( div_point_link );
					li_point_count.appendChild( div_point_count );
				ul_post_stats.appendChild( li_point_count );

					var div_avatar_row_pull_left = document.createElement('div');
					div_avatar_row_pull_left.className = 'avatar-row pull-left display-item-row';

						var length_data_user = data.users_action.length;
						for (var i = length_data_user - 1; i >= 0; i--) {
							var a_ava_rela = document.createElement('a');
								a_ava_rela.className = 'ava-rela';
								a_ava_rela.setAttribute('data-toggle',"tooltip");
								a_ava_rela.setAttribute('data-placement',"top");
								a_ava_rela.setAttribute('title',data.users_action[i].username);
								a_ava_rela.href = url + data.users_action[i].username;
									var img_ava_rela = document.createElement('img');
									img_ava_rela.className = 'img-rounded ava25';
									// img_ava_rela.src = url + data.users_action[i].ava;
									img_ava_rela.setAttribute('data-original',url + data.users_action[i].ava);
								a_ava_rela.appendChild(img_ava_rela);
							div_avatar_row_pull_left.appendChild(a_ava_rela);
						}

					// ket thuc lap
				ul_post_stats.appendChild(div_avatar_row_pull_left);
			div_post_permalink.appendChild(ul_post_stats);
		li.appendChild(div_post_permalink);
			var div_clear_li = document.createElement('div');
			div_clear_li.className = 'clear';
		li.appendChild(div_clear_li);
	div_left_stream_box.appendChild(li);
	return div_left_stream_box;
}

function popup_notification (id, img, notifi, ur,nickname,notifi_type,content,post_id) {
	var div_notifi = document.createElement('div');
	div_notifi.className = 'box-notifi';
		var a_link_notification = document.createElement('a');
		a_link_notification.className = 'link-notification';
		a_link_notification.href =  url+'posts/'+post_id + '/?notifi_id='+ id;
		a_link_notification.setAttribute('data-post', id);
			var avatar_img_notification = document.createElement('div');
			avatar_img_notification.className = 'avatar-img-notification';
				var ava_popup = document.createElement('img');
				ava_popup.className = 'ava-popup';
				ava_popup.src = img;
				// ava_popup.setAttribute('data-original',img);
				avatar_img_notification.appendChild(ava_popup);
			a_link_notification.appendChild(avatar_img_notification);
			var detail_notification = document.createElement('div');
			detail_notification.className = 'detail-notification';
				var detail_noti = document.createElement('div');
				detail_noti.className = 'detail-noti';
					var user_post_popup = document.createElement('span');
					user_post_popup.className = 'user-post-popup';
            		user_post_popup.appendChild(document.createTextNode(nickname + ' '));
					detail_noti.appendChild(user_post_popup);
					var span = document.createElement('span');
					if(notifi==1){
						span.className = 'msg-popup';
						span.appendChild(document.createTextNode(notifi+' : '+content));
						detail_noti.appendChild(span);
					} else {
						span.className = 'msg-popup';
						span.appendChild(document.createTextNode(notifi+' : '+content));
						detail_noti.appendChild(span);
					}
				detail_notification.appendChild(detail_noti);
				var type_post = document.createElement('div');
				type_post.className = 'type-post';
					switch (notifi_type){
						case '0' :
						var	i = document.createElement('i');
						i.className = 'icon-comment-1';
						type_post.appendChild(i);
						break;
						case '1' :
						var	i = document.createElement('i');
						i.className = 'icon-emo-thumbsup';
						type_post.appendChild(i);
						break;
						case '2' :
						var	i = document.createElement('i');
						i.className = 'icon-brick';
						type_post.appendChild(i);
						break;
						case '3' :
						var	i = document.createElement('i');
						i.className = 'icon-at';
						type_post.appendChild(i);
						break;
					}
					var span_time = document.createElement('span');
					span_time.className = 'popup-time' ;
					span_time.appendChild(document.createTextNode('một vài giây trước.'));
					type_post.appendChild(span_time);
				detail_notification.appendChild(type_post);
			a_link_notification.appendChild(detail_notification);
			var div_clear = document.createElement('div');
			div_clear.className = 'clearfix';
			a_link_notification.appendChild(div_clear);
	div_notifi.appendChild(a_link_notification);
	return div_notifi;
}

function create_box_reply(content_id,title,username,comment_id,Elm) {
	var li_comment = document.createElement('li');
	li_comment.className = 'ident-com-1 box-reply-'+content_id;
	li_comment.setAttribute('data-original-title',title);
	li_comment.setAttribute('data-user',username);
	li_comment.setAttribute('data-content-id',content_id);
		var div_PostBoxContent = document.createElement('div');
		div_PostBoxContent.className = 'PostBoxContent';
			var div_BoxContent = document.createElement('div');
			div_BoxContent.className = 'BoxContent-com';
				var div_post_reply_box = document.createElement('div');
				div_post_reply_box.className = 'post-reply-box';
					var div_avatar_reply_box = document.createElement('div');
					div_avatar_reply_box.className = 'avatar-reply-box';
						var a = document.createElement('a');
							var img_ava = document.createElement('img');
							img_ava.className = 'img-rounded';
							// img_ava.src = Auth_ava;
							img_ava.setAttribute('data-original',Auth_ava);
						a.appendChild(img_ava);
					div_avatar_reply_box.appendChild(a);
				div_post_reply_box.appendChild(div_avatar_reply_box);

					var form = document.createElement('form');
					form.id = 'reply_comment';
					form.method = "POST";
					form.className = 'form-horizontal';
					form.action = '/reply/'+ comment_id;
					form.enctype = "application/x-www-form-urlencoded";
						var div_form_group = document.createElement('div');
						div_form_group.className = 'form-group submit-comment';
							var div_col = document.createElement('div');
							div_col.className = 'col-sm-12 col-right-none';
								var div_content = document.createElement('div');
								div_content.className = 'form-control contenteditable reply-comment-text';
								div_content.setAttribute('data-original-title',Elm.attr('data-original-title'));
								div_content.setAttribute('contenteditable','false');
								div_content.setAttribute('data-user',username);
								div_content.setAttribute('data-content','false');
									var div_main = document.createElement('div');
									div_main.innerHTML = '@' + username + '&nbsp;' ;
								div_content.appendChild(div_main);
							div_col.appendChild(div_content);
								var typeahead = document.createElement('input');
								typeahead.className="typeahead";
								typeahead.type = 'hidden';
							div_col.appendChild(typeahead);
								var textarea = document.createElement('textarea');
								textarea.className="content hide";
								textarea.name = 'content';
								textarea.value = '';
								textarea.rows = '4';
								textarea.cols = '50';
							div_col.appendChild(textarea);

						div_form_group.appendChild(div_col);
					form.appendChild(div_form_group);
						var div_submit_comment = document.createElement('div');
						div_submit_comment.className = 'form-group submit-comment reply-uti';
							var div_submit_col = document.createElement('div');
							div_submit_col.className = "col-sm-12 col-right-none";

								var button_submit = document.createElement('button');
								button_submit.type = 'submit';
								button_submit.id ='submit-newComment';
								button_submit.className = 'btn btn-primary pull-right submit-newReply';
								button_submit.setAttribute('data-loading-text','Loading...');
								button_submit.setAttribute('autocomplete','off');
									var span_submit = document.createElement('span');
									span_submit.className = 'submit-cmm' ;
									span_submit.appendChild(document.createTextNode('Đăng'));
								button_submit.appendChild(span_submit);
							div_submit_col.appendChild(button_submit);

							var a = document.createElement('a');
								a.className = 'btn btn-default pull-right button-margin emo-rep add-emoticon';
								a.setAttribute('autocomplete','off');
									var span_submit = document.createElement('span');
									span_submit.className = 'add-emoticons icon-emo-sunglasses ' ;
										var	span = document.createElement('span')
										span.appendChild(document.createTextNode('Emo'));
									span_submit.appendChild(span);
								a.appendChild(span_submit);
							div_submit_col.appendChild(a);

								var span = document.createElement('span');
									span.className = 'count_text';
									span.appendChild(document.createTextNode('500'));
							div_submit_col.appendChild(span);
						div_submit_comment.appendChild(div_submit_col);
					form.appendChild(div_submit_comment);
					var	div = document.createElement('div');
						div.className = 'form-group submit-emo-reply hide';
							var	div_col = document.createElement('div');
							div_col.className = 'col-md-12 col-right-none';
								var div_face = document.createElement('div');
								div_face.className = 'facebox ';
									var f_header = document.createElement('div');
									f_header.className = "f-header col-md-12";
										var length_group = emo_group.length;
										var a = document.createElement('a');
											a.className = 'group-emo col-md-1 selected';
											a.setAttribute('data-group','Hot');
											a.appendChild(document.createTextNode('Hot'));
									f_header.appendChild(a);
									for(var i = 0; i < length_group ; i++){
											var a = document.createElement('a');
											a.className = 'group-emo col-md-1';
											a.setAttribute('data-group',emo_group[i].emo_group);
											a.appendChild(document.createTextNode(emo_group[i].emo_group));
										f_header.appendChild(a);
									}
										var div_clearfix = document.createElement('div');
										div_clearfix.className = 'clearfix';
									f_header.appendChild(div_clearfix);
								div_face.appendChild(f_header);
								var f_content = document.createElement('div');
									f_content.className = "f-content col-md-12";
								div_face.appendChild(f_content);
									var div_clearfix = document.createElement('div');
									div_clearfix.className = 'clearfix';
								div_face.appendChild(div_clearfix);
							div_col.appendChild(div_face);

						div.appendChild(div_col);
					form.appendChild(div);

				div_post_reply_box.appendChild(form);
			div_BoxContent.appendChild(div_post_reply_box);
		div_PostBoxContent.appendChild(div_BoxContent);
			var div_clear = document.createElement('div');
			div_clear.className = 'clear';
		div_PostBoxContent.appendChild(div_clear);

	li_comment.appendChild(div_PostBoxContent);
	$('ul.stream-reply[data-id-comment="'+comment_id+'"]').first().append(li_comment);
	var title = $(div_content).attr('data-original-title');
	if($(div_content).attr('data-content') == 'false'){
    	$(div_content).attr('contenteditable', 'true');
    	$(div_content).attr('data-content', 'true');
        username = '@' + username +' ';
        linkSelection = {
            start: username.length,
            end: username.length
        };
        doRestore($(div_content).children()[0], linkSelection);
        fill_data($(div_content));
        $(div_content).focus();
    }
	$(div_content).goTo(-400);
}

function create_element_user_online(data) {
	var li = document.createElement('li');
	li.className = 'simple-user pull-left';
	li.setAttribute('data-username',data['username']);
	li.setAttribute('data-toggle','tooltip');
	li.setAttribute('data-placement','right');
	li.setAttribute('title',data['username']);
		var a_ava_hero = document.createElement('a');
		a_ava_hero.className = 'ava-user-online';
		a_ava_hero.href = url+data['username'];
			var img_ava = document.createElement('img');
			img_ava.className = 'ava36 img-rounded ';
			img_ava.src = data['ava'];
		a_ava_hero.appendChild(img_ava);
	li.appendChild(a_ava_hero);
	return li;
}

function create_element_admin_online(data) {
	var li = document.createElement('li');
	li.className = 'simple-user pull-left';
	li.setAttribute('data-username',data['username']);
	li.setAttribute('data-toggle','tooltip');
	li.setAttribute('data-placement','right');
	li.setAttribute('title',data['username']);
		var a_ava_hero = document.createElement('a');
		a_ava_hero.className = 'ava-admin-online';
		a_ava_hero.href = url+data['username'];
			var img_ava = document.createElement('img');
			img_ava.className = 'ava36 img-rounded ';
			img_ava.src = data['ava'];
		a_ava_hero.appendChild(img_ava);
	li.appendChild(a_ava_hero);
	return li;
}

function load_emoticons(parents,data){
	var length = data.length;
	if(parents.find('img').length == 0){
		for (var i = length- 1; i >= 0; i--) {
			var _char = data[i][0];
			var emo = url+data[i][3]+data[i][2]+'/'+data[i][1];
			var img = document.createElement('img');
			// img.src = emo;
			img.setAttribute('data-original',emo);

			img.setAttribute('title',_char);
			parents.append(img);
		}
	}
}

function load_emoticons_ajax(parents,data){
	var length = data.length;
	if(parents.find('img').length == 0){
		for (var i = length- 1; i >= 0; i--) {
			var _char = data[i].char;
			var emo = url+data[i].url+data[i].emo_group+'/'+data[i].emoticon;
			var img = document.createElement('img');
			// img.src = emo;
			img.setAttribute('data-original',emo);
			img.setAttribute('title',_char);
			parents.append(img);
		}
	}
}
function profile(data){
	var ul = document.createElement('ul');
	ul.className = "left-stream profile-stream"
		var li = document.createElement('li');
		li.className = 'col-md-12 header-profile-list';
			var b = document.createElement('b');
				var span = document.createElement('span');
				span.className = 'pull-left col-md-6';
					var i = document.createElement('i');
					i.className = 'icon-user';
				span.appendChild(i);
				span.appendChild(document.createTextNode(data.nickname));
			b.appendChild(span);
				var span = document.createElement('span');
				span.className = "pull-right col-md-6";
					var i = document.createElement('i');
					i.className = 'icon-brick';
				span.appendChild(i);
				span.appendChild(document.createTextNode(data.has_brick));
			b.appendChild(span);
				var div = document.createElement('div');
				div.className = 'clear';
			b.appendChild(div);
		li.appendChild(b);
			var div = document.createElement('div');
			div.className = 'clear';
		li.appendChild(div);
	ul.appendChild(li);
		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('User @mentions:'));
		li.appendChild(b);
			var span = document.createElement('span');
			span.className = 'username-hightlight';
				var b = document.createElement('b');
				b.appendChild(document.createTextNode('@'+data.username));
			span.appendChild(b);
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Danh Hiệu:'));
		li.appendChild(b);
			var span = document.createElement('span');
			span.className = 'rank-profile-list';
				var b = document.createElement('b');
				b.appendChild(document.createTextNode(data.medal_name));
			span.appendChild(b);
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Huy chương:'));
		li.appendChild(b);
			var span = document.createElement('span');
				var img = document.createElement('img');
				img.className = 'img-rounded medals-on-list';
				// img.src = url+data.medal_icon_url;
				img.setAttribute('data-original',data.medal_icon_url);
			span.appendChild(img);
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Họ và tên: '));
		li.appendChild(b);
			var span = document.createElement('span');
			span.className = 'rank-profile-list';
			span.appendChild(document.createTextNode(data.firstname+' ' + data.lastname));
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Xác thực tài khoản: '));
		li.appendChild(b);
		if(data.confirmed == 1){
			var span = document.createElement('span');
				var i = document.createElement('i');
				i.className = 'icon-ok-circled-2';
			span.appendChild(i);
			span.appendChild(document.createTextNode('Tài khoản đã được xác thực.'));
		li.appendChild(span);
		}else {
			var span = document.createElement('span');
			span.appendChild(document.createTextNode('Chưa '));
		li.appendChild(span);
			var a = document.createElement('a');
			a.href='#';
			a.appendChild(document.createTextNode('xác thực'));
		li.appendChild(a);
		}
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Cấp độ: '));
		li.appendChild(b);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.user_level));
		li.appendChild(span);
		if(data.upLevel && Auth_userName == data.username){
			var a = document.createElement('a');
			a.className = 'up-level icon-uplevel';
			a.href = '#';
			a.setAttribute('title','Up Level');
				var span1 = document.createElement('span');
				span1.className = 'display-item-row span-uplv';
				span1.appendChild(document.createTextNode('Up Level'));
			a.appendChild(span1);
				var span2 = document.createElement('span');
				span2.className = 'icon-up-circle ';
			a.appendChild(span2);
			li.appendChild(a);
		}
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Ngày sinh: '));
		li.appendChild(b);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.birthday));
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Giới tính: '));
		li.appendChild(b);
			var span = document.createElement('span');
			if(data.gender == 0) span.appendChild(document.createTextNode('Thím'));
			else if(data.gender == 1) span.appendChild(document.createTextNode('Nam'));
			else if(data.gender == 2) span.appendChild(document.createTextNode('Nữ'));
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Vài nét về '+ data.nickname+' :' ));
		li.appendChild(b);
			var span = document.createElement('span');
			if(data.about != undefined && data.about != '' && data.about != ' ') span.appendChild(document.createTextNode(data.about));
			else  span.appendChild(document.createTextNode('...'));
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Đang ở: ' ));
		li.appendChild(b);
			var span = document.createElement('span');
			if(data.current_town_id != undefined && data.current_town_id != '' && data.current_town_id != ' ') span.appendChild(document.createTextNode(data.current_town_id));
			else  span.appendChild(document.createTextNode('...'));
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Lượng vàng hiện tại: ' ));
		li.appendChild(b);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.real_point));
		li.appendChild(span)
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Lượng vàng cao nhất từng có: ' ));
		li.appendChild(b);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.max_point));
		li.appendChild(span)
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Tổng số lượt thích đã nhận: ' ));
		li.appendChild(b);
			var	i = document.createElement('i');
			i.className = 'icon-emo-thumbsup';
		li.appendChild(i);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.total_like));
		li.appendChild(span)
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Tổng số gạch phải nhận: ' ));
		li.appendChild(b);
		var	i = document.createElement('i');
			i.className = 'icon-brick';
		li.appendChild(i);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.total_brick));
		li.appendChild(span)
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Tổng số bài đã đăng: ' ));
		li.appendChild(b);
		var	i = document.createElement('i');
			i.className = 'icon-comment-1';
		li.appendChild(i);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.total_post));
		li.appendChild(span)
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Được trả lời: ' ));
		li.appendChild(b);
		var	i = document.createElement('i');
			i.className = 'icon-reply';
		li.appendChild(i);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.total_comment));
		li.appendChild(span)
	ul.appendChild(li);
	return ul;
}

function profile_report(data){
	var ul = document.createElement('ul');
	ul.className = "left-stream"
		var li = document.createElement('li');
		li.className = 'col-md-12 header-profile-list';
			var b = document.createElement('b');
				var span = document.createElement('span');
				span.className = 'pull-left col-md-6';
					var i = document.createElement('i');
					i.className = 'icon-user';
				span.appendChild(i);
				span.appendChild(document.createTextNode(data.nickname + "'s Profile"));
			b.appendChild(span);
				if(data.upLevel && Auth_userName == data.username){
					var a = document.createElement('a');
					a.className = 'up-level';
					a.href = '#';
						var span = document.createElement('span');
						span.className = 'pull-right col-md-6';
						span.appendChild(document.createTextNode('Up level'));
					a.appendChild(span);
					b.appendChild(a);
				}
				var span = document.createElement('span');
				span.className = "pull-right col-md-6";
					var i = document.createElement('i');
					i.className = 'icon-brick';
				span.appendChild(i);
				span.appendChild(document.createTextNode(data.has_brick));
			b.appendChild(span);
				var div = document.createElement('div');
				div.className = 'clear';
			b.appendChild(div);
		li.appendChild(b);
			var div = document.createElement('div');
			div.className = 'clear';
		li.appendChild(div);
	ul.appendChild(li);
		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('User @mentions:'));
		li.appendChild(b);
			var span = document.createElement('span');
			span.className = 'username-hightlight';
				var b = document.createElement('b');
				b.appendChild(document.createTextNode('@'+data.username));
			span.appendChild(b);
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Danh Hiệu:'));
		li.appendChild(b);
			var span = document.createElement('span');
			span.className = 'rank-profile-list';
				var b = document.createElement('b');
				b.appendChild(document.createTextNode(data.medal_name));
			span.appendChild(b);
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('ava:'));
		li.appendChild(b);
			var span = document.createElement('span');
				var img = document.createElement('img');
				img.className = 'ava42 img-rounded';
				// img.src = url+data.ava;
				img.setAttribute('data-original',url+data.ava);
			span.appendChild(img);
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Họ và tên: '));
		li.appendChild(b);
			var span = document.createElement('span');
			span.className = 'rank-profile-list';
			span.appendChild(document.createTextNode(data.firstname+' ' + data.lastname));
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Xác thực tài khoản: '));
		li.appendChild(b);
		if(data.confirmed == 1){
			var span = document.createElement('span');
				var i = document.createElement('i');
				i.className = 'icon-ok-circled-2';
			span.appendChild(i);
			span.appendChild(document.createTextNode('Tài khoản đã được xác thực.'));
		li.appendChild(span);
		}else {
			var span = document.createElement('span');
			span.appendChild(document.createTextNode('Chưa '));
		li.appendChild(span);
			var a = document.createElement('a');
			a.href='#';
			a.appendChild(document.createTextNode('xác thực'));
		li.appendChild(a);
		}
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Cấp độ: '));
		li.appendChild(b);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.user_level));
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Giới tính: '));
		li.appendChild(b);
			var span = document.createElement('span');
			if(data.gender == 0) span.appendChild(document.createTextNode('Thím'));
			else if(data.gender == 1) span.appendChild(document.createTextNode('Nam'));
			else if(data.gender == 2) span.appendChild(document.createTextNode('Nữ'));
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Vài nét về '+ data.nickname+' :' ));
		li.appendChild(b);
			var span = document.createElement('span');
			if(data.about != undefined && data.about != '' && data.about != ' ') span.appendChild(document.createTextNode(data.about));
			else  span.appendChild(document.createTextNode('...'));
		li.appendChild(span);
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Lượng vàng hiện tại: ' ));
		li.appendChild(b);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.real_point));
		li.appendChild(span)
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Lượng vàng cao nhất từng có: ' ));
		li.appendChild(b);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.max_point));
		li.appendChild(span)
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Tổng số lượt thích đã nhận: ' ));
		li.appendChild(b);
			var	i = document.createElement('i');
			i.className = 'icon-emo-thumbsup';
		li.appendChild(i);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.total_like));
		li.appendChild(span)
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Tổng số gạch phải nhận: ' ));
		li.appendChild(b);
		var	i = document.createElement('i');
			i.className = 'icon-brick';
		li.appendChild(i);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.total_brick));
		li.appendChild(span)
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Tổng số bài đã đăng: ' ));
		li.appendChild(b);
		var	i = document.createElement('i');
			i.className = 'icon-comment-1';
		li.appendChild(i);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.total_post));
		li.appendChild(span)
	ul.appendChild(li);

		var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('Được trả lời: ' ));
		li.appendChild(b);
		var	i = document.createElement('i');
			i.className = 'icon-reply';
		li.appendChild(i);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.total_comment));
		li.appendChild(span)
	ul.appendChild(li);
	return ul;
}

function data_bane(Elm,data){


	var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('người bane: ' ));
		li.appendChild(b);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.staff_name));
		li.appendChild(span)
	Elm.appendChild(li);

	var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('lý do bane: ' ));
		li.appendChild(b);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(data.reason));
		li.appendChild(span)
	Elm.appendChild(li);

	var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('ngày bane: ' ));
		li.appendChild(b);
			var span = document.createElement('span');
			span.appendChild(document.createTextNode(new Date(data.created_time*1000)));
		li.appendChild(span)
	Elm.appendChild(li);

	var li = document.createElement('li');
			var b = document.createElement('b');
			b.className = 'col-md-4';
			b.appendChild(document.createTextNode('ngày kết thúc: ' ));
		li.appendChild(b);
			var span = document.createElement('span');
			if(data.end_time != null){
				span.appendChild(document.createTextNode(new Date(data.end_time*1000)));
			}else {
				span.appendChild(document.createTextNode('Không thể cứu vớt... T_T'));
			}
		li.appendChild(span)
	Elm.appendChild(li);

	return Elm;
}

function box_favor(name,link,code,class_active) {
	var li = document.createElement('li');
		var a = document.createElement('a');
		a.className = 'cate-post change-favor-active '+ class_active;
		a.href = link;
		a.setAttribute('data-code',code);
		a.appendChild(document.createTextNode(name));
	li.appendChild(a);
	// title
	return li;
}