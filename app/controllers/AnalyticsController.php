<?php
use Shaphira\Common\Common;
class AnalyticsController extends BaseController{

    public function __construct () {
        // start time nho hon , end time lon hon
    }

    public function analytics_Google_view_all ($dimensions,$start_time,$end_time,$metrics) {
        $optParams = array("dimensions" => $dimensions);
        $site_id = Analytics::getSiteIdByUrl(Config::get('Common.analytics.google.url-site'));
        // echo $site_id;
        $stats=  Analytics::query($site_id, $start_time, $end_time, $metrics,$optParams)->rows;
        // logic
        return $stats;
    }
    public function analytics_gutlo ($start_time,$end_time) {

        $data = array();
        $redis = Redis::connection();
        $keys = $redis->keys('analytics:*');
        $count_keys = count($keys);
        for ($i = $count_keys - 1; $i >=0 ; $i--) {
            # code...
            $key = explode(':', $keys[$i]);
            if($key[1] >= $start_time && $key[1] <= $end_time ){
                $count = $redis->hgetall($keys[$i])['count'];
                $array = array('start_time' => $start_time ,'count' => $count,'end_time' => $end_time );
                echo $start_time;
                echo $end_time;
                echo 'timeeeeeeeeee';
                array_push($data,$array);
                break;
            }
        }
        $data = $this->SelectionSortDescending($data);
        return $data;
    }


    public function run_data_analytic_view_all() {
        $GMT = 7 * 60 * 60 ;
        $dimensions = "ga:date,ga:hour";
        $metrics = 'ga:users,ga:newUsers,ga:pageviews';
        $redis = Redis::connection();
        if($redis->exists('lastTimeUpdate') != 1 ) return array('google' => array(),'gutlo'=>array(),'path-view'=>array(),'data_analytics_post'=>array());
        $end_time = $redis->hgetall('lastTimeUpdate')['end_time'];
        $start_time = $redis->hgetall('lastTimeUpdate')['start_time'];
        $start_date_google = 'yesterday';
        $end_date_google = 'today';
        $analytics_google = $this->analytics_Google_view_all($dimensions,$start_date_google,$end_date_google,$metrics);
        $analytics_gutlo = $this->analytics_gutlo($start_time,$end_time);
        $length_google_analytic = COUNT($analytics_google);
        $data_google = array();

        $time_ga = 0;
        $day_ga = 0;
        $alpha_hours = date('H', ($end_time/1000) + $GMT);
        $alpha_day = date('d', ($end_time/1000) + $GMT);
        $alpha_min = date('i', ($end_time/1000) + $GMT);
        if($alpha_min == 0) {
            $time_ago = $end_time - ( 60 * 60 * 1000 );
            $time_ga = date('H', ($time_ago/1000) + $GMT);
            $day_ga = date('d', ($time_ago/1000) + $GMT);
        }
        else {
            $day_ga = $alpha_day;
            $time_ga = $alpha_hours;
        }
        for ($i = $length_google_analytic -1; $i >=0 ; $i--) {
            # code...
            $hour_google = $analytics_google[$i][1];
            if($time_ga ==  24 ) $time_ga = 00;
            $len_date_ga = strlen($analytics_google[$i][0]);
            if(($time_ga == $hour_google || $time_ga + 1  == $hour_google) && substr( $analytics_google[$i][0],$len_date_ga - 2,$len_date_ga) == $day_ga) {
                array_push($data_google,$analytics_google[$i]);
            }
        }

        $data = $this->set_cache($start_time,$end_time,$data_google[0][2],$data_google[0][4],$data_google[0][3],$analytics_gutlo);

        // $data_google[0][2] = $data['User'];
        // $data_google[0][3] = $data['NewUser'];
        // $data_google[0][4] = $data['PageView'];
        return $this->run_data_analytic_view_path($data_google,$analytics_gutlo);
    }

    public function set_cache ($start_time,$end_time,$user_google,$pageview_google,$newUser_google,$analytics_gutlo) {
        $redis = Redis::connection();
        $count_user = 0;
        $count_pageView = 0;
        if (Cache::has('gua')) {
            $data = Cache::get('gua');
            // save data analytic to redis before clear cache

            if($data['Start_time'] != $start_time && $data['End_time'] != $end_time ) {
                // User visit
                $data['count_user_online'] = $analytics_gutlo[0]['count'];
                $user_cache = $data['User'];
                $user_ga = $user_google;
                $count_user =  $user_ga - $user_cache;
                $data['User'] = $user_ga;
                // page view
                $pageView_cache = $data['PageView'];
                $pageView_ga = $pageview_google;
                $count_pageView =  $pageView_ga - $pageView_cache;
                $data['PageView'] = $pageView_ga;

                // new User
                $newUser_cache = $data['NewUser'];
                $newUser_ga = $newUser_google;
                $count_pageView =  $newUser_ga - $newUser_cache;
                $data['NewUser'] = $newUser_ga;

                Cache::forget('gua');
                Cache::forever('gua', $data);
            }else {
                // get user visit and page view to html
                $count_user = $data['User'];
                $count_pageView = $data['PageView'];
                $count_newUser = $data['NewUser'];
            }
        } else {
            // set user visit, page view , starttime , end time to cache
            $user_ga = $user_google;
            $count_user = $user_ga;
            $pageView_ga = $pageview_google;
            $count_pageView = $pageView_ga;
            $count_newUser = $newUser_google;
            $data = array('count_user_online'=> $analytics_gutlo[0]['count'],'User'=> $user_ga, 'PageView' => $pageView_ga,'NewUser'=>$count_newUser,'Start_time' =>$start_time,'End_time' => $end_time );
            Cache::forever('gua', $data);
        }
        return array('user'=> $count_user,'pageview' =>$count_pageView );
    }


    public function run_data_analytic_view_path($data_view_all_google,$analytics_gutlo){
        $GMT = 7 * 60 * 60 ;
        $dimensions = "ga:date,ga:hour,ga:pagePath";
        $metrics = 'ga:users,ga:newUsers,ga:pageviews';
        $redis = Redis::connection();
        if($redis->exists('lastTimeUpdate') != 1 ) return array('google' => array(),'gutlo'=>array(),'path-view'=>array(),'data_analytics_post'=>array());
        $end_time = $redis->hgetall('lastTimeUpdate')['end_time'] ;
        $start_time = $redis->hgetall('lastTimeUpdate')['start_time'] ;
        $start_date_google = 'yesterday';
        $end_date_google = 'today';
        $analytics_google = $this->analytics_Google_view_all($dimensions,$start_date_google,$end_date_google,$metrics);
        $length_google_analytic = COUNT($analytics_google);
        $data_google = array();

        $time_ga = 0;
        $day_ga = 0;
        $alpha_hours = date('H', ($end_time/1000) + $GMT);
        $alpha_day = date('d', ($end_time/1000) + $GMT);
        $alpha_min = date('i', ($end_time/1000) + $GMT);
        if($alpha_min == 0) {
            $time_ago = $end_time - ( 60 * 60 * 1000 );
            $time_ga = date('H', ($time_ago/1000) + $GMT);
            $day_ga = date('d', ($time_ago/1000) + $GMT);
        }
        else {
            $day_ga = $alpha_day;
            $time_ga = $alpha_hours;
        }
        for ($i = $length_google_analytic -1; $i >=0 ; $i--) {
            # code...
            $hour_google = $analytics_google[$i][1];
            if($time_ga ==  24 ) $time_ga = 00;
            $len_date_ga = strlen($analytics_google[$i][0]);
            if(($time_ga == $hour_google || $time_ga + 1 == $hour_google) && substr( $analytics_google[$i][0],$len_date_ga - 2,$len_date_ga) == $day_ga) {
                array_push($data_google,$analytics_google[$i]);
            }
        }

        $data = $this->set_cache_view_path($start_time,$end_time,$data_google);
        $data_analytics_post = $this->analytic_gutlo_post($start_time,$end_time);

        return array('google' => $data_view_all_google,'gutlo'=>$analytics_gutlo,'path-view'=>$data,'data_analytics_post'=>$data_analytics_post);
    }



