<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Shaphira\Common\CustomSql;
class GutloPosts extends Eloquent{
	use SoftDeletingTrait;
	protected $table = 'gutlo_posts';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	const DELETED_AT = 'deleted_time';

    public function list_post($user_id,$limit,$offset){
        $GutloPosts = DB::table('gutlo_posts')
                    ->select(['mp.type as type_media','mp.media_api_id','user_informations.gender','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_posts.mention_id','gutlo_posts.hashtag_id','gutlo_id_vs_content.like_content as like_able','users.confirmed','gutlo_id_vs_content.brick_content as brick_able','gutlo_medals.medal_name','gutlo_medals.medal_icon_url','gutlo_posts.*','users.id as user_id','users.user_level','users.nickname','users.username','gutlo_point.real_point',
                        DB::raw('
                            (SELECT count(*) from rate_content where content_id = gutlo_posts.id AND type_content = '.Config::get('Common.post_type').') as count_rate
                            ,(SELECT avg(rate_content.point) from rate_content where content_id = gutlo_posts.id AND type_content = '.Config::get('Common.post_type').') as rate_point 
                            ,CONCAT(mp.media_url,mp.media_name) AS image
                            ,CONCAT(mp.media_url_thumb,mp.media_name_thumb) AS image_thumb
                            ,CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
                    ->join('users', 'gutlo_posts.from_id', '=', 'users.id')
                    ->join('user_informations','user_informations.id','=','users.id')
                    ->join('gutlo_media','gutlo_media.id','=','users.avatar_id')
                    ->leftJoin('gutlo_media as mp','mp.id','=','gutlo_posts.media_id')
                    ->whereNull('gutlo_posts.deleted_time')
                    ->join('gutlo_point','users.id','=','gutlo_point.user_id')
                    ->leftJoin('gutlo_medals', function($join) {
                        $join->on('gutlo_point.real_point','>=','gutlo_medals.min_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.max_point')
                        ->orOn('gutlo_point.real_point','>=','gutlo_medals.max_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.min_point');
                    })
                    ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_posts.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id )
                        ->where('gutlo_id_vs_content.content_type','=',0);
                    })
                    ->whereRaw('NOT gutlo_posts.id IN (select b.content_id from block_content as b where b.content_id = gutlo_posts.id and b.type = 0)')
                    ->whereNull('gutlo_id_vs_content.report_id')
                    ->whereNull('gutlo_posts.deleted_time')
                    ->orderBy('gutlo_posts.created_time','DESC')->take($limit)->skip($offset)->get();
        return $GutloPosts;
    }

    public function list_post_hot($user_id,$limit,$offset){
        $GutloPosts = DB::table('gutlo_posts')
                    ->select(['mp.type as type_media','mp.media_api_id','user_informations.gender','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_posts.mention_id','gutlo_hot_posts.pre_rate','gutlo_hot_posts.now_rate','gutlo_posts.hashtag_id','users.confirmed','gutlo_id_vs_content.like_content as like_able','gutlo_id_vs_content.brick_content as brick_able','gutlo_medals.medal_name','gutlo_medals.medal_icon_url','gutlo_posts.*','users.user_level','users.id as user_id','users.nickname','users.username','gutlo_point.real_point',
                        DB::raw('CONCAT(mp.media_url,mp.media_name) AS image
                            ,CONCAT(mp.media_url_thumb,mp.media_name_thumb) AS image_thumb
                            ,CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
                    ->join('users', 'gutlo_posts.from_id', '=', 'users.id')
                    ->join('user_informations','user_informations.id','=','users.id')
                    ->join('gutlo_media','gutlo_media.id','=','users.avatar_id')
                    ->leftJoin('gutlo_media as mp','mp.id','=','gutlo_posts.media_id')
                    ->whereNull('gutlo_posts.deleted_time')
                    ->join('gutlo_point','users.id','=','gutlo_point.user_id')
                    ->leftJoin('gutlo_medals', function($join) {
                        $join->on('gutlo_point.real_point','>=','gutlo_medals.min_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.max_point')
                        ->orOn('gutlo_point.real_point','>=','gutlo_medals.max_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.min_point');
                    })
                    ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_posts.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id )
                        ->where('gutlo_id_vs_content.content_type','=',0);
                    })
                    ->join('gutlo_hot_posts','gutlo_hot_posts.id_post','=','gutlo_posts.id')
                    ->whereRaw('NOT gutlo_posts.id IN (select b.content_id from block_content as b where b.content_id = gutlo_posts.id and b.type = 0)')
                    ->whereNull('gutlo_id_vs_content.report_id')
                    ->whereNull('gutlo_posts.deleted_time')
                    ->orderBy('gutlo_posts.created_time','DESC')->take($limit)->skip($offset)->get();
        return $GutloPosts;
    }

