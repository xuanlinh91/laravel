

function fomat_time_Datetime(time) {
    var seconds = time.getSeconds();
    if(seconds < 10 ) seconds = '0' + seconds;
    var min = time.getMinutes();
    if(min < 10 ) min = '0' + min;
    var hour = time.getHours();
    if(hour < 10 ) hour = '0' + hour;
    var date = time.getDate();
    if(date < 10 ) date = '0' + date;
    var month = time.getMonth() + 1;
    if(month < 10 ) month = '0' + month;
    var year = time.getFullYear();

    return hour + ':' + min + ':' + seconds + ' ' + date + '/' + month + '/' + year ;

}

function get_current_time () {
    var date = new Date();
    date = date.getTime();
    var time_item = $('.current-time');
    var length = time_item.length;

    time_item.each(function(i, obj) {
        var time_ago = parseInt( $(obj).attr('data-time') ) * 1000;
        var time = date - time_ago;
        time = new Date(time);
        $(obj).text( fomat_time_Datetime(time) );
    });
}
function playSound(filename){
    var a = document.getElementById("audio_sound");
    //if( !!(a.canPlayType && a.canPlayType('audio/mpeg;').replace(/no/, ''))){
        a.src= filename + '.mp3';
    // }else if(!!(a.canPlayType && a.canPlayType('audio/ogg; codecs="vorbis"').replace(/no/, ''))){
    //     a.src= filename + '.ogg';
    // }else if(!!(a.canPlayType && a.canPlayType('audio/wav; codecs="1"').replace(/no/, ''))){
    //     a.src= filename + '.wav';
    // }else if(!!(a.canPlayType && a.canPlayType('audio/mp4; codecs="mp4a.40.2"').replace(/no/, ''))){
    //     a.src= filename + '.mp4';
    // }else {
    //     a.src = filename+'.aac';
    // }
    a.play();
}

function show_notification(id, img, notifi, ur,nickname,notifi_type,content,post_id) {
    var item = popup_notification(id, img, notifi, ur,nickname,notifi_type,content,post_id);
    if($(".box-notifi").length > 0 ) $(".box-notifi").first().before(item);
    else $(".new-notification").append(item);
    $(item).fadeIn(750, function() {
        setTimeout(function() {
            $(item).fadeOut(750, function() {
               $(item).remove();
            });
            $(item).hover(function() {
                $(item).stop().animate({
                    opacity: 1
                }, 750);
            }, function() {
                setTimeout(function() {
                    $(item).fadeOut(750, function() {
                        $(item).remove();
                    });
                },2000);
            });
        }, 9000);
    });
}

/*function load data notification */
function loadMoreNotification(page_notifi) {
    var ul = $('#dd-notifi').find('.dd-notification').first();
    var check = $('#dd-notifi').attr('data-load');
    console.log(check);
    if(check == undefined || check == 'true' ){
        $.get(url + 'getNotification', {
            count: page_notifi
        }).done(function(data) {
            console.log(data);
            // $('.loading').addClass('hide');
            if (data.error == 'false') {
                console.log(data.data.length);
                if(data.data.length == 0 ){
                    console.log($('.loadding-notifi'));
                    $('.loadding-notifi').first().addClass('hide');
                    $('#dd-notifi').attr('data-load','false')
                }
                $.each(data.data, function(index, value) {
                    console.log(value.notifi_type);
                    var li = create_element_notifi(value);
                    ul.find('.dum').first().before(li);
                });
            }
        });
    }
}
function loadNewNotification (page_notifi) {
    var ul = $('#dd-notifi').find('.dd-notification').first();
    var check = $('#dd-notifi').attr('data-load');
        $.get(url + 'getNewNotification', {
            count: page_notifi
        }).done(function(data) {
            // $('.loading').addClass('hide');
            if (data.error == 'false') {
                $.each(data.data, function(index, value) {
                    var li = create_element_notifi(value);
                    if(page_notifi == 0){
                        ul.find('.dum').first().before(li);
                    }else{
                        console.log($('.row-notifi').first());
                        $('.row-notifi').first().before(li);
                    }
                });
            }
        });
}

