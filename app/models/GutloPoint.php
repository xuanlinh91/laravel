<?php

class GutloPoint extends Eloquent{

	protected $table = 'gutlo_point';
	protected $primaryKey = 'user_id';
	public $timestamps = false;
	

	public function update_real_point_user($user_id,$like,$brick,$post,$comment) {
		if($like > 1 || $like < -1 || $brick > 1 || $brick < -1 ) return array('error'=>'true','msg'=>'Có lỗi phat sinh vui lòng kiểm tra.');

		$GutloPoint = GutloPoint::find($user_id);
		if(empty($GutloPoint)) return array('error'=>'true','msg'=>'Có lỗi phat sinh vui lòng kiểm tra.');
		$total_post_Point = DB::table('gutlo_posts')
							->select([DB::raw('SUM( total_point ) AS total_point,COUNT(total_point) AS total_post')])
							->where('from_id','=',$user_id)
							->whereNull('deleted_time')->first() ;
		$total_comment_Point = DB::table('gutlo_comment')
							->select([DB::raw('SUM( total_point_com ) AS total_point')])
							->where('from_id','=',$user_id)
							->whereNull('deleted_time')->first() ;
		$total_reply_Point = DB::table('gutlo_reply')
							->select([DB::raw('SUM( total_point ) AS total_point')])
							->where('from_id','=',$user_id)
							->whereNull('deleted_time')->first() ;

		$GutloPoint->total_post = $total_post_Point->total_post + $post;
		$GutloPoint->total_comment = $GutloPoint->total_comment + $comment; 
		$GutloPoint->total_like = $GutloPoint->total_like + $like;
		$GutloPoint->total_brick = $GutloPoint->total_brick + $brick;
		$GutloPoint->real_point = ( $GutloPoint->total_plus_hot * 6 ) + $total_post_Point->total_point 
									+ $total_post_Point->total_post
									+ $total_reply_Point->total_point 
									+ $total_comment_Point->total_point
									+ $GutloPoint->bonus_point
									- $GutloPoint->used_point_for_lv;
		if($GutloPoint->real_point > $GutloPoint->max_point) $GutloPoint->max_point = $GutloPoint->real_point;
		$GutloPoint->save();
		return array('error'=>'false','msg'=>'');
	}

	public function update_point_all () {
		DB::statement('UPDATE gutlo_posts SET gutlo_posts.total_point = gutlo_posts.total_like + ( gutlo_posts.total_brick * '.Config::get('Common.coe_brick').') ');
		DB::statement('UPDATE gutlo_comment SET gutlo_comment.total_point_com = gutlo_comment.total_like + ( gutlo_comment.total_brick * '.Config::get('Common.coe_brick').') ');
		DB::statement('UPDATE gutlo_reply SET gutlo_reply.total_point = gutlo_reply.total_like + ( gutlo_reply.total_brick * '.Config::get('Common.coe_brick').') ');
	}
	public function update_has_brick ($user_id,$brick) {
		$GutloPoint_auth = GutloPoint::find( $user_id );
        $GutloPoint_auth->has_brick = $GutloPoint_auth->has_brick + $brick;
        $GutloPoint_auth->save();
	}
}