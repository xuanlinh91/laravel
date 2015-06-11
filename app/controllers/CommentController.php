<?php
use Shaphira\Common\Common;
use Shaphira\Common\HashtagMention;
use Shaphira\Common\RealTimeData;
use Shaphira\Common\Notification;

class CommentController extends BaseController{

	public function __construct () {

	}

	public function getCommentFreshById($id){
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        $offset = Input::get('count');
        $limit = Config::get('Common.count_load_more_comment');
        if($offset == null){
            $offset = 0 ;
        }
		$GutloComment = new GutloComment();
        $comments = $GutloComment->getCommentFresh($user_id, $id, $limit, $offset);

		$activity = new ActivityController();
		$comments = $activity->replateEmoticon_on_array($comments,true);
        $comments = $this->SelectionSortDescending($comments);
		return $comments;
	}

    public function getCommentHotById($id){
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        $offset = Input::get('count');
        $limit = Config::get('Common.count_load_more_comment');
        if($offset == null){
            $offset = 0 ;
        }
        $GutloComment = new GutloComment();
        $comments = $GutloComment->getCommentHot($user_id, $id, $limit, $offset);

        $activity = new ActivityController();
        $comments = $activity->replateEmoticon_on_array($comments,true);
        $comments = $this->SelectionSortDescending_by_point($comments);
        return $comments;
    }

    public function getMoreCommentHotById($id) {
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $comment = new GutloComment();
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        $comments = $this->getCommentHotById($id);
        $activity = new ActivityController();
        $comments = $activity->replateEmoticon_on_array($comments,true);
        $comments = $this->SelectionSortDescending_by_point($comments);
        if(empty($comments)) return Response::json(array('error'=>'false','msg'=>'','data'=>array()));
        else return Response::json(array('error'=>'false','msg'=>'','data'=>$comments,'count'=> $comment->get_count_comment($id,$user_id)));

    }
    public function getMoreCommentFreshById($id) {
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $comment = new GutloComment();
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        $comments = $this->getCommentFreshById($id);
        $activity = new ActivityController();
        $comments = $activity->replateEmoticon_on_array($comments,true);
        $comments = $this->SelectionSortDescending_by_point($comments);
        if(empty($comments)) return Response::json(array('error'=>'false','msg'=>'','data'=>array()));
        else return Response::json(array('error'=>'false','msg'=>'','data'=>$comments,'count'=> $comment->get_count_comment($id,$user_id)));
    }

