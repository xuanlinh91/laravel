
const mysql      = require('mysql');
const redis =   require('redis');
const client =  redis.createClient();



function save_log (data) {
	console.log(data.post_id);
	var time = data.created_time;
	var UTC = 7 * 60 * 60 * 1000;
	time = (new Date(time)).getTime();
	time = time + UTC;
	time = new Date(time);
	var day = get_days(time);
	var min = time.getMinutes().toString();
	var hour = time.getHours().toString();
	var month = time.getMonth().toString();
	var year = time.getFullYear().toString();
	var week = get_week(time);
	var object = {
			'created_time':time,
			'to_id':data.to_id,
			'from_id':data.from_id,
			'activity_types_id':data.activity_types_id,
			'content_id':data.content_id,
			'content_type':data.content_type,
			'post_id':data.post_id,
			'hashtag_id':data.hashtag_id,
			'cate_id':data.cate_id
    };
    time = time.getTime().toString();
    client.hmset("activityLog:log:"+year+':'+month+':'+week+':'+day+':'+hour+':'+min+':'+time, object);
	save_total_all_action(data);
	
}

function get_days(time){
	var days=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	return days[time.getDay()];
}

function get_week(time){
	prefixes = ['1', '2', '3', '4', '5'];
	return prefixes[0 | time.getDate() / 7];
}

