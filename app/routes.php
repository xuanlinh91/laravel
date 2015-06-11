<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('a-bc',array('as'=>'abc',function(){
	return View::make('test.linh');
}));


/*tam thoi dua fresh len dau de tranh viec kho su dung trong test*/
Route::get('/',array('as'=>'/', function(){
	$data = APP::make('PostController')->listPost_Hot();
	return View::make('users.home',compact('data'));
}));
Route::get('/testanalytic',array('as'=>'test_analytic', function(){
	return View::make('admin.testanalytic');
}));

Route::get('/8-privacy',array('as'=>'privacy', function(){
	return View::make('gutlo.privacy');
}));

Route::get('/8-terms',array('as'=>'terms', function(){
	return View::make('gutlo.terms');
}));

Route::get('/8-advertising',array('as'=>'advertising', function(){
	return View::make('gutlo.advertising');
}));

Route::get('/8-aboutUs',array('as'=>'aboutUs', function(){
	return View::make('gutlo.aboutUs');
}));

Route::get('/Callphp',array('as'=>'Callphp', function(){
	return Response::json(array('la' => 'ddd' ));
}));

Route::get('fresh',array('as'=>'fresh',function(){
	$data = APP::make('PostController')->listPost();
	return View::make('users.fresh',compact('data'));
}));

Route::get('/posts/{id}',array('as'=>'show_posts',function($id) {
	if(!isset($_GET['notifi_id'])){
		$data = APP::make('PostController')->showPost($id);
		if(empty($data)){
			$data = APP::make('PostController')->listPost();
			return Redirect::to('fresh')->with(array('data'=>$data));
		} else $data = $data[0];

		return View::make('users.show',compact('data'));
	} else {
		$data = APP::make('NotificationController')->showNotification($_GET['notifi_id']);
		if($data['error'] == 'false'){

			return View::make('users.showNotification',array('data'=>$data['data']));
		} else {

			return View::make('users.ErrorNotification');
		}
	}
}));

Route::get('/categories',array('as'=>'categories',function(){
	$data = App::make('CategoryController')->list_categories();
	return View::make('gutlo.categories',compact('data'));
}));
Route::get('trend',array('as'=>'trend',function(){
	$data = App::make('TrendController')->list_trends();
	return View::make('gutlo.trend',compact('data'));
}));
Route::get('/hashtag/{name}',array('as'=>'hashtag',function($name){
	$data = App::make('TrendController')->show_trend($name);
	return View::make('gutlo.hashtag',$data);
}));

Route::get('/g/{code}',array('as'=>'category-free',function($code){
	$data = App::make('CategoryController')->show_categories($code);
	return View::make('gutlo.showCategory',$data);
}));

/*
|---------------------------------------------------------------------------
| Route signup, verify , login , check email, username on server
|---------------------------------------------------------------------------
|
|
|
*/
Route::get('/share/link/fb',array('as'=>'share-link-fb','uses'=>'AccountController@share_facebook' ));

Route::get('/shareFbCallback','AccountController@share_facebook_callback' );

Route::get('login/fb/callback','AccountController@getLoginFBcallback');

Route::get('login/fb',array('as'=>'loginFacebook','uses'=>'AccountController@getLoginfb' ));

Route::get('signup',array('before'=>'guest','as'=>'signup',function(){
	// return View::make('guests.signupFacebook');
	return View::make('guests.loginFacebook');
}));

Route::get('/login',array('before'=>'guest','as'=>'login',function(){
	return View::make('guests.login');
}));

Route::get('/logout',array('as'=>'logout',function(){
	Auth::logout();
	return Redirect::to('/');
}));



Route::group(array('prefix'=>'check'),function(){
	Route::post("check-username",function(){
		if(User::check_username(Input::get("username")))
			return "true";
		else return "false";
	});
	Route::post("check-email",function(){
		if(User::check_email(Input::get("email")))
			return "true";
		else return "false";
	});
});
//cần khởi tạo hàm kiểm tra confirm cho việc verify
Route::get('verify-signup',array('before'=>'guest','as'=>'verify-signup',function(){
	return View::make('guests.verifySignup');
}));

