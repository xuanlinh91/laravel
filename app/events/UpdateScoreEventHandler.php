<?php 
class UpdateScoreEventHandler {
 
    CONST _EVENT = 'score.update';
    CONST CHANNEL = '';
 
    public function handle($data)
    {
        $redis = Redis::connection();
        $redis->publish($data['action'],json_encode($data));
    }
}