    public function analytic_gutlo_post($start_time,$end_time){
        $start_time = date("Y-m-d H:i:s",$start_time/1000);
        $end_time = date("Y-m-d H:i:s",$end_time /1000);

        $GutloPosts = new GutloPosts();
        $GutloComment = new GutloComment();
        $GutloReply = new GutloReply();
        $GutloActivityLog = new GutloActivityLog();

        $posts = $GutloPosts->get_posts_ontime($start_time,$end_time);

        $comments = $GutloComment->get_comments_ontime($start_time,$end_time);

        $replies = $GutloReply->get_replies_ontime($start_time,$end_time);

        $log_activity = $GutloActivityLog->get_log_post_in_ontime($start_time,$end_time);

        $this->calculate_percent_comment($log_activity);

        $total_post = COUNT($posts);
        $total_comment = COUNT($comments);
        $total_reply = COUNT($replies);

        $data_log = array(
                'total_post' => $total_post
                , 'total_comment_all_post' =>$total_comment
                , 'total_reply_all_post'=> $total_reply
                , 'total_like_all_post'=> 0
                , 'total_brick_all_post'=> 0
                , 'analytics_post'=>array()
        );
        $length_log_activity = COUNT($log_activity);
        echo '<pre>';
        echo 'abc';
        print_r($log_activity);
        echo '</pre>';

        for($i = 0 ; $i < $length_log_activity; $i++){
            $length_data_log = COUNT($data_log['analytics_post']);
            $new_record = true;
            $post_id = 0; $fillter = false;

            if($log_activity[$i]->content_type == 0) {
                echo '234';
                $post_id = $log_activity[$i]->content_id;
                $fillter = $this->fillter_post_hot($post_id);
            }
            else if($log_activity[$i]->content_type == 1){
                $gutloComment = DB::table('gutlo_comment')->select('gutlo_comment.*')->where('id','=',$log_activity[$i]->content_id)->first();
                if(!empty($gutloComment)){
                    echo '123123123123';
                    $post_id = $gutloComment->to_post_id;
                    $fillter = $this->fillter_post_hot($gutloComment->to_post_id);
                }
            }else {
                $GutloReply = DB::table('gutlo_reply')->select('gutlo_reply.*')->where('id','=',$log_activity[$i]->content_id)->first();
                $gutloComment = DB::table('gutlo_comment')->select('gutlo_comment.*')->where('id','=',$GutloReply->comment_id)->first();
                if(!empty($gutloComment)){
                    echo '123123123123ssssssssss';
                    $post_id = $gutloComment->to_post_id;
                    $fillter = $this->fillter_post_hot($gutloComment->to_post_id);
                }
            }
            echo '<br>';
            echo 'post iddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd';
            echo $post_id;
            echo '<br>';
            if($fillter) {
                // chay tinh hot comment
                switch($log_activity[$i]->activity_types_id) {
                    case 4:
                    case '4':
                    case 5:
                    case '5':
                    case 6:
                    case '6':
                        for($j = 0 ; $j <$length_data_log ; $j++) {
                            if(isset($data_log['analytics_post'][$j]) && is_array($data_log['analytics_post'][$j])) {
                                if($data_log['analytics_post'][$j]['post_id'] == $post_id){

                                    switch ($log_activity[$i]->content_type) {
                                        case 0:
                                        case '0':
                                            $data_log['analytics_post'][$j]['total_like'] = $data_log['analytics_post'][$j]['total_like'] + 1;
                                            break;
                                        case 1:
                                        case '1':
                                            $data_log['analytics_post'][$j]['total_like_comment'] = $data_log['analytics_post'][$j]['total_like_comment'] + 1;
                                            break;
                                        case 2:
                                        case '2':
                                            $data_log['analytics_post'][$j]['total_like_reply'] = $data_log['analytics_post'][$j]['total_like_reply'] + 1;
                                            break;
                                    }
                                    $new_record = false;
                                    break;
                                }
                            }
                        }
                        if($new_record){
                            $total_like = 0;
                            $total_like_comment = 0 ;
                            $total_like_reply = 0 ;
                            switch ($log_activity[$i]->content_type) {
                                case 0:
                                case '0':
                                    $total_like = 1;
                                    break;
                                case 1:
                                case '1':
                                    $total_like_comment = 1;
                                    break;
                                case 2:
                                case '2':
                                    $total_like_reply = 1;
                                    break;
                            }
                            $new_array = array(
                                    'post_id' => $post_id
                                    , 'total_like' => $total_like
                                    , 'total_brick'=> 0
                                    , 'total_comment' => 0
                                    , 'total_reply' => 0
                                    , 'total_like_comment' => $total_like_comment
                                    , 'total_like_reply'    => $total_like_reply
                                    , 'total_brick_comment' => 0
                                    , 'total_brick_reply'    => 0
                            );
                            array_push($data_log['analytics_post'],$new_array);
                        }
                        break;
                    // unLike
                    case 21:
                    case '21':
                    case 22:
                    case '22':
                    case 23:
                    case '23':
                        for($j = 0 ; $j <$length_data_log ; $j++) {
                            if(isset($data_log['analytics_post'][$j]) && is_array($data_log['analytics_post'][$j])) {
                                if($data_log['analytics_post'][$j]['post_id'] == $post_id){
                                    switch ($log_activity[$i]->content_type) {
                                        case 0:
                                        case '0':
                                            $data_log['analytics_post'][$j]['total_like'] = $data_log['analytics_post'][$j]['total_like'] - 1;
                                            break;
                                        case 1:
                                        case '1':
                                            $data_log['analytics_post'][$j]['total_like_comment'] = $data_log['analytics_post'][$j]['total_like_comment'] - 1;
                                            break;
                                        case 2:
                                        case '2':
                                            $data_log['analytics_post'][$j]['total_like_reply'] = $data_log['analytics_post'][$j]['total_like_reply'] - 1;
                                            break;
                                    }
                                    $new_record = false;
                                    break;
                                }
                            }
                        }
                        if($new_record){
                            $new_array = array(
                                        'post_id' => $post_id
                                        , 'total_like' => 0
                                        , 'total_brick'=> 0
                                        , 'total_comment' => 0
                                        , 'total_reply' => 0
                                        , 'total_like_comment' => 0
                                        , 'total_like_reply'    => 0
                                        , 'total_brick_comment' => 0
                                        , 'total_brick_reply'    => 0
                            );
                            array_push($data_log['analytics_post'],$new_array);
                        }
                        break;
                    // brick
                    case 7:
                    case '7':
                    case 8:
                    case '8':
                    case 9:
                    case '9':
                        for($j = 0 ; $j <$length_data_log ; $j++) {
                            if(isset($data_log['analytics_post'][$j]) && is_array($data_log['analytics_post'][$j])) {
                                if($data_log['analytics_post'][$j]['post_id'] == $post_id){
                                    switch ($log_activity[$i]->content_type) {
                                        case 0:
                                        case '0':
                                            $data_log['analytics_post'][$j]['total_brick'] = $data_log['analytics_post'][$j]['total_brick'] + 1;
                                            break;
                                        case 1:
                                        case '1':
                                            $data_log['analytics_post'][$j]['total_brick_comment'] = $data_log['analytics_post'][$j]['total_brick_comment'] + 1;
                                            break;
                                        case 2:
                                        case '2':
                                            $data_log['analytics_post'][$j]['total_brick_reply'] = $data_log['analytics_post'][$j]['total_brick_reply'] + 1;
                                            break;
                                    }
                                    $new_record = false;
                                    break;
                                }
                            }
                        }
                        if($new_record){
                            $total_brick = 0;
                            $total_brick_comment = 0 ;
                            $total_brick_reply = 0 ;
                            switch ($log_activity[$i]->content_type) {
                                case 0:
                                case '0':
                                    $total_brick = 1;
                                    break;
                                case 1:
                                case '1':
                                    $total_brick_comment = 1;
                                    break;
                                case 2:
                                case '2':
                                    $total_brick_reply = 1;
                                    break;
                            }
                            $new_array = array(
                                    'post_id' => $post_id
                                    , 'total_like' => 0
                                    , 'total_brick'=> $total_brick
                                    , 'total_comment' => 0
                                    , 'total_reply' => 0
                                    , 'total_like_comment' => 0
                                    , 'total_like_reply'    => 0
                                    , 'total_brick_comment' => $total_brick_comment
                                    , 'total_brick_reply'    => $total_brick_reply
                            );
                            array_push($data_log['analytics_post'],$new_array);
                        }
                        break;
                    // unBrick
                    case 24:
                    case '24':
                    case 25:
                    case '25':
                    case 26:
                    case '26':
                        for($j = 0 ; $j <$length_data_log ; $j++) {
                            if(isset($data_log['analytics_post'][$j]) && is_array($data_log['analytics_post'][$j])) {
                                if($data_log['analytics_post'][$j]['post_id'] == $post_id){
                                    switch ($log_activity[$i]->content_type) {
                                        case 0:
                                        case '0':
                                            $data_log['analytics_post'][$j]['total_brick'] = $data_log['analytics_post'][$j]['total_brick'] - 1;
                                            break;
                                        case 1:
                                        case '1':
                                            $data_log['analytics_post'][$j]['total_brick_comment'] = $data_log['analytics_post'][$j]['total_brick_comment'] - 1;
                                            break;
                                        case 2:
                                        case '2':
                                            $data_log['analytics_post'][$j]['total_brick_reply'] = $data_log['analytics_post'][$j]['total_brick_reply'] - 1;
                                            break;
                                    }
                                    $new_record = false;
                                    break;
                                }
                            }
                        }
                        if($new_record){
                            $new_array = array(
                                        'post_id' => $post_id
                                        , 'total_like' => 0
                                        , 'total_brick'=> 0
                                        , 'total_comment' => 0
                                        , 'total_reply' => 0
                                        , 'total_like_comment' => 0
                                        , 'total_like_reply'    => 0
                                        , 'total_brick_comment' => 0
                                        , 'total_brick_reply'    => 0
                            );
                            array_push($data_log['analytics_post'],$new_array);
                        }
                        break;
                }
            }
        }
        $length_data_log = COUNT($data_log['analytics_post']);
        $total_like_post = 0;
        $total_brick_post = 0;
        $total_like_comment = 0;
        $total_brick_comment = 0;
        $total_like_reply = 0;
        $total_brick_reply = 0;
        // laays ra tong co comment va reply trong 1 post
        $total_comment_post = 0 ;


        $total_all = 0;
        $length_data_log = COUNT($data_log['analytics_post']);

        for($j = 0 ; $j <$length_data_log ; $j++) {
            $total_all_comment = 0;
            $total_all_reply = 0;
            $total_all_like_post = 0;
            $total_all_brick_post = 0;
            $total_all_like_comment = 0;
            $total_all_brick_comment = 0;
            $total_all_like_reply = 0;
            $total_all_brick_reply = 0;

            $total_like_post = $total_like_post + $data_log['analytics_post'][$j]['total_like'];
            $total_brick_post = $total_brick_post + $data_log['analytics_post'][$j]['total_brick'];
            $total_like_comment = $total_like_comment + $data_log['analytics_post'][$j]['total_like_comment'];
            $total_brick_comment = $total_brick_comment + $data_log['analytics_post'][$j]['total_brick_comment'];
            $total_like_reply = $total_like_reply + $data_log['analytics_post'][$j]['total_like_reply'];
            $total_brick_reply = $total_brick_reply + $data_log['analytics_post'][$j]['total_brick_reply'];

            $_post = DB::table('gutlo_posts')->select('id','total_comment','total_like','total_brick')->where('id','=',$data_log['analytics_post'][$j]['post_id'])->first();
            $total_all_comment = $total_all_comment + $_post->total_comment;
            $total_all_like_post = $total_all_comment + $_post->total_like;
            $total_all_brick_post = $total_all_comment + $_post->total_brick;

            $_comments = DB::table('gutlo_comment')->select(DB::raw('SUM(total_reply) as total_reply, SUM(total_like) as total_like, SUM(total_brick) as total_brick '))->where('to_post_id','=',$post->id)->first();
            $total_all_reply = $total_all_reply + $_comments->total_reply;
            $total_all_like_comment = $total_all_like_comment + $_comments->total_like;
            $total_all_brick_comment = $total_all_brick_comment + $_comments->total_brick;

            $_reply = DB::table('gutlo_comment')->select(DB::raw('SUM(total_reply) as total_reply, SUM(total_like) as total_like, SUM(total_brick) as total_brick '))->where('to_post_id','=',$post->id)->first();
            $total_all_like_reply = $total_all_like_reply + $_reply->total_like;
            $total_all_brick_reply = $total_all_brick_reply + $_reply->total_brick;

            $total = $total_all_comment + $total_all_reply + $total_all_like_post + $total_all_brick_post + $total_all_like_comment
                        + $total_all_brick_comment + $total_all_like_reply + $total_all_brick_reply ;
            // lay ra tong so hanh dong cua bai post duoc nhac toi trong 15 phut
            $data_log['analytics_post'][$j]['total_all_of_post'] = $total;
            $total_all = $total_all + $total;
        }
        // lay ra tong so hanh dong cua tat ca bai viet duoc nhac toi trong 15 phut
        $data_log['analytics_post']['total_all'] = $total_all;

        for($c = 0 ; $c < $total_comment ; $c++){
            $new_row = true;
            $length_data_log = COUNT($data_log['analytics_post']);
            for($j = 0 ; $j <$length_data_log ; $j++) {

                if($comments[$c]->post_id == $data_log['analytics_post'][$j]['post_id']){
                    $new_row = false;
                    $data_log['analytics_post'][$j]['total_comment'] = $data_log['analytics_post'][$j]['total_comment'] + 1;
                    break;
                }
            }
            if($new_row){
                $new_array = array(
                            'post_id' => $comments[$c]->post_id
                            , 'total_like' => 0
                            , 'total_brick'=> 0
                            , 'total_comment' => 1
                            , 'total_reply' => 0
                            , 'total_like_comment' => 0
                            , 'total_like_reply'    => 0
                            , 'total_brick_comment' => 0
                            , 'total_brick_reply'    => 0
                );
                array_push($data_log['analytics_post'],$new_array);
            }
        }

        $total_reply_post = 0 ;
        for($r = 0 ; $r < $total_reply ; $r++){
            $new_row = true;
            $length_data_log = COUNT($data_log['analytics_post']);
            for($j = 0 ; $j <$length_data_log ; $j++) {
                if($replies[$r]->post_id == $data_log['analytics_post'][$j]['post_id']){
                    $new_row = false;
                    $data_log['analytics_post'][$j]['total_reply'] = $data_log['analytics_post'][$j]['total_reply'] + 1;
                    break;
                }
            }
            if($new_row){
                $new_array = array(
                            'post_id' => $replies[$r]->post_id
                            , 'total_like' => 0
                            , 'total_brick'=> 0
                            , 'total_comment' => 0
                            , 'total_reply' => 1
                            , 'total_like_comment' => 0
                            , 'total_like_reply'    => 0
                            , 'total_brick_comment' => 0
                            , 'total_brick_reply'    => 0
                );
                array_push($data_log['analytics_post'],$new_array);
            }
        }


        $data_log['total_like_all_post'] = $total_like_post;
        $data_log['total_brick_all_post'] = $total_brick_post;
        $data_log['total_like_comment_all_post'] = $total_like_comment;
        $data_log['total_brick_comment_all_post'] = $total_brick_comment;
        $data_log['total_like_reply_all_post'] = $total_like_reply;
        $data_log['total_brick_reply_all_post'] = $total_brick_reply;
        $data_cache = Cache::get('gua');
        $data_cache['total_post'] = $data_log['total_post'];
        $data_cache['total_comment_all_post'] = $data_log['total_comment_all_post'];
        $data_cache['total_reply_all_post'] = $data_log['total_reply_all_post'];
        $data_cache['total_like_all_post'] = $data_log['total_like_all_post'];
        $data_cache['total_brick_all_post'] = $data_log['total_brick_all_post'];
        $data_cache['total_like_comment_all_post'] = $data_log['total_like_comment_all_post'];
        $data_cache['total_brick_comment_all_post'] = $data_log['total_brick_comment_all_post'];
        $data_cache['total_like_reply_all_post'] = $data_log['total_like_reply_all_post'];
        $data_cache['total_brick_reply_all_post'] = $data_log['total_brick_reply_all_post'];

        $length_cache_view_path = count($data_cache['viewPath']);
        $length_post = count($data_log['analytics_post']);

        for ($i = $length_cache_view_path - 1; $i >= 0 ; $i--) {
            # code...
            $data_cache['viewPath'][$i]['total_comment'] = 0;
            $data_cache['viewPath'][$i]['total_reply'] = 0;
            $data_cache['viewPath'][$i]['total_brick'] = 0;
            $data_cache['viewPath'][$i]['total_like'] = 0;
            $data_cache['viewPath'][$i]['total_like_comment'] = 0;
            $data_cache['viewPath'][$i]['total_like_reply'] = 0;
            $data_cache['viewPath'][$i]['total_brick_comment'] = 0;
            $data_cache['viewPath'][$i]['total_brick_reply'] = 0;

            for ($j = $length_post - 1; $j >= 0 ; $j--) {
                $path = '/posts/'.$data_log['analytics_post'][$j]['post_id'];

                if($path == $data_cache['viewPath'][$i]['path']) {
                    $data_cache['viewPath'][$i]['total_comment'] = $data_log['analytics_post'][$j]['total_comment'];
                    $data_cache['viewPath'][$i]['total_reply'] = $data_log['analytics_post'][$j]['total_reply'];
                    $data_cache['viewPath'][$i]['total_brick'] = $data_log['analytics_post'][$j]['total_brick'];
                    $data_cache['viewPath'][$i]['total_like'] = $data_log['analytics_post'][$j]['total_like'];
                    $data_cache['viewPath'][$i]['total_brick_comment'] = $data_log['analytics_post'][$j]['total_brick_comment'];
                    $data_cache['viewPath'][$i]['total_like_comment'] = $data_log['analytics_post'][$j]['total_like_comment'];
                    $data_cache['viewPath'][$i]['total_brick_reply'] = $data_log['analytics_post'][$j]['total_brick_reply'];
                    $data_cache['viewPath'][$i]['total_like_reply'] = $data_log['analytics_post'][$j]['total_like_reply'];

                }
            }
        }
        echo '<pre>';
        print_r($data_log);
        echo '</pre>';
        $data_log = $this->calculate_percent_post($data_log);
        Cache::forget('gua');
        Cache::forever('gua', $data_cache);
        $redis = Redis::connection();
        $redis->hmset('gua:'.$data_cache['Start_time'].':'.$data_cache['End_time'],'data',json_encode($data_cache));

        //hot hashtag

        $this->calculate_percent_hashtag($data_log['analytics_post']);
        return $data_log;
    }
    public function calculate_percent_post($data_log) {
        $all_post = $data_log['total_post'];
        $total_comment_all_post = $data_log['total_comment_all_post'];
        $total_reply_all_post = $data_log['total_reply_all_post'];
        $total_like_all_post = $data_log['total_like_all_post'];
        $total_brick_all_post = $data_log['total_brick_all_post'];
        $total_like_comment_all_post = $data_log['total_like_comment_all_post'];
        $total_brick_comment_all_post = $data_log['total_brick_comment_all_post'];
        $total_like_reply_all_post = $data_log['total_like_reply_all_post'];
        $total_brick_reply_all_post = $data_log['total_brick_reply_all_post'];
        $total_all = $data_log['analytics_post']['total_all'] ;
        $length_data_log = count($data_log['analytics_post']);

        $coe_User = 0; $coe_PageView = 0; $coe_NewUser = 0; $coe_comment = 10;
        $coe_reply = 6; $coe_brick = 10; $coe_like = 10; $coe_point = 0;$coe_InterestLevel = 20;
        $coe_brick_comment = 4; $coe_like_comment = 4;$coe_brick_reply = 3; $coe_like_reply = 3;
        $coe_all = 10;
        $rate_mod = 20;
        $all_user = DB::table('users')->count();

        for ($i = $length_data_log -1 ; $i >= 0 ; $i--) {
            $percent_total_comment = 0; $percent_total_reply = 0; $percent_total_brick = 0;
            $percent_total_like = 0;$percent_total_point = 0;$percent_total_brick_comment = 0;
            $percent_total_like_comment = 0;$percent_total_brick_reply = 0;
            $percent_total_like_reply = 0;$percent_total_all = 0;

            $total_all_of_post = $data_log['analytics_post'][$i]['total_all_of_post'];
            $total_comment_in_time = $data_log['analytics_post'][$i]['total_comment'];
            $total_reply_in_time = $data_log['analytics_post'][$i]['total_reply'];
            $total_brick_in_time = $data_log['analytics_post'][$i]['total_brick'];
            $total_like_in_time = $data_log['analytics_post'][$i]['total_like'];
            $total_brick_comment_in_time = $data_log['analytics_post'][$i]['total_brick_comment'];
            $total_like_comment_in_time = $data_log['analytics_post'][$i]['total_like_comment'];
            $total_brick_reply_in_time = $data_log['analytics_post'][$i]['total_brick_reply'];
            $total_like_reply_in_time = $data_log['analytics_post'][$i]['total_like_reply'];
            echo '<pre>';
            echo 'data';
            print_r($data_log['analytics_post']);
            echo '</pre>';
            $id_post = $data_log['analytics_post'][$i]['post_id'];
            $total_brick_like_in_time = $total_like_in_time + $total_brick_in_time;
            $GutloPosts = GutloPosts::find($id_post);
            if(!empty($GutloPosts)) {

                $all_brick = $GutloPosts->total_brick;
                $all_like = $GutloPosts->total_like;
                $all_brick_like = $all_like + $all_brick;
                $total_point = $GutloPosts->total_point;
                $point_in_time = $total_like_in_time - $total_brick_in_time;
                // Tính mức độ quan tâm
                $a = $all_user;
                $b = $all_brick_like;
                $c = $total_brick_like_in_time;
                $n = Config::get('Common.fillter_hot_post.coefficient_of_interest');
                if($a != $b) {
                    $d = $b - $c;
                    if($d == 0 ) $d = $all_brick_like;
                    if($d <= 0) $d = 1;
                    $e = $a - $b;
                    $x = $d / $a;
                    if($e == 0 ) $e = 1;
                    $y = $c / $e;
                    $z = $x * $coe_InterestLevel;
                    $n = $y * $z;
                }
                $InterestLevel = $n;
                if($InterestLevel === 0) $InterestLevel = 1;
                echo $a.'a===='.$b.'b===='.$c.'c==='.$d.'d==='.$e.'e===='.$x.'x====='.$y.'y===='.$z.'z====n'.$n;
                echo '<br>';
                // Tính tỷ lệ các hành động của user đối với bài post;
                if($total_all != 0 ) $percent_total_all = ( $total_all_of_post / $total_all ) * 100;
                if($total_comment_all_post != 0 ) $percent_total_comment = ( $total_comment_in_time / $total_comment_all_post ) * 100;
                if($total_reply_all_post!= 0 ) $percent_total_reply = ( $total_reply_in_time / $total_reply_all_post ) * 100;
                if($total_brick_all_post != 0 ) $percent_total_brick = ( $total_brick_in_time / $total_brick_all_post ) * 100;
                if($total_like_all_post != 0 )  $percent_total_like = ( $total_like_in_time / $total_like_all_post ) * 100;
                if($total_brick_comment_all_post != 0 ) $percent_total_brick_comment = ( $total_brick_comment_in_time / $total_brick_comment_all_post ) * 100;
                if($total_like_comment_all_post != 0 )  $percent_total_like_comment = ( $total_like_comment_in_time / $total_like_comment_all_post ) * 100;
                if($total_brick_reply_all_post != 0 ) $percent_total_brick_reply = ( $total_brick_reply_in_time / $total_brick_reply_all_post ) * 100;
                if($total_like_reply_all_post != 0 )  $percent_total_like_reply = ( $total_like_reply_in_time / $total_like_reply_all_post ) * 100;
                if($total_point != 0 ) $percent_total_point = ($point_in_time / $total_point ) * 100;
                echo $total_like_comment_all_post.' total_like_comment_all_post//////' . $total_like_comment_in_time .' total_like_comment_in_time';
                echo '<br>';
                echo $total_like_in_time .'total_like_in_time////'.$total_like_all_post .' total_like_all_post===== '.$percent_total_like;
                echo 'percent_total_like/////' .round($percent_total_like,5). ' ////'.$coe_like ;
                echo '<br>';
                $rate_all_action = ( round($percent_total_all,5) * $coe_all )/100;
                $rate_user = $coe_User;
                $rate_PageView = $coe_PageView;
                $rate_NewUser = $coe_NewUser;
                $rate_comment = ( round($percent_total_comment,5) * $coe_comment )/100;
                $rate_reply =   ( round($percent_total_reply,5) * $coe_reply )/100;
                $rate_brick =   ( round($percent_total_brick,5) * $coe_brick )/100;
                $rate_like =    ( round($percent_total_like,5) * $coe_like )/100;
                $rate_brick_comment =   ( round($percent_total_brick_comment,5) * $coe_brick_comment )/100;
                $rate_like_comment =    ( round($percent_total_like_comment,5) * $coe_like_comment )/100;
                $rate_brick_reply =   ( round($percent_total_brick_reply,5) * $coe_brick_reply )/100;
                $rate_like_reply =    ( round($percent_total_like_reply,5) * $coe_like_reply )/100;
                $rate_point =   ( round($percent_total_point,5) * $coe_point )/100;

                $data_log['analytics_post'][$i]['InterestLevel'] =  $InterestLevel ;
                $data_log['analytics_post'][$i]['rate_user'] =  $coe_User ;
                $data_log['analytics_post'][$i]['rate_PageView'] =  $coe_PageView ;
                $data_log['analytics_post'][$i]['rate_NewUser'] =  $coe_NewUser ;
                $data_log['analytics_post'][$i]['rate_comment'] = $rate_comment ;
                $data_log['analytics_post'][$i]['rate_reply'] = $rate_reply ;
                $data_log['analytics_post'][$i]['rate_brick'] = $rate_brick ;
                $data_log['analytics_post'][$i]['rate_like'] = $rate_like ;
                $data_log['analytics_post'][$i]['rate_brick_comment'] = $rate_brick_comment ;
                $data_log['analytics_post'][$i]['rate_like_comment'] = $rate_like_comment ;
                $data_log['analytics_post'][$i]['rate_brick_reply'] = $rate_brick_reply ;
                $data_log['analytics_post'][$i]['rate_like_reply'] = $rate_like_reply ;
                $data_log['analytics_post'][$i]['rate_point'] = $rate_point ;
                $data_log['analytics_post'][$i]['rate_all_action'] = $rate_all_action ;


                echo $data_log['analytics_post'][$i]['post_id'].'post_id======='. $rate_user .' user-- '. $rate_PageView.' pageview-- '. $rate_NewUser.' newuser-- '. $rate_comment.' comment-- '. $rate_reply.' reply-- '
                . $rate_brick.' brick-- '. $rate_like.' like-- '.$rate_brick_comment.' brick_comment-- '. $rate_like_comment.' like_comment-- '.
                $rate_brick_reply.' brick_reply-- '. $rate_like_reply.' like_reply-- '. $rate_point.' point-- InterestLevel'. $InterestLevel.' ========== rate_all_action' . $rate_all_action;

                $rate = $rate_user
                        + $rate_PageView
                        + $rate_NewUser
                        + $rate_comment
                        + $rate_reply
                        + $rate_brick
                        + $rate_like
                        + $rate_brick_comment
                        + $rate_like_comment
                        + $rate_brick_reply
                        + $rate_like_reply
                        + $rate_point
                        + $InterestLevel
                        + $rate_all_action;
                $GutloPosts->rate = $rate;
                  echo '/////'. $rate;
                echo '<br>';
                $GutloPosts->count_update = $GutloPosts->count_update + 1;
                $GutloPosts->save();

                if($rate >= Config::get('Common.fillter_hot_post.min_rate_hot') ) {
                    $hotPost = GutloHotPosts::where('id_post','=',$id_post)->first();
                    if(empty($hotPost)){
                        // echo 'them moi vao hot';
                        $GutloHotPosts = new GutloHotPosts();
                        $GutloHotPosts->id_post = $id_post;
                        $GutloHotPosts->created_time = \Carbon\Carbon::now()->toDateTimeString();
                        $GutloHotPosts->pre_rate = 0;
                        $GutloHotPosts->now_rate = $rate;
                        $GutloHotPosts->count_update =  1;
                        $GutloHotPosts->save();
                    }else {
                        $GutloHotPosts = GutloHotPosts::find($hotPost->id);
                        $GutloHotPosts->id_post = $id_post;
                        $GutloHotPosts->created_time = \Carbon\Carbon::now()->toDateTimeString();
                        $GutloHotPosts->pre_rate = $GutloHotPosts->now_rate;
                        $GutloHotPosts->now_rate = $rate;
                        $GutloHotPosts->count_update =  $GutloHotPosts->count_update + 1;
                        $GutloHotPosts->save();
                    }
                }
            }
        }

        return $data_log;
    }
    function fillter_post_hot($post_id) {
        $user = new User();
        $GutloPosts = GutloPosts::find($post_id);
        if(empty($GutloPosts)) return false;
        $user_action = $user->get_user_like_post($GutloPosts->id);
        $GutloComment_count = new GutloComment();
        $count_comment = $GutloComment_count->get_total_comment($post_id);
        $min_comment = Config::get('Common.fillter_hot_post.min_comment');
        $min_user = Config::get('Common.fillter_hot_post.min_user');
        $total_like_brick = $GutloPosts->total_like + $GutloPosts->total_brick;
        if( $GutloPosts->hashtag_id != null && $GutloPosts->hashtag_id != '' && COUNT($user_action) >= $min_user && $count_comment >= $min_comment && $total_like_brick >= Config::get('Common.fillter_hot_post.min_like_brick')){
            return true;
        }
        else return false;
    }
    public function set_cache_view_path ($start_time,$end_time,$data_google) {

        $redis = Redis::connection();
        $count_user = 0;
        $count_pageView = 0;
        $data = Cache::get('gua');
        $array = array();
        $length_data_google = COUNT($data_google);
        for ($i = 0; $i < $length_data_google  ; $i++) {

            if(COUNT(explode('?', $data_google[$i][2])) == 1){
                array_push($array,array('path'=>$data_google[$i][2],'User'=>$data_google[$i][3],'PageView' => $data_google[$i][5],'NewUser' => $data_google[$i][4],'User_Notifi'=>0,'PageView_Notifi'=>0,'NewUser_Notifi'=>0));
            }
        }
        $data_path_level2 = $this->run_data_analytic_view_path_level2();
        $length_data_path_lv2 = COUNT($data_path_level2);
        $length_array = COUNT($array);

        for ($i = 0; $i < $length_data_path_lv2  ; $i++) {
            $new_row = true;
            $path = '/posts'.explode('?',$data_path_level2[$i][2])[0];
            for ($j = 0; $j < $length_array  ; $j++) {
                // $path = explode('?',$data_path_level2[$i][2])[0];
                if($path == $array[$j]['path'] && $data_path_level2[$i][3] == '/posts/'){
                    $new_row = false;
                    $array[$j]['User_Notifi'] = $data_path_level2[$i][4];
                    $array[$j]['PageView_Notifi'] = $data_path_level2[$i][6];
                    $array[$j]['NewUser_Notifi'] = $data_path_level2[$i][5];
                }else if($data_path_level2[$i][3] != '/posts/') $new_row = false;
            }
            if($new_row){
                array_push($array,array('path'=>$path,'User'=>0,'PageView' => 0,'NewUser' => 0,'User_Notifi'=>$data_path_level2[$i][4],'PageView_Notifi'=>$data_path_level2[$i][6],'NewUser_Notifi'=>$data_path_level2[$i][5]));
            }
        }
        // save data analytic to redis before clear cache

        if($data['Start_time'] != $start_time && $data['End_time'] != $end_time ) {

            // User visit
            if(!isset( $data['viewPath'] )) {
                // not set data view path to cache
                $length_data_array = COUNT($array);
                for ($i = 0; $i < $length_data_array  ; $i++) {
                    $length_data_cache = COUNT($data['viewPath']);
                    for ($j = 0; $j < $length_data_cache; $j++) {
                        $user = $data['viewPath'][$j]['User'];
                        $User_Notifi = $data['viewPath'][$j]['User_Notifi'];
                        $PageView = $data['PageView'][$j]['PageView'];
                        $PageView_Notifi = $data['PageView'][$j]['PageView_Notifi'];
                        $NewUser_Notifi = $data['PageView'][$j]['NewUser_Notifi'];
                        $NewUser = $data['PageView'][$j]['NewUser'];
                        $path = $data['PageView'][$j]['path'];
                        $new_path = true ;

                       if( $array[$i]['path'] == $path ){
                            $new_path = false;
                            $ga_user = $array[$i]['User'];
                            $ga_newUser = $array[$i]['NewUser'];
                            $ga_PageView = $array[$i]['PageView'];
                            $ga_user_Notifi = $array[$i]['User_Notifi'];
                            $ga_PageView_Notifi = $array[$i]['PageView_Notifi'];
                            $ga_NewUser_Notifi = $arra[$i]['NewUser_Notifi'];

                            $data_user = $ga_user - $user;
                            $data['viewPath'][$j]['User'] = $data_user;

                            $data_PageView = $ga_PageView - $PageView;
                            $data['viewPath'][$j]['PageView'] = $data_PageView;

                            $data_user_Notifi = $ga_user_Notifi - $User_Notifi;
                            $data['viewPath'][$j]['User_Notifi'] = $data_PageView;

                            $data_PageView_Notifi = $ga_PageView_Notifi - $PageView_Notifi;
                            $data['viewPath'][$j]['PageView_Notifi'] = $data_PageView;

                            $data_NewUser_Notifi = $ga_NewUser_Notifi - $NewUser_Notifi;
                            $data['viewPath'][$j]['NewUser_Notifi'] = $data_PageView;

                            // $data_NewUser = $ga_newUser - $NewUser;
                            // $data['viewPath'][$j]['NewUser'] = $data_NewUser;
                       }
                    }
                    if($new_path){
                        array_push($data['viewPath'],$array[$i]);
                    }
                }
            }else {
                $data['viewPath'] = $array;
            }

            // start time
            $data['Start_time'] = $start_time;
            // end time
            $data['End_time'] = $end_time;
            Cache::forget('gua');
            Cache::forever('gua', $data);
        } else {
            if(!isset( $data['viewPath'] )){
                $data['viewPath'] = $array;
                Cache::forget('gua');
                Cache::forever('gua', $data);
            }
        }
        return $data;
    }

