<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class GutloReply extends Eloquent{
    use SoftDeletingTrait;
    protected $table = 'gutlo_reply';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dates = ['deleted_at'];
    const DELETED_AT = 'deleted_time';
    public function Update_point($id,$like,$brick,$delete = false){
        // -1 =< $like <= 1
        // -1 =< $brick <= 1
        $GutloReply = GutloReply::find($id);
        $GutloReply->total_like = $GutloReply->total_like  + $like;
        $GutloReply->total_brick = $GutloReply->total_brick + $brick;
        $GutloReply->total_point = $GutloReply->total_like + ( $GutloReply->total_brick * (Config::get('Common.coe_brick')) );
        $GutloReply->save();

        $from_id = $GutloReply->from_id;
        if($delete){
            $GutloReply = GutloReply::find($id);
            $GutloReply->delete();
        }
        $GutloPoint_user_post = new GutloPoint();
        $GutloPoint_user_post->update_real_point_user($from_id,$like,$brick,0,0  );
        if($GutloPoint_user_post['error'] == 'true')  return Response::json( $GutloPoint_user_post );

        return array('error'=>'false','msg'=>'','data'=> array('total_like'=>$GutloReply->total_like,'total_brick'=> $GutloReply->total_brick,'total_point'=>$GutloReply->total_point));
    }

     public function get_count_reply($id,$user_id) {
        $data = DB::table('gutlo_reply')
                ->select([ DB::raw('COUNT(*) as count ') ])
                ->leftJoin('gutlo_id_vs_content',function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_reply.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id )
                        ->where('gutlo_id_vs_content.content_type','=',2);
                    })
                ->whereRaw('NOT gutlo_reply.id IN (select b.content_id from block_content as b where b.content_id = gutlo_reply.id and b.type = 2)')
                ->whereNull('gutlo_id_vs_content.report_id')
                ->where('gutlo_reply.comment_id','=',$id)
                ->whereNull('gutlo_reply.deleted_time')
                ->first();
        return $data;
    }
    public function get_reply_with_min_id($user_id,$id = null,$id_comment) {
        $data = null;
        $count_max = 0;
        $count_min = 0;
        if($id != null ) {
            // DB:: raw can chinh sua lai khi chuyen he thong database
            $data1 = DB::table('gutlo_reply')
                ->select(['gutlo_reply.mention_id','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_reply.hashtag_id','gutlo_id_vs_content.like_content','gutlo_id_vs_content.brick_content','gutlo_reply.comment_id','gutlo_reply.id','gutlo_reply.created_time','gutlo_reply.content','gutlo_reply.total_like','gutlo_reply.created_time','gutlo_reply.total_brick','gutlo_reply.total_point','users.nickname','users.username',
                        DB::raw('(select max(id) from gutlo_reply where comment_id = '.$id_comment.' ) as max_id_all
                                ,(select min(id) from gutlo_reply where comment_id = '.$id_comment.' ) as min_id_all
                                ,CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
                ->join('users','users.id','=','gutlo_reply.from_id')
                ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
                 ->leftJoin('gutlo_id_vs_content', function($join) use( $user_id ) {
                            $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_reply.id')
                            ->where('gutlo_id_vs_content.user_id','=',$user_id)
                            ->where('gutlo_id_vs_content.content_type','=',2);
                })
                ->whereRaw('NOT gutlo_reply.id IN (select b.content_id from block_content as b where b.content_id = gutlo_reply.id and b.type = 2)')
                ->where('gutlo_reply.id','<',$id)
                ->where('gutlo_reply.comment_id','=',$id_comment)
                ->whereNull('gutlo_id_vs_content.report_id')
                ->whereNull('gutlo_reply.deleted_time')
                ->orderBy('gutlo_reply.created_time','DESC')->limit(Config::get('Common.count_load_more_reply'))->get();

                $data2 = DB::table('gutlo_reply')
                ->select(['gutlo_reply.mention_id','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_reply.hashtag_id','gutlo_id_vs_content.like_content','gutlo_id_vs_content.brick_content','gutlo_reply.comment_id','gutlo_reply.id','gutlo_reply.created_time','gutlo_reply.content','gutlo_reply.total_like','gutlo_reply.created_time','gutlo_reply.total_brick','gutlo_reply.total_point','users.nickname','users.username',
                        DB::raw('(select max(id) from gutlo_reply where comment_id = '.$id_comment.' ) as max_id_all
                            ,(select min(id) from gutlo_reply where comment_id = '.$id_comment.' ) as min_id_all
                            ,CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
                ->join('users','users.id','=','gutlo_reply.from_id')
                ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
                 ->leftJoin('gutlo_id_vs_content', function($join) use( $user_id ) {
                            $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_reply.id')
                            ->where('gutlo_id_vs_content.user_id','=',$user_id)
                            ->where('gutlo_id_vs_content.content_type','=',2);
                })
                ->whereRaw('NOT gutlo_reply.id IN (select b.content_id from block_content as b where b.content_id = gutlo_reply.id and b.type = 2)')
                ->where('gutlo_reply.id', '=',$id)
                ->where('gutlo_reply.comment_id','=',$id_comment)
                ->whereNull('gutlo_id_vs_content.report_id')
                ->whereNull('gutlo_reply.deleted_time')
                ->orderBy('gutlo_reply.created_time','ASC')->limit(1)->get();

                $length_data_2  = COUNT($data2);
                $length_data_1 = COUNT($data1);
                for($i = 0 ;$i < $length_data_2; $i++){
                    $data1[$length_data_1+$i] = $data2[$i];
                }
                $data = $data1;

        }else {
            $data = DB::table('gutlo_reply')
                ->select(['gutlo_reply.mention_id','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_reply.hashtag_id','gutlo_id_vs_content.like_content','gutlo_id_vs_content.brick_content','gutlo_reply.comment_id','gutlo_reply.id','gutlo_reply.created_time','gutlo_reply.content','gutlo_reply.total_like','gutlo_reply.created_time','gutlo_reply.total_brick','gutlo_reply.total_point','users.nickname','users.username',
                        DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
                ->join('users','users.id','=','gutlo_reply.from_id')
                ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
                 ->leftJoin('gutlo_id_vs_content', function($join) use( $user_id ) {
                            $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_reply.id')
                            ->where('gutlo_id_vs_content.user_id','=',$user_id)
                            ->where('gutlo_id_vs_content.content_type','=',2);
                })
                ->whereRaw('NOT gutlo_reply.id IN (select b.content_id from block_content as b where b.content_id = gutlo_reply.id and b.type = 2)')
                ->where('gutlo_reply.comment_id','=',$id_comment)
                ->whereNull('gutlo_id_vs_content.report_id')
                ->whereNull('gutlo_reply.deleted_time')
                ->orderBy('gutlo_reply.created_time','DESC')->take(Config::get('Common.count_load_more_reply'))->skip(0)->get();

        }

        return array('data'=>$data);
    }

    public function loadmore_new_reply($user_id,$id = null,$id_comment,$id_min_new = 0){
       
        $raw = '(select max(id) from gutlo_reply where comment_id = '.$id_comment.' ) as max_id_all';
        if($id_min_new != 0 && $id_min_new != '0'){
            $raw = '(select max(id) from gutlo_reply where comment_id = '.$id_comment.' AND id < '.$id_min_new.') as max_id_all';
        }
        $data = DB::table('gutlo_reply')
                ->select(['gutlo_reply.id as ir','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_reply.mention_id','gutlo_reply.hashtag_id','gutlo_id_vs_content.like_content','gutlo_id_vs_content.brick_content','gutlo_reply.comment_id','gutlo_reply.id','gutlo_reply.created_time','gutlo_reply.content','gutlo_reply.total_like','gutlo_reply.created_time','gutlo_reply.total_brick','gutlo_reply.total_point','users.nickname','users.username',
                        DB::raw($raw.',CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
                ->join('users','users.id','=','gutlo_reply.from_id')
                ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
                 ->leftJoin('gutlo_id_vs_content', function($join) use( $user_id ) {
                            $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_reply.id')
                            ->where('gutlo_id_vs_content.user_id','=',$user_id)
                            ->where('gutlo_id_vs_content.content_type','=',2);
                });
        if($id != null ){
            $data = $data->where('gutlo_reply.id','>',$id);
        }
        if($id_min_new != 0 && $id_min_new != '0'){
            $data = $data->where('gutlo_reply.id','<',$id_min_new);
        }
        $data = $data->where('gutlo_reply.comment_id','=',$id_comment)

                ->whereRaw('NOT gutlo_reply.id IN (select b.content_id from block_content as b where b.content_id = gutlo_reply.id and b.type = 2)')
                ->whereNull('gutlo_id_vs_content.report_id')
                ->whereNull('gutlo_reply.deleted_time')
                ->orderBy('gutlo_reply.created_time','ASC')->limit(Config::get('Common.count_load_more_reply'))->get();
        return $data;
    }

    public function loadmore_previous_reply($user_id,$id = null,$id_comment){
        $data = DB::table('gutlo_reply')
                ->select(['gutlo_reply.id as ir','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_reply.mention_id','gutlo_reply.hashtag_id','gutlo_id_vs_content.like_content','gutlo_id_vs_content.brick_content','gutlo_reply.comment_id','gutlo_reply.id','gutlo_reply.created_time','gutlo_reply.content','gutlo_reply.total_like','gutlo_reply.created_time','gutlo_reply.total_brick','gutlo_reply.total_point','users.nickname','users.username',
                        DB::raw('(select min(id) from gutlo_reply where comment_id = '.$id_comment.' ) as min_id_all
                                ,CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
                ->join('users','users.id','=','gutlo_reply.from_id')
                ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
                 ->leftJoin('gutlo_id_vs_content', function($join) use( $user_id ) {
                            $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_reply.id')
                            ->where('gutlo_id_vs_content.user_id','=',$user_id)
                            ->where('gutlo_id_vs_content.content_type','=',2);
                })
                ->whereRaw('NOT gutlo_reply.id IN (select b.content_id from block_content as b where b.content_id = gutlo_reply.id and b.type = 2)')
                ->where('gutlo_reply.id','<',$id)
                ->where('gutlo_reply.comment_id','=',$id_comment)
                ->whereNull('gutlo_id_vs_content.report_id')
                ->whereNull('gutlo_reply.deleted_time')
                ->orderBy('gutlo_reply.created_time','DESC')->limit(Config::get('Common.count_load_more_reply'))->get();
        return $data;
    }

    public function get_replies_ontime ($start_time,$end_time) {
        $replies = DB::table('gutlo_reply')->select('gutlo_reply.id','gutlo_comment.to_post_id as post_id')
                                        ->join('gutlo_comment','gutlo_reply.comment_id','=','gutlo_comment.id')
                                         ->where('gutlo_reply.created_time','>=',$start_time)
                                         ->where('gutlo_reply.created_time','<',$end_time)
                                        ->whereRaw('NOT gutlo_reply.id IN (select b.content_id from block_content as b where b.content_id = gutlo_reply.id and b.type = 2)')
                                         ->get();
        return $replies;
    }

    public function get_sum_data_reply_by_post_id ($post_id) {
        $data = DB::table('gutlo_reply')
                ->select([DB::raw('SUM(gutlo_reply.total_like) as total_like_reply ,SUM(gutlo_reply.total_brick) as total_brick_reply, SUM(gutlo_reply.total_point) as total_point_reply')])
                ->join('users','users.id','=','gutlo_reply.from_id')
                ->join('gutlo_comment','gutlo_comment.id','=','gutlo_reply.comment_id')
                ->whereRaw('NOT gutlo_reply.id IN (select b.content_id from block_content as b where b.content_id = gutlo_reply.id and b.type = 2)')
                ->where('gutlo_comment.to_post_id','=',$post_id)
                ->whereNull('gutlo_reply.deleted_time')
                ->first();
        return $data;
    }

}