function save_total_all_action(data) {
	client.exists("activityLog:TotalAllTime", function(err, reply) {
		if(reply === 1){
			// da co
			client.hgetall("activityLog:TotalAllTime", function(err, object) {
                var keys = Object.keys(object);

                var key_action = '';
                console.log(data.activity_types_id);
				switch(data.activity_types_id) {
					case '1':
					case 1:
					// report post
						key_action = 'total_report_post';
						break;
					case '2':
					case 2:
					// report comment
						key_action = 'total_report_comment';
						break;
					case '3':
					case 3:
					// report reply
						key_action = 'total_report_reply';
						break;
					case '4':
					case 4:
					// like post
						key_action = 'total_like_post';
						break;
					case '5':
					case 5:
					// like comment
						key_action = 'total_like_comment';
						break;
					case '6':
					case 6:
					// like reply
						key_action = 'total_like_reply';
						break;
					case '7':
					case 7:
					// brick post
						key_action = 'total_brick_post';
						break;
					case '8':
					case 8:
					// brick comment
						key_action = 'total_brick_comment';
						break;
					case '9':
					case 9:
					// brick reply
						key_action = 'total_brick_reply';
						break;
					case '10':
					case 10:
					//total post
						key_action = 'total_post';
						break;
					case '11':
					case 11:
					// total comment
						key_action = 'total_comment';
						break;
					case '12':
					case 12:
					// total reply
						key_action = 'total_reply';
						break;
					case '17':
					case 17:
					// delete post
						key_action = 'total_delete_post';
						break;
					case '18':
					case 18:
					// delete comment
						key_action = 'total_delete_comment';
						break;
					case '19':
					case 19:
					// delete reply
						key_action = 'total_delete_reply';
						break;
				}
				for(var i = keys.length - 1 ; i >=0; i--) {
                    var key = keys[i];
                    if(key == key_action){
                    	var old_value = parseInt(object[key]);
                        object[key] = old_value + 1;
                        client.hmset("activityLog:TotalAllTime", object);
                        break;
                    }
                }
            });
		}else {
			// chua co
			var total_post = 0, total_comment = 0 , total_reply = 0 , total_like_post = 0, total_like_reply = 0, total_like_comment = 0 ;
			var total_brick_post = 0, total_brick_comment = 0 , total_brick_reply = 0,total_report_post = 0,total_report_comment = 0,total_report_reply = 0 ;
			var delete_post = 0 , delete_comment = 0, delete_reply = 0;
			switch(data.activity_types_id) {
				case '1':
				case 1:
				// report post
					total_report_post = 1;
					break;
				case '2':
				case 2:
				// report comment
					total_report_comment = 1;
					break;
				case '3':
				case 3:
				// report reply
					total_report_reply = 1;
					break;
				case '4':
				case 4:
				// like post
					total_like_post = 1;
					break;
				case '5':
				case 5:
				// like comment
					total_like_comment = 1;
					break;
				case '6':
				case 6:
				// like reply
					total_like_reply = 1;
					break;
				case '7':
				case 7:
				// brick post
					total_brick_post = 1;
					break;
				case '8':
				case 8:
				// brick comment
					total_brick_comment = 1;
					break;
				case '9':
				case 9:
				// brick reply
					total_brick_reply = 1;
					break;
				case '10':
				case 10:
				//total post
					total_post = 1;
					break;
				case '11':
				case 11:
				// total comment
					total_comment = 1;
					break;
				case '12':
				case 12:
				// total reply
					total_reply = 1;
					break;
				case '17':
				case 17:
				// delete post
					delete_post = 1;
					break;
				case '18':
				case 18:
				// delete comment
					delete_comment = 1;
					break;
				case '19':
				case 19:
				// delete reply
					delete_reply = 1;
					break;
			}

			var object = {
                "total_post": total_post,
                "total_like_post": total_like_post,
                "total_brick_post":total_brick_post,
                "total_comment":total_comment,
                "total_like_comment":total_like_comment,
                "total_brick_comment":total_brick_comment,
                "total_reply":total_reply,
                "total_like_reply":total_like_reply,
                "total_brick_reply":total_brick_reply,
                "total_delete_post":delete_post,
                "total_delete_comment":delete_comment,
                "total_delete_reply":delete_reply,
                "total_report_post":total_report_post,
                "total_report_comment":total_report_comment,
                "total_report_reply":total_report_reply

            };
            client.hmset("activityLog:TotalAllTime", object);
		}
	});
}
module.exports.save_log = save_log;

    //      switch(true){
       //      	case ( (hour == 7 ) || ( hour == 9 && min <= 30 ) || ( hour > 7  && hour < 9 ) ):
       //      		object[year+''][month+''][week+''][day+''][]
			    //     break;
			    // case ( hour >= 13  && hour <= 14 ):
			    //     break;
			    // case ( (hour == 10 && min >= 30 ) || ( hour == 11 && min <= 30 ) || ( hour > 10  && hour < 11 ) ):
			    //     break;
			    // case ( (hour == 16 && min >= 30 ) || ( hour == 17 && min <= 30 ) || ( hour > 16  && hour < 17 ) ):
			    //     break;
			    // case ( (hour == 20 && min >= 30 ) || ( hour == 21 && min <= 30 ) || ( hour > 20  && hour < 21 ) ):
			    //     break;
			    // case ( (hour == 22 && min >= 30 ) || ( ( hour == 24 || hour == 0) && min == 0 ) || ( hour > 22  && ( hour < 24 || hour <= 23) ) ):
			    //     break;
			    // case ( ( ( hour == 24 || hour == 0) && min > 0 ) || ( hour == 7 && min == 0 ) || ( ( hour > 24 || hour > 0)  && hour < 7 )):
			    //     break;
			    // case ( (hour == 9 && min > 30 ) || ( hour == 10 && min < 30 ) || ( hour > 9  && hour < 10 ) ):
			    //     break;
			    // case ( (hour == 11 && min > 30 ) || ( hour == 16 && min < 30 ) || ( hour > 11  && hour < 16 ) ):
			    //     break;
			    // case ( (hour == 21 && min > 30 ) || ( hour == 22 && min < 30 ) || ( hour > 21  && hour < 22 ) ):
			    //     break;
			    // case ( (hour == 17 && min > 30 ) || ( hour == 20 && min < 30 ) || ( hour > 17  && hour < 20 ) ):
			    //     break;
       //      }