function load_more_post_on_category(){
    var count_item = $('.simple-post:not(.new-post-bar)').length;
    var element_loading = $('.loading-post').first();
    var element_load_more = $('.read-more').first();
    var _url = '';
    if(element_loading.hasClass('hide')) element_loading.removeClass('hide');
    if($('.box-item-trend .active').length != 0) _url = $('.box-item-trend .active').first().attr('href');
    else _url = url+url_loadmore;
    $.ajax({
        type: "POST",
        url: _url,
        data: { count:count_item }
    }).done(function( data ) {
        if(data.error == 'false'){
            var length_data = data.data.length;
            if(length_data > 0 ){
                for (var i = 0; i <=  length_data - 1 ; i++) {
                    var hot = count_item + i ;
                    var item = loadMorePost(data.data[i],hot);
                    var post_content = $(item).find('div.post-content-fresh-hot').first();
                    var ava_row = $(item).find('div.avatar-row').first();
                    element_loading.before(item);
                    lazy_load(post_content.find('img'));
                    lazy_load(ava_row.find('img'));
                };
                count_load = count_load + 1;
                var a = count_item + length_data ;
                if( a%60 == 0 ){
                    element_load_more.removeClass('hide');
                    element_loading.addClass('hide');
                }else {
                    element_load_more.addClass('hide');
                    loadMore = true;
                }
                $('.no-post').remove();
            }else {
                loadMore = false ;
                element_load_more.addClass('hide');
                element_loading.addClass('hide');
                 if($('.left-stream-box:not(.wrap-loading-post)').length == 0){
                    $('.no-post').removeClass('hide');
                }
            }
        }else {
            var r = confirm("Gặp sự cố trong phiên làm việc vui lòng reload trang!");
            if (r == true) {
                window.location.reload();
            } else {
                console.log('load_more_post_on_category');
                console.log(data);
            }
        }
        element_loading.addClass('hide');
    });
}

function load_more_post_hot() {
    var count_item = $('div.left-stream-box:not(.wrap-loading-post)').length;
    var element_loading = $('.loading-post').first();
    var element_load_more = $('.read-more').first();
    $.ajax({
        type: "POST",
        url: url+url_loadmore,
        data: { count:count_item }
    }).done(function( data ) {
        if(data.error == 'false'){
            var length_data = data.data.length;
            if(length_data > 0 ){
                for (var i = 0; i <=  length_data - 1 ; i++) {
                    var item_hot = count_item + i +1;
                    var item = loadMorePostHot(data.data[i],item_hot);
                    var post_content = $(item).find('div.post-content-fresh-hot').first();
                    var ava_row = $(item).find('div.avatar-row').first();
                    element_loading.before(item);
                    lazy_load(post_content.find('img'));
                    lazy_load(ava_row.find('img'));
                };
                count_load = count_load + 1;
                var a = count_item + length_data ;
                if( a%60 == 0 ){
                    element_load_more.removeClass('hide');
                    element_loading.addClass('hide');
                }else {
                    element_load_more.addClass('hide');
                    loadMore = true;
                }
            }else { loadMore = false ; element_load_more.addClass('hide');  element_loading.addClass('hide'); }

        }else {
            var r = confirm("Gặp sự cố trong phiên làm việc vui lòng reload trang!");
            if (r == true) {
                window.location.reload();
            } else {
                console.log('load_more_post_hot');
                // console.log(data);
            }
        }
    });
}


function load_more_post () {
    var count_item = $('.simple-post:not(.new-post-bar)').length;
    var element_loading = $('.loading-post').first();
    var element_load_more = $('.read-more').first();
    if(element_loading.hasClass('hide')) element_loading.removeClass('hide');
     var _url = '';
    if($('.box-item-trend .active').length != 0 && page_index == 'trend') _url = $('.box-item-trend .active').first().attr('href');
    else _url = url+url_loadmore;
    $.ajax({
        type: "POST",
        url: _url,
        data: { count:count_item }
    }).done(function( data ) {
        if(data.error == 'false'){
            var length_data = data.data.length;
            if(length_data > 0 ){
                for (var i = 0; i <=  length_data - 1 ; i++) {
                    var hot = count_item + i ;
                    console.log(data.data[i]);
                    var item = loadMorePost(data.data[i]);
                    console.log(item);
                    var post_content = $(item).find('div.post-content-fresh-hot').first();
                    var ava_row = $(item).find('div.avatar-row').first();
                    element_loading.before(item);
                    lazy_load(post_content.find('img'));
                    lazy_load(ava_row.find('img'));
                }
                count_load = count_load + 1;
                var a = count_item + length_data ;
                if( a%60 == 0 ){
                    element_load_more.removeClass('hide');
                    element_loading.addClass('hide');
                } else {
                    element_load_more.addClass('hide');
                    loadMore = true;
                }
            } else { loadMore = false ; element_load_more.addClass('hide'); element_loading.addClass('hide');}
            update_rate();
        } else {
            var r = confirm("Gặp sự cố trong phiên làm việc vui lòng reload trang!");
            if (r == true) {
                window.location.reload();
            } else {
                console.log('load_more_post');
                console.log(data);
            }
        }
        element_loading.addClass('hide');
    });
}

