const mysql      = require('mysql');
const redis =   require('redis');
const client =  redis.createClient();

// var connection = mysql.createConnection({
//   host     : 'localhost',
//   user     : 'root',
//   password : 'gutlo@#!',
//   database : 'gutlo',
// });

// connection.connect(function(err) {
//   // connected! (unless `err` is set)
// });
// var activityLog = require('./activity_log');
// connection.query('SELECT * from gutlo_activity_log ', function(err, rows, fields) {
//   	if (err) throw err;
//   	for (var i = 0; i < rows.length; i++) {
//   		var cate = '';
//   		var hashtag_id = '';
//   		var created_time = rows[i]['created_time'];
//   		var to_id = rows[i]['to_id'];
//   		var from_id = rows[i]['from_id'];
//   		var activity_types_id = rows[i]['activity_types_id'];
//   		var content_id = rows[i]['content_id'];
//   		var content_type = rows[i]['content_type'];
//   		var post_id = rows[i]['post_id'];
//   		if(rows[i]['post_id'] != null){
//   			connection.query('SELECT * from gutlo_posts where id = ? ',[rows[i]['post_id']] ,function(err, _rows, fields) {
//   				for (var j = 0; j < _rows.length; j++) {
//   					cate = _rows[j].category_id;
//   					hashtag_id = _rows[j].hashtag_id;
//   				}
//   			});
// 		}
// 		var data = {
// 			  		'created_time' : created_time
// 			        ,'to_id' : to_id
// 					,'from_id' : from_id
// 					,'activity_types_id' : activity_types_id
// 					,'content_id' : content_id
// 					,'content_type' : content_type
// 					,'post_id' : post_id
// 					,'hashtag_id':hashtag_id
// 					,'cate_id' : cate
// 			  	};
// 		  	activityLog.save_log(data);


//   	};
//   	console.log('done');
// });
client.keys("activityLog:*", function (err, keys) {
    keys.forEach(function (key, pos) {
         client.del(key, function(err, o) {
      if (err) {
             console.error('No se elimino: ' + key);
          } 
          else {
           console.log('Se borro: ' + key);
         }
         if (pos === (keys.length - 1)) {
                client.quit();
         }
        });
    });
});