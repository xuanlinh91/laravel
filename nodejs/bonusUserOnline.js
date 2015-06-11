const mysql      = require('mysql');
const redis =   require('redis');
const client =  redis.createClient();
const check = true;
var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : 'gutlo@#!',
  database : 'gutlo',
});

connection.connect(function(err) {
  // connected! (unless `err` is set)
});
var day_now = '';
var last_day = '';
var dayCount = '';
var dayOnline = '';
var id = '';
var last_off_time = '';
var check_ = true;
function get_user() {
	var username = '';
	// if(check_) {
		check = false;
		client.keys('user:*', function (err, keys) {
	        if (err) return console.log(err);
	        var count = 0 ;
	        for(var i = 0, len = keys.length - 1; i <= len; i++) {
	            if(keys[i] != 'user:' && keys[i] != 'lastTimeUpdate'){
	                client.hgetall(keys[i], function(err, object) {
						connection.query('SELECT * from users where `username` = ?', [object.username], function(err, rows, fields) {
						  	if (err) throw err;
						  	console.log(object.username);
						  	dayCount = rows[0].dayCount;
							dayOnline = rows[0].dayOnline;
							id = rows[0].id;
						  	// mog GMT viet nam +7
						  	var UTC = 7 * 60 * 60 * 1000;
						  	// get day hien tai
						  	day_now = new Date();
					        day_now = day_now.getTime();
					        day_now = day_now + UTC;
					        // Tinh toan lay ra so ngay theo time stamp
					        // cong thuc tinh timestamp / ( 24 * 60 * 60 * 1000)
					        day_now = Math.floor(day_now/86400000);
					        // Lay ra danh sach user trong redis
					       	var keys = Object.keys(object);
					       	var length_keys = keys.length - 1;
					        for(var i = length_keys ; i >=0; i--) {

					            var key = keys[i];
					            var action = key.split('-');

					            if(action[action.length-1] == 'off'){
					            	// lay ra thoi gian off line
									last_off_time = Math.floor(action[0]/86400000);

					            }else if(action[action.length-1] == 'on'){
					            	// lay ra thoi gian online gan nhat
					            	last_day = parseInt(action[0]) + UTC;
									last_day = Math.floor(last_day/86400000);
					                break;

					            }
					        }
					        if(last_off_time == ''){
					        	// neu nguoi do online qua ngay ma khong offline
					        	// thi se update nguoi ta offline va online vao thoi diem nhan gift
						        var date = new Date();
						        object[date.getTime().toString()+'-off'] = 'logout';
						        // tao thoi gian online sau thoi gian off line 1s
						        object[(date.getTime() + 1000 ).toString()+'-on'] = 'login';
						        client.hmset("user:"+object.username, object);
								fillter(object.username);
					        }
					        last_off_time = '';

						});
	   				});
		        }
		    }
	    });
	// }
}
function update_gift_user_when_conect (username) {
	if(username != '' && username.trim() != ''){
		client.hgetall('user:'+username, function(err, object) {
			connection.query('SELECT * from users where `username` = ?', [username], function(err, rows, fields) {
			  	if (err) throw err;
			  	console.log(username);
			  	dayCount = rows[0].dayCount;
				dayOnline = rows[0].dayOnline;
				id = rows[0].id;
			  	// mog GMT viet nam +7
			  	var UTC = 7 * 60 * 60 * 1000;
			  	// get day hien tai
			  	day_now = new Date();
		        day_now = day_now.getTime();
		        day_now = day_now + UTC;
		        // Tinh toan lay ra so ngay theo time stamp
		        // cong thuc tinh timestamp / ( 24 * 60 * 60 * 1000)
		        day_now = Math.floor(day_now/86400000);
		        // Lay ra danh sach user trong redis
		        if(object != null){
			       	var keys = Object.keys(object);
			       	var length_keys = keys.length - 1;
			        for(var i = length_keys ; i >=0; i--) {

			            var key = keys[i];
			            var action = key.split('-');

			            if(action[action.length-1] == 'off'){
			            	// lay ra thoi gian off line
							last_off_time = Math.floor(action[0]/86400000);

			            }else if(action[action.length-1] == 'on'){
			            	// lay ra thoi gian online gan nhat
			            	last_day = parseInt(action[0]) + UTC;
							last_day = Math.floor(last_day/86400000);
			                break;

			            }
			        }
		        }
		        if(last_off_time != ''){
		        	// neu nguoi do online qua ngay ma khong offline
		        	// thi se update nguoi ta offline va online vao thoi diem nhan gift
			        var date = new Date();
			        object[date.getTime().toString()+'-off'] = 'logout';
			        // tao thoi gian online sau thoi gian off line 1s
			        object[(date.getTime() + 1000 ).toString()+'-on'] = 'login';
			        client.hmset("user:"+object.username, object);
					fillter(object.username);
		        }


			});
		});
	}
}
function fillter(username) {
	if(dayCount == "0") {
	  	update_user_bonus(1);
	}else {
		var day_diff = day_now - last_day;
		console.log(day_diff);
		switch (day_diff){
			case 0:
				break;
			case 1:
	  			update_user_bonus(dayCount + 1);
	  			break;
	  		default :
	  			if(day_diff > 0){
	  				update_user_bonus(1);
	  			}
		}
	}
	// connection.end(function (err) {
	//   // all connections in the pool have ended
	// });
}
function update_user_bonus(day_count) {
			  	console.log(day_count);

	var exp = 10;
	var gold = 20;
	switch (day_count){
		case 1:
			exp = 10;
			gold = 20;
			break;
		case 2:
  			exp = 20;
  			gold = 40;
  			break;
  		case 3:
  			exp = 30;
  			gold = 60;
  			break;
  		case 4:
  			exp = 40;
  			gold = 80;
  			break;
  		case 5:
  			exp = 50;
  			gold = 100;
  			break;
  		case 6:
  			exp = 60;
  			gold = 120;
  			break;
  		case 7:
  			exp = 70;
  			gold = 140;
  			break;
  		default:
			if (day_count > 7) {
				exp = 70;
				gold = 140;
				break;
			}else{
				console.log('update_user_bonus có vấn đề rồi !');
				break;
			};
	}
	console.log(day_count);
	connection.query("UPDATE users SET dayCount = '"+day_count+"',exp = exp + '"+exp+"' WHERE id = '"+id+"'",  function (err, result) {
		if (err) throw err;
	});

	connection.query("UPDATE gutlo_point SET bonus_point = bonus_point + '"+gold+"', real_point = real_point + '"+gold+"' WHERE user_id = '"+id+"'",  function (err, result) {
		if (err) throw err;
	});
}
module.exports.get_user = get_user;
module.exports.update_gift_user_when_conect = update_gift_user_when_conect;