	public function Update_point($id,$like,$brick,$delete = false){
		// -1 =< $like <= 1
		// -1 =< $brick <= 1
		$GutloPosts = GutloPosts::find($id);
		$GutloPosts->total_like = $GutloPosts->total_like  + $like;
		$GutloPosts->total_brick = $GutloPosts->total_brick + $brick;
		$GutloPosts->total_point = $GutloPosts->total_like + ( $GutloPosts->total_brick * (Config::get('Common.coe_brick')) );
		$GutloPosts->save();

		$from_id = $GutloPosts->from_id;
		if($delete){
			$GutloPosts = GutloPosts::find($id);
			$GutloPosts->delete();
		}
		$GutloPoint_user_post = new GutloPoint();
		$GutloPoint_user_post->update_real_point_user($from_id,$like,$brick,0,0  );
		if($GutloPoint_user_post['error'] == 'true')  return Response::json( $GutloPoint_user_post );

		return array('error'=>'false','msg'=>'','data'=> array('total_like'=>$GutloPosts->total_like,'total_brick'=> $GutloPosts->total_brick,'total_point'=>$GutloPosts->total_point));
	}

	public function get_post_by_category_trend($category_id,$trend_id,$user_id,$limit,$offset) {
		$GutloPosts = DB::table('gutlo_posts')
                    ->select(['mp.type as type_media','mp.media_api_id','user_informations.gender','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline'
                        ,'gutlo_posts.mention_id','gutlo_id_vs_content.like_content as like_able','gutlo_id_vs_content.brick_content as brick_able'
                        ,'gutlo_posts.hashtag_id','gutlo_medals.medal_name','gutlo_medals.medal_icon_url','gutlo_posts.*','users.confirmed'
                        ,'users.id as user_id','users.user_level','users.nickname','users.username','gutlo_point.real_point'
                        ,DB::raw('
                            (SELECT count(*) from rate_content where content_id = gutlo_posts.id AND type_content = '.Config::get('Common.post_type').') as count_rate
                            ,(SELECT avg(rate_content.point) from rate_content where content_id = gutlo_posts.id AND type_content = '.Config::get('Common.post_type').') as rate_point 
                            ,CONCAT(mp.media_url,mp.media_name) AS image
                            ,CONCAT(mp.media_url_thumb,mp.media_name_thumb) AS image_thumb
                            ,CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
                    ->join('users', 'gutlo_posts.from_id', '=', 'users.id')
                    ->join('user_informations','user_informations.id','=','users.id')
                    ->join('gutlo_media','gutlo_media.id','=','users.avatar_id')
                    ->leftJoin('gutlo_media as mp','mp.id','=','gutlo_posts.media_id')
                    ->whereNull('gutlo_posts.deleted_time')
                    ->join('gutlo_point','users.id','=','gutlo_point.user_id')
                    ->leftJoin('gutlo_medals', function($join) {
                        $join->on('gutlo_point.real_point','>=','gutlo_medals.min_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.max_point')
                        ->orOn('gutlo_point.real_point','>=','gutlo_medals.max_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.min_point');
                    })
                    ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_posts.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id )
                        ->where('gutlo_id_vs_content.content_type','=',0);
                    })
                    ->whereRaw('NOT gutlo_posts.id IN (select b.content_id from block_content as b where b.content_id = gutlo_posts.id and b.type = 0)')
                    ->where('gutlo_posts.category_id','=',$category_id)
                    ->whereNull('gutlo_id_vs_content.report_id')
                    ->whereNull('gutlo_posts.deleted_time')
                    ->orderBy('gutlo_posts.created_time','DESC')->take($limit)->skip($offset)->get();
        $postController = new PostController();
        $GutloPosts = $postController->SelectionSortDescending($GutloPosts);
        $activity = new ActivityController();
        $GutloPosts = $activity->replateEmoticon_on_post($GutloPosts);
        return $GutloPosts;
	}

