
function run () {
    var date = new Date();
    var time_zone = 7 * 60 *60 *1000;
    date = date.getTime() + time_zone;
    var _date = new Date(date);
    var hour = _date.getHours() ;
    var min = _date.getMinutes();
    if(hour == 24 || hour == 0 || hour == 2){
        var date_call = new Date();
        var time_call = date_call.getTime();
        var request = require("request");
        var accesstoken = (new Buffer(time_call.toString()).toString('base64'));
        request({
          uri: "http://aotusuma.tk/onlineGift",
          method: "POST"
        }, function(error, response, body) {
          // console.log(body);
        });
        var bonusUserOnline = require('./bonusUserOnline').get_user();
    }
}
var time_out = setInterval(function test(){
    run ();
},14400000);
