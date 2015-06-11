<?php
use Shaphira\Common\Common;
use Shaphira\Common\HashtagMention;
use Shaphira\Common\RealTimeData;

use Shaphira\Common\Notification;
class ReplyController extends BaseController{

    public function __construct () {

    }

    public function get_Reply_by_id($id){
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        $offset = Input::get('count_reply');
        $limit = Config::get('Common.count_load_more_comment');
        if($offset == null){
            $offset = 0 ;
            $limit = Config::get('Common.count_load_more_reply_first');
        }
        $replys = DB::table('gutlo_reply')
            ->select(['gutlo_reply.id as ir','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_reply.mention_id','gutlo_reply.hashtag_id','gutlo_id_vs_content.like_content','gutlo_id_vs_content.brick_content','gutlo_reply.comment_id','gutlo_reply.id','gutlo_reply.created_time','gutlo_reply.content','gutlo_reply.total_like','gutlo_reply.created_time','gutlo_reply.total_brick','gutlo_reply.total_point','users.nickname','users.username',
                    DB::raw('(select max(id) from gutlo_reply where comment_id = '.$id.' ) as max_id_all
                                ,(select min(id) from gutlo_reply where comment_id = '.$id.' ) as min_id_all 
                                ,CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
            ->join('users','users.id','=','gutlo_reply.from_id')
            ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
             ->leftJoin('gutlo_id_vs_content', function($join) use( $user_id ) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_reply.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id)
                        ->where('gutlo_id_vs_content.content_type','=',2);
            })
            ->whereRaw('NOT gutlo_reply.id IN (select b.content_id from block_content as b where b.content_id = gutlo_reply.id and b.type = 2)')
            ->where('gutlo_reply.comment_id','=',$id)
            ->whereNull('gutlo_id_vs_content.report_id')
            ->whereNull('gutlo_reply.deleted_time')
            ->orderBy('gutlo_reply.created_time','DESC')->take($limit)->skip($offset)->get();

        $activity = new ActivityController();
        $replys = $activity->replateEmoticon_on_array($replys);
        $replys = $this->SelectionSortByDateTime_ASC($replys);

        return $replys;
    }

    public function load_more_reply($id) {
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $reply = new GutloReply();
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        $replys = $this->get_Reply_by_id($id);
        if(empty($replys)) return Response::json(array('error'=>'false','msg'=>'','data'=>array()));
        else return Response::json(array('error'=>'false','msg'=>'','data'=>$replys,'count'=> $reply->get_count_reply($id,$user_id)));
    }


    public function replyComment($id) {
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $user = null;
        $GutloActivityLog = new GutloActivityLog();
        if(Auth::check()){
            $Auth = Auth::user();
            $user = User::where('id','=',$Auth->id)->where('username' , '=', $Auth->username)->get()->first();
            if(empty($user)) {
                Auth::logout();
                return Response::json(array('error'=>'true','msg'=>'Bạn cần login để thực hiện thao tác này .','data' => array('')));
            }
        }else return Response::json(array('error'=>'true','msg'=>'Bạn cần login để thực hiện thao tác này .','data' => array('')));

        $DataInput = array(
            'content'   => Input::get('content'),
        );

        $rule = array(
            'content'   => 'required|min:3|max:500',
        );
        $messes = array(
            'content'   => 'Không được để trống nội dung',
            'min'       => ':attribute quá ngắn',
            'max'       => ':attribute quá dài'
        );
        $validation = Validator::make($DataInput,$rule,$messes);

        if($validation->fails()){
            return Response::json(array('error'=>'true','msg'=>$validation->messages()->first(),'data' => array('')));
        } else {
            $GutloComment = GutloComment::find($id);
            if(empty($GutloComment)) { return Response::json(array('error'=>'true','msg'=>'Không tồn tại comment này ')); }

            $GutloComment->total_reply = $GutloComment->total_reply+1;
            $GutloComment->save();

            $GutloReply = new GutloReply();
            $GutloReply->from_id = $user->id;
            $GutloReply->to_id = $GutloComment->from_id;
            $GutloReply->comment_id = $id;
            $GutloReply->content= $DataInput['content'] ;
            $GutloReply->created_time= \Carbon\Carbon::now()->toDateTimeString();
            $GutloReply->save();

            $GutloActivityLog->new_log($user->id,0,$GutloReply->id,2,12);

            $GutloPost = GutloPosts::find($GutloComment->to_post_id);
            $category = $GutloPost->category_id;
            $HashtagMention = new HashtagMention();
            $mention_id = $HashtagMention->get_mentions_id($DataInput['content']);

            $GutloPoint = new GutloPoint();
            $GutloPoint = $GutloPoint->update_real_point_user($GutloComment->from_id,0,0,0,1);

            $data_update = array('mention_id' => $mention_id );
            DB::table('gutlo_reply')->where('id','=',$GutloReply->id)->update($data_update);

            $ava_user = DB::table('gutlo_media')
                        ->select(DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) as ava'))
                        ->where('id','=',$user->avatar_id)->first();
            $activity = new ActivityController();
            $content = $activity->replateEmoticon_on_string( $DataInput['content'],'',$mention_id );
            $time = $activity->time_stamp( $GutloReply->created_time );

            $GutloComment_total = new GutloComment();
            $total_comment_post = $GutloComment_total->get_total_comment($GutloComment->to_post_id);

            $data_realtime = new RealTimeData();

            $data_realtime->RealTime_reply_comment($GutloPost->id, array(
                                                            'user'=>array(
                                                                    'ava'=>url('/'.$ava_user->ava),
                                                                    'username'=>$user->username,
                                                                    'nickname'=> $user->nickname,
                                                            )
                                                            ,'new_content'=>$content
                                                            ,'time' => $time
                                                            ,'ir'=>$GutloReply->id
                                                            ,'total_comment' =>$GutloComment->total_reply
                                                            ,'total_comment_post' => $total_comment_post
                                                            ,'comment_id'=>$id
                                                            ,'type'=>'reply'
                                                            ,'post_id'  => $GutloComment->to_post_id
                                                        ));

            $notification = new Notification();
            $user_relation = DB::select(
                                DB::raw(
                                        'select users.username,users.id
                                        FROM users
                                        WHERE (
                                            id IN (
                                                    select CONCAT(gutlo_reply.from_id,",",gutlo_comment.from_id)
                                                    from gutlo_comment INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id
                                                    where gutlo_comment.id = '.$id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL )
                                            OR
                                            id IN (
                                                    select CONCAT(gutlo_reply.mention_id,",",gutlo_comment.mention_id)
                                                    from gutlo_comment  INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id
                                                    where gutlo_comment.id = '.$id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL
                                                    )
                                            ) AND id <> '.$user->id
                                )
                            );
            $userinfomation = UserInformation::find($user->id);
            $user_gender_msg = Config::get('Common.gender_user_msg_notifi.'.$userinfomation->gender);
            $array_mention = explode(',', $mention_id);
            $GutloNotifications = new GutloNotifications();
            for($i = count($user_relation) - 1 ; $i >=0 ; $i--){
                if($user_relation[$i]->id != $user->id  && $user_relation[$i]->id != $GutloComment->from_id && !in_array($user_relation[$i]->id, $array_mention) && $GutloPost->from_id != $user_relation[$i]->id) {
                    $id_notifi = $GutloNotifications->new_notifi($user->id ,$user_relation[$i]->id ,' trả lời một bình luận có liên quan đến bạn', $GutloReply->id,$GutloReply->content,Config::get('Common.content_type.reply'),Config::get('Common.notifi_type.reply'));
                    $notification->Notification_relationship($user_relation[$i]->id,array(
                                                            'msg'=>' trả lời một bình luận có liên quan đến bạn'
                                                            ,'user'=>$user->username
                                                            ,'nickname'=>$user->nickname
                                                            ,'id_notifi'=>$id_notifi
                                                            ,'ava'=>url('/'.$ava_user->ava)
                                                            ,'notifi_type'=>Config::get('Common.notifi_type.reply')
                                                            ,'content'  =>$GutloReply->content
                                                            ,'post_id'  => $GutloComment->to_post_id
                                                            ));
                }
            }

            for($i = count($array_mention) - 1 ; $i >=0 ; $i--){
                if($GutloComment->from_id != $array_mention[$i] && $array_mention[$i] != $user->id && $GutloPost->from_id != $array_mention[$i] && $array_mention[$i] != ''){
                    $id_notifi = $GutloNotifications->new_notifi($user->id ,$array_mention[$i] ,' thêm bạn vào trả lời của '.$user_gender_msg, $GutloReply->id,$GutloReply->content,Config::get('Common.content_type.reply'),Config::get('Common.notifi_type.mention'));
                    $notification->Notification_relationship($array_mention[$i],array(
                                                            'msg'=>' Thêm bạn vào trả lời của '.$user_gender_msg
                                                            ,'user'=>$user->username
                                                            ,'nickname'=>$user->nickname
                                                            ,'id_notifi'=>$id_notifi
                                                            ,'ava'=>url('/'.$ava_user->ava)
                                                            ,'notifi_type'=>Config::get('Common.notifi_type.mention')
                                                            ,'content'  =>$GutloReply->content
                                                            ,'post_id'  => $GutloComment->to_post_id
                                                            ));
                }
            }

            if($GutloComment->from_id != $user->id){

                $id_notifi = $GutloNotifications->new_notifi($user->id , $GutloComment->from_id ,' trả lời bình luận của bạn', $GutloReply->id,$GutloReply->content,Config::get('Common.content_type.reply'),Config::get('Common.notifi_type.reply'));

                $notification->Notification_relationship($GutloComment->from_id,array(
                                                            'msg'=>' trả lời bình luận của bạn'
                                                            ,'user'=>$user->username
                                                            ,'nickname'=>$user->nickname
                                                            ,'id_notifi'=>$id_notifi
                                                            ,'ava'=>url('/'.$ava_user->ava)
                                                            ,'notifi_type'=>Config::get('Common.notifi_type.reply')
                                                            ,'content'  =>$GutloReply->content
                                                            ,'post_id'  => $GutloComment->to_post_id
                                                            ));
            }
            if($GutloPost->from_id != $user->id && $GutloPost->from_id != $GutloComment->from_id ){

                $id_notifi =  $GutloNotifications->new_notifi($user->id , $GutloPost->from_id ,' trả lời bình luận trong bài viết của bạn', $GutloReply->id,$GutloReply->content,Config::get('Common.content_type.reply'),Config::get('Common.notifi_type.reply'));

                $notification->Notification_relationship($GutloPost->from_id,array(
                                                            'msg'=>' trả lời bình luận trong bài viết của bạn'
                                                            ,'user'=>$user->username
                                                            ,'nickname'=>$user->nickname
                                                            ,'id_notifi'=>$id_notifi
                                                            ,'ava'=>url('/'.$ava_user->ava)
                                                            ,'notifi_type'=>Config::get('Common.notifi_type.reply')
                                                            ,'content'  =>$GutloReply->content
                                                            ,'post_id'  => $GutloComment->to_post_id
                                                            ));
            }

            return Response::json(array('error'=>'false','msg'=>'','data' => array(
                                                                            'user'=>array(
                                                                                    'ava'=>url('/'.$ava_user->ava),
                                                                                    'username'=>$user->username,
                                                                                    'nickname'=> $user->nickname,
                                                                            )
                                                                            ,'new_content'=>$content
                                                                            ,'time' => $time
                                                                            ,'ir'=>$GutloReply->id
                                                                            ,'total_comment' =>$GutloComment->total_reply
                                                                            ,'total_comment_post' => $total_comment_post
                                                                            ,'comment_id'=>$id
                                                                        )));
        }
    }

    public function loadmore_new_reply () {
        $id_comment = Input::get('ir');
        $id = Input::get('id');
        $id_min_new = 0;
        if(Input::get('id_min_new') != null) {
            $id_min_new = Input::get('id_min_new');
        }
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        $GutloReply = new GutloReply();
        $replies = $GutloReply->loadmore_new_reply($user_id,$id,$id_comment,$id_min_new);
        $activity = new ActivityController();
        $replies = $activity->replateEmoticon_on_array($replies,false);
        $replies = $this->SelectionSortByDateTime_ASC($replies);
        $max_id = 0; $min_id = 0;
        if(!empty($replies)){
            $max_id = $replies[COUNT($replies) - 1]->id;
            $min_id = $replies[0]->id;
        } else {
            $max_id = null;
            $mmin_id = null;
        }
        return array('replies'=> $replies,'max_id'=>$max_id,'min_id'=> $min_id);
    }

    public function loadmore_previous_reply(){
        $id_comment = Input::get('ir');
        $id = Input::get('id');
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        $GutloReply = new GutloReply();
        $replies = $GutloReply->loadmore_previous_reply($user_id,$id,$id_comment);
        $activity = new ActivityController();
        $replies = $activity->replateEmoticon_on_array($replies,false);
        $replies = $this->SelectionSortDescending($replies);
        $max_id = 0; $min_id = 0;

        if(!empty($replies)){
            $max_id = $replies[COUNT($replies) - 1]->id;
            $min_id = $replies[0]->id;
        } else {
            $max_id = null;
            $mmin_id = null;
        }

        return array('replies'=> $replies,'max_id'=>$max_id,'min_id'=> $min_id);
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
}