<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class GutloComment extends Eloquent{
	use SoftDeletingTrait;
	protected $table = 'gutlo_comment';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	const DELETED_AT = 'deleted_time';
	public function Update_point($id,$like,$brick,$delete = false){
		// -1 =< $like <= 1
		// -1 =< $brick <= 1
		$GutloComment = GutloComment::find($id);
		$GutloComment->total_like = $GutloComment->total_like  + $like;
		$GutloComment->total_brick = $GutloComment->total_brick + $brick;
		$GutloComment->total_point_com = $GutloComment->total_like + ( $GutloComment->total_brick * (Config::get('Common.coe_brick')) );
		$GutloComment->save();

		$from_id = $GutloComment->from_id;
		if($delete){
			$GutloComment = GutloComment::find($id);
			$GutloComment->delete();
		}
		$GutloPoint_user_post = new GutloPoint();
		$GutloPoint_user_post->update_real_point_user($from_id,$like,$brick,0 ,0 );
		if($GutloPoint_user_post['error'] == 'true')  return Response::json( $GutloPoint_user_post );

		return array('error'=>'false','msg'=>'','data'=> array('total_like'=>$GutloComment->total_like,'total_brick'=> $GutloComment->total_brick,'total_point'=>$GutloComment->total_point_com));
	}

	public function get_total_comment($postId) {

        $total_comment = DB::table('gutlo_comment')
                        ->select( [ DB::raw('( ifnull(COUNT(*), 0) + ifnull(
                            ( Select COUNT(*) from gutlo_reply where gutlo_reply.comment_id = gutlo_comment.id 
                            AND NOT gutlo_reply.id IN (select b.content_id from block_content as b where b.content_id = gutlo_reply.id and b.type = 2)
                            AND gutlo_reply.deleted_time IS NULL
                            AND NOT gutlo_reply.comment_id IN (select b.content_id from block_content as b where b.content_id = gutlo_reply.comment_id and b.type = 1)),0)
                            ) as total_comment ') ] )
                        ->where('to_post_id','=',$postId)
                        ->whereRaw('NOT gutlo_comment.id IN (select b.content_id from block_content as b where b.content_id = gutlo_comment.id and b.type = 1)')
                        ->whereNull('deleted_time')->first();
        if(empty($total_comment) || $total_comment->total_comment == null) return '0';
        else return $total_comment->total_comment;
    }

    public function getCommentHot($user_id, $id, $limit, $offset){
    	$data = DB::table('gutlo_comment')
            ->select(['gutlo_comment.mention_id','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_comment.hashtag_id','gutlo_id_vs_content.like_content','gutlo_id_vs_content.brick_content','gutlo_comment.id','gutlo_comment.total_reply','gutlo_comment.created_time','gutlo_comment.content','gutlo_comment.total_like','gutlo_comment.total_brick','gutlo_comment.total_point_com','users.nickname','users.username',
                    DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
            ->join('users','users.id','=','gutlo_comment.from_id')
            ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
            ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_comment.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id)
                        ->where('gutlo_id_vs_content.content_type','=',1);
            })
            ->join('gutlo_hot_comment','gutlo_hot_comment.comment_id','=','gutlo_comment.id')
            ->whereRaw('NOT gutlo_comment.id IN (select b.content_id from block_content as b where b.content_id = gutlo_comment.id and b.type = 1)')
            ->whereNull('gutlo_id_vs_content.report_id')
            ->whereNull('gutlo_comment.deleted_time')
            ->where('gutlo_comment.to_post_id','=',$id)
            ->orderBy('gutlo_hot_comment.rate','DESC')->take($limit)->skip($offset)->get();
        return $data;
    }

    public function getCommentFresh($user_id, $id, $limit, $offset) {
    	$data = DB::table('gutlo_comment')
            ->select(['gutlo_comment.mention_id','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_comment.hashtag_id','gutlo_id_vs_content.like_content','gutlo_id_vs_content.brick_content','gutlo_comment.id','gutlo_comment.total_reply','gutlo_comment.created_time','gutlo_comment.content','gutlo_comment.total_like','gutlo_comment.total_brick','gutlo_comment.total_point_com','users.nickname','users.username',
                    DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
            ->join('users','users.id','=','gutlo_comment.from_id')
            ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
            ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_comment.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id)
                        ->where('gutlo_id_vs_content.content_type','=',1);
            })
            ->whereRaw('NOT gutlo_comment.id IN (select b.content_id from block_content as b where b.content_id = gutlo_comment.id and b.type = 1)')
            ->whereNull('gutlo_id_vs_content.report_id')
            ->whereNull('gutlo_comment.deleted_time')
            ->where('gutlo_comment.to_post_id','=',$id)
            ->orderBy('gutlo_comment.created_time','DESC')->take($limit)->skip($offset)->get();
        return $data;
    }
    public function getCommentNotifi($id_post,$user_id,$id_comment){
        $data1 = DB::table('gutlo_comment')
            ->select(['gutlo_comment.mention_id','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_comment.hashtag_id','gutlo_id_vs_content.like_content','gutlo_id_vs_content.brick_content','gutlo_comment.id','gutlo_comment.total_reply','gutlo_comment.created_time','gutlo_comment.content','gutlo_comment.total_like','gutlo_comment.total_brick','gutlo_comment.total_point_com','users.nickname','users.username',
                    DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
            ->join('users','users.id','=','gutlo_comment.from_id')
            ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
            ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_comment.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id)
                        ->where('gutlo_id_vs_content.content_type','=',1);
            })
            ->whereRaw('NOT gutlo_comment.id IN (select b.content_id from block_content as b where b.content_id = gutlo_comment.id and b.type = 1)')
            ->whereNull('gutlo_id_vs_content.report_id')
            ->whereNull('gutlo_comment.deleted_time')
            ->where('gutlo_comment.id', '<',$id_comment)
            ->where('gutlo_comment.to_post_id','=',$id_post)
            ->orderBy('gutlo_comment.created_time','DESC')->limit(Config::get('Common.count_load_more_comment'));

        $data2 = DB::table('gutlo_comment')
            ->select(['gutlo_comment.mention_id','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_comment.hashtag_id','gutlo_id_vs_content.like_content','gutlo_id_vs_content.brick_content','gutlo_comment.id','gutlo_comment.total_reply','gutlo_comment.created_time','gutlo_comment.content','gutlo_comment.total_like','gutlo_comment.total_brick','gutlo_comment.total_point_com','users.nickname','users.username',
                    DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
            ->join('users','users.id','=','gutlo_comment.from_id')
            ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
            ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_comment.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id)
                        ->where('gutlo_id_vs_content.content_type','=',1);
            })
            ->whereRaw('NOT gutlo_comment.id IN (select b.content_id from block_content as b where b.content_id = gutlo_comment.id and b.type = 1)')
            ->whereNull('gutlo_id_vs_content.report_id')
            ->whereNull('gutlo_comment.deleted_time')
            ->whereRaw(' (( gutlo_comment.id < '.$id_comment .') OR ( gutlo_comment.id = '.$id_comment .'))')
            ->where('gutlo_comment.to_post_id','=',$id_post)
            ->orderBy('gutlo_comment.created_time','DESC')->limit(Config::get('Common.count_load_more_comment')+1)->get();
            $length_data_2  = COUNT($data2);
            $length_data_1 = COUNT($data1);
            $reply = new GutloReply();
            for($i = 0 ;$i < $length_data_2; $i++){
                $data2[$i]->count_replys = $reply->get_count_reply($data2[$i]->id,$user_id);
            }
            return $data2;
        return $data2;
    }

    public function getCommentNotifi_by_reply($id_post,$user_id,$id_reply){
        $data1 = DB::table('gutlo_comment')
            ->select(['gutlo_comment.mention_id','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_comment.hashtag_id','gutlo_id_vs_content.like_content','gutlo_id_vs_content.brick_content','gutlo_comment.id','gutlo_comment.total_reply','gutlo_comment.created_time','gutlo_comment.content','gutlo_comment.total_like','gutlo_comment.total_brick','gutlo_comment.total_point_com','users.nickname','users.username',
                    DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
            ->join('users','users.id','=','gutlo_comment.from_id')
            ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
            ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_comment.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id)
                        ->where('gutlo_id_vs_content.content_type','=',1);
            })
            ->whereRaw('NOT gutlo_comment.id IN (select b.content_id from block_content as b where b.content_id = gutlo_comment.id and b.type = 1)')
            ->whereNull('gutlo_id_vs_content.report_id')
            ->whereNull('gutlo_comment.deleted_time')
            ->where('(( gutlo_comment.id ','<',$id_comment )
            ->where('gutlo_comment.to_post_id','=',$id_post)
            ->orderBy('gutlo_comment.created_time','DESC')->limit(Config::get('Common.count_load_more_comment'));

        $data2 = DB::table('gutlo_comment')
            ->select(['gutlo_comment.mention_id','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_comment.hashtag_id','gutlo_id_vs_content.like_content','gutlo_id_vs_content.brick_content','gutlo_comment.id','gutlo_comment.total_reply','gutlo_comment.created_time','gutlo_comment.content','gutlo_comment.total_like','gutlo_comment.total_brick','gutlo_comment.total_point_com','users.nickname','users.username',
                    DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
            ->join('users','users.id','=','gutlo_comment.from_id')
            ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
            ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_comment.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id)
                        ->where('gutlo_id_vs_content.content_type','=',1);
            })
            ->whereRaw('NOT gutlo_comment.id IN (select b.content_id from block_content as b where b.content_id = gutlo_comment.id and b.type = 1)')
            ->whereNull('gutlo_id_vs_content.report_id')
            ->whereNull('gutlo_comment.deleted_time')
            ->whereRaw(' (( gutlo_comment.id < '.$id_comment .') OR ( gutlo_comment.id = '.$id_comment .'))')
            ->where('gutlo_comment.to_post_id','=',$id_post)
            ->orderBy('gutlo_comment.created_time','ASC')->limit(21)->get();

        $length_data_2  = COUNT($data2);
        $length_data_1 = COUNT($data1);
        $reply = new GutloReply();
        for($i = 0 ;$i < $length_data_2; $i++){
            $data2[$i]->count_replys = $reply->get_count_reply($data2[$i]->id,$user_id);
        }
        return $data2;
    }

    public function loadmore_new_comment($id_post,$user_id,$id_comment){
        $data = DB::table('gutlo_comment')
            ->select(['gutlo_comment.mention_id','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_comment.hashtag_id','gutlo_id_vs_content.like_content','gutlo_id_vs_content.brick_content','gutlo_comment.id','gutlo_comment.total_reply','gutlo_comment.created_time','gutlo_comment.content','gutlo_comment.total_like','gutlo_comment.total_brick','gutlo_comment.total_point_com','users.nickname','users.username',
                    DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
            ->join('users','users.id','=','gutlo_comment.from_id')
            ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
            ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_comment.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id)
                        ->where('gutlo_id_vs_content.content_type','=',1);
            })
            ->whereRaw('NOT gutlo_comment.id IN (select b.content_id from block_content as b where b.content_id = gutlo_comment.id and b.type = 1)')
            ->whereNull('gutlo_id_vs_content.report_id')
            ->whereNull('gutlo_comment.deleted_time')
            ->where('gutlo_comment.id','>',$id_comment )
            ->where('gutlo_comment.to_post_id','=',$id_post)
            ->orderBy('gutlo_comment.created_time','DESC')->limit(Config::get('Common.count_load_more_comment'))->get();
        $length_data = COUNT($data);
        $reply = new GutloReply();
        for($i = 0 ;$i < $length_data; $i++){
            $data[$i]->count_replys = $reply->get_count_reply($data[$i]->id,$user_id);
            $data[$i]->replys = array();
        }
        return $data;
    }

    public function loadmore_previous_comment($id_post,$user_id,$id_comment){
        $data = DB::table('gutlo_comment')
            ->select(['gutlo_comment.mention_id','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_comment.hashtag_id','gutlo_id_vs_content.like_content','gutlo_id_vs_content.brick_content','gutlo_comment.id','gutlo_comment.total_reply','gutlo_comment.created_time','gutlo_comment.content','gutlo_comment.total_like','gutlo_comment.total_brick','gutlo_comment.total_point_com','users.nickname','users.username',
                    DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
            ->join('users','users.id','=','gutlo_comment.from_id')
            ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
            ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_comment.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id)
                        ->where('gutlo_id_vs_content.content_type','=',1);
            })
            ->whereRaw('NOT gutlo_comment.id IN (select b.content_id from block_content as b where b.content_id = gutlo_comment.id and b.type = 1)')
            ->whereNull('gutlo_id_vs_content.report_id')
            ->whereNull('gutlo_comment.deleted_time')
            ->where('gutlo_comment.id','<',$id_comment )
            ->where('gutlo_comment.to_post_id','=',$id_post)
            ->orderBy('gutlo_comment.created_time','DESC')->limit(Config::get('Common.count_load_more_comment'))->get();
        $length_data = COUNT($data);
        $reply = new GutloReply();
        for($i = 0 ;$i < $length_data; $i++){
            $data[$i]->count_replys = $reply->get_count_reply($data[$i]->id,$user_id);
            $data[$i]->replys = array();
        }
        return $data;
    }

    public function get_count_comment($id,$user_id) {
         $data = DB::table('gutlo_comment')
                ->select([ DB::raw('COUNT(*) as count ') ])
                ->leftJoin('gutlo_id_vs_content',function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_comment.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id )
                        ->where('gutlo_id_vs_content.content_type','=',1);
                    })
                ->whereRaw('NOT gutlo_comment.id IN (select b.content_id from block_content as b where b.content_id = gutlo_comment.id and b.type = 1)')
                ->whereNull('gutlo_id_vs_content.report_id')
                ->where('gutlo_comment.to_post_id','=',$id)
                ->whereNull('gutlo_comment.deleted_time')
                ->first();
        return $data;
    }

    public function get_comments_ontime ($start_time,$end_time) {
        $comments = DB::table('gutlo_comment')->select('id','to_post_id AS post_id')
                                         ->where('created_time','>=',$start_time)
                                         ->where('created_time','<',$end_time)
                                        ->whereRaw('NOT gutlo_comment.id IN (select b.content_id from block_content as b where b.content_id = gutlo_comment.id and b.type = 1)')
                                        ->get();
        return $comments;
    }

    public function get_all_data_comment_by_post_id($post_id) {
        $data = DB::table('gutlo_comment')
            ->select([DB::raw('SUM(gutlo_comment.total_like) as total_like_comment ,SUM(gutlo_comment.total_brick) as total_brick_comment, SUM(gutlo_comment.total_point_com) as total_point_comment')])
            ->join('users','users.id','=','gutlo_comment.from_id')
            ->whereRaw('NOT gutlo_comment.id IN (select b.content_id from block_content as b where b.content_id = gutlo_comment.id and b.type = 1)')
            ->whereNull('gutlo_comment.deleted_time')
            ->where('gutlo_comment.to_post_id','=',$post_id)
            ->first();
        return $data;
    }
}