    public function run_data_analytic_view_path_level2(){
        $GMT = 7 * 60 * 60 ;
        $dimensions = "ga:date,ga:hour,ga:pagePathLevel2,ga:pagePathLevel1";
        $metrics = 'ga:users,ga:newUsers,ga:pageviews';
        $redis = Redis::connection();
        if($redis->exists('lastTimeUpdate') != 1 ) return array('google' => array(),'gutlo'=>array(),'path-view'=>array(),'data_analytics_post'=>array());
        $end_time = $redis->hgetall('lastTimeUpdate')['end_time'];
        $start_time = $redis->hgetall('lastTimeUpdate')['start_time'];
        $start_date_google = 'yesterday';
        $end_date_google = 'today';
        $analytics_google = $this->analytics_Google_view_all($dimensions,$start_date_google,$end_date_google,$metrics);
        $length_google_analytic = COUNT($analytics_google);
        $data_google = array();

        $time_ga = 0;
        $day_ga = 0;
        $alpha_hours = date('H', ($end_time/1000) + $GMT);
        $alpha_day = date('d', ($end_time/1000) + $GMT);
        $alpha_min = date('i', ($end_time/1000) + $GMT);
        if($alpha_min == 0) {
            $time_ago = $end_time - ( 60 * 60 * 1000 );
            $time_ga = date('H', ($time_ago/1000) + $GMT);
            $day_ga = date('d', ($time_ago/1000) + $GMT);
        }
        else {
            $day_ga = $alpha_day;
            $time_ga = $alpha_hours;
        }
        for ($i = $length_google_analytic -1; $i >=0 ; $i--) {
            # code...
            $hour_google = $analytics_google[$i][1];
            if($time_ga ==  24 ) $time_ga = 00;
            $len_date_ga = strlen($analytics_google[$i][0]);
            if($time_ga == $hour_google && substr( $analytics_google[$i][0],$len_date_ga - 2,$len_date_ga) == $day_ga ) {
                array_push($data_google,$analytics_google[$i]);
            }
        }

        return $data_google;
    }



