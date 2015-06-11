
/*
|----------------------------------------------------------------------
| Tạo mã bảo mật cho ajax
|----------------------------------------------------------------------
| Khởi tạo mã bảo mật cho ajax
|
*/
$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });
});

function extractTextWithWhitespace(elems,is_return_data)
	{
	    var lineBreakNodeName = "BR"; // Use <br> as a default
	    if (check_browser || is_return_data)
	    {
	        lineBreakNodeName = "DIV";
	    }
	    var extractedText = extractTextWithWhitespaceWorker(elems, lineBreakNodeName,is_return_data);

	    return extractedText;
	}
	jQuery.br2nl = function(varTest){
	    return varTest.replace(/<br>/g, "\r");
	};
	jQuery.nl2br = function(varTest){
	    return varTest.replace(/(\r\n|\n\r|\r|\n)/g, "<br>");
	};
// Cribbed from jQuery 1.4.2 (getText) and modified to retain whitespace
function extractTextWithWhitespaceWorker(elems, lineBreakNodeName,is_return_data)
{
    var ret = "";
    var elem;

    for (var i = 0; elems[i]; i++)
    {
        elem = elems[i];
        if (elem.nodeType === 3     // text node
            || elem.nodeType === 4) // CDATA node
        {
        	if(elem.data.length > 0){
            	ret += elem.nodeValue;
        	}
        }

        if ((elem.nodeType !== 8 && !is_return_data ) || (elem.nodeType !== 8 && elem.nodeName != 'A')) // comment node
        {
            ret += extractTextWithWhitespace(elem.childNodes, is_return_data);
        } else if( elem.nodeType !== 8 && elem.nodeName == 'A' && is_return_data ){

        	var value = extractTextWithWhitespace(elem.childNodes, is_return_data);;
        	ret += '<a href="'+value.substring(0,value.length)+'">'+value+'</a>';
        }

        if (elem.nodeName === lineBreakNodeName)
        {
            ret += "\n";
        }
    }

    return ret;
}