	public function get_post_by_trend($trend_name,$user_id,$limit,$offset) {
		$GutloPosts = DB::table('gutlo_posts')
                    ->select(['mp.type as type_media','mp.media_api_id','user_informations.gender','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline'
                        ,'gutlo_posts.mention_id','gutlo_id_vs_content.like_content as like_able','gutlo_id_vs_content.brick_content as brick_able'
                        ,'gutlo_posts.hashtag_id','gutlo_medals.medal_name','gutlo_medals.medal_icon_url','gutlo_posts.*','users.confirmed'
                        ,'users.user_level','users.id as user_id','users.nickname','users.username','gutlo_point.real_point'
                        ,DB::raw('
                            (SELECT count(*) from rate_content where content_id = gutlo_posts.id AND type_content = '.Config::get('Common.post_type').') as count_rate
                            ,(SELECT avg(rate_content.point) from rate_content where content_id = gutlo_posts.id AND type_content = '.Config::get('Common.post_type').') as rate_point 
                            ,CONCAT(mp.media_url,mp.media_name) AS image
                            ,CONCAT(mp.media_url_thumb,mp.media_name_thumb) AS image_thumb
                            ,CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
                    ->join('users', 'gutlo_posts.from_id', '=', 'users.id')
                    ->join('user_informations','user_informations.id','=','users.id')
                    ->join('gutlo_media','gutlo_media.id','=','users.avatar_id')
                    ->leftJoin('gutlo_media as mp','mp.id','=','gutlo_posts.media_id')
                    ->whereNull('gutlo_posts.deleted_time')
                    ->join('gutlo_point','users.id','=','gutlo_point.user_id')
                    ->leftJoin('gutlo_medals', function($join) {
                        $join->on('gutlo_point.real_point','>=','gutlo_medals.min_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.max_point')
                        ->orOn('gutlo_point.real_point','>=','gutlo_medals.max_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.min_point');
                    })
                    ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_posts.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id )
                        ->where('gutlo_id_vs_content.content_type','=',0);
                    })
                    ->join('gutlo_hashtag', function($join) use($user_id) {
                        $join->on('gutlo_posts.id', '=', 'gutlo_posts.id');
                    })
                    ->whereRaw('NOT gutlo_posts.id IN (select b.content_id from block_content as b where b.content_id = gutlo_posts.id and b.type = 0)')
                    ->where('gutlo_hashtag.hashtag','=',''.$trend_name.'')
                    ->whereNull('gutlo_id_vs_content.report_id')
                    ->whereNull('gutlo_posts.deleted_time');
        //../Shaphira/Common/CustomSql.php
        $CustomSql = new CustomSql();
        $GutloPosts = $CustomSql->find_in_set($GutloPosts,'gutlo_hashtag.id','gutlo_posts.hashtag_id');
        // end
        $GutloPosts = $GutloPosts
                    ->orderBy('gutlo_posts.created_time','DESC')->take($limit)->skip($offset)->get();

        $postController = new PostController();
        $GutloPosts = $postController->SelectionSortDescending($GutloPosts);
        $activity = new ActivityController();
        $GutloPosts = $activity->replateEmoticon_on_post($GutloPosts);
        return $GutloPosts;
	}

