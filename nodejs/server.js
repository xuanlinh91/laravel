var express =   require('express');
var app = express();

var http =      require('http'),
    server =    http.createServer(app);

const mysql      = require('mysql');
const redis =   require('redis');
const io =      require('socket.io').listen(server);
const clientRedis =  redis.createClient();

var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : 'gutlo@#!',
  database : 'gutlo',
});

connection.connect(function(err) {
  // connected! (unless `err` is set)
});

var update_offline = [];
var list_user_online = [];
function arrayObjectIndexOf(myArray, searchTerm, property) {
    for(var i = 0, len = myArray.length; i < len; i++) {
        if (myArray[i][property] === searchTerm) return i;
    }
    return -1;
}
function remove_all_key () {
    clientRedis.keys('*', function (err, keys) {
      if (err) return console.log(err);
        for(var i = 0, len = keys.length - 1; i <= len; i++) {
            clientRedis.del(keys[i]);
        }
    });
}
function add_update_user(data){
    clientRedis.exists("user:"+data['username'], function(err, reply) {
        if (reply === 1) {
            clientRedis.hgetall("user:"+data['username'], function(err, object) {
                var date = new Date();
                var keys = Object.keys(object);
                for(var i = keys.length - 1 ; i >=0; i--) {
                    var key = keys[i];
                    var action = key.split('-');
                    if(action[action.length-1] == 'off'){
                        object[date.getTime().toString()+'-on'] = 'login';
                        object['ava'] = data['ava'];
                        clientRedis.hmset("user:"+data['username'], object);
                        break;
                    }else if(action[action.length-1] == 'on'){
                        break;
                    }
                }
            });
        } else {
            connection.query('SELECT * from users where `username` = ?', [data['username']], function(err, rows, fields) {
                if (err) return console.log(err);
                if(rows != null ){
                        var date = new Date();
                        var permission_role = rows[0].permission_role;
                        var object = {
                            "username": data['username'],
                            "nickname": data['nickname'],
                            "ava":data['ava'],
                            "permission_role":permission_role
                        };
                        object[date.getTime().toString()+'-on'] = 'login';
                        clientRedis.hmset("user:"+data['username'], object);
                }
                connection.end(function (err) {
                  // all connections in the pool have ended
                });
            });
        }
    });
}
function get_user_online () {
    clientRedis.keys('user:*', function (err, keys) {
      if (err) return console.log(err);
        for(var i = keys.length - 1; i >= 0; i--) {
            if(keys[i] != "user:"){
                clientRedis.hgetall(keys[i], function(err, object) {
                    var keys = Object.keys(object);
                    for(var i = keys.length - 1 ; i >=0; i--) {
                        var key = keys[i];
                        var action = key.split('-');
                        if(action[action.length-1] == 'on'){
                            var pr = false;
                            if(object.permission_role >1) pr = true;
                            var user = {
                                "username": object.username,
                                "nickname": object.nickname,
                                "ava": object.ava,
                                "pr":pr
                            }
                            list_user_online.push(user);
                            io.sockets.emit('list member online', list_user_online);
                            list_user_online = [];
                            break;
                        }else {
                           if(action[action.length-1] == 'off'){
                            break;
                           }
                        }
                    }
                });
            }
        }
    });
}
function updateUserLogout (data,redisClient) {
    var username = data['username'] ;
    var time_out = setTimeout(function() {
        clientRedis.hgetall("user:"+username, function(err, object) {
            var keys = Object.keys(object);
            for(var i = keys.length - 1 ; i >=0; i--) {
                var key = keys[i];
                var action = key.split('-');
                if(action[action.length-1] == 'on'){
                    var offline = [];
                    var user = {
                        "username": object.username,
                        "nickname": object.nickname,
                        "ava": object.ava,

                    }
                    offline.push(user);
                    var date = new Date();
                    object[date.getTime().toString()+'-off'] = 'logout';
                    clientRedis.hmset("user:"+username, object);
                    io.sockets.emit('member offline', offline);
                    break;
                }else if(action[action.length-1] == 'off'){
                    break;
                }
            }
        });
    }, 5000);
    redisClient.quit();
    update_offline.push({"timeout":time_out,'user':username});
}
server.listen(3000);
 var list_user = [];
 list_user.push({username:'', ava:'', nickname:''});
io.on('connection', function(client) {
    const redisClient = redis.createClient();
    redisClient.subscribe('data');
    redisClient.subscribe('notification');
    redisClient.subscribe('gift');
    redisClient.subscribe('Activity_log');

    redisClient.on("message", function(channel, data) {
        //Channel is e.g 'score.update'
        data = JSON.parse(data);
        switch(channel) {
            case 'gift':
                var chaneel_public = data.id_public;
                client.emit(chaneel_public, JSON.parse(data.data));
                break;
            case 'data':
                for(var i = list_user.length - 1 ; i >= 0 ; i --){
                    if(JSON.parse(data.data).user['username'] != list_user[i]['username']){
                        var chaneel_public = 'data_'+ data.id_public+'_'+list_user[i]['username'];
                        // setTimeout(function() {
                            client.emit(chaneel_public, JSON.parse(data.data));
                        // }, 5000);
                    }
                }
                break;
            case 'notification':
                if(typeof(data.id_public) == 'string'){
                    var chaneel_public = data.id_public;
                    // setTimeout(function() {
                        client.emit(chaneel_public, JSON.parse(data.data));
                    // }, 5000);
                }else {
                    for (var i = data.id_public.length - 1; i >= 0; i--) {
                        if(typeof(data.id_public[i]) == 'string' && data.id_public[i] != '0'){
                            var chaneel_public = data.id_public[i];
                            // setTimeout(function() {
                                client.emit(chaneel_public, JSON.parse(data.data));
                            // }, 5000);
                        }else {
                            if(data.id_public[i].id != '0'){
                                var chaneel_public = data.id_public[i].id;
                                // setTimeout(function() {
                                    client.emit(chaneel_public, JSON.parse(data.data));
                                // }, 5000);
                            }
                        }
                    };
                }
                break;
            case 'Activity_log':
                var activityLog = require('./activity_log').save_log(JSON.parse(data.data));
                break;
        }
    });

function updateNicknames(data){
    clientRedis.hgetall('user:'+data['username'], function(err, object) {
        // mog GMT viet nam +7
        if(object == null ) {
            var bonusUserOnline = require('./bonusUserOnline').update_gift_user_when_conect(data['username']);
        } else {
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
            var last_day = 0;
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
            if(day_now > last_day ){
              var bonusUserOnline = require('./bonusUserOnline').update_gift_user_when_conect(data['username']);
            }
        }
    });
}

    client.on('message', function(data) {
        //Assume the input msg is JSON structure {ChannelName:'channel-X',Message:'HI !!'}
        // remove_all_key ();
        var index = arrayObjectIndexOf(list_user, data.Message['username'], "username");
        client.nickname = data.Message;
        if(index == -1){
            list_user.push(client.nickname);
        }

        for (var i = update_offline.length -1 ; i >=0 ; i--){
            if(update_offline[i]['user'] == data.Message['username']){
                clearTimeout(update_offline[i]['timeout']);
                break;
            }
        }
        redisClient.subscribe('list member online');
        redisClient.subscribe('member offline');
        redisClient.subscribe(data.ChannelName);
        updateNicknames(data.Message);
        add_update_user(data.Message);
        get_user_online();
    });

    client.on('disconnect', function(data) {
        if(client.nickname){
            list_user.splice(arrayObjectIndexOf(list_user, client.nickname['username'], "username"), 1);
            updateUserLogout(client.nickname,redisClient);
        }
    });
});
