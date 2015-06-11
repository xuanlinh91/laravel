<?php
use Shaphira\Common\Common;
use Shaphira\Common\HashtagMention;
class CategoryController extends BaseController{

    public function __construct () {

    }
    public function list_categories() {
    	$Category = new Categories();
    	$categories = $Category->get_list_categories_and_trend();
    	return $categories;
    }

    public function show_categories($code){
    	$user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
    	$offset = Input::get('count');
        $limit = 10;
        if($offset == null){
            $offset = 0 ;
        }
    	$Categories = new Categories();
        $GutloPosts = new GutloPosts();
        $TrendController = new TrendController();
        $count_user_favor = 0;
        $count_post = 0;
        $trends = $TrendController->list_trends_by_category_code($code);
    	$Category = $Categories->get_show_category($code);
        $data_user_favor = DB::table('favorite_category')->select(DB::raw('COUNT(*) as count'))->where('category_id','=',$Category->id)->first();
        if(!empty($data_user_favor))  $count_user_favor = $data_user_favor->count;
        $data_count_post = DB::table('gutlo_posts')->select(DB::raw('COUNT(*) as count'))->where('category_id','=',$Category->id)->first();
        if(!empty($data_count_post))  $count_post = $data_count_post->count;
    	// $Category->posts = $GutloPosts->get_post_by_category_trend($Category->Category_id,$Category->trend_id,$user_id,$limit,$offset);
    	return array('data'=>$Category,'trends_category'=>$trends,'total_post'=>$count_post,'count_user'=>$count_user_favor);
    }
    public function loadMorePostOnCategories ($code) {
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        $offset = Input::get('count');
        $limit = 10;
        if($offset == null){
            $offset = 0 ;
        }
        $Categories = new Categories();
        $GutloPosts = new GutloPosts();
        $Category = $Categories->get_show_category($code);
        $Category->posts = $GutloPosts->get_post_by_category_trend($Category->Category_id,$Category->trend_id,$user_id,$limit,$offset);
        return array('data'=>$Category);
    }
    function cache_catgories() {
        $categories = null;
        Cache::forget('categories');
        if(Cache::has('categories'))  $categories = Cache::get('categories');
        else {
            $categories = DB::table('gutlo_categories')->select('name','cate_code','id')->get();
            Cache::forever('categories', $categories);
        }
        return $categories;
    }

    public function favor_Cate() {
        $id = 0;
        $user_id = 0;
        $favorite = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        else return array('error'=>'true','msg'=>'Vui lòng đăng nhập để sử dụng chức năng này !');
        if(Input::get('id') != null ) $id = Input::get('id');
        else return array('error'=>'true','msg'=>'có lỗi phát sinh vui lòng thử lại !');
        $FavoriteCategory = new FavoriteCategory();
        $favor =DB::table('favorite_category')->select('id','favorite')->where('category_id','=',$id)->where('user_id','=',$user_id)->first();

        if(empty($favor)){
            $FavoriteCategory = new FavoriteCategory();
            $FavoriteCategory->category_id = $id;
            $FavoriteCategory->user_id = $user_id;
            $FavoriteCategory->favorite = 1;
            $FavoriteCategory->created_time = \Carbon\Carbon::now()->toDateTimeString();
            $FavoriteCategory->save();
            $favorite = 1;
        }else if($favor->favorite == 1){
            $data_update = array('favorite'=>0,'updated_time'=>\Carbon\Carbon::now()->toDateTimeString());
            DB::table('favorite_category')->select('id','favorite')->where('category_id','=',$id)->where('user_id','=',$user_id)->update($data_update);
        }else {
            $data_update = array('favorite'=>1,'updated_time'=>\Carbon\Carbon::now()->toDateTimeString());
            DB::table('favorite_category')->select('id','favorite')->where('category_id','=',$id)->where('user_id','=',$user_id)->update($data_update);
            $favorite = 1;
        }
        return array('error'=>'false','msg'=>'','data'=>$favorite);
    }

    public function loadFavorCate() {
        $user_id = 0 ;
        if(Auth::check()) $user_id = Auth::user()->id;
        else return array('error'=>'true','msg'=>'vui lòng đăng nhập để sử dụng chức năng này !');
        $Category = new Categories();
        $data = $Category->listFavorCate($user_id);
        return array('error'=>'false','msg'=>'','data'=>$data);
    }
}