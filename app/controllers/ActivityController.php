<?php

use Shaphira\Common\HashtagMention;
use Shaphira\Common\Common;
use Shaphira\Common\RealTimeData;
use Shaphira\Common\Notification;
use Shaphira\Common\SValidator;
class ActivityController extends BaseController{

    protected $notification;
    protected $validate;
    protected $user;
    protected $common;
    protected $ava_user;


	public function __construct () {
        $this->notification = new Notification();
        $this->validate = new SValidator();
        $this->user = Auth::user();
        $this->common = new Common();
        if(Auth::check()){
            $this->ava_user = DB::table('gutlo_media')
                                ->select(DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) as ava'))
                                ->where('id','=',$this->user->avatar_id)->first();
        } else {
            $this->ava_user = '';
        }
	}


    public function getEmoticon () {
        $emoticon = Emoticon::All();
        return $emoticon;
    }

    public function replateEmoticon_on_array ($array, $get_reply = false,$content_type='0',$id_comment = 0) {
        $emoticon = $this->getEmoticon();
        $length_array = count($array);
        $length_emoticon = count($emoticon);
        $common = new Common();
        $HashtagMention = new HashtagMention();
        $ReplyController = new ReplyController();

        for($i = 0; $i < $length_array; $i++) {
            $array[$i]->created_time = $this->time_stamp($array[$i]->created_time);
            $array[$i]->content = htmlentities($array[$i]->content);
            $array[$i]->new_content = $array[$i]->content;
            for($j = 0; $j < $length_emoticon; $j++) {
                $array[$i]->new_content = str_replace($emoticon[$j]->char,"<img title=".$emoticon[$j]->char."  data-original='".url('/'.$emoticon[$j]->url.$emoticon[$j]->emo_group.'/'.$emoticon[$j]->emoticon)."'></img>",$array[$i]->new_content);
            }

            $Hashtag = $HashtagMention->add_hashtag_to_content($array[$i]->new_content,$array[$i]->hashtag_id);
            $array[$i]->new_content = $Hashtag['content'];
            $array[$i]->link_tag = $Hashtag['link_tag'];
            $Mention = $HashtagMention->add_mentions_to_content($array[$i]->new_content,$array[$i]->mention_id);
            $array[$i]->new_content = $Mention;


            if( $get_reply ) {
                if($id_comment == 0){
                    $replys = $ReplyController->get_Reply_by_id($array[$i]->id);
                    $reply = new GutloReply();
                    $array[$i]->replys = $replys;
                    $user_id = 0;
                    if(Auth::check()) $user_id = Auth::user()->id;
                    $array[$i]->count_replys = $reply->get_count_reply($array[$i]->id,$user_id);
                    $replys =  $this->SelectionSortByDateTime_ASC($replys);
                    if(!empty($replys)){
                        $array[$i]->max_id = $replys[COUNT($replys) - 1]->id;
                        $array[$i]->min_id = $replys[0]->id;
                    }else {
                        $array[$i]->max_id = null;
                        $array[$i]->min_id = null ;
                    }
                }else{
                    if($id_comment == $array[$i]->id ){
                        $replys = $ReplyController->get_Reply_by_id($array[$i]->id);
                        $reply = new GutloReply();
                        $array[$i]->replys = $replys;
                        $user_id = 0;
                        if(Auth::check()) $user_id = Auth::user()->id;
                        $array[$i]->count_replys = $reply->get_count_reply($array[$i]->id,$user_id);
                        $replys =  $this->SelectionSortByDateTime_ASC($replys);
                        if(!empty($replys)){
                            $array[$i]->max_id = $replys[COUNT($replys) - 1]->id;
                            $array[$i]->min_id = $replys[0]->id;
                        }else {
                            $array[$i]->max_id = null;
                            $array[$i]->min_id = null ;
                        }
                    }else {
                        $reply = new GutloReply();
                        $array[$i]->replys = array();
                        $user_id = 0;
                        if(Auth::check()) $user_id = Auth::user()->id;
                        $array[$i]->count_replys = $reply->get_count_reply($array[$i]->id,$user_id);
                    }
                }
            }
            // add user like

        }
        return $array;
    }

    public function replateEmoticon_on_post ($array) {
        $HashtagMention = new HashtagMention();

        $emoticon = $this->getEmoticon();
        $length_array = count($array);
        $length_emoticon = count($emoticon);

        for($i = 0; $i < $length_array; $i++) {
            $array[$i]->created_time = $this->time_stamp($array[$i]->created_time);
            $array[$i]->content = htmlentities($array[$i]->content);
            $array[$i]->new_content = $array[$i]->content;

            for($j = 0; $j < $length_emoticon; $j++) {
                $array[$i]->new_content = str_replace($emoticon[$j]->char,"<img title=".$emoticon[$j]->char." data-original='".url('/'.$emoticon[$j]->url.$emoticon[$j]->emo_group.'/'.$emoticon[$j]->emoticon)."'></img>",$array[$i]->new_content);
            }

            // $common = new Common();
            // $array[$i]->id = $common->encryptor('encrypt',$array[$i]->id);
            // add user like
            $Hashtag = $HashtagMention->add_hashtag_to_content($array[$i]->new_content,$array[$i]->hashtag_id);
            $array[$i]->new_content = $Hashtag['content'];
            $array[$i]->link_tag = $Hashtag['link_tag'];
            $Mention = $HashtagMention->add_mentions_to_content($array[$i]->new_content,$array[$i]->mention_id);
            $array[$i]->new_content = $Mention;

            if($length_array == 1){
                $postController = new PostController();
                $array[$i]->next_post = $postController->next_post($array[$i]->id);
                // $array[$i]->next_pre = $postController->pre_post($array[$i]->id);
            }

            $user = new User();
            $user_action = $user->get_user_like_post($array[$i]->id);
            $array[$i]->users_action = $user_action;
            $Categories = new Categories();
            $array[$i]->Categories = $Categories->get_categories($array[$i]->category_id);
            $GutloComment_count = new GutloComment();
            $count_comment = $GutloComment_count->get_total_comment($array[$i]->id);
            $array[$i]->total_comment = $count_comment;

        }
        return $array;
    }

    public function replateEmoticon_on_string ($string,$hashtag_id,$mention_id) {
        $emoticon = $this->getEmoticon();
        $length_emoticon = count($emoticon);
        $string = htmlentities($string);

        for($j = 0; $j < $length_emoticon; $j++) {
            $string = str_replace($emoticon[$j]->char,"<img title=".$emoticon[$j]->char." src='".url('/'.$emoticon[$j]->url.$emoticon[$j]->emo_group.'/'.$emoticon[$j]->emoticon)."'></img>",$string);
        }

        $HashtagMention = new HashtagMention();
        $Hashtag = $HashtagMention->add_hashtag_to_content($string,$hashtag_id);
        $string = $Hashtag['content'];
        $string = $HashtagMention->add_mentions_to_content($string,$mention_id);

        return $string;
    }