    public function get_post_by_trend_all($user_id,$limit,$offset) {
        $GutloPosts = DB::table('gutlo_posts')
                    ->select([
                            'mp.type as type_media','mp.media_api_id','user_informations.gender','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline'
                            ,'gutlo_posts.mention_id','gutlo_id_vs_content.like_content as like_able','gutlo_id_vs_content.brick_content as brick_able'
                            ,'gutlo_posts.hashtag_id','gutlo_medals.medal_name','gutlo_medals.medal_icon_url','gutlo_posts.*','users.id as user_id'
                            ,'users.confirmed','users.nickname','users.user_level','users.username','gutlo_point.real_point'
                            ,DB::raw('
                                (SELECT count(*) from rate_content where content_id = gutlo_posts.id AND type_content = '.Config::get('Common.post_type').') as count_rate
                                ,(SELECT avg(rate_content.point) from rate_content where content_id = gutlo_posts.id AND type_content = '.Config::get('Common.post_type').') as rate_point 
                                ,CONCAT(mp.media_url,mp.media_name) AS image
                                ,CONCAT(mp.media_url_thumb,mp.media_name_thumb) AS image_thumb
                                ,CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
                    ->join('users', 'gutlo_posts.from_id', '=', 'users.id')
                    ->join('user_informations','user_informations.id','=','users.id')
                    ->join('gutlo_media','gutlo_media.id','=','users.avatar_id')
                    ->leftJoin('gutlo_media as mp','mp.id','=','gutlo_posts.media_id')
                    ->whereNull('gutlo_posts.deleted_time')
                    ->join('gutlo_point','users.id','=','gutlo_point.user_id')
                    ->leftJoin('gutlo_medals', function($join) {
                        $join->on('gutlo_point.real_point','>=','gutlo_medals.min_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.max_point')
                        ->orOn('gutlo_point.real_point','>=','gutlo_medals.max_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.min_point');
                    })
                    ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_posts.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id )
                        ->where('gutlo_id_vs_content.content_type','=',0);
                    })
                    ->join('gutlo_hashtag','gutlo_hashtag.id', '=', 'gutlo_posts.hashtag_id')
                    ->whereRaw('NOT gutlo_posts.id IN (select b.content_id from block_content as b where b.content_id = gutlo_posts.id and b.type = 0)')
                    ->whereNull('gutlo_id_vs_content.report_id')
                    ->whereNull('gutlo_posts.deleted_time')
                    ->orderBy('gutlo_posts.created_time','DESC')->take($limit)->skip($offset)->get();
        $postController = new PostController();
        $GutloPosts = $postController->SelectionSortDescending($GutloPosts);
        $activity = new ActivityController();
        $GutloPosts = $activity->replateEmoticon_on_post($GutloPosts);
        return $GutloPosts;
    }

    public function get_posts_ontime ($start_time,$end_time) {
        $posts = DB::table('gutlo_posts')->select('id')
                                         ->where('created_time','>=',$start_time)
                                         ->where('created_time','<',$end_time)->get();
        return $posts;
    }