function callback_socket_show(data){
    switch(data.type) {
        case 'comment':
            if($('li.comment-' + data.comment_id).length > 0) return;
            var ul = document.createElement('ul');
            ul.className = 'stream-reply';
            ul.setAttribute('data-id-comment',data.comment_id);
            $($('ul.item-stream').children()[0]).before( new_Comment(data) );
            $($('ul.item-stream').children()[0]).after(ul);

            $('.post-stats').find('.comment').text(data.total_comment);
            $('.info-bottom').find('.comment').text(data.total_comment);
            break;
        case 'reply':
            if($('li.id-comment-' + data.comment_id+'[data-id-comment="'+data.ir+'"]').length > 0) return;

            $('.stream').find('ul.post-stats').first().find('.comment').text(data.total_comment_post);
            if( $('ul.stream-reply[data-id-comment="'+data.comment_id+'"]').find('li.id-comment-' + data.comment_id).length > 0 ) $('ul.stream-reply[data-id-comment="'+data.comment_id+'"]').find('li.id-comment-' + data.comment_id).last().after(new_Reply(data));
            else  $('ul.stream-reply[data-id-comment="'+data.comment_id+'"]').first().append(new_Reply(data));
            break;
        case 'point':
            var parent = null;
            switch(data.content_type){
                case '0':
                    parent = $('ul.post-stats[data-post-id="'+data.id+'"]').first();
                break;
                case '1':
                    parent = $('li.comment-'+data.id).first();
                break;
                case '2':
                    parent = $('li.ident-com-1[data-content-id="'+data.id+'"]').first();
                break;
            }

            parent.find('strong.brick').first().text(data.total_brick);
            parent.find('strong.like').first().text(data.total_like);
            parent.find('.point').first().text(data.point);
            break;
    }
}
var event_click_content = function event_click_content (e) {

    var Elm = $(e.target);
    if( Elm.hasClass('reply-comment-text') || Elm.parent().hasClass('reply-comment-text') || Elm.parent().parent().hasClass('reply-comment-text') ) {
        return;
    } else if(Elm.parent().hasClass('button-reply')) {
        Elm = Elm.parent();
        var title = Elm.attr('data-original-title');
        var ir = Elm.attr('data-id-comment');
        var id = Elm.attr('data-id');
        var box_comment = $('li.box-reply-'+ir+'[data-id="'+id+'"]');
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
        // if(is_createElement == true) {
            var content_editable = $('.reply-comment-text');
            content_editable.each(function( index,obj ) {
                var data_user = $(obj).attr('data-user');
                var value = $(obj).parent().find('textarea').first().val();
                if(data_user != undefined) {
                    // if(value == $(this).children()[0])
                    if(value.trim() == '@' + data_user || value == '' || value == " ") {
                        if($(obj).parents('li.box-reply-'+ir+'[data-id="'+id+'"]').length == 0){
                            $(obj).parents('li').first().remove();
                        }
                    }
                }
            });
        // }
    } else if (Elm.hasClass('emo-rep') || Elm.parents('a').hasClass('emo-rep') || Elm.hasClass('f-content') || Elm.parent('.f-content')) {
        return;
    } else {
        var content_editable = $('.reply-comment-text');
        content_editable.each(function( index,obj ) {
            var data_user = $(obj).attr('data-user');
            var value = $(obj).parent().find('textarea').first().val();
            if(data_user != undefined) {
                // if(value == $(this).children()[0])
                if(value.trim() == '@' + data_user || value == '' || value == " ") {
                    $(obj).parents('li').first().remove();
                }
            }
        });

        $("body").off("click");
    }
}

function ajax_trend_post(Elm) {
    var _this = Elm;
    var hashtag = _this.text();
    $('li.only-one div.only-one-top span').text(hashtag);
    if($('.box-item-trend .active').first().attr('class') == _this.attr('class')) return;
    $('div.left-stream-box:not(.wrap-loading-post)').addClass('hide');
    var element_loading = $('.loading-post').first();
    var element_load_more = $('.read-more').first();
    element_loading.removeClass('hide');
    $.ajax({
        type: "POST",
        url: _this.attr('href'),
        data: { count_post_cate:0 }
    }).done(function( data ) {
        if(data.error == 'false'){
            $('a.favor-hashtag').first().removeClass('hide');
            $('a.favor-hashtag').attr('data-name',Elm.attr('data-name'));
            $('.mod-block').first().attr('data-content-id',hashtag.substring(1,hashtag.length));
            $('.mod-block').first().removeClass('hide');

            if(data.favorite == '1' || data.favorite == 1){

                $('a.favor-hashtag .icon-star').first().addClass('favorited');
            }
            else{
                $('a.favor-hashtag .icon-star').first().removeClass('favorited');
            }
            var length_data = data.data.length;
            if(length_data > 0 ){
                $('div.left-stream-box:not(.wrap-loading-post)').remove();
                for (var i = 0; i <=  length_data - 1 ; i++) {
                    var item = loadMorePost(data.data[i]);
                    var post_content = $(item).find('div.post-content-fresh-hot').first();
                    var ava_row = $(item).find('div.avatar-row').first();
                    element_loading.before(item);
                    lazy_load(post_content.find('img'));
                    lazy_load(ava_row.find('img'));
                };
                element_loading.addClass('hide');
                element_load_more.addClass('hide');
                $('.ajax-trend-post').removeClass('active');
                _this.addClass('active');
            }else { $('div.left-stream-box').removeClass('hide');}

        }else {
            var r = confirm("Gặp sự cố trong phiên làm việc vui lòng reload trang!");
            if (r == true) {
                window.location.reload();
            } else {
                console.log('ajax_trend_post');
                // console.log(data);
            }
        }
    });
}