    public function SelectionSortByDateTime_ASC($array1)
    {
        // dem tong so phan tu cua mang
        $length = count($array1);
        // for de sap xep mang
        for ($i = 0; $i < $length - 1; $i++)
        {
            // tim vi tri nho nhat theo tung for
            $min = $i;
            for ($j = $i + 1; $j < $length; $j++){
                $date =  $array1[$j]['time'];
                $date_2 =  $array1[$min]['time'];
                if ($date < $date_2){
                    $min = $j;
                }
            }
            // sau khi tim dc min thi hoan vi voi i
            // voi vi tri thu $i
            $temp = $array1[$i];
            $array1[$i] = $array1[$min];
            $array1[$min] = $temp;
        }
        return $array1;
    }

    public function SelectionSortDescending($array1)
    {

        // // loi khong tim thay index time
        // // dem tong so phan tu cua mang
        // $length = count($array1);
        // // for de sap xep mang
        // for ($i = 0; $i < $length - 1; $i++)
        // {
        //     // tim vi tri lon nhat theo tung for
        //     $max = $i;
        //     for ($j = $i + 1; $j < $length; $j++){
        //         $date =  $array1[$j]['time'];
        //         $date_2 =  $array1[$max]['time'];
        //         if ($date > $date_2){
        //             $max = $j;
        //         }
        //     }
        //     // sau khi tim dc max thi hoan vi voi i
        //     // voi vi tri thu $i
        //     $temp = $array1[$i];
        //     $array1[$i] = $array1[$max];
        //     $array1[$max] = $temp;
        // }
        return $array1;
    }

