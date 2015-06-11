const redis =   require('redis');
const client =  redis.createClient();
var request = require("request");
var date = new Date();
var start_time = date.getTime();//
var end_time = start_time + 900000;
var count = [];
// client.keys('analytics:*', function (err, keys) {
//   if (err) return console.log(err);
//     for(var i = 0, len = keys.length - 1; i <= len; i++) {
//         client.del(keys[i]);
//     }
// });
// client.del('lastTimeUpdate');

function aggregating_data (time_run) {
    client.exists("lastTimeUpdate", function(err, reply) {
        if (reply === 1) {
            client.hgetall("lastTimeUpdate", function(err, object) {
                start_time = parseInt(object['start_time']);
                end_time = parseInt(object['end_time']);
                client.hmset("analytics:" + start_time.toString(), 'count',0, redis.print);
            });
        } else {
            end_time = time_run;
            start_time = end_time - 900000 ;
            client.hmset("lastTimeUpdate", 'start_time',start_time,'end_time',end_time, redis.print);
            client.hmset("analytics:" + start_time.toString(), 'count',0, redis.print);
        }
    });
    data_synthesis();
    run_time_out();
}
function run_time_out(){
    setInterval(function get_analytic () {
        client.hgetall("lastTimeUpdate", function(err, object) {
            if(object != null){
                start_time = parseInt(object['end_time']);
                end_time = start_time + 900000;
            }else {
                var date = new Date();
                end_time = date.getTime();
                start_time = end_time - 900000;
            }
        });
        client.hmset("analytics:" + start_time.toString(), 'count',0, redis.print);
        data_synthesis();
    },900000);
}
function data_synthesis(){
    client.keys('user:*', function (err, keys) {
        if (err) return console.log(err);
        var count = 0 ;
        for(var i = 0, len = keys.length - 1; i <= len; i++) {
            if(keys[i] != 'user:' && keys[i] != 'lastTimeUpdate'){
                client.hgetall(keys[i], function(err, object) {
                    var keys_obj = Object.keys(object);
                    for(var j = keys_obj.length - 1 ; j >=0; j--) {
                        var key = keys_obj[j];
                        var action = key.split('-');
                        time_ol = parseInt(action[0]);
                        if(( action[action.length-1] == 'off' || action[action.length-1] == 'on') && time_ol >= start_time && time_ol < end_time ){
                            count = count +1;
                            client.hmset("analytics:" + start_time.toString(), 'count',count, redis.print);
                            break;
                        }else if(action[action.length-1] == 'on' && parseInt(action[0]) < ( start_time + 1 )  ){
                            count = count +1;
                            client.hmset("analytics:" + start_time.toString(), 'count',count, redis.print);
                            break;
                        }else if(action[action.length-1] == 'off' && parseInt(action[0]) < ( start_time + 1 )) {
                            break;
                        }
                    }
                });
            }
        }
        client.hgetall("lastTimeUpdate", function(err, object) {
            // time = end_time cua lan lam viec hien tai
            // start_time la thoi gian bat dau lan lam viec hien tai
            //---------------------------------------------------------------------------
            //---------------------------Doan nay bi bi loi nen time khong he duoc update
            //--------------------------------------------------------------------------
            client.hmset("lastTimeUpdate", 'start_time',start_time,'end_time',end_time, redis.print);
        });
        call_php();
    });
}

function call_php () {
    var date_call = new Date();
    var time_call = date_call.getTime();
    var accesstoken = (new Buffer(time_call.toString()).toString('base64'));
    client.hmset("accesstoken", "accesstoken",accesstoken, redis.print);
    request({
      uri: "http://52.68.136.170/admin/hot100",
      method: "POST"
    }, function(error, response, body) {
      console.log(body);
    });
}
module.exports.aggregating_data = aggregating_data;
module.exports.run_time_out = run_time_out;