$(document).ready(function(){

	$('#submit-newPost').on('click', function () {
	   var $btn = $(this).button('loading');
	}) ;
	$('#submit-newComment').on('click', function () {
		var $btn = $(this).button('loading') ;
		// business logic...
		$btn.button('reset') ;
	});
	$('.submit-newReply').on('click', function () {
		var $btn = $(this).button('loading');
		// business logic...
		$btn.button('reset');
	});
	$('body').tooltip({
	    selector: '[data-toggle="tooltip"]'
	});
	get_current_time();
	if(isOpera || isSafari || isChrome){
		check_browser = true;
		$('.contenteditable').html('<div>Bình luận bài viết !</div>');
	}else {
		if(isIE){
			var div_content = $('.contenteditable');
			div_content.html('') ;
		    var oTextNode = document.createTextNode("Bình luận bài viết !");
		    var oReplaceNode = div_content.append(oTextNode);
		    div_content.append(document.createElement('br'));
	    }else {
			$('.contenteditable').html('Bình luận bài viết !<br>');
	    }
	}


	$(document).on('focus click', '.contenteditable', function(e) {
        if($(this).attr('data-content') == 'false'){
        	$(this).attr('contenteditable', 'true');
        	$(this).attr('data-content', 'true');
        	var id = $(this).attr('id');
        	if(id != undefined && ( id == 'div_content' || id == 'port_content') ) {
	        	if(check_browser) {
		            title_contenteditable = $(this).children()[0].innerHTML;
		            $(this).children()[0].innerHTML = "<br>";
		            linkSelection = {
		                start: 0,
		                end: 0
		            };
		            doRestore($(this).children()[0], linkSelection);
	            }else {
	            	title_contenteditable = $(this).contents()[0].data;
	            	$(this).contents()[0].data = '';
	           		$(this).focus();
	            }
            }

        }
    });

	$(document).on('focusout', '.contenteditable', function(e) {
		if(!$(this).hasClass('reply-comment-text')){
	        var value = $(this).parent().find('textarea').first().val();
	        if(value == '') {
	        	$(this).attr('contenteditable', 'false');
		    	$(this).attr('data-content', 'false');
	        	if(check_browser) {
		            $(this).html('<div></div>');
	        		$(this).children()[0].innerHTML = title_contenteditable ;
	        	}else {
	        		if(isIE){
		        		var div_content = $(this);
		        		div_content.html('');
					    var oTextNode = document.createTextNode(title_contenteditable);
					    var oReplaceNode = div_content.append(oTextNode);
					    div_content.append(document.createElement('br'));
				    }else {
						$(this).html('Click vào đây để <br>');
				    }
	        	}

	        }
	    }
    });
	$(document).on('keydown','.contenteditable',function(e) {
		fill_data($(this));
	// 	if(e.keyCode == 17 ) {
 //            e.preventDefault();
 //            return;
 //        }
 //        selection = rangy.getSelection().saveCharacterRanges(this);
 //        var node_forcus = rangy.getSelection().focusNode;
 //        var node_parent = node_forcus.parentNode;
 //        if(node_parent.nodeName == "A") {
 //            switch (e.keyCode) {
 //                case 38:
 //                case 40:
 //                case 17:
 //                	e.preventDefault();
 //                    return;
 //                case 13:
 //                case 9:
 //                    // var a = $(this).parent().find('.twitter-typeahead').find('.tt-cursor')[0];
 //                    if(a == undefined){
 //                        var ev = jQuery.Event("keydown");
 //                        ev.which = 40;
 //                        // $(this).parent().find(".typeahead").trigger(ev);
 //                    }
 //                    e.preventDefault();
 //                    return;
 //                default :
 //                    // $(this).parent().find(".typeahead").typeahead('close');
 //            }
 //            node_parent = node_parent.parentNode;
 //        } else {
 //              switch (e.keyCode) {
 //                case 13:
 //                if($(this).parent().attr('class') == 'comment-box'){
 //                    var ElmParent = $(this).parents('li').first();
 //                    var _url = url + 'new-reply/' + ElmParent.data('ir');
 //                    fill_data($(this));
 //                    $.post(_url, {
 //                        content: ElmParent.find('.content').first().val()
 //                    }, function(data) {
 //                        if (data != "false") {
 //                            //phan tuy chinh thu tu tren duoi cua reply
 //                            ElmParent.find('.content').first().val('');
 //                        }
 //                    });
 //                    var div = $(this);
 //                    if(check_browser){
 //                    	div.html('<div>Trả lời bài viết.../div>');
 //                    }else {
 //                    	if(isIE){
	// 		        		var div_content = $(this);
	// 		        		div_content.html('');
	// 					    var oTextNode = document.createTextNode('Trả lời bài viết...');
	// 					    var oReplaceNode = div_content.firstChild.replaceNode(oTextNode);
	// 					    div_content.append(document.createElement('br'));
	// 				    }else {
	// 						$(this).html('Trả lời bài viết...<br>');
	// 				    }
 //                    }
 //                    div.attr('contenteditable', 'false');
 //                    div.attr('data-content', 'false');
 //                } else {
	// 	            if(e.keyCode == 13){
	// 	            	var div = $(this);
	// 	            	var TextNode = document.createTextNode('');
	// 	            	div.append(TextNode);
	// 	                div.append('<br>');
	// 	                linkSelection = {
	// 	                    start: 0,
	// 	                    end: 0
	// 	                };
	// 	                doRestore(TextNode, linkSelection);
	// 	                e.preventDefault();
	// 	            }
 //                }
 //                e.preventDefault();
 //                return;
 //            }
 //            // $(this).parent().find(".typeahead").typeahead('close');
 //        }

	});


	$(document).on('keyup','.contenteditable',function(e) {
		// if(e.keyCode == 17)  {
  //           e.preventDefault();
  //           return;
  //       }
        if (e.keyCode == 8 && $(this).text().length == 0) {
            // if(check_browser) {
	            $(this).html("<div><br></div>");
	            linkSelection = {
	                start: 0,
	                end: 0
	            };
	            doRestore($(this).children()[0], linkSelection);
	            fill_data($(this));
	            e.preventDefault();
	            return;
       //      }else {
       //  		var div_content = $(this);
       //  		div_content.html('');
			    // var oTextNode = document.createTextNode('');
			    // div_content.append(oTextNode);
			    // div_content.append(document.createElement('br'));
			    // linkSelection = {
	      //           start: 0,
	      //           end: 0
	      //       };
	      //       doRestore(oTextNode, linkSelection);
       //      }
        }
  //       selection = rangy.getSelection().saveCharacterRanges(this);
  //       var node_forcus = rangy.getSelection().focusNode;
  //       var node_parent = node_forcus.parentNode;
  //       if(node_parent.nodeName == "A") {
  //           switch (e.keyCode) {
  //               case 38:
  //               case 40:
  //                   // $(this).parent().find(".typeahead").typeahead('open');
  //                   var ev = jQuery.Event("keydown");
  //                   ev.which = e.keyCode;
  //                   // $(this).parent().find(".typeahead").trigger(ev);
  //                   e.preventDefault();
  //                   return;
  //               case 13:
  //               case 9:
  //                   var ev = jQuery.Event("keydown");
  //                   ev.which = e.keyCode;
  //                   // $(this).parent().find(".typeahead").trigger(ev);
  //                   e.preventDefault();
  //                   return;
  //               default :
  //                   // $(this).parent().find(".typeahead").typeahead('close');
  //             }
  //           node_parent = node_parent.parentNode;
  //        } //else { $(this).parent().find(".typeahead").typeahead('close');}
  //       if(node_forcus.nodeName == '#text' && node_parent.nodeName != 'A'){
  //       	var position_tagkey = node_forcus.data.search('@');
  //       	if(position_tagkey != -1 && node_forcus.data[position_tagkey + 1] != undefined && regExp.test(node_forcus.data[position_tagkey + 1]) && (/\s+$/.test(node_forcus.data[position_tagkey - 1]) || position_tagkey == 0)){
  //       		var div_contenteditable = document.createElement('div');
		//         var data ='';
		//         var len_child = $(this).children().length;
		//         data = extractTextWithWhitespace($(this).contents(),false);
		//         if(data != ''){
		//         	data = data.split('\n');
		//             var data_length = data.length;
		//             var text = '',text_link = '',is_tag = false;
		//             for(var i = 0 ; i < data_length ; i ++){
		//         		var div = document.createElement('div');
		//             	var _data = data[i];
		//             	var len_2 = _data.length;
		//             	text = '',text_link = '',is_tag = false;
		// 	            for(var j = 0 ; j < len_2; j ++){
		// 	                if($.inArray(_data[j - 1], _tagKey) != -1 && (/\s+$/.test(_data[j - 2]) ||  j-1 == 0)  &&  regExp.test(_data[j])) {
		// 	                    is_tag = true;
		// 	                }
		// 	                if(!is_tag) {
		// 	                    if($.inArray(_data[j - 1], _tagKey) != -1 && /\s+$/.test(_data[j - 2]) && regExp.test(_data[j])) {
		// 	                        is_tag = true;
		// 	                    } else {
		// 	                        if(text_link !="") {
		// 	                            var link  = document.createElement('a');
		// 	                            link.href= text_link.substring(1,text_link.length);
		// 	                            link.appendChild(document.createTextNode(text_link));
		// 	                            div.appendChild(link);
		// 	                            text_link = '';
		// 	                        }
		// 	                        text += _data[j];
		// 	                    }
		// 	                } else {
		// 	                    if( regExp.test(_data[j]) ) {
		// 	                        if(text !="") {
		// 	                            div.appendChild(document.createTextNode(text.substring(0,text.length -1)));
		// 	                            text = '';
		// 	                        }
		// 	                        if($.inArray(_data[j-1], _tagKey) != -1) text_link += _data[j-1];
		// 	                        text_link += _data[j];
		// 	                        if($.inArray(text_link[0], _tagKey_max_length['tagKey']) != -1 && text_link.length == _tagKey_max_length[text_link[0]]) {
		// 	                            is_tag = false;
		// 	                        }
		// 	                    // }else if( $.inArray(data[j-1], _mentions) != -1 || $.inArray(text_link[0], _mentions) != -1){
		// 	                    //     if(text !="") {
		// 	                    //         div.appendChild(document.createTextNode(text.substring(0,text.length -1)));
		// 	                    //         text = '';
		// 	                    //     }
		// 	                    //     if($.inArray(data[j-1], _tagKey) != -1) text_link += data[j-1];
		// 	                    //     text_link += data[j];
		// 	                    //     if($.inArray(text_link[0], _tagKey_max_length) != -1 && text_link.length == 17) {
		// 	                    //         is_tag = false;
		// 	                    //     }
		// 	                    } else if(($.inArray(_data[j], _tagKey) == -1 && !regExp.test(_data[j]))   || $.inArray(_data[j], _tagKey) != -1){
		// 	                        is_tag = false;
		// 	                        if(/\s+$/.test(_data[j])) {
		// 	                            text += _data[j];
		// 	                            var link  = document.createElement('a');
		// 	                            link.href= text_link.substring(1,text_link.length);
		// 	                            link.appendChild(document.createTextNode(text_link));
		// 	                            div.appendChild(link);
		// 	                            text_link = '';
		// 	                        }else {
		// 	                        	if(div.innerHTML != ''){
		// 		                            text = div.lastChild.data + text_link + _data[j];
		// 		                            div.lastChild.remove();
		// 	                            }
		// 	                        }
		// 	                        text_link = '';
		// 	                    }
		// 	                }
		// 	            }

		// 	            if(text_link != ''){
		// 	                var link  = document.createElement('a');
		// 	                link.href= text_link.substring(1,text_link.length);
		// 	                link.appendChild(document.createTextNode(text_link));
		// 	                div.appendChild(link);
		// 	            } else if(text != ''){
		// 	                div.appendChild(document.createTextNode(text));
		// 	            }
		// 	            if(div.innerHTML == ''){
		// 	            	if(check_browser){
		// 	            		div.appendChild(document.createElement('br'));
		// 	            	}
		// 	            }
		// 	            $(div_contenteditable).append($(div));
		//             }
		//         }
		//         if(!check_browser){
		//         	$(div_contenteditable).html( $.nl2br(extractTextWithWhitespace($(div_contenteditable).contents(),true)));
		//         	$(':last-child', $(div_contenteditable)).remove();
		//         	$(this).html($(div_contenteditable).html());
		//         } else {
		// 	    	for(var i = 0 ; i < $(this).children().length; i ++){
		// 	    		var a = $($(div_contenteditable).children()[0]);
		// 	    		$($(this).children()[i]).replaceWith(a);
		// 	    	}
		//     	}
		//         rangy.getSelection().restoreCharacterRanges(this, selection);
		//         selection = rangy.getSelection().saveCharacterRanges(this);
		//         node_forcus = rangy.getSelection().focusNode;
		//         if(node_forcus.parentNode.nodeName == "A") {
		//             text_link = node_forcus.parentNode.childNodes[0].data;
		//             // $(this).parent().find('.typeahead').eq(1).val(text_link.substring(1,text_link.length)).trigger("input");
		//          }//else $(this).parent().find(".typeahead").typeahead('close');
  //       	}
  //       }else {
  //       	var div_contenteditable = document.createElement('div');
	 //        var data ='';
	 //        var len_child = $(this).children().length;
	 //        data = extractTextWithWhitespace($(this).contents(),false);
	 //        if(data != ''){
	 //        	data = data.split('\n');
	 //            var data_length = data.length;
	 //            var text = '',text_link = '',is_tag = false;
	 //            for(var i = 0 ; i < data_length ; i ++){
	 //        		var div = document.createElement('div');
	 //            	var _data = data[i];
	 //            	var len_2 = _data.length;
	 //            	text = '',text_link = '',is_tag = false;
		//             for(var j = 0 ; j < len_2; j ++){
		//                 if($.inArray(_data[j - 1], _tagKey) != -1 && (/\s+$/.test(_data[j - 2]) ||  j-1 == 0)  &&  regExp.test(_data[j])) {
		//                     is_tag = true;
		//                 }
		//                 if(!is_tag) {
		//                     if($.inArray(_data[j - 1], _tagKey) != -1 && /\s+$/.test(_data[j - 2]) && regExp.test(_data[j])) {
		//                         is_tag = true;
		//                     } else {
		//                         if(text_link !="") {
		//                             var link  = document.createElement('a');
		//                             link.href= text_link.substring(1,text_link.length);
		//                             link.appendChild(document.createTextNode(text_link));
		//                             div.appendChild(link);
		//                             text_link = '';
		//                         }
		//                         text += _data[j];
		//                     }
		//                 } else {
		//                     if( regExp.test(_data[j]) ) {
		//                         if(text !="") {
		//                             div.appendChild(document.createTextNode(text.substring(0,text.length -1)));
		//                             text = '';
		//                         }
		//                         if($.inArray(_data[j-1], _tagKey) != -1) text_link += _data[j-1];
		//                         text_link += _data[j];
		//                         if($.inArray(text_link[0], _tagKey_max_length['tagKey']) != -1 && text_link.length == _tagKey_max_length[text_link[0]]) {
		//                             is_tag = false;
		//                         }
		//                     // }else if( $.inArray(data[j-1], _mentions) != -1 || $.inArray(text_link[0], _mentions) != -1){
		//                     //     if(text !="") {
		//                     //         div.appendChild(document.createTextNode(text.substring(0,text.length -1)));
		//                     //         text = '';
		//                     //     }
		//                     //     if($.inArray(data[j-1], _tagKey) != -1) text_link += data[j-1];
		//                     //     text_link += data[j];
		//                     //     if($.inArray(text_link[0], _tagKey_max_length) != -1 && text_link.length == 17) {
		//                     //         is_tag = false;
		//                     //     }
		//                     } else if(($.inArray(_data[j], _tagKey) == -1 && !regExp.test(_data[j]))   || $.inArray(_data[j], _tagKey) != -1){
		//                         is_tag = false;
		//                         if(/\s+$/.test(_data[j])) {
		//                             text += _data[j];
		//                             var link  = document.createElement('a');
		//                             link.href= text_link.substring(1,text_link.length);
		//                             link.appendChild(document.createTextNode(text_link));
		//                             div.appendChild(link);
		//                             text_link = '';
		//                         }else {
		//                         	if(div.innerHTML != ''){
		// 	                            text = div.lastChild.data + text_link + _data[j];
		// 	                            div.lastChild.remove();
		//                             }
		//                         }
		//                         text_link = '';
		//                     }
		//                 }
		//             }

		//             if(text_link != ''){
		//                 var link  = document.createElement('a');
		//                 link.href= text_link.substring(1,text_link.length);
		//                 link.appendChild(document.createTextNode(text_link));
		//                 div.appendChild(link);
		//             } else if(text != ''){
		//                 div.appendChild(document.createTextNode(text));
		//             }
		//             if(div.innerHTML == ''){
		//             	if(check_browser){
		//             		div.appendChild(document.createElement('br'));
		//             	}
		//             }
		//             $(div_contenteditable).append($(div));
	 //            }
	 //        }
	 //        if(!check_browser){
	 //        	$(div_contenteditable).html( $.nl2br(extractTextWithWhitespace($(div_contenteditable).contents(),true)));
	 //        	$(':last-child', $(div_contenteditable)).remove();
	 //        	$(this).html($(div_contenteditable).html());
	 //        } else {
		//     	for(var i = 0 ; i < $(this).children().length; i ++){
		//     		var a = $($(div_contenteditable).children()[0]);
		//     		$($(this).children()[i]).replaceWith(a);
		//     	}
	 //    	}
	 //        rangy.getSelection().restoreCharacterRanges(this, selection);
	 //        selection = rangy.getSelection().saveCharacterRanges(this);
	 //        node_forcus = rangy.getSelection().focusNode;
	 //        if(node_forcus.parentNode.nodeName == "A") {
	 //            text_link = node_forcus.parentNode.childNodes[0].data;
	 //            // $(this).parent().find('.typeahead').eq(1).val(text_link.substring(1,text_link.length)).trigger("input");
	 //        }//else $(this).parent().find(".typeahead").typeahead('close');
  //       }

        fill_data($(this));
        if($(this).parent().find('.content').val() != '' || $('.status-box-content').find('.valueImg').val() != '') $('#cancel-from-status').removeClass('hide');
        else $('#cancel-from-status').addClass('hide');
        if ($(this).parent().find('input[type="submit"]').val() != '') $(this).parent().find('input[type="submit"]').removeAttr('disabled');
	});

     // setup_typeahead_tag($('.typeahead'),"{{URL::to('user/listUser')}}" );
     $('#newPost').submit(function(e) {
	   	var $btn = $('#submit-newPost').button('loading');
     	var title = $(this).find('#inputTitle').first().val();
     	var content = $(this).find('.content').first().val();
     	var caterogies = $(this).find('#inputCate').val();
     	var msg = [];
     	if(AuthName == ''){ $('#login_modal').modal(); return;}
     	if(title == '' || title == ' ') msg.push('Tiêu đề không được để trống');
     	if(content == '' || content == ' ') msg.push('Nội dung không được để trống');
     	if(content.length > max_content_post)  msg.push('Nội dung không được quá '+max_content_post+' ký tự');
     	if(title.length > max_title_post ) msg.push('Tiêu đề không được quá '+max_title_post+' ký tự');
     	if(caterogies ==  null ) msg.push('Chuyên đề không được để trống');
     	if(content.length < min_content_post)  msg.push('Nội dung không được ít hơn '+min_content_post+' ký tự');
     	if(title.length < min_title_post ) msg.push('Tiêu đề không được ít hơn '+min_title_post+' ký tự');
     	var _length = msg.length;
     	$(this).find('span.help-block').remove();
     	if(_length > 0){
     		e.preventDefault();
     		for(var i = 0 ; i < _length; i++){
     			var span_error = document.createElement('span');
     			span_error.className = 'help-block';
     			span_error.appendChild(document.createTextNode(msg[i]));
     			$(this).append(span_error);
     		}
     		$btn.button('reset') ;
     	}

     });
    $(document).on('keyup keydown','#inputTitle',function(e) {
    	var count = $(this).val().length;
    	var length_text = max_title_post - count;
    	if( length_text < 0 ) $(this).parent().find('.count_text').first().addClass('red');
    	else $(this).parent().find('.count_text').first().removeClass('red');
    	$(this).parent().find('.count_text').first().text(length_text);
    });
    $(document).on('click','.button-delete,.button-report',function(e){
    	if(AuthName == '') { $('#login_modal').modal(); return; }
    	var button = $(this);
		var id = button.parents('li').attr('data-content-id');
		var data_action = button.attr('data-action');
		var _url = url + data_action + '/' + id;
		var modal = 'confirm-report';
		var button_action = 'report';
		if(data_action == 'delete-reply' || data_action == 'delete-comment'){
			modal = 'confirm-delete';
		    button_action = 'delete';
		}
		$('#'+modal).modal({ backdrop: 'static', keyboard: false })
	        .one('click', '#'+button_action, function (e) {
				$.post(_url, function(data, status) {
		            if (data.error == "false") {
		            	if(data_action == 'delete-comment' || data_action == 'report-comment')
		            	{
		            		$('li.id-comment-'+id).remove();
		            	}
		            	button.parents('li').first().remove();
		            	$('.load-more-rep > a.load-more-reply[data-content-id="'+id+'"]').first().remove();
		            	if(data_action == 'delete-comment' || data_action == 'delete-reply') {
		            		$('.BoxContent').first().find('strong.comment').first().text(data.data.total_comment);
		            		$('.info-bottom').first().find('span.comment').first().text(data.data.total_comment);
		            		if(data_action == 'delete_reply') {
		            			$('li.comment-'+data.data.comment_id).first().find('strong.reply').first().text(data.data.total_reply);
		            		}
		            	}
		            } else {
		                message_error(data.msg);
		            }
		        });
	        });
    });
    $(document).on('click','.icons--brick,.icons-brick,.icon--like,.icon-like',function(){
		var button = $(this);
    	var parent = button.parents('.button-action-line').first();
    	var $btn1 = parent.find('.icon--like').first().button('loading') ;
    	var $btn2 = parent.find('.icon-like').first().button('loading') ;
		var $btn3 = parent.find('.icons--brick').first().button('loading') ;
    	var $btn4 = parent.find('.icons-brick').first().button('loading') ;
		if(AuthName == '') { $('#login_modal').modal(); return; }
		var id = button.parents('li').attr('data-content-id');
		if(id == undefined) id = button.attr('data-content-id');
		var data_action = button.attr('data-action');
		var _url = url + data_action + '/' + id;
		$.post(_url, function(data, status) {
            if (data.error == "false") {
            	button.parents('.PostBoxContent').first().find('.brick').first().text(data.data.total_brick);
            	button.parents('.PostBoxContent').first().find('.like').first().text(data.data.total_like);
            	button.parents('.PostBoxContent').first().find('.point').first().text(data.data.point);
            	if(data.data.brick_able){
            		$btn3.button('reset');
            		$btn4.button('reset');
            		button.parents('.PostBoxContent').first().find('.button-action-line').first().find('.icons--brick').first().removeClass('hide');
            		button.parents('.PostBoxContent').first().find('.button-action-line').first().find('.icons-brick').first().addClass('hide');

            	}else {
            		$btn4.button('reset');
            		$btn3.button('reset');
            		button.parents('.PostBoxContent').first().find('.button-action-line').first().find('.icons-brick').first().removeClass('hide');
            		button.parents('.PostBoxContent').first().find('.button-action-line').first().find('.icons--brick').first().addClass('hide');
            	}
            	if(data.data.like_able){
            		$btn1.button('reset');
            		$btn2.button('reset');
            		button.parents('.PostBoxContent').first().find('.button-action-line').first().find('.icon--like').first().removeClass('hide');
            		button.parents('.PostBoxContent').first().find('.button-action-line').first().find('.icon-like').first().addClass('hide');
            	}else {
            		$btn2.button('reset');
            		$btn1.button('reset');
            		button.parents('.PostBoxContent').first().find('.button-action-line').first().find('.icon-like').first().removeClass('hide');
            		button.parents('.PostBoxContent').first().find('.button-action-line').first().find('.icon--like').first().addClass('hide');
            	}

            } else {
                message_error(data.msg);
            }
        });
	});

	 $(document).on('click','a.brick--post,a.brick-post,a.like-post,a.like--post',function(){
		var button = $(this);
    	var parent = button.parents('ul.post-stats').first();
    	var $btn1 = button.button('') ;
		if(AuthName == '') { $('#login_modal').modal(); return; }
		var id = parent.attr('data-content-id');
		if(id == undefined) id = button.attr('data-content-id');
		var data_action = button.attr('data-action');
		var _url = url + data_action + '/' + id;
		$.post(_url, function(data, status) {
            if (data.error == "false") {
            	if(data.data.brick_able){
            		parent.find('a.brick-post').first().addClass('brick--post bold');
            		parent.find('a.brick-post').first().removeClass('brick-post');
            		parent.find('.brick-count').first().find('strong').first().text(data.data.total_brick);
	            	parent.find('.like-count').first().find('strong').first().text(data.data.total_like);
	            	parent.find('.point-count').first().find('strong').first().text(data.data.point);
            	}else {
            		parent.find('a.brick--post').first().addClass('brick-post');
            		parent.find('a.brick--post').first().removeClass('brick--post bold');
            		parent.find('.brick-count').first().find('strong').first().text(data.data.total_brick);
	            	parent.find('.like-count').first().find('strong').first().text(data.data.total_like);
	            	parent.find('.point-count').first().find('strong').first().text(data.data.point);
	           		$btn1.button('reset');
            	}
            	if(data.data.like_able){
            		parent.find('a.like-post').first().addClass('like--post bold');
            		parent.find('a.like-post').first().removeClass('like-post');
            		parent.find('.brick-count').first().find('strong').first().text(data.data.total_brick);
	            	parent.find('.like-count').first().find('strong').first().text(data.data.total_like);
	            	parent.find('.point-count').first().find('strong').first().text(data.data.point);

            	}else {
            		parent.find('a.like--post').first().addClass('like-post');
            		parent.find('a.like--post').first().removeClass('like--post bold');
            		parent.find('.brick-count').first().find('strong').first().text(data.data.total_brick);
	            	parent.find('.like-count').first().find('strong').first().text(data.data.total_like);
	            	parent.find('.point-count').first().find('strong').first().text(data.data.point);
            	}
            	$btn1.button('reset');

            } else {
                message_error(data.msg);
            }

        });
	});

	$(document).on('submit','#comment_post',function(e){
		e.preventDefault();
		if(AuthName == '') {  $('#login_modal').modal(); return; }
		var form =  $(this);
		var $btn = form.find('#submit-newComment').button('loading');
		var content = form.find('.content').first().val();
		var msg =[], _url = form.attr('action');
		if(content == '' || content == ' ') msg.push('Nội dung không được để trống');
		if(content.length > max_content_post)  msg.push('Nội dung không được quá '+max_content_post+' ký tự');

		var _length = msg.length;
     	$(this).find('span.help-block').remove();
     	if(_length > 0){
     		for(var i = 0 ; i < _length; i++){
     			var span_error = document.createElement('span');
     			span_error.className = 'help-block';
     			span_error.appendChild(document.createTextNode(msg[i]));
     			form.append(span_error);
	            $btn.button('reset') ;
     		}
     	}else {
     		$.ajax({
	            type: "POST",
	            url: _url,
	            data: { content:content }
	        }).done(function( data ) {
	            if(data.error == 'false'){
	                form.find('.content').first().val("");
	                if(check_browser){
	                	form.find('#div_content').first().html('<div>Click me !</div>');
	                } else {
	                	if(isIE){
							var div_content = form.find('#div_content').first();
							div_content.html('') ;
						    var oTextNode = document.createTextNode("Click me !");
						    div_content.append(oTextNode);
						    div_content.append(document.createElement('br'));
					    }else {
							form.find('#div_content').first().html('Click vào đây để viết status<br>');
					    }
	                }
	                form.find('#div_content').first().attr('contenteditable', 'false');
    				form.find('#div_content').first().attr('data-content', 'false');
    				form.find('.count_text').first().text('500');
    				$('.post-stats').find('.comment').text(data.data.total_comment);
    				$('.info-bottom').find('.comment').text(data.data.total_comment);
    				var ul = document.createElement('ul');
    				ul.className = 'stream-reply';
    				ul.setAttribute('data-id-comment',data.data.comment_id);
    				var item = new_Comment(data.data);
    				if($('.ident-com-0').length > 0){
    					$( ".ident-com-0" ).first().before( item );

    				}
    				else {
    					$('ul.item-stream').first().children(":first").before(item);
    				}
    				$( item ).after( ul );
    				lazy_load($(item).find('img'));

	            }else {
	            	form.find('span.help-block').remove();
	            	var span_error = document.createElement('span');
	     			span_error.className = 'help-block';
	     			span_error.appendChild(document.createTextNode(data.msg));
	     			form.append(span_error);
	            }
	            $btn.button('reset') ;
	        });

     	}
	});

	$(document).on('click','.cancel-cmm',function(e) {
		e.preventDefault();
		$(this).parents('li').first().remove();
	});

	$(document).on('click','a.emo-cmm',function(e){
		var button = $(this);
		var parents = button.parents('form').first().find('.f-content').first();
		var fillter = button.parents('form').first().find('div.submit-emo').first().hasClass('hide');
		$('div.submit-emo').addClass('hide');
		$('div.submit-emo-reply').addClass('hide');
		load_emoticons(parents,emoticons)
    	if (fillter) {
    		button.parents('form').first().find('div.submit-emo').first().removeClass('hide');
		}else{
    		button.parents('form').first().find('div.submit-emo').first().addClass('hide');
		};
		lazy_load(parents.find('img'));
    });
    $(document).on('click','a.emo-rep',function(e){
		var button = $(this);
		var fillter = button.parents('form').first().find('div.submit-emo-reply').first().hasClass('hide');
		$('div.submit-emo').addClass('hide');
		$('div.submit-emo-reply').addClass('hide');
		var parents = button.parents('form').first().find('.f-content').first();
		load_emoticons(parents,emoticons)
    	if (fillter) {
    		button.parents('form').first().find('div.submit-emo-reply').first().removeClass('hide');
		}else{
    		button.parents('form').first().find('div.submit-emo-reply').first().addClass('hide');
		};
		lazy_load(parents.find('img'));
    });
    $(document).on('click','.group-emo',function(e) {
	   e.preventDefault();
	   var button = $(this);
	   var parents = button.parents('form').first();
	   parents.find('.group-emo').removeClass('selected');
	   $(this).addClass('selected');
	   $('.f-content').html('');
	   var group = $(this).attr('data-group');
	   if(group == 'Hot'){
		   	var parent = parents.find('.f-content').first();
			load_emoticons(parent,emoticons);
			lazy_load(parent.find('img'));
			return;
	   }else {
		   $.ajax({
	            type: "POST",
	            url: url + 'admin/loadEmoticon',
	            data: { group:group }
	        }).done(function( data ) {
	            if(data.error == 'false'){
	            	var parent = parents.find('.f-content').first();
					load_emoticons_ajax(parent,data.data);
					lazy_load(parent.find('img'));
	            }else {
	            	message_error(data.msg);
	            }
	        });
        }
	});
	$(document).on('click','.f-content img',function(e){
		var img = $(this);
		var _char = img.attr('title');
		var parents = img.parents('form').first();
		var contentEditable = parents.find('div.contenteditable').first();
        var id = contentEditable.attr('id');
        if(contentEditable.length != 0 ) {
			if(contentEditable.attr('data-content') == 'false'){
	        	contentEditable.attr('contenteditable', 'true');
	        	contentEditable.attr('data-content', 'true');
		        	if(check_browser) {
			            title_contenteditable = contentEditable.children()[0].innerHTML;
			            contentEditable.children()[0].innerHTML = _char;
			            linkSelection = {
			                start: _char.length,
			                end: _char.length
			            };
			            doRestore(contentEditable.children()[0], linkSelection);
		            }else {
		            	title_contenteditable = contentEditable.contents()[0].data;
		            	contentEditable.contents()[0].data = _char;
		           		contentEditable.focus();
		            }

	            linkSelection = {
	                start: _char.length,
	                end: _char.length
	            };
	            doRestore(contentEditable.children()[0], linkSelection);
	        }else {
	        	insertTextAtCursor(_char,contentEditable);
	        }
	        fill_data(contentEditable)
        }

    });
	$(document).on('submit','#reply_comment',function(e){
		e.preventDefault();
		var form =  $(this);
		var $btn = form.find('.submit-newReply').first().button('loading');
		var content = form.find('.content').first().val();
		var msg =[], _url = form.attr('action');
		if(content == '' || content == ' ') msg.push('Nội dung không được để trống');
		if(content.length > 500)  msg.push('Nội dung không được quá 500 ký tự');
		if(AuthName == '') { $('#login_modal').modal(); return; }
		var _length = msg.length;
     	$(this).find('span.help-block').remove();
     	if(_length > 0){
     		for(var i = 0 ; i < _length; i++){
     			var span_error = document.createElement('span');
     			span_error.className = 'help-block';
     			span_error.appendChild(document.createTextNode(msg[i]));
     			form.append(span_error);
     		}
     	}else {
	        var parent_li = $(this).parents('li');
	        var parent = $(this).parent();
	        var parent_ul = $(this).parents('ul.stream-reply').first();
	        var ir = $(this).attr('data-ir');
     		$.ajax({
	            type: "POST",
	            url: _url,
	            data: { content:content }
	        }).done(function( data ) {
	            if(data.error == 'false'){
    				//$('.comment-'+ir).find('.reply').text(data.data.total_comment);
    				$('.stream').find('ul.post-stats').first().find('.comment').text(data.data.total_comment_post);
    				var length_rep = $('li.id-comment-' + data.data.comment_id).length;
						if(length_rep > 0 ){
							var is_add_reply = false;
							for(var j = 0 ; j < length_rep; j++){

								var ir = $($('li.id-comment-' + data.data.comment_id)[j]).attr('data-ir');
								if(ir == data.data.ir) {
									is_add_reply = true;
									break;
								}else if(ir > data.data.ir){
									var item = new_Reply(data.data);
									$($('li.id-comment-' + data.data.comment_id)[j]).before( item );
									lazy_load($(item).find('img'));
									is_add_reply = true;
									break;
								}
							}
							if(!is_add_reply){
								var item = new_Reply(data.data);
								$('ul.stream-reply[data-id-comment="'+data.data.comment_id+'"]').append( item );
								lazy_load($(item).find('img'));
							}
						} else{
							var item = new_Reply(data.data);
							$('ul.stream-reply[data-id-comment="'+data.data.comment_id+'"]').append( item );
							lazy_load($(item).find('img'));
						}
	                parent_li.remove();
	                if( parent_ul.find('.loadmore-next-reply').first().attr('data-id-min-new') == '0' ){
	                	parent_ul.find('.loadmore-next-reply').first().attr('data-id-min-new',data.data.ir);
	                }
	            }else {
	            	form.find('span.help-block').remove();
	            	var span_error = document.createElement('span');
	     			span_error.className = 'help-block';
	     			span_error.appendChild(document.createTextNode(data.msg));
	     			form.append(span_error);
	            }
	        });

     	}
	});

	$(document).on('click','.load-more-comment',function(e){
		e.preventDefault();
		var ir = $(this).attr('data-ir');
		var parent = $(this).parent().first();
		var _url = url + 'loadMoreCommentFresh/'+ir;
		var count = $('.ident-com-0').length;
		$.ajax({
            type: "POST",
            url: _url,
            data: { count:count }
        }).done(function( data ) {
            if(data.error == 'false'){
				// $('.comment-'+ir).find('.reply').text(data.data.total_comment);
				// $('.stream').find('ul.post-stats').first().find('.comment').text(data.data.total_comment_post);
				var _length = data.data.length;
				if(_length > 0 ){
					for( var i = 0 ; i < _length ; i ++ ){
						var item = load_more_Comment(data.data[i]);
						$(parent).before( item );
						lazy_load($(item).find('img'));
						if(data.data[i].count_replys.count > $('.id-comment-'+ data.data[i].id).length && data.data[i].count_replys.count > max_default_reply ){

	                        var load_more_rep = document.createElement('div');
	                        load_more_rep.className = 'load-more-rep';
	                            var a = document.createElement('a');
	                            a.href = 'javascript:void(0)';
	                            a.className="load-more-reply";
	                            a.setAttribute('data-content-id',data.data[i].id);
	                            var count = 0;
	                            if($('.id-comment-'+ data.data[i].id).length == 0){
	                                count = data.data[i].count_replys.count - data.data[i].replys.length;
	                            } else {
	                                count = data.data[i].count_replys.count - $('.id-comment-'+ data.data[i].id).length  - data.data[i].replys.length;
	                            }
	                            a.appendChild(document.createTextNode('Xem thêm '+ count +' trả lời.'));
	                        load_more_rep.appendChild(a);
	                        array.push( load_more_rep );
	                    }
						var ul = document.createElement('ul');
						ul.className = 'stream-reply';
						ul.setAttribute('data-ir',data.data[i].id);
						var _length_reply = data.data[i].replys.length;
						for( var j = 0 ; j < _length_reply ; j ++ ){
							var item = load_more_Reply(data.data[i].replys[j]);
							ul.appendChild( item );
							lazy_load($(item).find('img'));
						}
						$(parent).before(ul);

						if(data.count.count >= $('.ident-com-0').length ) parent.addClass('hide');
					}
					parent.attr('data-load','true');
				}else {
					parent.addClass('hide');
				}

            }else {
            	message_error(data.msg);
            }
        });
	});
	$(document).on('click','.load-more-reply',function(e) {
		e.preventDefault();
		var _this = $(this);
		var comment_id = _this.attr('data-content-id');
		var parent = _this.parent().first();
		var _url = url + 'loadMoreReply/'+comment_id;
		var count = $('.stream-reply .id-comment-'+comment_id).length;
		$.ajax({
            type: "POST",
            url: _url,
            data: { count_reply:count }
        }).done(function( data ) {
            if(data.error == 'false'){
				$('.comment-'+comment_id).find('.reply').text(data.data.total_comment);
				$('.stream').find('ul.post-stats').first().find('.comment').text(data.data.total_comment_post);
				var _length = data.data.length;
				if(_length > 0 ){
					var count_reply = data.count.count - $('ul.stream-reply li.id-comment-' + data.data[0].comment_id).length - _length;
					_this.text('Xem thêm '+count_reply+' trả lời.');
					if(count_reply == 0) parent.remove();
					for( var i = 0 ; i < _length ; i ++ ){
						var length_rep = $('ul.stream-reply li.id-comment-' + data.data[i].comment_id).length;
						if(length_rep > 0 ){
							var is_add_reply = true;
							for(var j = 0 ; j < length_rep; j++){
								var comment_id_new = parseInt( $($('ul.stream-reply li.id-comment-' + data.data[i].comment_id)[j]).attr('data-content-id'));
								if(comment_id_new == parseInt(data.data[i].id)) {
									is_add_reply = false;
									break;
								}else if(comment_id_new > parseInt(data.data[i].id)){
									var item = load_more_Reply(data.data[i]);
									$($('ul.stream-reply li.id-comment-' + data.data[i].comment_id)[j]).before( item );
									lazy_load($(item).find('img'));
									is_add_reply = false;
									break;
								}
							}
							if(is_add_reply){
								var item = load_more_Reply(data.data[i]);
								parent.before( item );
								lazy_load($(item).find('img'));
							}
						} else{
							var item = load_more_Reply(data.data[i]);
							$('ul.stream-reply[data-id-comment="'+data.data[i].comment_id+'"]').append( item );
							lazy_load($(item).find('img'));
						}
					}

					if(data.count.count <= $('ul.stream-reply li.id-comment-'+ data.data.id).length) parent.remove();
				}else {
					parent.remove();
				}

            }else {
            	message_error(data.msg);
            }
        });

	});

	$(document).on('click','.comment-post',function(e){
		if($('#div_content').attr('data-content') == 'false'){
        	$('#div_content').attr('contenteditable', 'true');
        	$('#div_content').attr('data-content', 'true');
        	if(check_browser) {
	            title_contenteditable = $('#div_content').children()[0].innerHTML;
	            $('#div_content').children()[0].innerHTML = "<br>";
	            linkSelection = {
	                start: 0,
	                end: 0
	            };
	            doRestore($('#div_content').children()[0], linkSelection);
            }else {
            	title_contenteditable = $('#div_content').contents()[0].data;
            	$('#div_content').contents()[0].data = '';
           		$('#div_content').focus();
            }

        }
		$('#div_content').focus();
		$('#comment_post').goTo(-400);
	});

	$(document).on('click','.load-more-post-category',function(e){
		if( !loadMore ){
			load_more_post_on_category();
		}
	});
	$(document).on('click','.load-more-post-fresh',function(e){
		e.preventDefault();
		load_more_post();
	});

	$(document).on('click','.load-more-post-hot',function(e){
		e.preventDefault();
		load_more_post_hot();
	});

	$(document).on('click','.load-more-post',function(e){
		e.preventDefault();
		load_more_post();
	});
	$(document).on('click','#share-fb',function(e){
		var width = $(this).attr('data-width');
		var height = $(this).attr('data-height');
		var share = $(this).attr('data-share');
		var ir = $(this).attr('data-ir');

		var myWindow = window.open("http://www.facebook.com/sharer/sharer.php?u="+ share+ '/' +ir , "", "width="+ width +", height="+height+"");
	});

	$(document).on('click','.report,.delete',function(e){
	    var button = $(this);
	    var action = 'report';
		if(AuthName == '') { $('#login_modal').modal(); return; }
	    if($(this).hasClass('delete')) action = 'delete';
		$('#confirm-' + action).modal()
	        .one('click', '#'+action, function (e) {
				var id = button.attr('data-post-id');
				var data_action = button.attr('data-action');
				var _url = url + data_action + '/' + id;
				$.post(_url, function(data, status) {
					if(data.error == 'true') {
						message_error(data.msg);
					}else {
						if(button.prop("tagName") != 'I'){
							window.location = url;
						}else {
							button.parents('div.left-stream-box').first().remove();
						}
					}
				});
	        });
	});

	$(document).on('click','.fresh-nav,.hot-nav',function(){
		var button = $(this);
		var action = button.attr('data-action');
		var post_id = button.attr('data-post-id');
		var _url = url + action + '/' + post_id;
		if($(this).hasClass('hot-fresh-active')) return;

		$('.ident-com-1,.ident-com-0').remove();
		$('.hot-fresh-active').removeClass('hot-fresh-active');
		$('.load-more-rep').remove();
		button.addClass('hot-fresh-active');
		var parent = $($('ul.item-stream').children()[0]);
		load_data_comment (_url,post_id,parent) ;
	});

	$(document).on('shown.bs.dropdown','#dd-notifi', function() {
		var page_notifi = $('.row-notifi').length;
		$('.count-inner').text('0');
		loadNewNotification(page_notifi);
    });

    $(window).scroll(function() {
	   if($(window).scrollTop() + $(window).height() >= $('.left-stream-wrap').first().height() ) {
	   	if(loadMore){
	   		loadMore = false;
	   		if(page_index  != undefined ){
	   			switch (page_index) {
					case 'show-cate':
						load_more_post_on_category();
						break;
					case 'trend':
						load_more_post();
						break;
					case 'post-fresh':
						load_more_post();
						break;
					case 'post-hot':
						load_more_post_hot();
						break;
					case 'hashtag':
						load_more_post();
						break;
					case 'profile':
						load_post_of_user();
						break;
				}
	   		}

	   	}
	   }
	});
	function load(){
		var width = $(window).width();
		if(width < 939) {
		  $('.container-media').removeClass('container-media');
		  $('.left-stream-media').removeClass('left-stream-media');
		  $('.right-stream-media').removeClass('right-stream-media');
		}
	}
	load();
	$(document).on('click','.new-post-bar',function(){
		window.location.reload();
	});
	// $('.scroll-stream').scrollbar();



	$(document).on('click','.button-reply',function(e){
		e.preventDefault();
		var parent = $(this).parents('li');
		var username = parent.attr('data-user');
		var content_id = 0,comment_id = 0;
		if(parent.hasClass('ident-com-0')){
			content_id = parent.attr('data-content-id');
			comment_id = parent.attr('data-content-id');
		}
		else {
			content_id = parent.attr('data-content-id');
			comment_id = parent.attr('data-comment-id');
		}
		var ul = $(this).parents('ul.item-stream');
		var title = $(this).attr('data-original-title');
		var li ;
		if($('.id-comment-'+content_id).length > 0 ) {
			if( $( 'li.box-reply-'+content_id ).length > 0 ){
				li = $( 'li.box-reply-'+content_id ).last();
			} else li = $('.id-comment-'+content_id).last();
		}
		else li = $(this).parents('li');
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
			create_box_reply(content_id,title,username,comment_id,parent);
		} else {

			var textarea = box_comment.find('.content').first().val();
			linkSelection = {
                start: textarea.length,
                end: textarea.length
            };
	        doRestore(box_comment.find('.contenteditable').first().children().last()[0], linkSelection);
			box_comment.find('.contenteditable').first().focus();
			box_comment.find('.contenteditable').first().goTo(-400);
		}
		$('body').on('click',event_click_content);
	});

	$(document).on('click','.ajax-trend-post',function(e) {
		e.preventDefault();
		ajax_trend_post($(this));
	});

	$(document).on('click','.change-favor-active',function(e) {
		e.preventDefault();
		$('.favor-active').removeClass('favor-active');
		$(this).addClass('favor-active');
		change_favor_active($(this));
	});

	$(document).on('click','a.favor-cate',function(e){
		e.preventDefault();
		if(AuthName == '' || AuthName == ' '){
			$('#login_modal').modal();
		}else favorite_cate($(this));
	});

	$(document).on('click','a.favor-hashtag',function(e){
		e.preventDefault();
		if(AuthName == '' || AuthName == ' '){
			$('#login_modal').modal();
		}else  favorite_hashtag($(this));
	});

	$(document).on('click','a.timeline',function(e){
		e.preventDefault();
		$('.tlink-active').removeClass('tlink-active');
		$(this).addClass('tlink-active');
		if(AuthName == '' || AuthName == ' '){
			$('#login_modal').modal();
		}else{
			$('ul.left-stream').remove();
			if($('div.left-stream-wrap div.left-stream-box:not(.wrap-loading-post)').length == 0){
				load_post_of_user();
			}
		}
	});

	$(document).on('click','a.profile',function(e){
		e.preventDefault();
		$('.tlink-active').removeClass('tlink-active');
		$(this).addClass('tlink-active');
		if(AuthName == '' || AuthName == ' '){
			$('#login_modal').modal();
		}else{
			if($('ul.left-stream').length == 0){
				$('div.left-stream-wrap div.left-stream-box:not(.wrap-loading-post)').remove();
			  	load_profile_of_user();
		  	}
		}
	});

	$(document).on('click','a.posts-related-user',function(e){
		e.preventDefault();
		$('.box-header-farvorite').remove();
		if(AuthName == '' || AuthName == ' '){
			$('#login_modal').modal();
		}else if(url_loadmore != 'post_related_users'){
			$('.nav-left-selected').removeClass('nav-left-selected');
			$(this).addClass('nav-left-selected');
			$('.loading-post').removeClass('hide');
			$('#media-max-min div.left-stream-box:not(.wrap-loading-post)').remove();
			url_loadmore = 'post_related_users';
			load_post_of_user();
		}
	});

	$(document).on('click','a.posts-of-user',function(e){
		e.preventDefault();
		$('.box-header-farvorite').remove();
		if(AuthName == '' || AuthName == ' '){
			$('#login_modal').modal();
		}else if(url_loadmore != 'post_of_users'){
			$('.nav-left-selected').removeClass('nav-left-selected');
			$(this).addClass('nav-left-selected');
			$('.loading-post').removeClass('hide');
			$('#media-max-min div.left-stream-box:not(.wrap-loading-post)').remove();
			url_loadmore = 'post_of_users';
			load_post_of_user();
		}
	});

	$(document).on('click','a.posts-fresh',function(e){
		e.preventDefault();
		$('.box-header-farvorite').remove();
		if(url_loadmore != 'loadMorePostFresh'){
			$('.nav-left-selected').removeClass('nav-left-selected');
			$(this).addClass('nav-left-selected');
			$('.loading-post').removeClass('hide');
			$('#media-max-min div.left-stream-box:not(.wrap-loading-post)').remove();
			url_loadmore = 'loadMorePostFresh';
			load_more_post();
		}
	});

	$(document).on('click','a.link-favor-hashtag',function(e){
		e.preventDefault();
		if(AuthName == '' || AuthName == ' '){
			$('#login_modal').modal();
		}else{
			$('.left-stream-media div.left-stream-box:not(.wrap-loading-post)').remove();
			$('.nav-left-selected').removeClass('nav-left-selected');
			$(this).addClass('nav-left-selected');
			load_favor('loadFavorHashtag');
		}
	});

	$(document).on('click','a.link-favor-cate',function(e){
		e.preventDefault();
		if(AuthName == '' || AuthName == ' '){
			$('#login_modal').modal();
		}else{
			$('.left-stream-media div.left-stream-box:not(.wrap-loading-post)').remove();
			$('.nav-left-selected').removeClass('nav-left-selected');
			$(this).addClass('nav-left-selected');
			load_favor('loadFavorCate');
		}
	});
	$(document).on('click','a.up-level',function(e){
		e.preventDefault();
		$.ajax({
        type: "POST",
        url: 'level_up_user'
	    }).done(function( data ) {
	        if(data.error == 'false'){
	        	$(this).remove();
	        }else {
	           message_error(data.msg);
	        }
	    });
	});

	$(document).on('click','div.mod-rate .start-rate',function(){
		var Elm = $(this);
		var _url = url + Elm.parents('.mod-rate').attr('data-type');
		var id = Elm.parents('.mod-rate').attr('data-content-id');
		var point = Elm.val();
		$.ajax({
	        type: "POST",
	        url: _url,
	        data: {id:id,point:point}
		    }).done(function( data ) {
		        if(data.error == 'false'){
		        	var parent = Elm.parents('div.mod-rate').first();
		        	parent.find('input:checked').first().prop('checked', false);
		        	var persent_rate = (data.data.rate_point / 5) * 100;
		        	var star = Math.floor(data.data.rate_point);
			        parent.find('input[value='+star+']').first().prop('checked', true);
			        parent.find('input[value='+star+'] + i:not(.delete-report-ico-tl)').first().css('width',persent_rate+'%');
			        parent.parent().first().find('.count-mod-rate').first().text((Math.round(data.data.rate_point * 1000)/1000)+' on '+ data.data.count_rate);
		        }else {
		           message_error(data.msg);
		        }
	    });
	});
	var element_block = null;
	$(document).on('click','.mod-block',function(){
		if(typeof auth_permission === 'undefined') return;
		var button = $(this);
		var content_id = button.attr('data-content-id');
		var action = button.attr('data-action');
		$('#block_Content').attr('data-content-id',content_id);
		$('#block_Content').attr('action',url+action);
		$('#confirm-block').modal();
		switch(action){
			case 'block-post':
				if(button.parents('.left-stream-box').length > 0){
					element_block = button.parents('.left-stream-box').first();
				}else {element_block = 'reload';}
			break;
			case 'block-comment':
			case 'block-reply':
				element_block = button.parents('li').first();
			break;
		}
	});

	$(document).on('submit','#block_Content',function(e){
		e.preventDefault();
		var _url = $(this).attr('action');
		var id = $(this).attr('data-content-id');
		var reason = $('#reason').val();

		$.ajax({
	        type: "POST",
	        url: _url,
	        data: {id:id,reason:reason}
		    }).done(function( data ) {
		        if(data.error == 'false'){
		        	$('#reason').val('');
		        	$('#block_Content').attr('data-content-id','');
					$('#block_Content').attr('action','');
					$('#confirm-block').modal('hide');
					$('.help-block').remove();
					if(element_block != 'reload'){
						element_block.remove();
					}else {
						window.location.reload();
					}
					if(_url == url+'block-comment'){
						$('.stream-reply[data-id-comment='+id+']').remove();
					}
		        }else {
		        	var span_error = document.createElement('span');
	     			span_error.className = 'help-block';
	     			span_error.appendChild(document.createTextNode(data.msg));
		        	$('#block_Content').append(span_error);
		        }
	    });
	});
	$(document).on('click','#confirm-block>.modal-body',function(e) {
		if(e.target.nodeName == 'DIV' && $(e.target).parents('.modal-body').length == 0){
			$('#confirm-block').modal('hide');
		}
	});

	$(document).on('click','.media-thumb,.media-zoom',function(e) {
		if($(this).hasClass('media-thumb')){
			$(this).addClass('media-zoom');
			$(this).removeClass('media-thumb');
		}else {
			$(this).addClass('media-thumb');
			$(this).removeClass('media-zoom');
		}
		var src = $(this).attr('src');
		var data_src = $(this).attr('data-src');
		$(this).attr('data-src',src);
		$(this).attr('data-original',data_src);
        lazy_load($(this));
	});

	$(document).on('click','li.A_media',function(e){
		var parent = $(this);
		$(this).removeClass('A_video');
		var div_video_thumb = parent.find('.video-thumb').first();
		var div_player = parent.find('.player-youtube').first();
		var tagname_player = div_player.prop("tagName");
		div_player.removeClass('hide');
		div_player.addClass('video-play');
		div_video_thumb.addClass('hide');
		if(tagname_player == 'DIV'){
			var id = div_player.attr('id');
			var video_id = div_video_thumb.attr('data-media-id');
			var width = '800';
			var height = '400';
		    onYouTubeIframeAPIReady(id,video_id,width,height);
	    }
		// var i_frame = document.createElement('iframe');
		// i_frame.className = "video-play";
		// i_frame.width = 800;
		// i_frame.height = 400;
		// i_frame.setAttribute('src','"https://www.youtube.com/embed/'+$(this).attr('data-media-id')+'?feature=oembed&autoplay=1&wmode=opaque&rel=0&showinfo=0&modestbranding=0');
		// i_frame.setAttribute('frameborder',0);
		// i_frame.setAttribute('allowfullscreen','');
			// parent.append(i_frame);
			// $(this).addClass('hide');
	});
});