    public function open_beta_data_post_hot () {
        $posts = DB::table('gutlo_posts')->select('id')->whereNull('deleted_time')->get();
        $length_posts = COUNT($posts);
        $rate = 25;
        for ($i=$length_posts-1; $i >= 0 ; $i--) {
            # code...
            $fillter = $this->fillter_open_beta_data_post_hot($posts[$i]->id);
            if($fillter){
                $GutloPosts = GutloPosts::find($posts[$i]->id);
                $GutloPosts->rate = $rate;
                $GutloPosts->count_update = $GutloPosts->count_update + 1;
                $GutloPosts->save();

                $GutloHotPosts = new GutloHotPosts();
                $GutloHotPosts->id_post = $posts[$i]->id;
                $GutloHotPosts->created_time = \Carbon\Carbon::now()->toDateTimeString();
                $GutloHotPosts->pre_rate = 0;
                $GutloHotPosts->now_rate = $rate;
                $GutloHotPosts->count_update =  1;
                $GutloHotPosts->save();
            }
        }
    }

    public function fillter_open_beta_data_post_hot ($post_id) {
        // giong fillter neu dat dieu kien thi cho rate = 25;
        $user = new User();
        $GutloPosts = GutloPosts::find($post_id);
        $user_action = $user->get_user_like_post($GutloPosts->id);
        $GutloComment_count = new GutloComment();
        $count_comment = $GutloComment_count->get_total_comment($post_id);
        $min_comment = 6;
        $min_user = 3;
        if( $GutloPosts->hashtag_id != null && $GutloPosts->hashtag_id != '' && COUNT($user_action) >= $min_user && $count_comment >= $min_comment){
            return true;
        }
        else return false;
    }

