var express =   require('express'),
    http =      require('http'),
    server =    http.createServer(app),
     httpcli  = require('httpclient');

var app = express();

const redis =   require('redis');
const io =      require('socket.io');
const _redis =  redis.createClient();

// client.get('abc',function(err,data){
//   console.log(data); kill khi co the
// });
// function connect_mysql () {
//     var mysql      = require('mysql');
//     var connection = mysql.createConnection({
//       host     : 'localhost',
//       user     : 'root',
//       password : 'gutlo@#!',
//       database : 'gutlo'
//     });

//     connection.connect();

//     connection.query('SELECT * from users', function(err, rows, fields) {
//       if (!err)
//         console.log('The solution is: ', rows);
//       else
//         console.log('Error while performing Query.');
//     });

//     connection.end();
// }

// function arrayObjectIndexOf(myArray, searchTerm, property) {
//     for(var i = 0, len = myArray.length; i < len; i++) {
//         if (myArray[i][property] === searchTerm) return i;
//     }
//     return -1;
// }
//   var abc = [];

// function list_user_a (data,client) {
//     setTimeout(function() {
//         console.log('abc');
//         client.emit('update_up', data);
//         list_user_a(data);
//     }, 4000);
// }
//  const redisClient = redis.createClient();
// client.sadd("users", "user:rahul");
// client.hmset("user:rahul", "username", "rahul", "foo", "bar");

// // add second user
// client.sadd("users", "user:namita");
// client.hmset("user:namita", "username", "namita", "foo", "baz");
// client.hgetall('users', function(err, object) {
// });
// client.keys('*', function (err, keys) {
//   if (err) return console.log(err);
//   for(var i = 0, len = keys.length; i < len; i++) {
//     client.hgetall(keys[i], function(err, object) {
//     	if(object != undefined){
//         callback(object);
// 	    }
// 	});
//     client.del(keys[i]);
//   }
//   console.log(abc);
// });
// function callback(object) {
//   abc.push(object);
//   console.log(abc);
  
// }

var client = new httpcli.httpclient();  
var url = "http://52.68.136.170/Callphp"
client.perform(url, "GET", function(result) {
// Result is the response of the server
console.log(result);
}, null);