    public function brick($id,$data_common,$Update_point_content,$data_content,$user_relation){

        // $id = $common->encryptor('decrypt',$id);
        // lay ra user dang dang nhap
        if(empty($data_content)) return Response::json(array('error'=>'true','msg'=>'Nội dung đã bị xóa hoặc không tồn tại vui lòng kiểm tra lại !.'));
        $brick_able = true;$like_able = false;
        $GutloActivityLog = new GutloActivityLog();
        $GutloPoint_auth = new  GutloPoint();
        $GutloIdVsContent_model = new GutloIdVsContent();
        $NotificationController = new NotificationController();
        $array_mention = explode(',' , $data_content->mention_id);
        // lay ra hanh dong cua nguoi dang dang nhap doi voi bai viet
        $GutloIdVsContent = GutloIdVsContent::where('user_id','=',$this->user->id)->where('content_id','=',$id)->where('content_type','=',$data_common['content_type'])->first();

        $data_realtime = new RealTimeData();
        $post_id = 0;
        switch ($data_common['content_type']) {
            case Config::get('Common.content_type.comment'):
                $post_id = $data_content->to_post_id;
                break;
            case Config::get('Common.content_type.reply') :
                $post_id = GutloComment::find($data_content->comment_id)->to_post_id;
                break;
            default:
                $post_id = $id;
                break;
        }
        if( empty($GutloIdVsContent) || !$GutloIdVsContent->brick_content) { // ok cho nem

            if( !$this->validate->validate_has_brick($this->user->id ) ) {
                return Response::json( array( 'error'=>'true','msg'=>'Xin lỗi hôm nay bạn đã sử dụng hết gạch để mai ném tiếp nhé :D','data'=>array() ) );
            }

            if( !empty($GutloIdVsContent) && $GutloIdVsContent->like_content) {// da like bai viet

                $GutloActivityLog->new_log($data_content->from_id,$this->user->id,$data_content->id,$data_common['content_type'],$data_common['id_log']['unLike']);// log huy link
                $GutloActivityLog->new_log($data_content->from_id,$this->user->id,$data_content->id,$data_common['content_type'],$data_common['id_log']['brick']);// log nem gach


                $Update_point_content = $Update_point_content->Update_point($id,-1,1);// update lai like , brick
                if($Update_point_content['error'] == 'true') return Response::json( $Update_point_content );

            } else {

                $GutloActivityLog->new_log($data_content->from_id,$this->user->id,$data_content->id,$data_common['content_type'],$data_common['id_log']['brick']);// log nem gach

                $Update_point_content = $Update_point_content->Update_point($id,0,1);
                if($Update_point_content['error'] == 'true') return Response::json( $Update_point_content );

            }

            $GutloPoint_auth->update_has_brick($this->user->id,-1);// tru gach

            if(empty($GutloIdVsContent)) {
                $GutloIdVsContent_model->insert_new_record($this->user->id,$id,$data_common['content_type'],false,true);
            } else {
                $GutloIdVsContent_model->update_recode($this->user->id,$id,$data_common['content_type'],false,true);
            }

            // if($this->user->id != $data_content->from_id){
            //     $NotificationController->update_update_notifi(\Carbon\Carbon::now()->toDateTimeString(),$this->user->id,$data_content->from_id,$id,1,$data_common['content_type']);
            //     $NotificationController->update_update_notifi(\Carbon\Carbon::now()->toDateTimeString(),$this->user->id,$data_content->from_id,$id,1,$data_common['content_type']);

            // }

            for($i = count($array_mention) - 1 ; $i >=0 ; $i--){
                if($this->user->id != $array_mention[$i] && $data_content->from_id != $array_mention[$i] && $array_mention[$i] != '' ){
                    $notifi = $NotificationController->get_notification($this->user->id ,$array_mention[$i] ,$id ,$data_common['msg_notifi'][1] ,$data_common['content_type']);
                    if(COUNT($notifi) == 1 && $notifi[0]->count == 0 ){
                        $id_notifi = $NotificationController->new_notifi($this->user->id  ,$array_mention[$i]  ,$data_common['msg_notifi'][1] ,$data_content->id ,$data_content->content,$data_common['content_type'],Config::get('Common.notifi_type.brick'));
                        $this->notification->Notification_relationship($array_mention[$i] ,array(
                                                                    'msg'=>$data_common['msg_notifi'][1]
                                                                    ,'user'=>$this->user->username
                                                                    ,'nickname'=>$this->user->nickname
                                                                    ,'id_notifi'=>$id_notifi
                                                                    ,'ava'=>url('/'.$this->ava_user->ava)
                                                                    ,'notifi_type'=>Config::get('Common.notifi_type.brick')
                                                                    ,'content'  =>$data_content->content
                                                                    ,'post_id'  => $post_id
                                                                    ));
                    }else {
                        $NotificationController->update_update_notifi(null ,$this->user->id ,$array_mention[$i] ,$id ,2,$data_common['content_type']);
                    }
                }
            }

            for($i = count($user_relation) - 1 ; $i >=0 ; $i--){
                if($user_relation[$i]->id != $this->user->id && !in_array($user_relation[$i]->id , $array_mention) && $data_content->from_id != $user_relation[$i]->id) {
                    $notifi = $NotificationController->get_notification($this->user->id ,$user_relation[$i]->id ,$id ,$data_common['msg_notifi'][1] ,$data_common['content_type']);
                    if(COUNT($notifi) == 1 && $notifi[0]->count == 0 ){
                        $id_notifi = $NotificationController->new_notifi($this->user->id  ,$user_relation[$i]->id  ,$data_common['msg_notifi'][1] ,$data_content->id ,$data_content->content ,$data_common['content_type'],Config::get('Common.notifi_type.brick'));
                        $this->notification->Notification_relationship($user_relation[$i]->id ,array(
                                                                    'msg'=>$data_common['msg_notifi'][1]
                                                                    ,'user'=>$this->user->username
                                                                    ,'nickname'=>$this->user->nickname
                                                                    ,'id_notifi'=>$id_notifi
                                                                    ,'ava'=>url('/'.$this->ava_user->ava)
                                                                    ,'notifi_type'=>Config::get('Common.notifi_type.brick')
                                                                    ,'content'  =>$data_content->content
                                                                    ,'post_id'  => $post_id
                                                                    ));
                    }else {
                        $NotificationController->update_update_notifi(null ,$this->user->id ,$user_relation[$i]->id ,$id ,2 ,$data_common['content_type']);
                    }
                }
            }

            if($data_content->from_id != $this->user->id){
                $notifi = $NotificationController->get_notification($this->user->id ,$data_content->from_id ,$id ,$data_common['msg_notifi'][0] ,$data_common['content_type']);
                if(COUNT($notifi) == 1 && $notifi[0]->count == 0 ){
                    $id_notifi = $NotificationController->new_notifi($this->user->id  ,$data_content->from_id  ,$data_common['msg_notifi'][0] ,$data_content->id ,$data_content->content,$data_common['content_type'],Config::get('Common.notifi_type.brick'));
                    $this->notification->Notification_relationship($data_content->from_id ,array(
                                                                    'msg'=>$data_common['msg_notifi'][0]
                                                                    ,'user'=>$this->user->username
                                                                    ,'nickname'=>$this->user->nickname
                                                                    ,'id_notifi'=>$id_notifi
                                                                    ,'ava'=>url('/'.$this->ava_user->ava)
                                                                    ,'notifi_type'=>Config::get('Common.notifi_type.brick')
                                                                    ,'content'  =>$data_content->content
                                                                    ,'post_id'  => $post_id
                                                                    ));
                }else {
                    $NotificationController->update_update_notifi(null ,$this->user->id ,$data_content->from_id ,$id ,2 ,$data_common['content_type']);
                }
            }

        } else {
            $brick_able = false;

            $GutloActivityLog->new_log($data_content->from_id,$this->user->id,$data_content->id,$data_common['content_type'],$data_common['id_log']['unBrick']);// huy nem

            $Update_point_content = $Update_point_content->Update_point($id,0,-1); // hoi lai gach cho bai viet

            if($Update_point_content['error'] == 'true') return Response::json( $Update_point_content );

            $GutloIdVsContent_model->update_recode($this->user->id,$id,$data_common['content_type'],false,false);

            for($i = count($array_mention) - 1 ; $i >=0 ; $i--){
                if($this->user->id == $array_mention[$i]){
                    $array_mention[$i] = '0' ;
                }else {
                    $NotificationController->update_update_notifi(\Carbon\Carbon::now()->toDateTimeString() ,$this->user->id ,$array_mention[$i] ,$id ,2 ,$data_common['content_type']);
                }
            }
            for($i = count($user_relation) - 1 ; $i >=0 ; $i--){
                if($user_relation[$i]->id == $this->user->id || in_array($user_relation[$i]->id , $array_mention)) {
                    $user_relation[$i]->id = '0';
                }else {
                    $NotificationController->update_update_notifi(\Carbon\Carbon::now()->toDateTimeString() ,$this->user->id ,$user_relation[$i]->id ,$id ,2 ,$data_common['content_type']);
                }
            }
            if($data_content->from_id != $this->user->id){
                $NotificationController->update_update_notifi(\Carbon\Carbon::now()->toDateTimeString() ,$this->user->id ,$data_content->from_id ,$id ,2 ,$data_common['content_type']);
            }
        }

        $data_realtime->RealTime_reply_comment($post_id, array(
                                                        'user'=>array(
                                                                'username'=>$this->user->username,
                                                        )
                                                        ,'total_like'=>$Update_point_content['data']['total_like']
                                                        ,'total_brick' => $Update_point_content['data']['total_brick']
                                                        ,'id'=>$id
                                                        ,'point' =>$Update_point_content['data']['total_point']
                                                        ,'content_type' => $data_common['content_type']
                                                        ,'type'=>'point'
                                                    ));

        return Response::json(array('error'=>'false','msg'=>'','data'=>array(
                                                                             'total_like'=>$Update_point_content['data']['total_like']
                                                                            ,'total_brick' => $Update_point_content['data']['total_brick']
                                                                            ,'point'=>$Update_point_content['data']['total_point']
                                                                            ,'brick_able'=>$brick_able
                                                                            ,'ir'=>$id
                                                                            ,'like_able'=>$like_able
                                                                        )));
    }