    public function get_all_data_post_by_trend_id ($trend_id) {
        $GutloPosts = DB::table('gutlo_posts')
                    ->select(['mp.type as type_media','mp.media_api_id','gutlo_medals.medal_name','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_posts.id','gutlo_posts.total_brick','gutlo_posts.total_like','gutlo_posts.total_point','gutlo_posts.rate','users.id as user_id','gutlo_point.real_point'])
                    ->join('users', 'gutlo_posts.from_id', '=', 'users.id')
                    ->whereNull('gutlo_posts.deleted_time')
                    ->join('gutlo_point','users.id','=','gutlo_point.user_id')
                    ->leftJoin('gutlo_medals', function($join) {
                        $join->on('gutlo_point.real_point','>=','gutlo_medals.min_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.max_point')
                        ->orOn('gutlo_point.real_point','>=','gutlo_medals.max_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.min_point');
                    })
                    ->whereRaw('NOT gutlo_posts.id IN (select b.content_id from block_content as b where b.content_id = gutlo_posts.id and b.type = 0)')
                    ->whereNull('gutlo_posts.deleted_time');
        //../Shaphira/Common/CustomSql.php
        $CustomSql = new CustomSql();
        $GutloPosts = $CustomSql->find_in_set($GutloPosts,$trend_id,'gutlo_posts.hashtag_id');
        // end
        $GutloPosts = $GutloPosts
                    ->orderBy('gutlo_posts.created_time','DESC')->get();

        return $GutloPosts;
    }
    public function post_related_users ($user_id,$limit,$offset) {
        $CustomSql = new CustomSql();
        $post = DB::table('gutlo_posts')
                ->select(['mp.type as type_media','mp.media_api_id','user_informations.gender','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_posts.mention_id','gutlo_posts.hashtag_id','gutlo_id_vs_content.like_content as like_able','users.confirmed','gutlo_id_vs_content.brick_content as brick_able','gutlo_medals.medal_name','gutlo_medals.medal_icon_url','gutlo_posts.*','users.id as user_id','users.user_level','users.nickname','users.username','gutlo_point.real_point',
                        DB::raw('
                            (SELECT count(*) from rate_content where content_id = gutlo_posts.id AND type_content = '.Config::get('Common.post_type').') as count_rate
                            ,(SELECT avg(rate_content.point) from rate_content where content_id = gutlo_posts.id AND type_content = '.Config::get('Common.post_type').') as rate_point 
                            ,CONCAT(mp.media_url,mp.media_name) AS image
                            ,CONCAT(mp.media_url_thumb,mp.media_name_thumb) AS image_thumb
                            ,CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
                ->join('users', 'gutlo_posts.from_id', '=', 'users.id')
                ->join('user_informations','user_informations.id','=','users.id')
                ->join('gutlo_media','gutlo_media.id','=','users.avatar_id')
                ->leftJoin('gutlo_media as mp','mp.id','=','gutlo_posts.media_id')
                ->whereNull('gutlo_posts.deleted_time')
                ->join('gutlo_point','users.id','=','gutlo_point.user_id')
                ->leftJoin('gutlo_medals', function($join) {
                    $join->on('gutlo_point.real_point','>=','gutlo_medals.min_point')
                    ->on('gutlo_point.real_point','<=','gutlo_medals.max_point')
                    ->orOn('gutlo_point.real_point','>=','gutlo_medals.max_point')
                    ->on('gutlo_point.real_point','<=','gutlo_medals.min_point');
                })
                ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                    $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_posts.id')
                    ->where('gutlo_id_vs_content.user_id','=',$user_id )
                    ->where('gutlo_id_vs_content.content_type','=',0);
                })
                ->join('gutlo_activity_log','gutlo_activity_log.post_id','=','gutlo_posts.id');
        $post = $CustomSql->find_not_in_set($post,$user_id,'gutlo_posts.mention_id');
        $post = $post
                    ->whereRaw('NOT gutlo_posts.id IN (select b.content_id from block_content as b where b.content_id = gutlo_posts.id and b.type = 0)')
                    ->Where('gutlo_posts.from_id','<>',$user_id)
                    ->where('gutlo_activity_log.from_id','=',$user_id)
                    ->whereNull('gutlo_id_vs_content.report_id')
                    ->whereNull('gutlo_posts.deleted_time')
                    ->groupBy('gutlo_posts.id')
                    ->orderBy('gutlo_posts.created_time','DESC')->take($limit)->skip($offset)->get();
        return $post;
    }

    public function post_of_users ($user_id,$limit,$offset) {
        $CustomSql = new CustomSql();
        $post = DB::table('gutlo_posts')
                ->select(['mp.type as type_media','mp.media_api_id','user_informations.gender','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_posts.mention_id','gutlo_posts.hashtag_id','gutlo_id_vs_content.like_content as like_able','users.confirmed','gutlo_id_vs_content.brick_content as brick_able','gutlo_medals.medal_name','gutlo_medals.medal_icon_url','gutlo_posts.*','users.id as user_id','users.user_level','users.nickname','users.username','gutlo_point.real_point',
                        DB::raw('
                            (SELECT count(*) from rate_content where content_id = gutlo_posts.id AND type_content = '.Config::get('Common.post_type').') as count_rate
                            ,(SELECT avg(rate_content.point) from rate_content where content_id = gutlo_posts.id AND type_content = '.Config::get('Common.post_type').') as rate_point 
                            ,CONCAT(mp.media_url,mp.media_name) AS image
                            ,CONCAT(mp.media_url_thumb,mp.media_name_thumb) AS image_thumb
                            ,CONCAT(gutlo_media.media_url,gutlo_media.media_name) AS ava')])
                ->join('users', 'gutlo_posts.from_id', '=', 'users.id')
                    ->join('user_informations','user_informations.id','=','users.id')
                    ->join('gutlo_media','gutlo_media.id','=','users.avatar_id')
                    ->leftJoin('gutlo_media as mp','mp.id','=','gutlo_posts.media_id')
                    ->whereNull('gutlo_posts.deleted_time')
                    ->join('gutlo_point','users.id','=','gutlo_point.user_id')
                    ->leftJoin('gutlo_medals', function($join) {
                        $join->on('gutlo_point.real_point','>=','gutlo_medals.min_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.max_point')
                        ->orOn('gutlo_point.real_point','>=','gutlo_medals.max_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.min_point');
                    })
                    ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_posts.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id )
                        ->where('gutlo_id_vs_content.content_type','=',0);
                    });
        $post = $CustomSql->find_in_set($post,$user_id,'gutlo_posts.mention_id');
        $post = $post
                    ->orWhere('gutlo_posts.from_id','=',$user_id)
                    ->whereRaw('NOT gutlo_posts.id IN (select b.content_id from block_content as b where b.content_id = gutlo_posts.id and b.type = 0)')
                    ->whereNull('gutlo_id_vs_content.report_id')
                    ->whereNull('gutlo_posts.deleted_time')
                    ->orderBy('gutlo_posts.created_time','DESC')->take($limit)->skip($offset)->get();
        return $post;
    }

}