/*
|--------------------------------------------------------------------------------------
|	Route Post
|--------------------------------------------------------------------------------------
|
|
*/
Route::get('new-post',array('before'=>'Auth','as'=>'new-post',function() {
	$categories = App::make('CategoryController')->cache_catgories();
	return View::make('users.post',array('categories'=>$categories));
}));

Route::get('test',array('as'=>'new-post2',function() {
	$categories = App::make('CategoryController')->cache_catgories();
	return View::make('admin.test',array('categories'=>$categories));
}));
/*
|--------------------------------------------------------------------------------------
|	Route show notifi
|--------------------------------------------------------------------------------------
|
|
*/
Route::get('/show-notifi/{id}',array('before'=>'Auth','as'=>'showNotification',function($id) {
	$data = APP::make('NotificationController')->showNotification($id);
	if($data['error'] == 'false'){
		return View::make('users.showNotification',array('data'=>$data['data']));
	}else {
		return View::make('users.ErrorNotification');
	}
}));

/*
|-----------------------------------------------------------------------------------
|	Route post
|-----------------------------------------------------------------------------------
|
|
*/

Route::group(array('before' => 'csrf'), function()
{

	Route::post('/admin/category',array('before'=>'sadmin','as'=>'news/category',function(){
		$data = App::make('App\Controllers\Admin\CategoriesController')->newCategory();
		Cache::forever('categories', $data);
		return View::make('admin.categories',compact('data'));
	}))->before('cache.category');

	Route::post('/newPost',array('before'=>'Auth.alert','as'=>'newPost',function() {
		$data = APP::make('PostController')->newPost();
		if($data['error'] == 'true') return Redirect::back()->with('msg', $data['msg']);
		return Redirect::to('/fresh')->with(array('data'=>$data));
	}));

	Route::post('/newPost2',array('as'=>'newPost2',function() {
		$data = APP::make('PostController')->newPost2();
	}));

	Route::post('/comment/{id}',array('before'=>'Auth.alert','as'=>'comment',function($id) {
		$data = APP::make('CommentController')->commentPost($id);
		return $data;
	}));

	Route::post('/reply/{id}',array('before'=>'Auth.alert','as'=>'reply',function($id) {
		$data = APP::make('ReplyController')->replyComment($id);
		return $data;
	}));

	Route::post('signup',array('before'=>'guest','as'=>'signup',function(){
		$data = App::make('AccountController')->postSignup();
		if($data == true)
			return Redirect::to('verify-signup');
	}));

	Route::post('/brick-post/{id}',array('before'=>'Auth.alert','as'=>'brick-post',function($id){
		$data_common = array(
							'content_type'=>Config::get('Common.content_type.post')
							,'id_log'=>array(
								'unLike'=>21
								,'brick'=>7
								,'unBrick'=>24
							)
							,'msg_notifi'=>array(0=>' ném gạch bài viết của bạn',1=>' ném gạch bài viết có liên quan đến bạn')
		);
        $Update_point_content = new GutloPosts();
        $data_content = GutloPosts::find($id);
        $user_relation = DB::select(DB::raw('select users.username,users.id from users where (id IN ( select CONCAT(gutlo_reply.from_id,",",gutlo_comment.from_id) from gutlo_comment INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id where gutlo_comment.to_post_id = '.$id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL) OR id IN ( select CONCAT(gutlo_reply.mention_id,",",gutlo_comment.mention_id) from gutlo_comment  INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id where gutlo_comment.to_post_id = '.$id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL)) AND id <> '.Auth::user()->id  ));
		$data = App::make('ActivityController')->brick($id,$data_common,$Update_point_content,$data_content,$user_relation);
		return $data;
	}));

	Route::post('/brick-comment/{id}',array('before'=>'Auth.alert','as'=>'brick-comment',function($id){
		$data_common = array(
							'content_type'=>Config::get('Common.content_type.comment')
							,'id_log'=>array(
								'unLike'=>22
								,'brick'=>8
								,'unBrick'=>25
							)
							,'msg_notifi'=>array(0=>' ném gạch bình luận của bạn',1=>' ném gạch bình luận có liên quan đến bạn')
		);
        $Update_point_content = new GutloComment();
        $data_content = GutloComment::find($id);
        $user_relation = DB::select(DB::raw('select users.username,users.id from users where (id IN ( select CONCAT(gutlo_reply.from_id,",",gutlo_comment.from_id) from gutlo_comment INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id where gutlo_comment.id = '.$data_content->id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL) OR id IN ( select CONCAT(gutlo_reply.mention_id,",",gutlo_comment.mention_id) from gutlo_comment  INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id where gutlo_comment.id = '.$data_content->id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL)) AND id <> '.Auth::user()->id  ));
		$data = App::make('ActivityController')->brick($id,$data_common,$Update_point_content,$data_content,$user_relation);
		return $data;
	}));

	Route::post('/brick-reply/{id}',array('before'=>'Auth.alert','as'=>'brick-reply',function($id){
		$data_common = array(
							'content_type'=>Config::get('Common.content_type.reply')
							,'id_log'=>array(
								'unLike'=>23
								,'brick'=>9
								,'unBrick'=>26
							)
							,'msg_notifi'=>array(0=>' ném gạch trả lời của bạn',1=>' ném gạch trả lời có liên quan đến bạn')
		);
        $Update_point_content = new GutloReply();
        $data_content = GutloReply::find($id);
        $user_relation = DB::select(DB::raw('select users.username,users.id from users where (id IN ( select CONCAT(gutlo_reply.from_id,",",gutlo_comment.from_id) from gutlo_comment INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id where gutlo_comment.id = '.$data_content->comment_id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL) OR id IN ( select CONCAT(gutlo_reply.mention_id,",",gutlo_comment.mention_id) from gutlo_comment  INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id where gutlo_comment.id = '.$data_content->comment_id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL)) AND id <> '.Auth::user()->id  ));
		$data = App::make('ActivityController')->brick($id,$data_common,$Update_point_content,$data_content,$user_relation);
		return $data;
	}));

	Route::post('/like-post/{id}',array('before'=>'Auth.alert','as'=>'like-post',function($id){
		$data_common = array(
							'content_type'=>Config::get('Common.content_type.post')
							,'id_log'=>array(
								'like'=>4
								,'unLike'=>21
								,'brick'=>7
								,'unBrick'=>24
							)
							,'msg_notifi'=>array(0=>' thích bài viết của bạn',1=>' thích bài viết có liên quan đến bạn')
		);
		$Update_point_content = new GutloPosts();
        $Gutloposts = GutloPosts::find($id);
        $user_relation = DB::select(DB::raw('select users.username,users.id from users where (id IN ( select CONCAT(gutlo_reply.from_id,",",gutlo_comment.from_id) from gutlo_comment INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id where gutlo_comment.to_post_id = '.$id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL) OR id IN ( select CONCAT(gutlo_reply.mention_id,",",gutlo_comment.mention_id) from gutlo_comment  INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id where gutlo_comment.to_post_id = '.$id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL)) AND id <> '.Auth::user()->id  ));
		$data = App::make('ActivityController')->like_post($id,$Gutloposts,$user_relation,$data_common,$Update_point_content);
		return $data;
	}));

	Route::post('/like-comment/{id}',array('before'=>'Auth.alert','as'=>'like-comment',function($id){
		$data_common = array(
							'content_type'=>Config::get('Common.content_type.comment')
							,'id_log'=>array(
								'like'=>5
								,'unLike'=>22
								,'brick'=>8
								,'unBrick'=>25
							)
							,'msg_notifi'=>array(0=>' thích bình luận của bạn',1=>' thích bình luận có liên quan đến bạn')
		);
		$Update_point_content = new GutloComment();
        $GutloComment = GutloComment::find($id);
        $user_relation = DB::select(DB::raw('select users.username,users.id from users where (id IN ( select CONCAT(gutlo_reply.from_id,",",gutlo_comment.from_id) from gutlo_comment INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id where gutlo_comment.id = '.$GutloComment->id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL) OR id IN ( select CONCAT(gutlo_reply.mention_id,",",gutlo_comment.mention_id) from gutlo_comment  INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id where gutlo_comment.id = '.$GutloComment->id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL)) AND id <> '.Auth::user()->id  ));
		$data = App::make('ActivityController')->like_post($id,$GutloComment,$user_relation,$data_common,$Update_point_content);
		return $data;
	}));

	Route::post('/like-reply/{id}',array('before'=>'Auth.alert','as'=>'like-reply',function($id){
		$data_common = array(
							'content_type'=>Config::get('Common.content_type.reply')
							,'id_log'=>array(
								'like'=>6
								,'unLike'=>22
								,'brick'=>8
								,'unBrick'=>25
							)
							,'msg_notifi'=>array(0=>' thích trả lời của bạn',1=>' thích trả lời có liên quan đến bạn')
		);
		$Update_point_content = new GutloReply();
        $GutloReply = GutloReply::find($id);
        $user_relation = DB::select(DB::raw('select users.username,users.id from users where (id IN ( select CONCAT(gutlo_reply.from_id,",",gutlo_comment.from_id) from gutlo_comment INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id where gutlo_comment.id = '.$GutloReply->comment_id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL) OR id IN ( select CONCAT(gutlo_reply.mention_id,",",gutlo_comment.mention_id) from gutlo_comment  INNER JOIN gutlo_reply ON gutlo_reply.comment_id = gutlo_comment.id where gutlo_comment.id = '.$GutloReply->comment_id.' AND gutlo_comment.deleted_time IS NULL AND gutlo_reply.deleted_time IS NULL)) AND id <> '.Auth::user()->id  ));
		$data = App::make('ActivityController')->like_post($id,$GutloReply,$user_relation,$data_common,$Update_point_content);
		return $data;
	}));

	Route::post('/report-post/{id}',array('before'=>'Auth.alert','as'=>'report-post',function($id){
		$data = App::make('ActivityController')->report_post($id);
		return $data;
	}));

	Route::post('/report-comment/{id}',array('before'=>'Auth.alert','as'=>'report-comment',function($id){
		$data = App::make('ActivityController')->report_comment($id);
		return $data;
	}));

	Route::post('/report-reply/{id}',array('before'=>'Auth.alert','as'=>'report-reply',function($id){
		$data = App::make('ActivityController')->report_reply($id);
		return $data;
	}));

	Route::post('/delete-post/{id}',array('before'=>'Auth.alert','as'=>'delete-post',function($id){
		$data = App::make('ActivityController')->delete_post($id);
		return $data;
	}));

	Route::post('/delete-comment/{id}',array('before'=>'Auth.alert','as'=>'delete-comment',function($id){
		$data = App::make('ActivityController')->delete_comment($id);
		return $data;
	}));

	Route::post('/delete-reply/{id}',array('before'=>'Auth.alert','as'=>'delete-reply',function($id){
		$data = App::make('ActivityController')->delete_reply($id);
		return $data;
	}));

	Route::post('/loadMoreReply/{id}',array('as'=>'load-more-reply',function($id){
		$data = App::make('ReplyController')->load_more_reply($id);
		return $data;
	}));

	Route::post('/loadMoreCommentFresh/{id}',array('as'=>'load-more-comment-fresh',function($id){
		$data = App::make('CommentController')->getMoreCommentFreshById($id);
		return $data;
	}));

	Route::post('/loadMoreCommentHot/{id}',array('as'=>'load-more-comment-hot',function($id){
		$data = App::make('CommentController')->getMoreCommentHotById($id);
		return $data;
	}));

	Route::post('/loadMorePostFresh',array('as'=>'load-more-post-Fresh',function(){
		$data = App::make('PostController')->load_more_post_fresh();
		return $data;
	}));

	Route::post('/loadMorePostHot',array('as'=>'load-more-post-Fresh',function(){
		$data = App::make('PostController')->load_more_post_hot();
		return $data;
	}));

	Route::post('/fresh-comment/{id}',array('as'=>'resh-comment',function($id){
		$data = App::make('CommentController')->getCommentFreshById($id);
		return  Response::json(array('error'=>'false','msg'=>'','data'=>$data));
	}));

	Route::post('/hot-comment/{id}',array('as'=>'hot-comment',function($id){
		$data = App::make('CommentController')->getCommentHotById($id);
		return  Response::json(array('error'=>'false','msg'=>'','data'=>$data));
	}));

	Route::get('/getNotification',array('as'=>'getNotification',function(){
		$data = App::make('NotificationController')->list_notification();
		return Response::json(array('error'=>'false','msg'=>'','data'=>$data));
	}));

	Route::get('/getNewNotification',array('as'=>'getNewNotification',function(){
		$data = App::make('NotificationController')->get_new_notification();
		return $data;
	}));

	Route::post('/g/{code}',array('as'=>'loadMorePostOnCategory',function($code){
		$data = App::make('CategoryController')->loadMorePostOnCategories($code);
		return Response::json(array('error'=>'false','msg'=>'','data'=>$data['data']->posts));
	}));

	Route::post('/loadMoreNextReply',array('as'=>'loadMoreNextReply',function(){
		$data = App::make('ReplyController')->loadmore_new_reply();
		return Response::json(array('error'=>'false','msg'=>'','data'=>$data));
	}));

	Route::post('/loadMorePreviousReply',array('as'=>'loadMorePreviousReply',function() {
		$data = App::make('ReplyController')->loadmore_previous_reply();
		return Response::json(array('error'=>'false','msg'=>'','data'=>$data));
	}));

	Route::post('/loadMoreNextComment',array('as'=>'loadMoreNextComment',function(){
		$data = App::make('CommentController')->loadmore_new_comment();
		return $data;
	}));

	Route::post('/loadMorePreviousComment',array('as'=>'loadMorePreviousRComment',function(){
		$data = App::make('CommentController')->loadmore_previous_comment();
		return $data;
	}));

	Route::post('trend',array('as'=>'trend',function(){
		$data = App::make('TrendController')->list_trends();
		return Response::json(array('error'=>'false','msg'=>'','data'=>$data));
	}));

	Route::post('/hashtag/{name}',array('as'=>'hashtag',function($name){
		$data = App::make('TrendController')->show_trend($name);
		return Response::json($data);
	}));

	Route::post('/admin/emoticon',array('before'=>'auth.mod','as'=>'emoticon',function(){
		$data = APP::make('EmoticonController')->addNewEmoticon();
		return Response::json($data);
	}));

	Route::post('/admin/loadEmoticon',array('as'=>'loadEmoticon',function(){
		$data = APP::make('EmoticonController')->loadEmoticon();
		return Response::json($data);
	}));
	
	Route::post('/loadCommentNotifi',array('as'=>'loadCommentNotifi',function(){
		$data = App::make('NotificationController')->get_Data_post_notifi();
		return Response::json($data);
	}));

	Route::post('/post_of_users',array('as'=>'postOfUsers',function(){
		$data = App::make('PostController')->load_more_post_of_users();
		return Response::json($data);
	}));

	Route::post('/post_related_users',array('as'=>'postRelatedUsers',function(){
		$data = App::make('PostController')->load_more_post_related_users();
		return Response::json($data);
	}));

	Route::post('/favor-cate',array('as'=>'favorCate',function(){
		$data = App::make('CategoryController')->favor_Cate();
		return Response::json($data);
	}));

	Route::post('/favor-hashtag',array('as'=>'favorHashtag',function(){
		$data = App::make('TrendController')->favor_hashtag();
		return Response::json($data);
	}));

	Route::post('/profile_user',array('as'=>'profileUser',function(){
		$data = App::make('AccountController')->load_profile();
		return $data;
	}));

	Route::post('/loadFavorCate',array('as'=>'loadFavorCate',function(){
		$data = App::make('CategoryController')->loadFavorCate();
		return Response::json($data);
	}));

	Route::post('/loadFavorHashtag',array('as'=>'loadFavorHasgtag',function(){
		$data = App::make('TrendController')->loadFavorHashtag();
		return Response::json($data);
	}));

	Route::post('/level_up_user',array('before'=>'Auth.alert','as'=>'levelUpUser',function() {
		$data = APP::make('AccountController')->levelUpUser();
		return Response::json($data);
	}));

	Route::get('/admin/searchUserName/{key}',array('before'=>'auth.mod','as'=>'searchUser',function($key) {
		$data = APP::make('AccountController')->searchUser($key);
		return Response::json($data);
	}));
	
	Route::post('/admin/get_data_report_user',array('before'=>'Aauth.mod','as'=>'getLogReportUser',function() {
		$data = APP::make('AccountController')->get_data_report_user();
		return Response::json($data);
	}));

	Route::post('/admin/bane_user',array('before'=>'auth.mod','as'=>'baneUser',function() {
		$data = APP::make('ManagerbaneController')->bane_user();
		return Response::json($data);
	}));

	Route::post('/rate_post',array('before'=>'auth.mod','as'=>'ratePost',function() {
		$data = APP::make('PostController')->rate_post();
		return Response::json($data);
	}));

	Route::post('/block-post',array('before'=>'auth.mod','as'=>'blockPost',function() {
		if(Input::get('id') == null) return array('error'=>'true','msg'=>'có lỗi sảy ra vui lòng thử lại !','data'=>array());
		$id = Input::get('id');
		$data_common = array(
							'content_type'=>Config::get('Common.content_type.post')
							,'id_log'=>array(
								'block'=>28
							)
		);
        $GutloPosts = GutloPosts::find($id);
		$data = APP::make('ActivityController')->mod_block($id,$data_common,$GutloPosts);
		return Response::json($data);
	}));

	Route::post('/block-comment',array('before'=>'auth.mod','as'=>'blockComment',function() {
		if(Input::get('id') == null) return array('error'=>'true','msg'=>'có lỗi sảy ra vui lòng thử lại !','data'=>array());
		$id = Input::get('id');
		$data_common = array(
							'content_type'=>Config::get('Common.content_type.comment')
							,'id_log'=>array(
								'block'=>29
							)
		);
        $GutloComment = GutloComment::find($id);
		$data = APP::make('ActivityController')->mod_block($id,$data_common,$GutloComment);
		return Response::json($data);
	}));

	Route::post('/block-reply',array('before'=>'auth.mod','as'=>'blockReply',function() {
		if(Input::get('id') == null) return array('error'=>'true','msg'=>'có lỗi sảy ra vui lòng thử lại !','data'=>array());
		$id = Input::get('id');
		$data_common = array(
							'content_type'=>Config::get('Common.content_type.reply')
							,'id_log'=>array(
								'block'=>30
							)
		);
        $GutloReply = GutloReply::find($id);
		$data = APP::make('ActivityController')->mod_block($id,$data_common,$GutloReply);
		return Response::json($data);
	}));

	Route::post('/block-hashtag',array('before'=>'auth.mod','as'=>'blockHashtag',function() {
		$data = APP::make('ActivityController')->mod_block_hashtag();
		return Response::json($data);
	}));

	Route::post('/get-data-link',array('before'=>'Auth.alert','as'=>'getDataLink',function() {
		$data = APP::make('PostController')->get_data_link();
		return Response::json($data);
	}));

});
Route::post('/login',array('as'=>'hashtag',function(){
	$data = App::make('TrendController')->show_trend($name);
	return Response::json(array('error'=>'false','msg'=>'','data'=>$data));
}));
/*
|-----------------------------------------------------------------------------------
|	Route admin
|-----------------------------------------------------------------------------------
|
|
*/
Route::post('login',array('as'=>'login',function(){
	$data = App::make('AccountController')->postLogin();
	if($data === true) {
			return Redirect::back();
	} else{
		return View::make('guests.login')->with("error_message",$data);
	}
}));
Route::post('/admin/hot100',array('as'=>'nodejs',function(){
	$data = App::make('AnalyticsController')->run_data_analytic_view_all();
	print_r($data);
}));