  	public function commentPost($id) {
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $user = null;
        $GutloActivityLog = new GutloActivityLog();
        if(Auth::check()){
            $Auth = Auth::user();
            $user = User::where('id','=',$Auth->id)->where('username' , '=', $Auth->username)->get()->first();
            if(empty($user)) {
                Auth::logout();
                Redirect::to('/login');
            }
        }else Redirect::to('/login');

        $DataInput = array(
            'content'   => trim(Input::get('content')),
        );

        $rule = array(
            'content'   => 'required|min:3|max:500',
        );
        $messes = array(
            'required'   => ':attribute Không được để trống nội dung',
            'min'       => ':attribute quá ngắn',
            'max'       => ':attribute quá dài'
        );
        $validation = Validator::make($DataInput,$rule,$messes);

        if($validation->fails()){
            return Response::json(array('error'=>'true','msg'=>$validation->messages()->first(),'data' => array('')));
        } else {

            $GutloPosts = GutloPosts::find($id);
            if(empty($GutloPosts)) { return Response::json(array('error'=>'true','msg'=>'Không tồn tại comment này ')); }

            $GutloPosts->total_comment = $GutloPosts->total_comment + 1 ;
            $GutloPosts->save();
            $GutloComment = new GutloComment();
            $GutloComment->from_id = $user->id;
            $GutloComment->to_id = $GutloPosts->from_id;
            $GutloComment->to_post_id = $id;
            $GutloComment->content= $DataInput['content'];
            $GutloComment->created_time= \Carbon\Carbon::now()->toDateTimeString();
            $GutloComment->save();

            $HashtagMention = new HashtagMention();
            $mention_id = $HashtagMention->get_mentions_id($DataInput['content']);

            $GutloActivityLog->new_log($user->id,0,$GutloComment->id,1,11);

            $data_update = array('mention_id' => $mention_id );
            DB::table('gutlo_comment')->where('id','=',$GutloComment->id)->update($data_update);

            $GutloPoint = new GutloPoint();
            $GutloPoint = $GutloPoint->update_real_point_user($GutloPosts->from_id,0,0,0,1);

            $ava_user = DB::table('gutlo_media')
                        ->select(DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) as ava'))
                        ->where('id','=',$user->avatar_id)->first();
            $activity = new ActivityController();
            $hashtag_id = '';
            $content = $activity->replateEmoticon_on_string( $DataInput['content'],$hashtag_id,$mention_id );
            $time = $activity->time_stamp( $GutloComment->created_time );

            $GutloComment_count = new GutloComment();
            $total_comment = $GutloComment_count->get_total_comment($id);

            $data_realtime = new RealTimeData();

            $data_realtime->RealTime_reply_comment($id, array(
                                                            'user'=>array(
                                                                    'ava'=>url('/'.$ava_user->ava),
                                                                    'username'=>$user->username,
                                                                    'nickname'=> $user->nickname,
                                                            )
                                                            ,'new_content'=>$content
                                                            ,'time' => $time
                                                            ,'comment_id'=>$GutloComment->id
                                                            ,'total_comment' =>$total_comment
                                                            ,'type'=>'comment'
                                                            ,'post_id'  => $GutloComment->to_post_id
                                                        ));
            $notification = new Notification();
            $user_relation = DB::select(DB::raw('select users.username,users.id from users where (id IN ( select CONCAT(gutlo_reply.from_id,",",gutlo_comment.from_id) from gutlo_comment INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id where gutlo_comment.to_post_id = '.$id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL) OR id IN ( select CONCAT(gutlo_reply.mention_id,",",gutlo_comment.mention_id) from gutlo_comment  INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id where gutlo_comment.to_post_id = '.$id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL))  AND id <> '.$user->id  ));

            $array_mention = explode(',', $mention_id);
            $GutloNotifications = new GutloNotifications();
            for($i = count($user_relation) - 1 ; $i >=0 ; $i--){
                if($user_relation[$i]->id != $GutloPosts->from_id && !in_array($user_relation[$i]->id, $array_mention) &&  $user_relation[$i]->id != $user->id ) {
                    $id_notifi = $GutloNotifications->new_notifi($user->id ,$user_relation[$i]->id ,' bình luận bài viết có liên quan đến bạn', $GutloComment->id,$GutloComment->content,Config::get('Common.content_type.comment'),Config::get('Common.notifi_type.comment'));
                    $notification->Notification_relationship($user_relation[$i]->id,array(
                                                            'msg'=>' bình luận bài viết có liên quan đến bạn'
                                                            ,'user'=>$user->username
                                                            ,'nickname'=>$user->nickname
                                                            ,'id_notifi'=>$id_notifi
                                                            ,'ava'=>url('/'.$ava_user->ava)
                                                            ,'notifi_type'=>Config::get('Common.notifi_type.comment')
                                                            ,'content'  =>$GutloComment->content
                                                            ,'post_id'  => $GutloComment->to_post_id
                                                            ));
                }
            }

            $userinfomation = UserInformation::find($user->id);
            $user_gender_msg = Config::get('Common.gender_user_msg_notifi.'.$userinfomation->gender);
            if(empty($array_mention)) echo 'áđá';
            for($i = count($array_mention) - 1 ; $i >=0 ; $i--){
                if($GutloPosts->from_id != $array_mention[$i] && $array_mention[$i] != $user->id && $array_mention[$i] != '' ){
                    $id_notifi =$GutloNotifications->new_notifi($user->id ,$array_mention[$i] ,' thêm bạn vào một bình luận '.$user_gender_msg, $GutloComment->id,$GutloComment->content,Config::get('Common.content_type.comment'),Config::get('Common.notifi_type.mention'));
                    $notification->Notification_relationship($array_mention[$i],array(
                                                            'msg'=>' thêm bạn vào một bình luận '.$user_gender_msg
                                                            ,'user'=>$user->username
                                                            ,'nickname'=>$user->nickname
                                                            ,'id_notifi'=>$id_notifi
                                                            ,'ava'=>url('/'.$ava_user->ava)
                                                            ,'notifi_type'=>Config::get('Common.notifi_type.mention')
                                                            ,'content'  =>$GutloComment->content
                                                            ,'post_id'  => $GutloComment->to_post_id
                                                            ));
                }
            }



            if($GutloPosts->from_id != $user->id){

                $id_notifi = $GutloNotifications->new_notifi($user->id , $GutloPosts->from_id ,' bình luận bài viết của bạn', $GutloComment->id,$GutloComment->content,Config::get('Common.content_type.comment'),Config::get('Common.notifi_type.comment'));

                $notification->Notification_relationship($GutloPosts->from_id,array(
                                                            'msg'=>' bình luận bài viết của bạn'
                                                            ,'user'=>$user->username
                                                            ,'nickname'=>$user->nickname
                                                            ,'id_notifi'=>$id_notifi
                                                            ,'ava'=>url('/'.$ava_user->ava)
                                                            ,'notifi_type'=>Config::get('Common.notifi_type.comment')
                                                            ,'content'  =>$GutloComment->content
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
                                                                            ,'comment_id'=>$GutloComment->id
                                                                            ,'total_comment' =>$total_comment
                                                                        )));
        }
    }

