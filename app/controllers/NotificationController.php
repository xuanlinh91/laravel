<?php
use Shaphira\Common\Common;
use Shaphira\Common\HashtagMention;
class NotificationController extends BaseController{

	protected $GutloNotifications;
	protected $user ;
	public function __construct () {
		$this->GutloNotifications = new GutloNotifications();
		if(Auth::check()){
            $Auth = Auth::user();
            $user = User::where('id','=',$Auth->id)->where('username' , '=', $Auth->username)->get()->first();
            if(empty($user)) {
                Auth::logout();
                return Response::json(array('error'=>'true','msg'=> 'Bạn cần login lại để sử dụng chức năng này '));
            }else {
            	$this->user = $user;
            }
        }else return Response::json(array('error'=>'true','msg'=> 'Bạn cần login để sử dụng chức năng này '));
	}
	public function get_new_notification (){
		$offset = Input::get('count');
		$notifications = $this->GutloNotifications->get_new_notification($this->user->id);

		$length_notifi = count($notifications);
		$ActivityController = new ActivityController();
		for($i = $length_notifi - 1 ; $i >=0; $i-- ){
			$notifications[$i]->created_time = $ActivityController->time_stamp($notifications[$i]->created_time);

			if($notifications[$i]->notifi_type  == Config::get('Common.notifi_type.like')) {
				switch ($notifications[$i]->content_type) {
					case Config::get('Common.content_type.comment'):
						$GutloComment = DB::table('gutlo_comment')->select('to_post_id','content')->where('id','=',$notifications[$i]->content_id)->first();
						$notifications[$i]->content = $GutloComment->content;
						$notifications[$i]->post_id = $GutloComment->to_post_id;
						break;
					case Config::get('Common.content_type.reply'):
						$reply = DB::table('gutlo_reply')->select('comment_id','content')->where('id','=',$notifications[$i]->content_id)->first();
						$GutloComment = DB::table('gutlo_comment')->select('to_post_id')->where('id','=',$reply->comment_id)->first();
						$notifications[$i]->content = $reply->content;
						$notifications[$i]->post_id = $GutloComment->to_post_id;
						break;
					case Config::get('Common.content_type.post'):
						$post = DB::table('gutlo_posts')->select('id','content')->where('id','=',$notifications[$i]->content_id)->first();
						$notifications[$i]->content = $post->content;
						$notifications[$i]->post_id = $notifications[$i]->content_id;
						break;
				}
			}else {
				$notifications[$i]->content =  '';
				switch ($notifications[$i]->content_type) {
					case Config::get('Common.content_type.comment'):
						$GutloComment = DB::table('gutlo_comment')->select('to_post_id')->where('id','=',$notifications[$i]->content_id)->first();
						$notifications[$i]->post_id = $GutloComment->to_post_id;
						break;
					case Config::get('Common.content_type.reply'):
						$reply = DB::table('gutlo_reply')->select('comment_id')->where('id','=',$notifications[$i]->content_id)->first();
						$GutloComment = DB::table('gutlo_comment')->select('to_post_id')->where('id','=',$reply->comment_id)->first();
						$notifications[$i]->post_id = $GutloComment->to_post_id;
						break;
					case Config::get('Common.content_type.post'):
						$notifications[$i]->post_id = $notifications[$i]->content_id;
						break;
				}
			}
		}

		$this->GutloNotifications->update_reach_notifi($this->user->id);
		
		if($length_notifi < 5 && $offset == 0 ) $notifications = $this->list_notification();
		return Response::json(array('error'=>'false','msg'=> '','data'=>$notifications)) ;
	}
	public function list_notification() {

        $offset = Input::get('count');
        $limit = 10;
        if($offset == null){
            $offset = 0 ;
        }
		$notifications = $this->GutloNotifications->list_notification($this->user->id,$limit,$offset);

		$length_notifi = count($notifications);
		$ActivityController = new ActivityController();
		for($i = $length_notifi - 1 ; $i >=0; $i-- ){
			$notifications[$i]->created_time = $ActivityController->time_stamp($notifications[$i]->created_time);
			if($notifications[$i]->notifi_type  == Config::get('Common.notifi_type.like')) {
				switch ($notifications[$i]->content_type) {
					case Config::get('Common.content_type.comment'):
						$GutloComment = DB::table('gutlo_comment')->select('to_post_id','content')->where('id','=',$notifications[$i]->content_id)->first();
						$notifications[$i]->content = $GutloComment->content;
						$notifications[$i]->post_id = $GutloComment->to_post_id;
						break;
					case Config::get('Common.content_type.reply'):
						$reply = DB::table('gutlo_reply')->select('comment_id','content')->where('id','=',$notifications[$i]->content_id)->first();
						$GutloComment = DB::table('gutlo_comment')->select('to_post_id')->where('id','=',$reply->comment_id)->first();
						$notifications[$i]->content = $reply->content;
						$notifications[$i]->post_id = $GutloComment->to_post_id;
						break;
					case Config::get('Common.content_type.post'):
						$post = DB::table('gutlo_posts')->select('id','content')->where('id','=',$notifications[$i]->content_id)->first();
						$notifications[$i]->content = $post->content;
						$notifications[$i]->post_id = $notifications[$i]->content_id;
						break;
				}
			}else {
				$notifications[$i]->content =  '';
				switch ($notifications[$i]->content_type) {
					case Config::get('Common.content_type.comment'):
						$GutloComment = DB::table('gutlo_comment')->select('to_post_id')->where('id','=',$notifications[$i]->content_id)->first();
						$notifications[$i]->post_id = $GutloComment->to_post_id;
						break;
					case Config::get('Common.content_type.reply'):
						$reply = DB::table('gutlo_reply')->select('comment_id')->where('id','=',$notifications[$i]->content_id)->first();
						$GutloComment = DB::table('gutlo_comment')->select('to_post_id')->where('id','=',$reply->comment_id)->first();
						$notifications[$i]->post_id = $GutloComment->to_post_id;
						break;
					case Config::get('Common.content_type.post'):
						$notifications[$i]->post_id = $notifications[$i]->content_id;
						break;
				}
			}
		}

		$this->GutloNotifications->update_reach_notifi($this->user->id);
		$length_notifi = count($notifications);
		return $notifications ;

	}