// window.onerror = function(msg, url, linenumber) {
//     alert('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
//     return true;
// }

$(window).resize(function(){
   if($(this).width() < 939){
      $('.container-media').removeClass('container-media');
      $('.left-stream-media').removeClass('left-stream-media');
      $('.right-stream-media').removeClass('right-stream-media');
   }
});

$( window ).scroll(function() {
	var position = $(this).scrollTop();
	if($('#naviLeft').first().position() != undefined){
		var min_position_col_left = $('#naviLeft').first().position().top -46;
		var width_left = $('#naviLeft div.fixed').first().width();
		// left
		if(position >= min_position_col_left && $('#naviLeft div.fixed').first().attr('style') == undefined){
			$('#naviLeft div.fixed').first().attr('style','position:fixed; top: 53px;width: '+width_left+'px;');
		}else if(position < min_position_col_left) {
			$('#naviLeft div.fixed').first().removeAttr('style');
		}
	}
  // right
	  var width_right = $('.display-item-col div.fixed').first().width();
	  var height_right = $('.display-item-col').first().height();
	  var min_position_col_right = height_right + 230 ;

	  if(position >= min_position_col_right && $('.display-item-col div.fixed').first().attr('style') == undefined) {
	  	$('.display-item-col div.fixed').first().attr('style','position:fixed; top: 60px;width: '+width_right+'px;')
	  }else if(position < min_position_col_right + $('.display-item-col div.fixed').first().height()){
	  	$('.display-item-col div.fixed').first().removeAttr('style');
	  }

});

$(window).resize(function(){
   if($(this).width() >= 939){
      $('.container').addClass('container-media');
      $('.left-stream-wrap').addClass('left-stream-media');
      $('.right-stream').addClass('right-stream-media');
   }
});

function fill_data(Elm) {
    var text = 1;
    var textarea = Elm.parent().find('.content').first();
    textarea.val("");
    var value = extractTextWithWhitespace(Elm.contents(),false);
    textarea.val(value.substring(0,value.length-1));
    var length_text = 0;
    ( Elm.attr('id') == 'port_content' ) ? length_text = max_content_post - textarea.val().length : length_text = 500 - textarea.val().length;
    var count_text = null;
    ( Elm.attr('id') == 'port_content' ) ? count_text = Elm.parents('form').find('.count_text').last() : count_text = Elm.parents('form').find('.count_text').first();
    if( length_text < 0 ) count_text.addClass('red');
    else count_text.removeClass('red');
    count_text.text(length_text);
}

// $(document).ajaxSuccess(function( event, xhr, settings ) {
//   lazy_load();
// });