    public function get_comment_by_notifi_comment($id_post,$id) {
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        $GutloComment = new GutloComment();
        $comments = $GutloComment->getCommentNotifi($id_post,$user_id,$id);

        $activity = new ActivityController();
        $comments = $activity->replateEmoticon_on_array($comments,true,Config::get('Common.comment_type'),$id);
        return $comments;
    }

    public function loadmore_new_comment () {
        $id_comment = Input::get('id');
        $id_post = Input::get('ir');
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        $GutloComment = new GutloComment();
        $comments = $GutloComment->loadmore_new_comment($id_post,$user_id,$id_comment);
        $activity = new ActivityController();
        $comments = $activity->replateEmoticon_on_array($comments,false);
        $comments = $this->SelectionSortByDateTime_ASC($comments);
        return Response::json(array('error'=>'false','msg'=>'','data'=>$comments,'count'=> $GutloComment->get_count_comment($id_post,$user_id)));
    }

    public function loadmore_previous_comment() {
        $id_comment = Input::get('id');
        $id_post = Input::get('ir');
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        $GutloComment = new GutloComment();
        $comments = $GutloComment->loadmore_previous_comment($id_post,$user_id,$id_comment);
        $activity = new ActivityController();
        $comments = $activity->replateEmoticon_on_array($comments,false);
        return Response::json(array('error'=>'false','msg'=>'','data'=>$comments,'count'=> $GutloComment->get_count_comment($id_post,$user_id)));
    }
    public function get_comment_by_notifi_reply($id_post,$idcontent) {
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;

        $reply =  DB::table('gutlo_reply')->select('*')->where('id','=',$idcontent)->first();
        $GutloComment = new GutloComment();
        $comments = $GutloComment->getCommentNotifi($id_post,$user_id,$reply->comment_id);
        $activity = new ActivityController();
        $comments = $activity->replateEmoticon_on_array($comments,false);
        $GutloReply = new GutloReply();
        $comment_length = count($comments);
        for ($i = 0; $i < $comment_length; $i++) {
            if($comments[$i]->id == $reply->comment_id ) {
                $replys = $GutloReply->get_reply_with_min_id($user_id,$idcontent,$reply->comment_id);
                $replys_data = $activity->replateEmoticon_on_array($replys['data']);
                $replys_data = $this->SelectionSortByDateTime_ASC($replys['data']);
                $comments[$i]->replys = $replys_data;
                $comments[$i]->max_id = $replys_data[COUNT($replys_data) - 1]->id;
                $comments[$i]->min_id = $replys_data[0]->id;

            }else {
                $comments[$i]->replys = array();
                // $comments[$i]->count_max = 0;
            }
        }

        return $comments;
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

    public function SelectionSortDescending_by_point($array1)
    {
        // dem tong so phan tu cua mang
        $length = count($array1);
        // for de sap xep mang
        for ($i = 0; $i < $length - 1; $i++)
        {
            // tim vi tri lon nhat theo tung for
            $max = $i;
            for ($j = $i + 1; $j < $length; $j++){
                $date =  strtotime($array1[$j]->total_point_com);
                $date_2 =  strtotime($array1[$max]->total_point_com);
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