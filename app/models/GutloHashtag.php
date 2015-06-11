<?php
use Shaphira\Common\CustomSql;
class GutloHashtag extends Eloquent{

	protected $table = 'gutlo_hashtag';
	protected $primaryKey = 'id';
	public $timestamps = false;

	public function get_trend_by_category_id($id){
		$trends = DB::table('gutlo_hashtag')->select('hashtag')
							->join('gutlo_posts','gutlo_hashtag.id','=','gutlo_posts.hashtag_id')
							->where('gutlo_hashtag.category_id','=',$id)
							->whereNull('gutlo_posts.deleted_time')->groupBy('hashtag')->get();
		return $trends;
	}

	public function get_trend_by_category_code($code){
		$trends = DB::table('gutlo_hashtag')->select('hashtag')
								->join('gutlo_categories','gutlo_hashtag.category_id','=','gutlo_categories.id')
								->join('gutlo_posts','gutlo_posts.hashtag_id','=','gutlo_hashtag.id')
								->whereNull('gutlo_posts.deleted_time')
								->where('gutlo_categories.cate_code','=',$code)->groupBy('hashtag')->get();
		return $trends;
	}


	public function list_hot_trend ($limit, $offset) {
		$trends = DB::table('gutlo_hashtag')->select(['gutlo_posts.*','hashtag',DB::raw('COUNT(*) as count')])
					->join('gutlo_posts','gutlo_hashtag.id','=','gutlo_posts.hashtag_id')
					->whereRaw('total_point = (select max(`total_point`) from gutlo_posts WHERE gutlo_posts.hashtag_id = gutlo_hashtag.id)')
					->whereNull('gutlo_posts.deleted_time')
					->groupBy('hashtag')->orderBy('count','DESC')->take($limit)->skip($offset)->get();
		return $trends;
	}
	public function update_count_post($count,$id){
		$data_update = array('total_post'=>$count);
		DB::table('gutlo_hashtag')->where('id','=',$id)
						->increment('total_post', $count);

	}
	public function list_hashtag () {
		$hashtags = DB::table('gutlo_hashtag')
        ->select(['gutlo_hashtag.id','gutlo_hashtag.hashtag',DB::raw('SUM(gutlo_hashtag.total_post) as total_post ')])
        ->join('gutlo_posts','gutlo_hashtag.id','=','gutlo_hashtag.id');
       //../Shaphira/Common/CustomSql.php
        $CustomSql = new CustomSql();
        $hashtags = $CustomSql->find_in_set($hashtags,'gutlo_hashtag.id','gutlo_posts.hashtag_id');
        // end
        $hashtags = $hashtags
                ->whereNull('gutlo_posts.deleted_time')
                ->groupBy('gutlo_hashtag.hashtag')->get();
        return $hashtags;
	}
	public function listFavorHashtag($user_id){
		$data = DB::table('gutlo_hashtag')->select('gutlo_hashtag.hashtag')
											->join('favorite_hashtag','favorite_hashtag.hashtag','=','gutlo_hashtag.hashtag')
											->where('favorite_hashtag.favorite','=',1)
											->where('favorite_hashtag.user_id','=',$user_id)
											->groupBy('gutlo_hashtag.hashtag')
											->get();
		return $data;
	}
}