Route::get('/admin/manage-categories',array('before'=>'auth.admin','as'=>'manage-categories',function(){
	$data = App::make('App\Controllers\Admin\CategoriesController')->list_categories();
	return View::make('admin.manage-categories',compact('data'));
}));

Route::get('/admin/manage-staff',array('before'=>'auth.sadmin','as'=>'manage-staff',function(){
	return View::make('admin.manage-staff');
}));

Route::get('/admin',array('before'=>'auth.mod','as'=>'admin-dashboard',function(){
	return View::make('admin.dashboard');
}));

Route::get('/admin/analytics',array('before'=>'auth.mod','as'=>'analytics',function(){
	return View::make('admin.analytics');
}));

Route::get('/admin/analytics-tested',array('before'=>'auth.mod','as'=>'analytics-tested',function(){
	return View::make('admin.analytics-tested');
}));

Route::get('/admin/hot100',array('before'=>'auth.mod','as'=>'hot100',function(){
	return View::make('admin.hot100');
}));

Route::get('/admin/alert-permission',array('as'=>'alert-permission',function(){
	return View::make('admin.alert-permission');
}));

Route::get('/oauth2callback',array('as'=>'oauth2callback','uses'=>'AccountController@login_google'));
Route::get('/index-google',array('as'=>'indexGoogle','uses'=>'AccountController@index_google'));
Route::get('/accounts', 'AccountController@accounts');