	public function get_notification ($from_id,$to_id,$content_id,$message,$content_type) {
		$notifications = $this->GutloNotifications->get_notification($to_id,$from_id,$content_id,$message,$content_type);

		return $notifications;
	}

	public function count_new_notification($to_id){
		$count = $this->GutloNotifications->count_new_notification($to_id);
		return $count;
	}

	public function update_delete_notifi($time_delete,$from_id,$to_id,$content_id,$message,$content_type) {
		$this->GutloNotifications->update_delete_notifi($time_delete,$from_id,$to_id,$content_id,$message,$content_type);
	}

	public function update_update_notifi($time_update,$from_id,$to_id,$content_id,$notifi_type,$content_type) {
		$this->GutloNotifications->update_update_notifi($time_update,$from_id,$to_id,$content_id,$notifi_type,$content_type);
	}

	public function update_delete_notifi_by_id($time_delete,$content_id,$content_type){
		$this->GutloNotifications->update_delete_notifi_by_id($time_delete,$content_id,$content_type);

	}
	public function new_notifi($from_id,$to_id,$msg,$content_id,$content,$content_type,$notifi_type) {
		return $this->GutloNotifications->new_notifi($from_id,$to_id,$msg,$content_id,$content,$content_type,$notifi_type);
	}
	public function showNotification($id) {
		$notification = DB::table('gutlo_notifications')->select('*')->where('id','=',$id)->first();
		if($notification->deleted_time != null ) {
			if($notification->content_type == Config::get('Common.content_type.post')) {
				return array('error'=>'true','msg'=>'','data'=>array());
			}
		}
		if(empty($notification)) return array();
		$id_content = $notification->content_id;
		$post_id = $id_content;

		switch ($notification->content_type) {
			case Config::get('Common.content_type.comment'):
				$GutloComment = DB::table('gutlo_comment')->select('to_post_id')->where('id','=',$id_content)->first();
				$post_id = $GutloComment->to_post_id;
				break;
			case Config::get('Common.content_type.reply'):
				$gutloReply = DB::table('gutlo_reply')->select('comment_id')->where('id','=',$id_content)->first();
				$GutloComment = DB::table('gutlo_comment')->select('to_post_id')->where('id','=',$gutloReply->comment_id)->first();
				$post_id = $GutloComment->to_post_id;
				break;
			default:
				break;
		}
		$PostController = new PostController();
		$data = $PostController->showPost($post_id);
		if(empty($data)) return array('error'=>'true','msg'=>'bài viết đã bị block');
		$commentController = new CommentController();
		$this->GutloNotifications->update_reach_time_notifi($id);
		return array('error'=>'false','msg'=>'','data'=>array('id_content'=>$id_content,'post'=>$data,'content_type'=>$notification->content_type));
	}
	public function get_Data_post_notifi () {
		$GutloComment_count = new GutloComment();
		$activity = new ActivityController();
		$commentController = new CommentController();
		$GutloReply = new GutloReply();
		$total_comment = $GutloComment_count->get_count_comment(Input::get('id'),Input::get('user_Id'))->count;
		$comments = null;
		$comment_id = 0;
		switch (Input::get('content_type')) {
			case Config::get('Common.content_type.comment'):
				$comments = $commentController->get_comment_by_notifi_comment(Input::get('id'),Input::get('id_content'));
				break;
			case Config::get('Common.content_type.reply'):
				$comments = $commentController->get_comment_by_notifi_reply(Input::get('id'),Input::get('id_content'));
				$comment_id =  DB::table('gutlo_reply')->select('comment_id')->where('id','=',Input::get('id_content'))->first()->comment_id;
				break;
			case Config::get('Common.content_type.post'):
				$comments = $commentController->getCommentFreshById(Input::get('id'));
				break;
		}
		$total_comment_all = $GutloComment_count->get_total_comment(Input::get('id'));
		return array('error'=>'false','msg'=>'','data'=>array('comments'=>$comments,'comment_id'=>$comment_id,'total_comment_all' =>$total_comment_all,'total_comment'=>$total_comment));
	}
}