    public function fillter_data_comment_hot ($array) {
        $length_array = COUNT($array);
        $coe_like = 1;
        $coe_brick = 1;
        $coe_reply = 1;

        for ($i=0; $i < $length_array; $i++) {
            $hotComment = DB::table('gutlo_hot_comment')->select('comment_id')
                                                    ->where('post_id','=',$array[$i]['post_id'])->get();
            $length_hot_comment = count($hotComment);

            $is_hot = false;
            for ($j=0; $j < $length_hot_comment; $j++) {
                if($hotComment[$j]->comment_id == $array[$i]['comment_id']){
                    $is_hot = true;
                    break;
                }
            }
            $GutloComment = DB::table('gutlo_comment')->select('gutlo_comment.*')->where('id','=',$array[$i]['comment_id'])->first();
            $total_brick_like = $GutloComment->total_like + $GutloComment->total_brick;

            $rate_like = $GutloComment->total_like * $coe_like;
            $rate_brick = $GutloComment->total_brick * $coe_brick;

            $GutloReply = new GutloReply();
            $user_id = 0;
            $reply = $GutloReply->get_count_reply($array[$i]['comment_id'],$user_id);
            $count_reply = 0;
            if(empty($reply)) {
                $count_reply = 0;
            }else{
                $count_reply = $reply->count;
            }
            $rate_reply = $count_reply * $coe_reply;
            $rate = $rate_reply + $rate_brick + $rate_like;
            if($is_hot){
                if($total_brick_like == 0 && $count_reply == 0){
                    $GutloCommentHot = GutloCommentHot::where('comment_id','=',$array[$i]['comment_id'])->first();
                    $GutloCommentHot->update_zero = 0;
                    $GutloCommentHot->rate = $rate;
                    $GutloCommentHot->save();
                }else {
                    $GutloCommentHot = GutloCommentHot::where('comment_id','=',$array[$i]['comment_id'])->first();
                    $GutloCommentHot->update_zero = 1;
                    $GutloCommentHot->rate = $rate;
                    $GutloCommentHot->save();
                }
            }else{
                if($count_reply > 0 || $total_brick_like > 0){
                    $GutloCommentHot = new GutloCommentHot();
                    $GutloCommentHot->post_id = $array[$i]['post_id'];
                    $GutloCommentHot->comment_id = $array[$i]['comment_id'];
                    $GutloCommentHot->created_time = \Carbon\Carbon::now()->toDateTimeString();
                    $GutloCommentHot->rate = $rate;
                    $GutloCommentHot->save();
                }
            }
        }
    }

