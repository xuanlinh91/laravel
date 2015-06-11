<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	protected $primaryKey = 'id';
	const CREATED_AT = 'created_time';
	const UPDATED_AT = 'updated_time';
	
//Sign Up
	//Check username thuc hien kiem tra username da ton tai hay chua
	public static function check_username($username){
		if(User::where('username','=',$username)->count()>0)
			return false;
		else return true;
	}
	//Check email thuc hien kiem tra username da ton tai hay chua
	public static function check_email($email){
		if(User::where('email','=',$email)->count()>0)
			return false;
		else return true;
	}

	public function get_profile_user($id){
		$data = DB::table('users')->select(['users.username','users.id','users.facebook_id','users.last_visited_time','users.created_time','users.email','users.mobile','users.confirmed','users.user_level','users.avatar_id','users.permission_role','users.edited_username','users.exp'
				,'users.dayCount','users.dayOnline','users.vip','users.blogger_level','users.shaphira_verified','users.username','users.nickname','gutlo_point.has_brick','gutlo_point.real_point','gutlo_point.max_point','gutlo_point.total_like','gutlo_point.total_brick','gutlo_point.total_post'
				,'gutlo_point.total_comment','gutlo_medals.medal_name','gutlo_medals.medal_icon_url','user_informations.firstname'
				,'user_informations.lastname','user_informations.birthday','user_informations.gender',DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
					->join('user_informations','user_informations.id','=','users.id')
					->join('gutlo_media','gutlo_media.id','=','users.avatar_id')
					->join('gutlo_point','gutlo_point.user_id','=','users.id')
					->leftJoin('gutlo_medals', function($join) {
                        $join->on('gutlo_point.real_point','>=','gutlo_medals.min_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.max_point')
                        ->orOn('gutlo_point.real_point','>=','gutlo_medals.max_point') 
                        ->on('gutlo_point.real_point','<=','gutlo_medals.min_point');
                    })
                    ->where('users.id','=',$id)->first();
        return $data;
	}

	public function get_data_report($username){
		$data = DB::table('users')->select(['users.id','users.facebook_id','users.last_visited_time','users.created_time','users.email','users.mobile','users.confirmed','users.user_level','users.avatar_id','users.permission_role','users.edited_username','users.exp'
				,'users.dayCount','users.dayOnline','users.vip','users.blogger_level','users.shaphira_verified','users.username','users.nickname','gutlo_point.has_brick','gutlo_point.real_point','gutlo_point.max_point','gutlo_point.total_like','gutlo_point.total_brick','gutlo_point.total_post'
				,'gutlo_point.total_comment','gutlo_medals.medal_name','gutlo_medals.medal_icon_url','user_informations.firstname'
				,'user_informations.lastname','user_informations.birthday','user_informations.gender',DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
					->join('user_informations','user_informations.id','=','users.id')
					->join('gutlo_media','gutlo_media.id','=','users.avatar_id')
					->join('gutlo_point','gutlo_point.user_id','=','users.id')
					->leftJoin('gutlo_medals', function($join) {
                        $join->on('gutlo_point.real_point','>=','gutlo_medals.min_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.max_point')
                        ->orOn('gutlo_point.real_point','>=','gutlo_medals.max_point') 
                        ->on('gutlo_point.real_point','<=','gutlo_medals.min_point');
                    })
		->where('username','=',$username)->first();
		return $data;
	}

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
	
	public function get_user_like_post($content_id) {
		$users_like = DB::table('gutlo_media')
						->select(['users.username','users.nickname',DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
						->join('gutlo_id_vs_content',function($join) use ($content_id){
							$join->where('content_id','=',$content_id)
							->where('content_type','=','0')
							->where('like_content','=',true);
						})
						->join('users',function($join){
							$join->on('users.id','=','gutlo_id_vs_content.user_id')
							->on('users.avatar_id','=','gutlo_media.id');
						})->take(5)->skip(0)->get(); 

		$users_comment = DB::table('gutlo_media')
						->select(['users.username','users.nickname',DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
						->join('gutlo_comment',function($join) use ($content_id){
							$join->where('to_post_id','=',$content_id);
						})
						->join('users',function($join){
							$join->on('users.id','=','gutlo_comment.from_id')
							->on('users.avatar_id','=','gutlo_media.id');
						})
						->whereNull('gutlo_comment.deleted_time')->groupBy('users.username')->take(5)->skip(0)->get();
		$users_reply = DB::table('gutlo_media')
						->select(['users.username','users.nickname',DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
						->join('gutlo_comment',function($join) use ($content_id){
							$join->where('to_post_id','=',$content_id);
						})
						->join('gutlo_reply','gutlo_reply.comment_id','=','gutlo_comment.id')
						->join('users',function($join){
							$join->on('users.id','=','gutlo_reply.from_id')
							->on('users.avatar_id','=','gutlo_media.id');
						})
						->whereNull('gutlo_reply.deleted_time')->groupBy('users.username')->take(5)->skip(0)->get();

		$users_action = array();
		for($i = 0 ; $i < 3; $i++){
			try{
				$check = false;
				for($j = 0; $j < count($users_action); $j++){
					if($users_like[$i]->username == $users_action[$j]->username ){
						$check = true;
					}
				}
				if(!$check) array_push($users_action,$users_like[$i]);
			}catch(Exception $e){

			}
			try{
				$check = false;
				for($j = 0; $j < count($users_action); $j++){
					if($users_comment[$i]->username == $users_action[$j]->username ){
						$check = true;
					}
				}
				if(!$check) array_push($users_action,$users_comment[$i]);
			}catch(Exception $e){

			}
			try{
				$check = false;
				for($j = 0; $j < count($users_action); $j++){
					if($users_reply[$i]->username == $users_action[$j]->username ){
						$check = true;
					}
				}
				if(!$check) array_push($users_action,$users_reply[$i]);
			}catch(Exception $e){

			}
		}
		return $users_action;	
	}

}
