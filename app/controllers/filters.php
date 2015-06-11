<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/
use Shaphira\Common\Common;
use Shaphira\Common\SValidator;
App::before(function($request)
{
	$segment = Request::segment(1);
	$user = User::where('username','=',$segment)->get()->first();
	if(!empty($user)){
		if (Auth::check()){
			$data = App::make('AccountController')->get_profile_user($user->id);
			return View::make('users.profile',compact('data'));
		} else return Redirect::guest('login');
	}
});

App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});

Route::filter('csrf', function() {
    $token = Request::ajax() ? Request::header('X-CSRF-Token') : Input::get('_token');
    if (Session::token() != $token) 
        return Redirect::back()->with(array('error'=>'Bạn không thể đăng tải bài viết mới vui lòng liên hệ admin để biết thêm thông tin !!! '));
});

Route::filter('Auth',function(){
	if (!Auth::check()) {
		return Redirect::guest('login');
	} 
});

Route::filter('Auth.alert',function(){
	if (!Auth::check()) {
		return Response::json(array('error'=>'true','msg'=>'Bạn cần đăng nhập để sử dụng chức năng này'));
	} else {
		$validate = new SValidator();
		if(!$validate->validate_user()) {
			Auth::logout();
			return Response::json(array('error'=>'true','msg'=>'Bạn cần đăng nhập để sử dụng chức năng này'));
		}
	}
});

Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Permission Filter
|--------------------------------------------------------------------------
|
| The "Permission" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

Route::filter('auth.smember',function(){
	if (Auth::check()) {
		if(!Auth::user()->permission_role >= 1) {
			return Redirect::route('alert-permission');
		}
	}else Redirect::guest('login');
});

Route::filter('auth.mod',function(){
	if (Auth::check()) {
		if(!Auth::user()->permission_role >= 2) {
			return Redirect::route('alert-permission');
		}
	}else return Redirect::guest('login');
});
Route::filter('auth.smod',function(){
	if (Auth::check()) {
		if(!Auth::user()->permission_role >= 3) {
			return Redirect::route('alert-permission');
		}
	}else return View::make('guests.login');
});
Route::filter('auth.admin',function(){
	if (Auth::check()) {
		if(!Auth::user()->permission_role >= 4) {
			return Redirect::route('alert-permission');
		}
	}else return View::make('guests.login');
});
Route::filter('auth.sadmin',function(){
	if (Auth::check()) {
		if(!Auth::user()->permission_role >= 5) {
			return Redirect::route('alert-permission');
		}
	}else return View::make('guests.login');
});
Route::filter('auth.owner',function(){
	if (Auth::check()) {
		if(!Auth::user()->permission_role == 999) {
			return Redirect::route('alert-permission');
		}
	}else return View::make('guests.login');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/
/*
|-------------------------------------------------------------
|	Push, get cache
|-------------------------------------------------------------
*/

Route::filter('cache.category',function(){
	if (Cache::has('categories')) {
		Cache::forget('categories');
	}
});