    public function like_post($id,$data_content,$user_relation,$data_common,$Update_point_content){
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        // lay ra user dang dang nhap
        $NotificationController = new NotificationController();

        $brick_able = false; $like_able = true;
        // lay ra hanh dong cua nguoi dang dang nhap doi voi bai viet
        $GutloIdVsContent = GutloIdVsContent::where('user_id' ,'=' ,$this->user->id)->where('content_id' ,'=' ,$id)->where('content_type' ,'=' ,$data_common['content_type'])->first();
        $array_mention = explode(',' , $data_content->mention_id);
        $GutloIdVsContent_model = new GutloIdVsContent();
        $GutloActivityLog = new GutloActivityLog();
        $data_realtime = new RealTimeData();

        $post_id = 0;
        switch ($data_common['content_type']) {
            case Config::get('Common.content_type.comment'):
                $post_id = $data_content->to_post_id;
                break;
            case Config::get('Common.content_type.reply') :
                $post_id = GutloComment::find($data_content->comment_id)->to_post_id;
                break;
            default:
                $post_id = $id;
                break;
        }
        if( empty($GutloIdVsContent) || !$GutloIdVsContent->like_content) { // ok cho like

            if( !empty($GutloIdVsContent) && $GutloIdVsContent->brick_content) {
                // da brick bai viet
                $GutloActivityLog->new_log($data_content->from_id ,$this->user->id ,$data_content->id ,$data_common['content_type'] ,$data_common['id_log']['like']);
                // nem gach
                $GutloActivityLog->new_log($data_content->from_id ,$this->user->id ,$data_content->id ,$data_common['content_type'] ,$data_common['id_log']['unBrick']);

                // update lai like  , brick
                $Update_point_content = $Update_point_content->Update_point($id ,1 ,-1);
                if($Update_point_content['error'] == 'true') return Response::json( $Update_point_content );

            } else {
                $GutloActivityLog->new_log($data_content->from_id ,$this->user->id ,$data_content->id ,$data_common['content_type'] ,$data_common['id_log']['like']);

                $Update_point_content = $Update_point_content->Update_point($id ,1 ,0);
                if($Update_point_content['error'] == 'true') return Response::json( $Update_point_content );

            }

            if(empty($GutloIdVsContent)) {
                $GutloIdVsContent_model->insert_new_record($this->user->id ,$id ,$data_common['content_type'] ,true ,false);
            } else {
                $GutloIdVsContent_model->update_recode($this->user->id ,$id ,$data_common['content_type'] ,true ,false);
            }

            for($i = count($array_mention) - 1 ; $i >=0 ; $i--){
                if($this->user->id != $array_mention[$i] && $data_content->from_id != $array_mention[$i] && $array_mention[$i] != '' ){
                    $notifi = $NotificationController->get_notification($this->user->id ,$array_mention[$i] ,$id ,$data_common['msg_notifi'][1] ,$data_common['content_type']);
                    if(COUNT($notifi) == 1 && $notifi[0]->count == 0 ){
                        $id_notifi = $NotificationController->new_notifi($this->user->id  ,$array_mention[$i]  ,$data_common['msg_notifi'][1] ,$data_content->id ,$data_content->content,$data_common['content_type'],Config::get('Common.notifi_type.like'));
                        $this->notification->Notification_relationship($array_mention[$i] ,array(
                                                                    'msg'=>$data_common['msg_notifi'][1]
                                                                    ,'user'=>$this->user->username
                                                                    ,'nickname'=>$this->user->nickname
                                                                    ,'id_notifi'=>$id_notifi
                                                                    ,'ava'=>url('/'.$this->ava_user->ava)
                                                                    ,'notifi_type'=>Config::get('Common.notifi_type.like')
                                                                    ,'content'  =>$data_content->content
                                                                    ,'post_id'  => $post_id
                                                                    ));
                    }else {
                        $NotificationController->update_update_notifi(null ,$this->user->id ,$array_mention[$i] ,$id ,1,$data_common['content_type']);
                    }
                }
            }

            for($i = count($user_relation) - 1 ; $i >=0 ; $i--){
                if($user_relation[$i]->id != $this->user->id && !in_array($user_relation[$i]->id , $array_mention) && $data_content->from_id != $user_relation[$i]->id) {
                    $notifi = $NotificationController->get_notification($this->user->id ,$user_relation[$i]->id ,$id ,$data_common['msg_notifi'][1] ,$data_common['content_type']);
                    if(COUNT($notifi) == 1 && $notifi[0]->count == 0 ){
                        $id_notifi = $NotificationController->new_notifi($this->user->id  ,$user_relation[$i]->id  ,$data_common['msg_notifi'][1] ,$data_content->id ,$data_content->content ,$data_common['content_type'],Config::get('Common.notifi_type.like'));
                        $this->notification->Notification_relationship($user_relation[$i]->id ,array(
                                                                    'msg'=>$data_common['msg_notifi'][1]
                                                                    ,'user'=>$this->user->username
                                                                    ,'nickname'=>$this->user->nickname
                                                                    ,'id_notifi'=>$id_notifi
                                                                    ,'ava'=>url('/'.$this->ava_user->ava)
                                                                    ,'notifi_type'=>Config::get('Common.notifi_type.like')
                                                                    ,'content'  =>$data_content->content
                                                                    ,'post_id'  => $post_id
                                                                    ));
                    }else {
                        $NotificationController->update_update_notifi(null ,$this->user->id ,$user_relation[$i]->id ,$id ,1 ,$data_common['content_type']);
                    }
                }
            }

            if($data_content->from_id != $this->user->id){
                $notifi = $NotificationController->get_notification($this->user->id ,$data_content->from_id ,$id ,$data_common['msg_notifi'][0] ,$data_common['content_type']);
                if(COUNT($notifi) == 1 && $notifi[0]->count == 0 ){
                    $id_notifi = $NotificationController->new_notifi($this->user->id  ,$data_content->from_id  ,$data_common['msg_notifi'][0] ,$data_content->id ,$data_content->content,$data_common['content_type'],Config::get('Common.notifi_type.like'));
                    $this->notification->Notification_relationship($data_content->from_id ,array(
                                                                    'msg'=>$data_common['msg_notifi'][0]
                                                                    ,'user'=>$this->user->username
                                                                    ,'nickname'=>$this->user->nickname
                                                                    ,'id_notifi'=>$id_notifi
                                                                    ,'ava'=>url('/'.$this->ava_user->ava)
                                                                    ,'notifi_type'=>Config::get('Common.notifi_type.like')
                                                                    ,'content'  =>$data_content->content
                                                                    ,'post_id'  => $post_id
                                                                    ));
                }else {
                    $NotificationController->update_update_notifi(null ,$this->user->id ,$data_content->from_id ,$id ,1 ,$data_common['content_type']);
                }
            }


        } else {
            $brick_able = false; $like_able = false;
            // huy nem
            $GutloActivityLog->new_log($data_content->from_id ,$this->user->id ,$data_content->id ,$data_common['content_type'] ,$data_common['id_log']['unLike']);
            // hoi lai gach cho bai viet
            $Update_point_content = $Update_point_content->Update_point($id ,-1 ,0);
            if($Update_point_content['error'] == 'true') return Response::json( $Update_point_content );

            $GutloIdVsContent_model->update_recode($this->user->id ,$id ,$data_common['content_type'] ,false ,false);

            for($i = count($array_mention) - 1 ; $i >=0 ; $i--){
                if($this->user->id == $array_mention[$i]){
                    $array_mention[$i] = '0' ;
                }else {
                    $NotificationController->update_update_notifi(\Carbon\Carbon::now()->toDateTimeString() ,$this->user->id ,$array_mention[$i] ,$id ,1 ,$data_common['content_type']);
                }
            }
            for($i = count($user_relation) - 1 ; $i >=0 ; $i--){
                if($user_relation[$i]->id == $this->user->id || in_array($user_relation[$i]->id , $array_mention)) {
                    $user_relation[$i]->id = '0';
                }else {
                    $NotificationController->update_update_notifi(\Carbon\Carbon::now()->toDateTimeString() ,$this->user->id ,$user_relation[$i]->id ,$id ,1 ,$data_common['content_type']);
                }
            }
            if($data_content->from_id != $this->user->id){
                $NotificationController->update_update_notifi(\Carbon\Carbon::now()->toDateTimeString() ,$this->user->id ,$data_content->from_id ,$id ,1 ,$data_common['content_type']);
            }
        }



        $data_realtime->RealTime_reply_comment($post_id, array(
                                                        'user'=>array(
                                                                'username'=>$this->user->username,
                                                        )
                                                        ,'total_like'=>$Update_point_content['data']['total_like']
                                                        ,'total_brick' => $Update_point_content['data']['total_brick']
                                                        ,'id'=>$id
                                                        ,'point' =>$Update_point_content['data']['total_point']
                                                        ,'content_type' => $data_common['content_type']
                                                        ,'type'=>'point'
                                                        ,'post_id'  => $post_id
                                                    ));

        return Response::json(array('error'=>'false' ,'msg'=>'' ,'data'=>array(
                                                                             'total_like'=>$Update_point_content['data']['total_like']
                                                                            ,'total_brick' => $Update_point_content['data']['total_brick']
                                                                            ,'point'=>$Update_point_content['data']['total_point']
                                                                            ,'brick_able'=>$brick_able
                                                                            ,'like_able' =>$like_able
                                                                            ,'ir'=>$id
                                                                        )));
    }

