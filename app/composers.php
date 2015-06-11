<?php
// tao phan chung cho ca trang web
View::composer('templates/top', 'Shaphira\Composers\sidebar\TopsComposers');
View::composer('templates/bet', 'Shaphira\Composers\sidebar\BetComposers');

View::composer('templates/trendRight', 'Shaphira\Composers\sidebar\TopsComposers');
View::composer('templates/navigator', 'Shaphira\Composers\navigator\CategoriesComposers');
View::composer('templates/header', 'Shaphira\Composers\view\TrendComposers');

View::composer('gutlo.trend', 'Shaphira\Composers\view\TrendComposers');
View::composer('gutlo.categories', 'Shaphira\Composers\navigator\CategoriesComposers');
View::composer('gutlo.hashtag', 'Shaphira\Composers\view\TrendComposers');
// emoticon
View::composer('users.show', 'Shaphira\Composers\view\EmoticonComposers');
View::composer('users.post', 'Shaphira\Composers\view\EmoticonComposers');
View::composer('users.showNotification', 'Shaphira\Composers\view\EmoticonComposers');
View::composer('admin.editEmoticon', 'Shaphira\Composers\view\EmoticonComposers');


View::composer('*', function($view){
	$array = null;
	if(Auth::check()){
		$user = DB::table('users')
				->select(['users.id','users.confirmed','users.email','users.username','users.permission_role','users.nickname','users.facebook_id','users.last_visited_time','users.user_level'
					,'gutlo_medals.medal_name','gutlo_medals.medal_icon_url','gutlo_point.has_brick','gutlo_point.total_like','gutlo_point.total_brick','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline'
					,'gutlo_point.total_post','gutlo_point.total_comment','gutlo_point.total_plus_hot','gutlo_point.bonus_point','gutlo_point.real_point'
					,DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) as ava')])
				->join('gutlo_media','gutlo_media.id','=','users.avatar_id')
				->join('gutlo_point','gutlo_point.user_id','=','users.id')
				->leftJoin('gutlo_medals', function($join) {
                        $join->on('gutlo_point.real_point','>=','gutlo_medals.min_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.max_point')
                        ->orOn('gutlo_point.real_point','>=','gutlo_medals.max_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.min_point');
                })
				->where('users.id','=',Auth::user()->id)->first();
		$array = array('auth_name'=>$user->username
								,'auth_username'=>$user->username
								,'auth_permission'=>$user->permission_role
								,'auth_id'=>$user->id
								,'auth_email'=>$user->email
								,'auth_nickname'=>$user->nickname
								,'auth_facebook_id'=>$user->facebook_id
								,'auth_last_visited_time'=>$user->last_visited_time
								,'auth_level'=>$user->user_level
								,'auth_ava'=>url('/'.$user->ava)
								,'auth_medal_name' => $user->medal_name
								,'auth_medal_icon_url' => $user->medal_icon_url
								,'auth_has_brick' => $user->has_brick
								,'auth_total_like' => $user->total_like
								,'auth_total_brick' => $user->total_brick
								,'auth_total_post' => $user->total_post
								,'auth_total_comment' => $user->total_comment
								,'auth_total_plus_hot' => $user->total_plus_hot
								,'auth_bonus_point' => $user->bonus_point
								,'auth_real_point' => $user->real_point
								,'auth_vip' => $user->vip
								,'auth_blogger_level' => $user->blogger_level
								,'auth_shaphira_verified' => $user->shaphira_verified
								,'auth_dayOnline' => $user->dayOnline

							);
		
	}else {
		$array = array('auth_name'=>''
								,'auth_id'=>'0'
								,'auth_username'=>''
								,'auth_permission'=>''
								,'auth_nickname'=>''
								,'auth_email'=>''
								,'auth_last_visited_time'=>''
								,'auth_level'=>''
								,'auth_ava'=>url('/assets/image/default/avatar/avatar_icon.png')
								,'auth_medal_name' => ''
								,'auth_medal_icon_url' => ''
								,'auth_has_brick' => ''
								,'auth_total_like' => ''
								,'auth_total_brick' => ''
								,'auth_total_post' => ''
								,'auth_total_comment' => ''
								,'auth_total_plus_hot' => ''
								,'auth_bonus_point' => ''
								,'auth_real_point' => ''
							);;
	}

	$view->with('_auth',$array);
});