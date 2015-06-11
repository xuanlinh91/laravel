<?php 
use Shaphira\Common\Common;
use Shaphira\Common\HashtagMention;
use Shaphira\Common\CustomSql;
class TrendController extends BaseController{
	protected $GutloHashtag ;
	public function __construct () { 
		$this->GutloHashtag = new GutloHashtag();
	}

	public function list_trends() {
		$offset = Input::get('count');
        $limit = 10;
        if($offset == null){
            $offset = 0 ; 
        }
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
		// $trends = $this->GutloHashtag->list_hot_trend($limit,$offset);
		$GutloPosts = new GutloPosts();
		$post = $GutloPosts->get_post_by_trend_all($user_id,$limit,$offset);
		return $post;

	}

	public function list_trends_by_category_code($cate_code) {
		$trends = $this->GutloHashtag->get_trend_by_category_code($cate_code);
		return $trends;

	}
	public function update_count_post($count,$id) {
		$this->GutloHashtag->update_count_post($count,$id);
	}

	public function show_trend($name){
		$CustomSql = new CustomSql();
		$count_user_favor = 0;
		$data_count_post = 0;
		$user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
		$offset = Input::get('count');
        $limit = 10;
        if($offset == null){
            $offset = 0 ; 
        }
        $favorite = 0;
  //       $common = new Common();
		// $name = $common->encryptor('decrypt',$name);
		$GutloPosts = new GutloPosts();
		$data = $GutloPosts->get_post_by_trend($name,$user_id,$limit,$offset);
		$data_user_favor = DB::table('favorite_hashtag')->select(DB::raw('COUNT(*) as count'))->where('hashtag','=',$name)->first();
        if(!empty($data_user_favor))  $count_user_favor = $data_user_favor->count;
        $data_count_post = DB::table('gutlo_posts')->select(DB::raw('COUNT(*) as count'))
        											->join('gutlo_hashtag','gutlo_posts.id','=','gutlo_posts.id')
        											->where('gutlo_hashtag.hashtag','=',$name);
		$data_count_post = $CustomSql->find_in_set($data_count_post,'gutlo_hashtag.id','gutlo_posts.hashtag_id');
		$data_count_post = $data_count_post->first();
        if(!empty($data_count_post))  $count_post = $data_count_post->count;

		$data_favor = DB::table('favorite_hashtag')->select('favorite')->where('hashtag','=',$name)->where('user_id','=',$user_id)->first();
		if(!empty($data_favor)) $favorite = $data_favor->favorite;

		return array('error'=>'false','msg'=>'','data'=>$data,'name'=>$name,'count_post'=>$count_post,'count_user_favor'=>$count_user_favor,'favorite'=>$favorite);
	}

	public function favor_hashtag() {
        $name = '';
        $user_id = 0;
        $favorite = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        else return array('error'=>'true','msg'=>'Vui lòng đăng nhập để sử dụng chức năng này !');
        if(Input::get('name') != null ) $name = Input::get('name');
        else return array('error'=>'true','msg'=>'có lỗi phát sinh vui lòng thử lại !');
        $FavoriteCategory = new FavoriteCategory();
        $favor =DB::table('favorite_hashtag')->select('id','favorite')->where('hashtag','=',$name)->where('user_id','=',$user_id)->first();

        if(empty($favor)){
            $FavoriteHashtag = new FavoriteHashtag();
            $FavoriteHashtag->hashtag = $name;
            $FavoriteHashtag->user_id = $user_id;
            $FavoriteHashtag->favorite = 1;
            $FavoriteHashtag->created_time = \Carbon\Carbon::now()->toDateTimeString();
            $FavoriteHashtag->save();
            $favorite = 1;
        }else if($favor->favorite == 1){
            $data_update = array('favorite'=>0,'updated_time'=>\Carbon\Carbon::now()->toDateTimeString());
            DB::table('favorite_hashtag')->select('id','favorite')->where('hashtag','=',$name)->where('user_id','=',$user_id)->update($data_update);
        }else {
            $data_update = array('favorite'=>1,'updated_time'=>\Carbon\Carbon::now()->toDateTimeString());
            DB::table('favorite_hashtag')->select('id','favorite')->where('hashtag','=',$name)->where('user_id','=',$user_id)->update($data_update);
            $favorite = 1;
        }
        return array('error'=>'false','msg'=>'','data'=>$favorite);
    }

	public function list_hashtag () {
		return $this->GutloHashtag->list_hashtag();
	}

     public function loadFavorHashtag() {
        $user_id = 0 ;
        if(Auth::check()) $user_id = Auth::user()->id;
        else return array('error'=>'true','msg'=>'vui lòng đăng nhập để sử dụng chức năng này !');
        $data = $this->GutloHashtag->listFavorHashtag($user_id);
        return array('error'=>'false','msg'=>'','data'=>$data);
    }
}