    public function report_post($id){
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $user = User::where('id','=',Auth::user()->id)->where('username','=',Auth::user()->username)->first();
        if( empty( $user ) ) {
            return Response::json( array( 'error'=>'true','msg'=>'Vui lòng login lại để sử dụng chức năng này','data'=>array() ) );
        }
        $post = GutloPosts::find($id);
        if(empty( $post )) return Response::json( array( 'error'=>'true','msg'=>'Có lỗi sảy ra vui lòng liên hệ quản trị viên để biết thêm thông tin','data'=>array() ) );
        if($post->from_id == $this->user->id ) return Response::json( array( 'error'=>'true','msg'=>' Bạn không thể sử dụng chức năng này vui lòng liên hệ admin để biết thêm chi tiết','data'=>array() ) );

        $report = new GutloReports();
        $_return = $report->report($this->user->id,$id,1,'report bài viết');
        if($_return['error'] == 'false'){
             $GutloActivityLog = new GutloActivityLog();
             $GutloActivityLog->new_log($this->user->id ,$post->from_id,$id,0,1);
             // $post->report_id = $_return['id'];
             // $post->save();
        }
        if($_return['error'] == 'true'){
            return Response::json($_return);
        }
        $GutloIdVsContent = GutloIdVsContent::where('user_id','=',$this->user->id)->where('content_id','=',$id)->where('content_type','=',0)->first();

        if(empty( $GutloIdVsContent )) {
            $GutloIdVsContent = new GutloIdVsContent();
            $GutloIdVsContent->user_id = $this->user->id;
            $GutloIdVsContent->content_id = $id;
            $GutloIdVsContent->content_type = 0;
            $GutloIdVsContent->like_content = false;
            $GutloIdVsContent->brick_content= false;
            $GutloIdVsContent->report_id = $_return['id'];
            $GutloIdVsContent->save();
        } else {
            $GutloIdVsContent_data = array('report_id'=>$_return['id']);
            $GutloIdVsContent = GutloIdVsContent::where('user_id','=',$this->user->id)->where('content_id','=',$id)->where('content_type','=',0)->update($GutloIdVsContent_data);
        }
        return Response::json( array('error'=>'false','msg'=> '' ));
    }