    public function calculate_percent_comment ($log_activity) {
        $array = array();
        $length_log = COUNT($log_activity);
        for ($i=0; $i < $length_log; $i++) {
            if($log_activity[$i]->content_type == 1){
                $new_array = array('post_id'=>$log_activity[$i]->post_id,'comment_id'=>$log_activity[$i]->content_id);
                array_push($array,$new_array);
            }else if($log_activity[$i]->content_type == 2){
                $GutloReply = DB::table('gutlo_reply')
                                    ->select('comment_id')
                                    ->where('id','=',$log_activity[$i]->content_id)
                                    ->first();
                $new_array = array('post_id'=>$log_activity[$i]->post_id,'comment_id'=>$GutloReply->comment_id);
                array_push($array,$new_array);
            }
        }
        $this->fillter_data_comment_hot($array);
    }

    public function open_beta_data_comment_hot () {
        $posts = DB::table('gutlo_posts')->select('id')->whereNull('deleted_time')->get();
        $length_posts = COUNT($posts);
        for ($i=$length_posts-1; $i >= 0 ; $i--) {
            $this->calculate_percent_comment($posts[$i]->id);
        }
    }

    public function calculate_percent_hashtag($data_post_in_time) {
        // defalul
        $level_user = 0 ;
        $rate_user = 0;
        $verify_user = 0;
        $madal_user = 0;

        $array_all_trend = array();
        $array_all_trend_in_time = array();
        // du lieu all trend

        $array_all_trend = $this->get_all_data_post_by_trend($array_all_trend);

        // get du lieu trend in time
        $array_all_trend_in_time = $this->get_data_post_by_trend_in_time($array_all_trend_in_time,$data_post_in_time);
        // echo '<pre>';
        // echo 'full_time';
        // print_r($array_all_trend);
        // echo 'In time';
        // print_r($array_all_trend_in_time);
        // echo '</pre>';

    }
    public function fillter_data_post_by_trend_in_time ($post) {
        if(!empty($post) && $post->hashtag_id != null && $post->hashtag_id != '') {
            return true;
        }else return false;
    }
    public function get_data_post_by_trend_in_time ($array_all_trend_in_time,$data_post_in_time) {
        $length_data_post_in_time = COUNT($data_post_in_time);
        for ($i = $length_data_post_in_time -1; $i >= 0 ; $i--) {
            $post = GutloPosts::find($data_post_in_time[$i]['post_id']);
            $fillter = $this->fillter_data_post_by_trend_in_time($post);
            if($fillter) {
                $list_trend = explode(',', $post->hashtag_id);
                $length_list_trend = COUNT($list_trend);
                for ($j = $length_list_trend -1 ; $j >= 0 ; $j--) {
                    $GutloHashtag = GutloHashtag::find($list_trend[$j]);
                    $array_all_trend_in_time = $this->get_data_post($GutloHashtag->id,$array_all_trend_in_time,$GutloHashtag->hashtag);
                }
            }
        }
        return $array_all_trend_in_time;
    }

