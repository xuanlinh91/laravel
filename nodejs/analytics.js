const redis =   require('redis');
const client =  redis.createClient();
var date = new Date();
var time = 1431917100000;

var time_out = setInterval(function test(){
    var new_date = new Date();
    var new_time = new_date.getTime();
    if(( new_time - time ) <= 5 && ( new_time - time ) >= 0 ) {
        new_time = time;
        client.exists("lastTimeUpdate", function(err, reply) {
            if (reply === 1) {
                client.hgetall("lastTimeUpdate", function(err, object) {
                    new_time = parseInt(object['start_time']);
                    client.hmset("analytics:" + new_time, 'count',0, redis.print);
                });
            } else {
                client.hmset("lastTimeUpdate", 'start_time',new_time - 900000 ,'end_time',new_time );
                client.hmset("analytics:" + new_time, 'count',0, redis.print);
            }
        });
        var aggregating_data = require('./aggregating_data').aggregating_data(new_time);
        clearInterval(time_out);
    }else if(new_time > time){
        clearInterval(time_out);
    }
},1);

// client.keys('analytics:*', function (err, keys) {
//   if (err) return console.log(err);
//     for(var i = 0, len = keys.length - 1; i <= len; i++) {
//         client.del(keys[i]);
//     }
// });
// client.del('lastTimeUpdate');