    public function report_comment($id){
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $user = User::where('id','=',Auth::user()->id)->where('username','=',Auth::user()->username)->first();
        if( empty( $user ) ) {
            return Response::json( array( 'error'=>'true','msg'=>'Vui lòng login lại để sử dụng chức năng này','data'=>array() ) );
        }
        $GutloComment = GutloComment::find($id);
        if(empty( $GutloComment )) return Response::json( array( 'error'=>'true','msg'=>'Có lỗi sảy ra vui lòng liên hệ quản trị viên để biết thêm thông tin','data'=>array() ) );
        if($GutloComment->from_id == $this->user->id ) return Response::json( array( 'error'=>'true','msg'=>' Bạn không thể sử dụng chức năng này vui lòng liên hệ admin để biết thêm chi tiết','data'=>array() ) );
        $report = new GutloReports();
        $_return = $report->report($this->user->id,$id,2,'report bài comment');
        if($_return['error'] == 'false'){
             $GutloActivityLog = new GutloActivityLog();
             $GutloActivityLog->new_log($this->user->id ,$GutloComment->from_id,$id,0,1);
             // $GutloComment->report_id = $_return['id'];
             // $GutloComment->save();
        }
        if($_return['error'] == 'true'){
            return Response::json($_return);
        }
        $GutloIdVsContent = GutloIdVsContent::where('user_id','=',$this->user->id)->where('content_id','=',$id)->where('content_type','=',1)->first();

        if(empty( $GutloIdVsContent )) {
            $GutloIdVsContent = new GutloIdVsContent();
            $GutloIdVsContent->user_id = $this->user->id;
            $GutloIdVsContent->content_id = $id;
            $GutloIdVsContent->content_type = 1;
            $GutloIdVsContent->like_content = false;
            $GutloIdVsContent->brick_content= false;
            $GutloIdVsContent->report_id = $_return['id'];
            $GutloIdVsContent->save();
        } else {
            $GutloIdVsContent_data = array('report_id'=>$_return['id']);
            $GutloIdVsContent = GutloIdVsContent::where('user_id','=',$this->user->id)->where('content_id','=',$id)->where('content_type','=',1)->update($GutloIdVsContent_data);
        }
        return Response::json( array('error'=>'false','msg'=> '' ));
    }

    public function report_reply($id){
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $user = User::where('id','=',Auth::user()->id)->where('username','=',Auth::user()->username)->first();
        if( empty( $user ) ) {
            return Response::json( array( 'error'=>'true','msg'=>'Vui lòng login lại để sử dụng chức năng này','data'=>array() ) );
        }
        $GutloReply = GutloReply::find($id);
        if(empty( $GutloReply )) return Response::json( array( 'error'=>'true','msg'=>'Có lỗi sảy ra vui lòng liên hệ quản trị viên để biết thêm thông tin','data'=>array() ) );
        if($GutloReply->from_id == $this->user->id ) return Response::json( array( 'error'=>'true','msg'=>' Bạn không thể sử dụng chức năng này vui lòng liên hệ admin để biết thêm chi tiết','data'=>array() ) );

        $report = new GutloReports();
        $_return = $report->report($this->user->id,$id,3,'report bài comment');
        if($_return['error'] == 'false'){
             $GutloActivityLog = new GutloActivityLog();
             $GutloActivityLog->new_log($this->user->id ,$GutloReply->from_id,$id,0,1);
             // $GutloReply->report_id = $_return['id'];
             // $GutloReply->save();
        }
        if($_return['error'] == 'true'){
            return Response::json($_return);
        }

        $GutloIdVsContent = GutloIdVsContent::where('user_id','=',$this->user->id)->where('content_id','=',$id)->where('content_type','=',2)->first();

        if(empty( $GutloIdVsContent )) {
            $GutloIdVsContent = new GutloIdVsContent();
            $GutloIdVsContent->user_id = $this->user->id;
            $GutloIdVsContent->content_id = $id;
            $GutloIdVsContent->content_type = 2;
            $GutloIdVsContent->like_content = false;
            $GutloIdVsContent->brick_content= false;
            $GutloIdVsContent->report_id = $_return['id'];
            $GutloIdVsContent->save();
        } else {
            $GutloIdVsContent_data = array('report_id'=>$_return['id']);
            $GutloIdVsContent = GutloIdVsContent::where('user_id','=',$this->user->id)->where('content_id','=',$id)->where('content_type','=',2)->update($GutloIdVsContent_data);
        }
        return Response::json( array('error'=>'false','msg'=> '' ));
    }

