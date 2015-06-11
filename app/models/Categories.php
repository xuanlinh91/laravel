<?php

class Categories extends Eloquent{

	protected $table = 'gutlo_categories';
	protected $primaryKey = 'id';
	public $timestamps =false;

	public function get_categories ($category_id) {
		$categories = DB::table('gutlo_categories')->select('name','cate_code','id')->whereIn('id', explode(",",$category_id))->get();
		return $categories;
	}

	public function get_list_categories_and_trend () {
		$categories = DB::table('gutlo_categories')->select('name','id')->get();
		$length = count($categories);
		$GutloComment_count = new GutloComment();
		for($i = $length - 1 ; $i >= 0 ; $i --){
			$trends = new GutloHashtag();
			$trends = $trends->get_trend_by_category_id($categories[$i]->id);
			if(!empty($trends)) $categories[$i]->trend = $trends;
			else $categories[$i]->trend = array();

			$posts = DB::table('gutlo_posts')->select('id','total_like','total_brick','total_point')
											->whereNull('deleted_time')
											->where('category_id','=',$categories[$i]->id)->get();
			$length_post = count($posts);
			$total_point = 0; $total_like = 0; $total_brick = 0;$total_comment = 0;
			for($j = $length_post - 1 ; $j >=0; $j--){
				if( $posts[$j]->total_point != null ) $total_point = $total_point + $posts[$j]->total_point;
				if( $posts[$j]->total_like != null ) $total_like = $total_like + $posts[$j]->total_like;
				if( $posts[$j]->total_brick != null ) $total_brick = $total_brick + $posts[$j]->total_brick;
				$total_comment - $total_comment + $GutloComment_count->get_total_comment($posts[$j]->id);
			}
			$categories[$i]->total_point = $total_point;
			$categories[$i]->total_like = $total_like;
			$categories[$i]->total_brick = $total_brick;
			$categories[$i]->total_comment = $total_comment;
		}
		return $categories;
	}

	public function get_show_category ($name) {
		$user_id = 0 ;
		if(Auth::check()) $user_id = Auth::user()->id ;
		$data = DB::table('gutlo_categories')->select('favorite_category.favorite','gutlo_categories.id','gutlo_categories.cate_code','gutlo_hashtag.hashtag','gutlo_categories.id as Category_id','gutlo_categories.name','gutlo_hashtag.id as trend_id')
		->leftJoin('gutlo_hashtag','gutlo_hashtag.category_id','=','gutlo_categories.id')
		->leftjoin('favorite_category', function($join) use($user_id){
                        $join->on('favorite_category.category_id','=','gutlo_categories.id')
                        ->where('favorite_category.user_id','=',$user_id);
                    })
		->where('gutlo_categories.cate_code','=',$name)->first();
		return $data;
	}
	public function listFavorCate($user_id){
		$data = DB::table('gutlo_categories')->select('gutlo_categories.id','gutlo_categories.name','gutlo_categories.cate_code as code')
											->join('favorite_category','favorite_category.category_id','=','gutlo_categories.id')
											->where('favorite_category.favorite','=',1)
											->where('favorite_category.user_id','=',$user_id)
											->get();
		return $data;
	}
}