function update_user_online(data) {
    if(typeof data == 'string') return;
    // if(!data[0].pr){
        var length_user = $('.user-online .simple-user').length;
        var element_remove = [];
        for (var i = length_user - 1; i >= 0; i--) {
            var username = $($('.simple-user')[i]).attr('data-username');
            var check = false;
            for (var j = data.length - 1; j >= 0; j--) {
                if(username == data[j]['username']){
                    check = false;
                    data.splice(j,1);
                    break;
                }else if(username != data[j]['username']) {
                    check = true;
                }
            }
            if(check) element_remove.push(username);
        }
        for (var i = data.length - 1; i >= 0 ; i--) {
            if( data[i]['username'] != '' && $('.user-online .simple-user[data-username="'+data[i]['username']+'"]').length == 0){
                var item = create_element_user_online(data[i]);
                $('.user-online .clear').before(item);
            }
        }
    if(data[0] != undefined && data[0].pr){
        var length_user = $('.admin-online .simple-user').length;
        var element_remove = [];
        for (var i = length_user - 1; i >= 0; i--) {
            var username = $($('.simple-user')[i]).attr('data-username');
            var check = false;
            for (var j = data.length - 1; j >= 0; j--) {
                if(username == data[j]['username']){
                    check = false;
                    data.splice(j,1);
                    break;
                }else if(username != data[j]['username']) {
                    check = true;
                }
            }
            if(check) element_remove.push(username);
        }
        for (var i = data.length - 1; i >= 0 ; i--) {
            if( data[i]['username'] != '' && $('.admin-online .simple-user[data-username="'+data[i]['username']+'"]').length == 0){
                var item = create_element_admin_online(data[i]);
                $('.admin-online .clear').before(item);
            }
        }
    }
}

function update_user_offline(data) {
    if(typeof data == 'string') return;
    $('.user-online .simple-user[data-username="'+data[0]['username']+'"]').remove();
}