    public function delete_post($id){
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $user = User::where('id','=',Auth::user()->id)->where('username','=',Auth::user()->username)->first();
        $GutloPosts = GutloPosts::find($id);
        if($GutloPosts->total_comment >= Config::get('Common.delete.comment')
            || $GutloPosts->total_point >= Config::get('Common.delete.point')
            || $GutloPosts->total_point <= (- Config::get('Common.delete.point'))
            || $GutloPosts->total_like >= Config::get('Common.delete.like')
            || $GutloPosts->total_brick >= Config::get('Common.delete.brick') ) {
            return Response::json( array( 'error'=>'true','msg'=> 'Bạn không thể xóa bài viết này vì bài viết này quá hot . Mọi chi tiết vui lòng liên hệ admin để biết thêm chi tiết','data' => array() ) );
        }
        if( empty( $user ) ) {
            return Response::json( array( 'error'=>'true','msg'=>'Vui lòng login lại để sử dụng chức năng này','data'=>array() ) );
        }
        if($GutloPosts->from_id != $this->user->id)  return Response::json( array( 'error'=>'true','msg'=>'Vui lòng login lại để sử dụng chức năng này','data'=>array() ) );

        $comments = DB::table('gutlo_comment')->select('id')->where('to_post_id','=',$id)->whereNull('deleted_time')->get();
        $length_comments = count($comments);
        for($i = $length_comments - 1 ; $i >= 0 ; $i--){
            $this->delete_comment($comments[$i]->id,$user);
        }
        if($GutloPosts->hashtag_id != null || $GutloPosts->hashtag_id != '' ){
            $TrendController = new TrendController();
            $TrendController->update_count_post(-1,$GutloPosts->hashtag_id);
        }
        $total_brick = $GutloPosts->total_brick;
        $total_like = $GutloPosts->total_like;
        $update_post_point = new GutloPosts();
        $update_post_point = $update_post_point->Update_point($id,( - $total_like ),( - $total_brick ),true);
        if($update_post_point['error'] == 'true') return Response::json( $update_post_point );

        $GutloIdVsContent = GutloIdVsContent::where('user_id','=',$this->user->id)->where('content_id','=',$id)->where('content_type','=',0)->first();
        if(empty( $GutloIdVsContent )) {
            $GutloIdVsContent = new GutloIdVsContent();
            $GutloIdVsContent->user_id = $this->user->id;
            $GutloIdVsContent->content_id = $id;
            $GutloIdVsContent->content_type = 0;
            $GutloIdVsContent->like_content = false;
            $GutloIdVsContent->brick_content= false;
            $GutloIdVsContent->delete_content = true;
            $GutloIdVsContent->save();
        } else {
            $GutloIdVsContent_data = array('delete_content'=>true);
            $GutloIdVsContent = GutloIdVsContent::where('user_id','=',$this->user->id)->where('content_id','=',$id)->where('content_type','=',0)->update($GutloIdVsContent_data);
        }

        $GutloActivityLog = new GutloActivityLog();
        $GutloActivityLog->new_log($this->user->id ,$this->user->id,$id,1,19);
        $NotificationController = new NotificationController();
        $NotificationController->update_delete_notifi_by_id(\Carbon\Carbon::now()->toDateTimeString(),$id,Config::get('Common.content_type.post'));
        return array('error'=>'false','msg'=>'','data'=>array());
    }

    public function delete_comment($id,$user = null){
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $delete_only_comment = true;
        $GutloComment = GutloComment::find($id);
        if($GutloComment->total_reply >= Config::get('Common.delete.comment')
            || $GutloComment->total_point_com >= Config::get('Common.delete.point')
            || $GutloComment->total_point_com <= (- Config::get('Common.delete.point'))
            || $GutloComment->total_like >= Config::get('Common.delete.like')
            || $GutloComment->total_brick >= Config::get('Common.delete.brick') ) {
            return Response::json( array( 'error'=>'true','msg'=> 'Bạn không thể xóa bài viết này vì bài viết này quá hot . Mọi chi tiết vui lòng liên hệ admin để biết thêm chi tiết','data' => array() ) );
        }
        $from_id = $GutloComment->from_id;
        $user_delete = 0;
        if($user == null) {
            $user = User::where('id','=',Auth::user()->id)->where('username','=',Auth::user()->username)->first();
            if( empty( $user ) ) {
                return Response::json( array( 'error'=>'true','msg'=>'Vui lòng login lại để sử dụng chức năng này','data'=>array() ) );
            } else {
                $user_delete = $user;
            }
        } else {
            $user_delete = $user;
            $user = User::find($from_id);
            $delete_only_comment = false;
        }

        $replys = DB::table('gutlo_reply')->select('id')->where('comment_id','=',$id)->whereNull('deleted_time')->get();
        $length_reply = count($replys);
        for($i = $length_reply - 1 ; $i >= 0 ; $i--){
            $this->delete_reply($replys[$i]->id,$user_delete);
        }

        if($delete_only_comment && $from_id != $this->user->id)  return Response::json( array( 'error'=>'true','msg'=>'Vui lòng login lại để sử dụng chức năng này','data'=>array() ) );
        // lay ra thong so cua rep like (like , brich, comment)
        $total_brick = $GutloComment->total_brick;
        $total_like = $GutloComment->total_like;

        $update_comment_point = new GutloComment();
        $update_comment_point = $update_comment_point->Update_point($id,( - $total_like ),( - $total_brick ),true);
        if($update_comment_point['error'] == 'true') return Response::json( $update_comment_point );

        $GutloPosts = GutloPosts::find($GutloComment->to_post_id);
        $total_comment = $GutloPosts->total_comment - 1;
        $GutloPosts->total_comment = $total_comment;
        $GutloPosts->save();

        $total_comment_of_post = new GutloComment();
        $total_comment_of_post = $total_comment_of_post->get_total_comment($GutloComment->to_post_id);
        $GutloIdVsContent = GutloIdVsContent::where('user_id','=',$user_delete->id)->where('content_id','=',$id)->where('content_type','=',1)->first();

        if(empty( $GutloIdVsContent )) {
            $GutloIdVsContent = new GutloIdVsContent();
            $GutloIdVsContent->user_id = $user_delete->id;
            $GutloIdVsContent->content_id = $id;
            $GutloIdVsContent->content_type = 1;
            $GutloIdVsContent->like_content = false;
            $GutloIdVsContent->brick_content= false;
            $GutloIdVsContent->delete_content = true;
            $GutloIdVsContent->save();
        } else {
            $GutloIdVsContent_data = array('delete_content'=>true);
            $GutloIdVsContent = GutloIdVsContent::where('user_id','=',$user_delete->id)->where('content_id','=',$id)->where('content_type','=',1)->update($GutloIdVsContent_data);
        }

        $GutloActivityLog = new GutloActivityLog();
        $GutloActivityLog->new_log($user_delete->id ,$this->user->id,$id,1,19);
        $NotificationController = new NotificationController();
        $NotificationController->update_delete_notifi_by_id(\Carbon\Carbon::now()->toDateTimeString(),$id,Config::get('Common.content_type.comment'));
        if($delete_only_comment) return Response::json(array('error'=>'false','msg'=>'','data' => array('comment_id'=>$GutloComment->id,'total_reply'=>0,'total_comment'=>$total_comment_of_post)));
        else return array('error'=>'false','msg'=>'','data'=>array());
    }

