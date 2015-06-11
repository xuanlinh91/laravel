<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class GutloNotifications extends Eloquent{
	use SoftDeletingTrait;
	protected $table = 'gutlo_notifications';
	protected $primaryKey = 'id';
	
	const CREATED_AT = 'created_time';
	const UPDATED_AT = 'updated_time';
	protected $dates = ['deleted_at'];
	const DELETED_AT = 'deleted_time';

	public function new_notifi($from_id,$to_id,$msg,$content_id,$content,$content_type,$notifi_type) {
		$GutloNotifications = new GutloNotifications();
        $GutloNotifications->from_id = $from_id;
        $GutloNotifications->to_id = $to_id;
        $GutloNotifications->message = $msg;
        $GutloNotifications->content_id = $content_id;
        $GutloNotifications->created_time = \Carbon\Carbon::now()->toDateTimeString();
        $GutloNotifications->content_type = $content_type;
        $GutloNotifications->notifi_type = $notifi_type;
        $GutloNotifications->content = $content;
        $GutloNotifications->save();

        return $GutloNotifications->id;
	}

	public function list_notification($user_id,$limit,$offset){
		$notifications = DB::table('gutlo_notifications')
						->select(['gutlo_notifications.id','gutlo_notifications.content_type','gutlo_notifications.content','gutlo_notifications.notifi_type','gutlo_notifications.message','gutlo_notifications.created_time','gutlo_notifications.content_id'
									,'gutlo_notifications.reach_time','gutlo_notifications.reach_notifi'
									,'users.username','users.nickname','users.facebook_id',DB::raw('count(*) as count,CONCAT(gutlo_media.media_url,gutlo_media.media_name) as ava')])
						->join('users','users.id','=','gutlo_notifications.from_id')
						->join('gutlo_media','gutlo_media.id','=','users.avatar_id')
						->where('to_id','=',$user_id)
						->whereNull('gutlo_notifications.deleted_time')
						->groupBy('to_id')
						->groupBy('content_id')
						->groupBy('message')
						->groupBy('content_type')
						->orderBy('gutlo_notifications.created_time','DESC')->take($limit)->skip($offset)->get();
		return $notifications;
	}
	public function get_new_notification($user_id){
		$notifications = DB::table('gutlo_notifications')
						->select(['gutlo_notifications.id','gutlo_notifications.content_type','gutlo_notifications.content','gutlo_notifications.notifi_type','gutlo_notifications.message','gutlo_notifications.created_time','gutlo_notifications.content_id'
									,'gutlo_notifications.reach_time','gutlo_notifications.reach_notifi'
									,'users.username','users.nickname','users.facebook_id',DB::raw('count(*) as count,CONCAT(gutlo_media.media_url,gutlo_media.media_name) as ava')])
						->join('users','users.id','=','gutlo_notifications.from_id')
						->join('gutlo_media','gutlo_media.id','=','users.avatar_id')
						->where('to_id','=',$user_id)
						->whereNull('gutlo_notifications.deleted_time')
						->whereNull('gutlo_notifications.reach_notifi')
						->whereNull('gutlo_notifications.reach_time')
						->groupBy('to_id')
						->groupBy('content_id')
						->groupBy('message')
						->groupBy('content_type')
						->orderBy('gutlo_notifications.created_time','DESC')->get();
		return $notifications;
	}
	public function update_reach_notifi($user_id){
		$data_update = array('reach_notifi'=>\Carbon\Carbon::now()->toDateTimeString());
		DB::table('gutlo_notifications')
						->where('to_id','=',$user_id)
						->whereNull('gutlo_notifications.deleted_time')
						->update($data_update);
	}

	public function update_reach_time_notifi($id){
		$data_update = array('reach_time'=>\Carbon\Carbon::now()->toDateTimeString());
		DB::table('gutlo_notifications')
						->where('id','=',$id)
						->whereNull('gutlo_notifications.deleted_time')
						->update($data_update);
	}

	public function update_delete_notifi($time_delete,$from_id,$to_id,$content_id,$message,$content_type){
		$data_update = array('deleted_time'=>$time_delete);
		DB::table('gutlo_notifications')
						->where('from_id','=',$from_id)
						->where('to_id','=',$to_id)
						->where('content_id','=',$content_id)
						->where('message','=',$message)
						->where('content_type','=',$content_type)
						->update($data_update);
	}

	public function update_update_notifi($time_update,$from_id,$to_id,$content_id,$notifi_type,$content_type){
		$data_update = array('updated_time'=>$time_update);
		DB::table('gutlo_notifications')
						->where('from_id','=',$from_id)
						->where('to_id','=',$to_id)
						->where('content_id','=',$content_id)
						->where('notifi_type','=',$notifi_type)
						->where('content_type','=',$content_type)
						->update($data_update);
	}

	public function get_notification($to_id,$from_id,$content_id,$message,$content_type) {
		$notifications = DB::table('gutlo_notifications')->select( [ DB::raw('count(*) as count') ] )
														->where('to_id','=',$to_id)
														->where('from_id','=',$from_id)
														->where('content_id','=',$content_id)
														->where('content_type','=',$content_type)
														->where('message','=',$message)->get();
		return $notifications;
	}

	public function count_new_notification($to_id){
		$count = 0 ;
		$notification = DB::table('gutlo_notifications')
						->select( [ DB::raw('COUNT(*) as count') ] )
						->where('to_id','=',$to_id)
						->whereNull('reach_time')
						->whereNull('reach_notifi')
						->whereNull('gutlo_notifications.deleted_time')
						->groupBy('from_id')
						->groupBy('content_id')
						->groupBy('content_type')
						->groupBy('message')->get();

		if(!empty($notification)) $count = count($notification);
		return $count;
	}

	public function update_delete_notifi_by_id($time_delete,$content_id,$content_type){
		$data_update = array('deleted_time'=>$time_delete);
		DB::table('gutlo_notifications')
						->where('content_id','=',$content_id)
						->where('content_type','=',$content_type)
						->update($data_update);
	}
}