function load_data_comment (_url,post_id,parent) {
    $('.loading').removeClass('hide');
    $.ajax({
        type: "POST",
        url: _url,
        data: { id:post_id }
    }).done(function( data ) {
        if(data.error == 'false'){
            var array = [];
            var _length = data.data.length;
            if(_length > 0 ){
                for( var i = 0 ; i < _length ; i ++ ){
                    var comments = load_more_Comment(data.data[i]);
                    array.push( comments );
                    if(data.data[i].count_replys.count > $('.id-comment-'+ data.data[i].id).length && data.data[i].count_replys.count > max_default_reply ){

                        var load_more_rep = document.createElement('div');
                        load_more_rep.className = 'load-more-rep';
                            var a = document.createElement('a');
                            a.href = 'javascript:void(0)';
                            a.className="load-more-reply";
                            a.setAttribute('data-content-id',data.data[i].id);
                            a.setAttribute('data-max-id-load',data.data[i].max_id);
                            a.setAttribute('data-max-id-realTime',0);
                            a.setAttribute('data-min-id-realTime',0);

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
                    ul.setAttribute('data-id-comment',data.data[i].id);
                    var _length_reply = data.data[i].replys.length;

                    for( var j = 0 ; j < _length_reply ; j ++ ){
                        var item = load_more_Reply(data.data[i].replys[j]);
                        ul.appendChild( item );
                    }
                    array.push(ul);
                }
            }
            // setTimeout(function() {
            $('.loading').addClass('hide');
            for(var i = 0 ; i < array.length; i ++ ){
                if(parent.hasClass('load-more-com')){
                    parent.after( array[i] );
                } else parent.before( array[i] );
                lazy_load($(array[i]).find('img'));
            }
            // }, 550)


        }else {
            var r = confirm("Gặp sự cố trong phiên làm việc vui lòng reload trang!");
            if (r == true) {
                window.location.reload();
            } else {
                console.log('load_data_comment');
                console.log(data);
            }
        }
    });
}

function load_data_comment_notification(type_comment,type_reply,post_id,auth_id,id_content,content_type,url_next_Comment,url_Previous_Comment,url_Previous_Reply,url_next_reply) {
$('.loading').removeClass('hide');
$.ajax({
    type: "POST",
    url: url+'loadCommentNotifi',
    data: { id:post_id,user_Id:auth_id,id_content:id_content,content_type:content_type }
}).done(function( data ) {
    if(data.error == 'false'){
        var array = [];
        var _length = data.data.comments.length;

        if(_length > 0 ){
            if(data.data.total_comment > _length) {
                var div = document.createElement('div');
                div.className = 'load-more-com';
                //     var span = document.createElement('span');
                //     span.className = 'count-total-com';
                //         var _span = document.createElement('span');
                //         _span.className = 'count-comments';
                //         _span.appendChild(document.createTextNode(_length));
                //     span.appendChild(_span);
                //     span.appendChild(document.createTextNode(' trong số  '+data.data.total_comment));
                // div.appendChild(span);
                    var a = document.createElement('a');
                    a.href = 'javascript:void(0)';
                    a.className = 'loadmore-next-comment';
                    a.setAttribute('data-url',url_next_Comment);
                    a.setAttribute('data-post-id',post_id);
                    a.appendChild(document.createTextNode('Xem bình luận mới'));
                div.appendChild(a);
                array.push(div);

            }
            for( var i = 0 ; i < _length ; i ++ ){
                var a_max = 0,a_min = 0 ,b_max = 0 , b_min = 0;
                if(data.data.comments[i].replys[0] != undefined){
                    a_max = data.data.comments[i].max_id;
                    a_min = data.data.comments[i].min_id;
                    b_max = data.data.comments[i].replys[0].max_id_all;
                    b_min = data.data.comments[i].replys[0].min_id_all;
                }

                array.push( load_more_Comment(data.data.comments[i]) );


                var ul = document.createElement('ul');
                ul.className = 'stream-reply';
                ul.setAttribute('data-id-comment',data.data.comments[i].id);
                var _length_reply = data.data.comments[i].replys.length;

                if(a_min > b_min) {
                    var div = document.createElement('div');
                    div.className = 'load-more-rep';
                    //     var span = document.createElement('span');
                    //     span.className = 'count-total-rep';
                    //         var _span = document.createElement('span');
                    //         _span.className = 'count-replies';
                    //         _span.setAttribute('data-com-id',data.data.comments[i].id);
                    //         _span.appendChild(document.createTextNode(_length));
                    //     span.appendChild(_span);
                    //     span.appendChild(document.createTextNode(' trong số  '));
                    //         var __span = document.createElement('span');
                    //         __span.className='total_teplies';
                    //         __span.appendChild(document.createTextNode(data.data.comments[i].count_replys.count));
                    //     span.appendChild(__span);
                    // div.appendChild(span);
                        var a = document.createElement('a');
                        a.href = 'javascript:void(0)';
                        a.className = 'loadmore-previous-reply';
                        a.setAttribute('data-url',url_Previous_Reply);
                        a.setAttribute('data-comment-id',data.data.comments[i].id);
                        a.appendChild(document.createTextNode('Xem trả lời trước'));
                    div.appendChild(a);
                    ul.appendChild(div);
                }

                for( var j = 0 ; j < _length_reply ; j ++ ){

                    var item = load_more_Reply(data.data.comments[i].replys[j]);
                    ul.appendChild( item );
                }

                if(a_max < b_max) {
                    var div = document.createElement('div');
                    div.className = 'load-more-rep';
                    //     var span = document.createElement('span');
                    //     span.className = 'count-total-rep';
                    //         var _span = document.createElement('span');
                    //         _span.className = 'count-replies';
                    //         _span.setAttribute('data-com-id',data.data.comments[i].id);
                    //         _span.appendChild(document.createTextNode(_length));
                    //     span.appendChild(_span);
                    //     span.appendChild(document.createTextNode(' trong số  '));
                    //         var __span = document.createElement('span');
                    //         __span.className='total_teplies';
                    //         __span.appendChild(document.createTextNode(data.data.comments[i].count_replys.count));
                    //     span.appendChild(__span);
                    // div.appendChild(span);
                        var a = document.createElement('a');
                        a.href = 'javascript:void(0)';
                        a.className = 'loadmore-next-reply';
                        a.setAttribute('data-url',url_next_reply);
                        a.setAttribute('data-id-max-load',a_max);
                        a.setAttribute('data-id-min-new',0);
                        a.setAttribute('data-comment-id',data.data.comments[i].id);
                        a.appendChild(document.createTextNode('Xem trả lời mới'));
                    div.appendChild(a);
                    ul.appendChild(div);
                }else if(data.data.comments[i].replys.length  == 0){
                    if(data.data.comments[i].count_replys.count > 0 ){
                        var div = document.createElement('div');
                        div.className = 'load-more-rep';
                        //     var span = document.createElement('span');
                        //     span.className = 'count-total-rep';
                        //         var _span = document.createElement('span');
                        //         _span.className = 'count-replies';
                        //         _span.setAttribute('data-com-id',data.data.comments[i].id);
                        //         _span.appendChild(document.createTextNode('0'));
                        //     span.appendChild(_span);
                        //     span.appendChild(document.createTextNode(' trong số  '));
                        //         var __span = document.createElement('span');
                        //         __span.className='total_teplies';
                        //         __span.appendChild(document.createTextNode(data.data.comments[i].count_replys.count));
                        //     span.appendChild(__span);
                        // div.appendChild(span);
                            var a = document.createElement('a');
                            a.href = 'javascript:void(0)';
                            a.className = 'loadmore-next-reply';
                            a.setAttribute('data-url',url_next_reply);
                            a.setAttribute('data-comment-id',data.data.comments[i].id);
                            a.appendChild(document.createTextNode('Xem trả lời mới'));
                        div.appendChild(a);
                        ul.appendChild(div);
                    }
                }

                array.push(ul);
            }
            if(data.data.total_comment > _length && a_max < b_max) {
                var div = document.createElement('div');
                div.className = 'load-more-com';
                    var span = document.createElement('span');
                    span.className = 'count-total-com';
                        var _span = document.createElement('span');
                        _span.className = 'count-comments';
                        _span.appendChild(document.createTextNode(_length));
                    span.appendChild(_span);
                    span.appendChild(document.createTextNode(' trong số  '+data.data.total_comment));
                div.appendChild(span);
                    var a = document.createElement('a');
                    a.href = 'javascript:void(0)';
                    a.className = 'loadmore-previous-comment';
                    a.setAttribute('data-url',url_Previous_Comment);
                    a.setAttribute('data-post-id',post_id);
                    a.appendChild(document.createTextNode('Xem bình luận trước'));
                div.appendChild(a);
                array.push(div);
            }
        }
        $('.loading').addClass('hide');
        for(var i = 0 ; i < array.length; i ++ ){
            $('ul.item-stream  li.loading').before( array[i] );
            lazy_load($(array[i]).find('img'));
        }

        switch (parseInt(content_type)) {
            case parseInt(type_comment):
                $(".id-comment-"+id_content).goTo(-200);
                event_click_reply_onload($("li.id-comment-"+id_content),Auth_ava,Auth_userName ) ;
                break;
            case parseInt(type_reply):
                event_click_reply_onload($("li.ident-com-1[data-content-id='"+id_content+"']"),Auth_ava,Auth_userName ) ;
                break;
        }

    }else {
        var r = confirm("Gặp sự cố trong phiên làm việc vui lòng reload trang!");
        if (r == true) {
            window.location.reload();
        } else {
            console.log('load_data_comment');
            // console.log(data);
        }
    }
});
}

function insertTextAtCursor(text,content_focus) {
    var sel, range, html;
    sel = window.getSelection();
    range = sel.getRangeAt(0);
    var  nodeSelection = getNodeSelection();
    var contentEditAble = nodeSelection['node_par_parent'];
    var node_parent = nodeSelection['node_parent'];
    var node_forcus = nodeSelection['node_focus'];
    if(check_browser) {
        if($(contentEditAble).hasClass('contenteditable'))
        {
            range.deleteContents();
            var textNode = document.createTextNode(text);
            range.insertNode(textNode);
            range.setStartAfter(textNode);
            sel.removeAllRanges();
            sel.addRange(range);
        } else {
            var child = $(content_focus).children();
            var last_child = $(child[child.length - 1]);
            last_child.text(last_child.text() + text);
            var length_char = last_child.text().length;
            linkSelection = {
                start: length_char,
                end: length_char
            };
            doRestore(child[child.length - 1], linkSelection);
        }
    }else {
        if($(contentEditAble).hasClass('contenteditable') || $(node_parent).hasClass('contenteditable'))
        {
            if(node_forcus.nodeName == "BR"){
                var text_node = GetPreviousSibling(node_forcus);
                text_node.data = text_node.data + text;
                 linkSelection = {
                    start: text_node.length,
                    end: text_node.length
                };
                doRestore(text_node, linkSelection);
            }else if(node_forcus.nodeName == "DIV") {
                range.deleteContents();
                var textNode = document.createTextNode(text);
                range.insertNode(textNode);
                range.setStartAfter(textNode);
                sel.removeAllRanges();
                sel.addRange(range);
                linkSelection = {
                    start: text.length,
                    end: text.length
                };
                doRestore(textNode, linkSelection);
            }else {
                rangLength = range.startOffset;
                var text_1 = node_forcus.data.substring(0,rangLength);
                var text_2 = node_forcus.data.substring(rangLength+1,node_forcus.data.length);
                node_forcus.data = text_1 + text+text_2;
                linkSelection = {
                    start: (text_1 + text).length,
                    end: (text_1 + text).length
                };
                doRestore(node_forcus, linkSelection);
            }
        }else {
            var child = $(content_focus).children();
            var last_child = child[child.length - 1];
            if(last_child.nodeName == "BR"){
                var text_node = GetPreviousSibling(last_child);
                text_node.data = text_node.data + text;
                 linkSelection = {
                    start: text_node.length,
                    end: text_node.length
                };
                doRestore(text_node, linkSelection);
            }
        }
    }
    content_focus.focus();
}

function message_error(message) {
    var span = $('div#message .modal-body span.message').first();
    span.text(message);
    $('#message').modal();

}

function load_post_of_user () {

    var count_item = $('.simple-post:not(.new-post-bar)').length;
    var element_loading = $('.loading-post').first();
    var element_load_more = $('.read-more').first();
    element_loading.removeClass('hide');
    var _url = url+url_loadmore;
    var _id = 0;
    if(typeof id != 'undefined' ) _id = id;
    // if($('.box-item-trend .active').length != 0 && page_index == 'trend') _url = $('.box-item-trend .active').first().attr('href');
    // else _url = url+url_loadmore;
    // console.log(_url);
    $.ajax({
        type: "POST",
        url: _url,
        data: { count:count_item,id:_id }
    }).done(function( data ) {
        if(data.error == 'false'){
            var length_data = data.data.length;
            if(length_data > 0 ){
                for (var i = 0; i <=  length_data - 1 ; i++) {
                    var hot = count_item + i ;
                    var item = loadMorePost(data.data[i]);
                    var post_content = $(item).find('div.post-content-fresh-hot').first();
                    var ava_row = $(item).find('div.avatar-row').first();
                    element_loading.before(item);
                    lazy_load(post_content.find('img'));
                    lazy_load(ava_row.find('img'));
                };
                count_load = count_load + 1;
                var a = count_item + length_data ;
                if( a%60 == 0 ){
                    element_load_more.removeClass('hide');
                    element_loading.addClass('hide');
                }else {
                    element_load_more.addClass('hide');
                    loadMore = true;
                }
            }else { loadMore = false ; element_load_more.addClass('hide'); element_loading.addClass('hide');}

        }else {
            var r = confirm("Gặp sự cố trong phiên làm việc vui lòng reload trang!");
            if (r == true) {
                window.location.reload();
            } else {
                console.log('load_more_post');
                console.log(data);
            }
        }
        element_loading.addClass('hide');
    });
}

function favorite_cate(Elm) {
    var id = Elm.attr('data-id');
    $.ajax({
        type: "POST",
        url: url+'favor-cate',
        data: { id:id }
    }).done(function( data ) {
        if(data.data == 1 || data.data == "1"){
            Elm.find('i').first().addClass('favorited');
        }else {
            Elm.find('i').first().removeClass('favorited');
        }
    });

}
function favorite_hashtag(Elm) {
    var name = Elm.attr('data-name');
    $.ajax({
        type: "POST",
        url: url+'favor-hashtag',
        data: { name:name }
    }).done(function( data ) {
        if(data.data == 1 || data.data == "1"){
            Elm.find('i').first().addClass('favorited');
        }else {
            Elm.find('i').first().removeClass('favorited');
        }
    });
}

function load_profile_of_user() {
    page_index = '';
    $.ajax({
        type: "POST",
        url: url+'profile_user',
        data: { id:id }
    }).done(function( data ) {
        var ul = profile(data.data);
        $('div.left-stream-wrap').first().append(ul);
        lazy_load($(ul).find('img'));
    });
}
function load_favor (_url) {
      $.ajax({
        type: "POST",
        url: url+_url
    }).done(function( data ) {
        var length_data = data.data.length;
        var array = {};
        if(length_data > 0 ){
            $('.box-header-farvorite').remove();
            var text = '# Yêu thích #';
            var li = document.createElement('li');
            li.className = 'col-md-12 left-stream left-stream-box box-header-farvorite';
                var div = document.createElement('div');
                div.className = 'col-md-12 header-farvorite';
                    var div_2 = document.createElement('div');
                    div_2.className = 'line-header-farvorite';
                        var span = document.createElement('span');
                        if(_url == 'loadFavorCate'){
                            text = 'Mục Yêu thích';
                            page_index='show-cate';
                            url_loadmore = 'g/'+data.data[0].code;

                        }else {
                            url_loadmore = 'hashtag/' + data.data[0].hashtag;
                            page_index='trend';
                        }
                        span.appendChild(document.createTextNode(text+' (Đã thích '+length_data+')'));
                    div_2.appendChild(span);
                div.appendChild(div_2);
            li.appendChild(div);
                var ul = document.createElement('ul');
                ul.className = 'tags item-header-farvorite';
                var class_active = 'favor-active';
                for(var i = 0 ; i < length_data; i++){
                    var item ;
                    var post_content = $(item).find('div.post-content-fresh-hot').first();
                    var ava_row = $(item).find('div.avatar-row').first();
                    if(i > 0 ) class_active = '';
                    if(_url == 'loadFavorCate'){
                        item = box_favor(data.data[i].name,url+'g/'+data.data[i].code,data.data[i].code,class_active);
                    }
                    else{
                        item = box_favor('#'+data.data[i].hashtag,url+'/hashtag/'+data.data[i].hashtag,data.data[i].hashtag,class_active);
                    }
                    ul.appendChild(item);
                    lazy_load(post_content.find('img'));
                    lazy_load(ava_row.find('img'));
                }
            li.appendChild(ul);
                var div_clear = document.createElement('div');
                div_clear.className = 'clear';
                li.appendChild(div_clear);

            $('.left-stream-wrap').first().children(":first").before(li);
            load_more_post();
        }
    });
}

function change_favor_active(Elm) {
    var _this = Elm;
    var hashtag = _this.text();
    $('li.only-one div.only-one-top span').text(hashtag);
    if($('.box-item-trend .active').first().attr('class') == _this.attr('class')) return;
    $('div.left-stream-box:not(.wrap-loading-post)').addClass('hide');
    var element_loading = $('.loading-post').first();
    var element_load_more = $('.read-more').first();
    element_loading.removeClass('hide');
    if(page_index == 'trend') {
        url_loadmore = 'hashtag/' + _this.attr('data-code');
    }else {
        url_loadmore = 'g/'+_this.attr('data-code');
    }
    $.ajax({
        type: "POST",
        url: url_loadmore,
        data: { count_post_cate:0 }
    }).done(function( data ) {
        if(data.error == 'false'){
            var length_data = data.data.length;
            $('div.left-stream-box:not(.wrap-loading-post)').remove();
            if(length_data > 0 ){
                $('.no-post').addClass('hide');
                for (var i = 0; i <=  length_data - 1 ; i++) {
                    var item = loadMorePost(data.data[i]);
                    var post_content = $(item).find('div.post-content-fresh-hot').first();
                    var ava_row = $(item).find('div.avatar-row').first();
                    element_loading.before(item);
                    lazy_load(post_content.find('img'));
                    lazy_load(ava_row.find('img'));
                };
                element_loading.addClass('hide');
                element_load_more.addClass('hide');
            }else {
                $('div.left-stream-box').removeClass('hide');
                if($('div.left-stream-box:not(.wrap-loading-post)').length == 0){
                    $('.no-post').removeClass('hide');
                }
                element_loading.addClass('hide');
                element_load_more.addClass('hide');
            }

        }else {
            var r = confirm("Gặp sự cố trong phiên làm việc vui lòng reload trang!");
            if (r == true) {
                window.location.reload();
            } else {
                console.log('ajax_trend_post');
                // console.log(data);
            }
        }
    });
}

function update_rate () {
    // var item = $('div.mod-rate').find('input[checked=checked]');

    // for (var i = item.length -1; i >= 0; i--) {
    //     $(item[i]).prop('checked', true);
    // }
}

function onYouTubeIframeAPIReady(id,video_id,width,height) {
var player;
player = new YT.Player(id, {
  height: height,
  width: width,
  videoId: video_id,
  events: {
    'onReady': onPlayerReady,
    'onStateChange': onPlayerStateChange
  }
});
}

// 4. The API will call this function when the video player is ready.
function onPlayerReady(event) {
    event.target.playVideo();
}

// 5. The API calls this function when the player's state changes.
//    The function indicates that when playing a video (state=1),
//    the player should play for six seconds and then stop.
var done = false;
function onPlayerStateChange(event) {
    var stop_event = 2;
    var start_event = 1;
    if (event.data == stop_event) {
        var ifream = $(event.target.c);
        ifream.addClass('hide');
        ifream.removeClass('video-play');
        var parent = ifream.parent().first();
        parent.addClass('A_video');
        var img = parent.find('img').first();
        img.removeClass('hide');
    }
}
function stopVideo(Elm) {
    Elm.stopVideo();
}

function lazy_load (Elm) {
    Elm.lazyload({
        effect : "fadeIn"
    });
}