    public function delete_reply($id,$user = null){
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $delete_only_reply = true;
        $GutloReply = GutloReply::find($id);
        $from_id = $GutloReply->from_id;
         if($GutloReply->total_point >= Config::get('Common.delete.point')
            || $GutloReply->total_point <= (- Config::get('Common.delete.point'))
            || $GutloReply->total_like >= Config::get('Common.delete.like')
            || $GutloReply->total_brick >= Config::get('Common.delete.brick') ) {
            return Response::json( array( 'error'=>'true','msg'=> 'Bạn không thể xóa bài viết này vì bài viết này quá hot . Mọi chi tiết vui lòng liên hệ admin để biết thêm chi tiết','data' => array() ) );
        }
        $user_delete = 0;
        if($user == null) {
            $user = User::where('id','=',Auth::user()->id)->where('username','=',Auth::user()->username)->first();
            if( empty( $user ) ) {
                return Response::json( array( 'error'=>'true','msg'=>'Vui lòng login lại để sử dụng chức năng này','data'=>array() ) );
            } else {
                $user_delete = $user;
            }
        } else {
            $user_delete = $user;
            $user = User::find($from_id);
            $delete_only_reply = false;
        }


        if($delete_only_reply && $from_id != $this->user->id)  return Response::json( array( 'error'=>'true','msg'=>'Vui lòng login lại để sử dụng chức năng này','data'=>array() ) );
        // lay ra thong so cua rep like (like , brich, comment)
        $total_brick = $GutloReply->total_brick;
        $total_like = $GutloReply->total_like;

        $update_reply_point = new GutloReply();
        $update_reply_point = $update_reply_point->Update_point($id,( - $total_like ),( - $total_brick ),true);
        if($update_reply_point['error'] == 'true') return Response::json( $update_reply_point );

        $Gutlo_comment = GutloComment::find($GutloReply->comment_id);
        $total_reply = $Gutlo_comment->total_reply - 1;
        $Gutlo_comment->total_reply = $total_reply;
        $Gutlo_comment->save();

        $total_comment = new GutloComment();
        $total_comment = $total_comment->get_total_comment($Gutlo_comment->to_post_id);
        $GutloIdVsContent = GutloIdVsContent::where('user_id','=',$user_delete->id)->where('content_id','=',$id)->where('content_type','=',2)->first();

        if(empty( $GutloIdVsContent )) {
            $GutloIdVsContent = new GutloIdVsContent();
            $GutloIdVsContent->user_id = $user_delete->id;
            $GutloIdVsContent->content_id = $id;
            $GutloIdVsContent->content_type = 2;
            $GutloIdVsContent->like_content = false;
            $GutloIdVsContent->brick_content= false;
            $GutloIdVsContent->delete_content = true;
            $GutloIdVsContent->save();
        } else {
            $GutloIdVsContent_data = array('delete_content'=>true);
            $GutloIdVsContent = GutloIdVsContent::where('user_id','=',$user_delete->id)->where('content_id','=',$id)->where('content_type','=',2)->update($GutloIdVsContent_data);
        }

        $GutloActivityLog = new GutloActivityLog();
        $GutloActivityLog->new_log($user_delete->id ,$this->user->id,$id,2,19);
        $NotificationController = new NotificationController();
        $NotificationController->update_delete_notifi_by_id(\Carbon\Carbon::now()->toDateTimeString(),$id,Config::get('Common.content_type.reply'));
        if($delete_only_reply) return Response::json(array('error'=>'false','msg'=>'','data' => array('comment_id'=>$GutloReply->comment_id,'total_reply'=>$total_reply,'total_comment'=>$total_comment)));
        else return array('error'=>'false','msg'=>'','data'=>array());
    }

    public function update_point_all () {
        $GutloPoint = new GutloPoint();
        $GutloPoint->update_point_all();
    }

    public function mod_block ($id_content,$data_common,$data_content) {

        if(Input::get('reason') == null) return array('error'=>'true','msg'=>'Vui lòng nhập lý do !','data'=>array());
        $reason = Input::get('reason');

        $data_block = DB::table('block_content')->select('id')->where('staff_id','=',Auth::user()->id)
                                                ->where('content_id','=',$id_content)
                                                ->where('type','=',$data_common['content_type'])
                                                ->first();
        if(empty($data_block)){
            $GutloActivityLog = new GutloActivityLog();
            $BlockContent = new BlockContent();
            $BlockContent->user_id = $data_content->from_id;
            $BlockContent->staff_id = $this->user->id;
            $BlockContent->content_id = $id_content;
            $BlockContent->type = $data_common['content_type'];
            $BlockContent->reason = Input::get('reason');
            $BlockContent->created_time = \Carbon\Carbon::now()->toDateTimeString();
            $BlockContent->save();

            $GutloActivityLog->new_log($data_content->from_id,$this->user->id,$data_content->id,$data_common['content_type'],$data_common['id_log']['block']);// huy nem
            return array('error'=>'false','msg'=>'');
        }else{
            return array('error'=>'true','msg'=>'Bạn đã block content này !');
        }
    }