// route online gift
Route::get('/onlineGift',array('as'=>'onlineGift',function(){
	App::make('GiftController')->onlineGift();
}));



// add emoticon

Route::get('/admin/emoticon',array('before'=>'auth.mod','as'=>'adminemoticon',function(){
	$data = App::make('EmoticonController')->getAllEmoticon();
	return View::make('admin.editEmoticon',compact('data'));
}));
Route::get('/admin/manage-rating-post',array('before'=>'auth.mod','as'=>'rating-post',function(){
	return View::make('admin.manageratingP');
}));
Route::get('/admin/manage-blocking-user',array('before'=>'auth.smod','as'=>'blocking-user',function(){
	return View::make('admin.manageblockingU');
}));
Route::get('/admin/manage-blocking-content',array('before'=>'auth.mod','as'=>'blocking-content',function(){
	return View::make('admin.manageblockingP');
}));
Route::get('/admin/manage-blocking-content',array('before'=>'auth.mod','as'=>'blocking-content',function(){
	return View::make('admin.manageblockingC');
}));
Route::get('/admin/manage-report',array('before'=>'auth.mod','as'=>'manage-report',function(){
	return View::make('admin.manageReport');
}));
// update point
// Route::get('/admin/updatePoint',array('before'=>'auth.mod','as'=>'updatePoint',function(){
// 	APP::make('ActivityController')->update_point_all();
// }));