    public function get_data_post ($trend_id,$array,$trend_name) {
        $GutloComment = new GutloComment();
        $GutloReply = new GutloReply();

        $data_post = $this->get_data_post_trend($trend_id);
        $length_data_post = COUNT($data_post);

        $total_rate_post = 0 ;
        $total_like_post = 0 ;
        $total_brick_post = 0 ;
        $total_point_post = 0 ;
        $total_like_comment = 0 ;
        $total_brick_comment = 0 ;
        $total_point_comment = 0 ;
        $total_like_reply = 0 ;
        $total_brick_reply = 0 ;
        $total_point_reply = 0 ;
        $total_comment_reply = 0;
        $data_comment = array(); $data_reply = array();
        for ($j = $length_data_post -1 ; $j >= 0 ; $j--) {
            $total_like_post = $total_like_post + $data_post[$j]->total_like;
            $total_brick_post = $total_brick_post + $data_post[$j]->total_brick;
            $total_point_post = $total_point_post + $data_post[$j]->total_point;
            $total_rate_post = $total_rate_post + $data_post[$j]->rate;

            $count_comment = $GutloComment->get_total_comment($data_post[$j]->id);
            $total_comment_reply = $total_comment_reply + $count_comment;

            $data_comment = $GutloComment->get_all_data_comment_by_post_id($data_post[$j]->id);

            if(empty($data_comment) || $data_comment->total_like_comment == null){
                $data_comment = array(
                    'total_like_comment'=>0
                    ,'total_brick_comment'=>0
                    ,'total_point_comment'=>0
                );
            } else {
                $data_comment = array(
                    'total_like_comment'=>$data_comment->total_like_comment
                    ,'total_brick_comment'=>$data_comment->total_brick_comment
                    ,'total_point_comment'=>$data_comment->total_point_comment
                );
            }

            $data_reply = $GutloReply->get_sum_data_reply_by_post_id($data_post[$j]->id);

            if(empty($data_reply) || $data_reply->total_like_reply == null){
                $data_reply = array(
                    'total_like_reply'=>0
                    ,'total_brick_reply'=>0
                    ,'total_point_reply'=>0
                );
            } else {
                $data_reply = array(
                    'total_like_reply'=>$data_reply->total_like_reply
                    ,'total_brick_reply'=>$data_reply->total_brick_reply
                    ,'total_point_reply'=>$data_reply->total_point_reply
                );
            }

        }


        $new_array = array(
                        'hashtag'=>$trend_name
                        ,'id_hashtag'=>$trend_id
                        ,'total_post'=>$length_data_post
                        ,'total_comment_reply'=>$total_comment_reply
                        ,'total_rate_post'=>$total_rate_post
                        ,'total_like_post'=>$total_like_post
                        ,'total_brick_post'=>$total_brick_post
                        ,'total_point_post'=>$total_point_post
                        ,'total_like_comment'=>$data_comment['total_like_comment']
                        ,'total_brick_comment'=>$data_comment['total_brick_comment']
                        ,'total_point_comment'=>$data_comment['total_point_comment']
                        ,'total_like_reply'=>$data_reply['total_like_reply']
                        ,'total_brick_reply'=>$data_reply['total_brick_reply']
                        ,'total_point_reply'=>$data_reply['total_point_reply']

        );
        array_push($array, $new_array);
        return $array;
    }

    public function get_all_data_post_by_trend ($array_all_trend){
        $TrendController = new TrendController();


        $list_hashtag = $TrendController->list_hashtag();
        $length_list_hashtag = COUNT($list_hashtag);
        for ($i= $length_list_hashtag -1; $i >= 0 ; $i--) {

            $array_all_trend = $this->get_data_post($list_hashtag[$i]->id,$array_all_trend,$list_hashtag[$i]->hashtag);
        }
        return $array_all_trend;
    }
    public function get_data_post_trend ($id_trend) {
        $posts = new GutloPosts();
        $data_post = $posts->get_all_data_post_by_trend_id($id_trend);
        return $data_post;
    }
}