    public function mod_block_hashtag () {

        if(Input::get('id') == null) return array('error'=>'true','msg'=>'Vui lòng nhập lý do !','data'=>array());
        $hashtag = Input::get('id');
        if(Input::get('reason') == null) return array('error'=>'true','msg'=>'Vui lòng nhập lý do !','data'=>array());
        $reason = Input::get('reason');

        $data_Hashtag = DB::table('gutlo_hashtag')->select('id','hashtag')->where('hashtag','=',$hashtag)->get();
        if(!empty($data_Hashtag)) {
            $length_hashtag = COUNT($data_Hashtag);
            for ($i = $length_hashtag -1; $i >= 0 ; $i--) {
                $id = $data_Hashtag[$i]->id;
                $hashtag = $data_Hashtag[$i]->hashtag;

                $data_block = DB::table('block_content')->select('id')
                                                ->where('content_id','=',$id)
                                                ->where('type','=',3)
                                                ->first();
                if(empty($data_block)){
                    $GutloActivityLog = new GutloActivityLog();
                    $BlockContent = new BlockContent();
                    $BlockContent->user_id = 0;
                    $BlockContent->staff_id = Auth::user()->id;
                    $BlockContent->content_id = $id;
                    $BlockContent->type = 3;
                    $BlockContent->reason = Input::get('reason');
                    $BlockContent->created_time = \Carbon\Carbon::now()->toDateTimeString();
                    $BlockContent->save();

                    $GutloActivityLog->new_log(0,Auth::user()->id,$id,4,32);// huy nem
                }
                else{

                    $data_TextBlackList = TextBlackList::where('text','=',$hashtag)->get();
                    if(count($data_TextBlackList) == 0){
                        $TextBlackList = new TextBlackList();
                        $TextBlackList->text = $hashtag;
                        $TextBlackList->reason = $reason;
                        $TextBlackList->save();
                    }
                    $sub_text = explode('_', $hashtag);
                    $sub_text = implode(' ', $sub_text);
                    $sub_data_TextBlackList = TextBlackList::where('text','=',$sub_text)->get();
                    if(COUNT($sub_data_TextBlackList) == 0){
                        $TextBlackList = new TextBlackList();
                        $TextBlackList->text = $sub_text;
                        $TextBlackList->reason = $reason;
                        $TextBlackList->save();
                    }
                    return array('error'=>'true','msg'=>'Bạn đã block content này !');
                }

                $data_TextBlackList = TextBlackList::where('text','=',$hashtag)->get();
                if(count($data_TextBlackList) == 0){
                    $TextBlackList = new TextBlackList();
                    $TextBlackList->text = $hashtag;
                    $TextBlackList->reason = $reason;
                    $TextBlackList->save();
                }
                $sub_text = explode('_', $hashtag);
                $sub_text = implode(' ', $sub_text);
                $sub_data_TextBlackList = TextBlackList::where('text','=',$sub_text)->get();
                if(count($sub_data_TextBlackList) == 0){
                    $TextBlackList = new TextBlackList();
                    $TextBlackList->text = $sub_text;
                    $TextBlackList->reason = $reason;
                    $TextBlackList->save();
                }
                return array('error'=>'false','msg'=>'');
            }
        }else {
            return array('error'=>'true','msg'=>'Có lỗi sảy ra vui lòng kiểm tra lại');
        }
    }


	public function SelectionSortDescending($array1)
    {
        // dem tong so phan tu cua mang
        $length = count($array1);
        // for de sap xep mang
        for ($i = 0; $i < $length - 1; $i++)
        {
            // tim vi tri lon nhat theo tung for
            $max = $i;
            for ($j = $i + 1; $j < $length; $j++){
            	$date =  strtotime($array1[$j]->created_time['created_time']);
                $date_2 =  strtotime($array1[$max]->created_time['created_time']);
                if ($date > $date_2){
                    $max = $j;
                }
            }
            // sau khi tim dc max thi hoan vi voi i
            // voi vi tri thu $i
            $temp = $array1[$i];
            $array1[$i] = $array1[$max];
            $array1[$max] = $temp;
        }
        return $array1;
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
                $date =  strtotime($array1[$j]->created_time['created_time']);
                $date_2 =  strtotime($array1[$min]->created_time['created_time']);
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

    function time_stamp($time_ago)
    {
        $cur_time=\Carbon\Carbon::now()->toDateTimeString();
        $time_elapsed = strtotime($cur_time) - strtotime($time_ago);
        $seconds = $time_elapsed ;
        $minutes = round($time_elapsed / 60 );
        $hours = round($time_elapsed / 3600);
        $days = round($time_elapsed / 86400 );
        $weeks = round($time_elapsed / 604800);
        $months = round($time_elapsed / 2600640 );
        $years = round($time_elapsed / 31207680 );
        // Seconds
        if($seconds <= 60) {
            return  array('seconds' => $seconds, 'time-ago'=> $seconds. " giây trước",'created_time'=>$time_ago) ;
        }
        //Minutes
        else if($minutes <=60) {
            if($minutes==1) {
                return array('seconds' => $seconds, 'time-ago'=> " 1 phút trước",'created_time'=>$time_ago);
            } else {
                return array('seconds' => $seconds, 'time-ago'=> $minutes." phút trước",'created_time'=>$time_ago);
            }
        }
        //Hours
        else if($hours <=24) {
            if($hours==1) {
                return array('seconds' => $seconds, 'time-ago'=> "1 giờ trước",'created_time'=>$time_ago);
            }
            else {
                return array('seconds' => $seconds, 'time-ago'=> $hours." giờ trước",'created_time'=>$time_ago);
            }
        }
        //Days
        else if($days <= 7) {
            if($days==1) {
                return array('seconds' => $seconds, 'time-ago'=> "1 ngày trước",'created_time'=>$time_ago);
            } else {
                return array('seconds' => $seconds, 'time-ago'=> $days." ngày trước ",'created_time'=>$time_ago);
            }
        }
        //Weeks
        else if($weeks <= 4.3) {
            if($weeks==1) {
                return array('seconds' => $seconds, 'time-ago'=> "1 tuần trước",'created_time'=>$time_ago ) ;
            } else {
                return array('seconds' => $seconds, 'time-ago'=> $weeks." tuần trước",'created_time'=>$time_ago);
            }
        }
        //Months
        else if($months <=12) {
            if($months==1) {
                return array('seconds' => $seconds, 'time-ago'=> "1 tháng trước",'created_time'=>$time_ago);
            } else {
                return array('seconds' => $seconds, 'time-ago'=> $months." tháng trước",'created_time'=>$time_ago);
            }
        }
        //Years
        else {
            if($years==1) {
                return array('seconds' => $seconds, 'time-ago'=> "1 năm trước",'created_time'=>$time_ago);
            } else {
                return array('seconds' => $seconds, 'time-ago'=> $years." năm trước",'created_time'=>$time_ago);
            }
        }
    }

    function convert_vi_to_en($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = str_replace(" ", "", str_replace("&*#39;","",$str));

        return $str;
    }

}