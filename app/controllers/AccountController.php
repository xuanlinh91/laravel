<?php
session_start();
class AccountController extends BaseController{
	private $fb;
	private $ga;
	public function __construct (FacebookHelper $fb,GA_Service $ga) {
		$this->fb = $fb;
		$this->ga = $ga;
	}
	function get_path_url_img($array){
		$path = '';$start = false;
		for ($i = 0; $i < sizeof($array) -1; $i++) {
			if($array[$i] == 'uploads') $start = true;
			if($start) {
				$path = $path .'/'.$array[$i];
			}
		}
		return $path;
	}
	public function index_google()
    {
        if( $this->ga->isLoggedIn() ){
            return 'Show home page';
        }
        else{
            $url = $this->ga->getLoginUrl();
            // return View::make('guests.login', [ 'url' => $url ]);
            return Redirect::to($url);

        }

    }//index
	public function login_google(){
		// echo Input::has('code');
	    if( Input::has('code') ){
	        $code = Input::get('code');
	        $this->ga->login($code);

	        return "Go to the home <a href='/'>page</a>";
	    }
	    else{
	        return "Invalide request parameters";
	    }//else
	}//login
	function postSignup(){

		$rules=array(//Rules validator cua Sever
			'firstname'	=>'required|max:45',
			'lastname'	=>'required|max:45',
			'username'	=>'required|min:3|max:20',
			'password'	=>'required|min:6|max:70',
			'email'		=>'required|email',
			'gender'	=>'required'
			);
		if(!Validator::make(Input::all(),$rules)->fails()){ // Xet dieu kien validator cua Sever
				$IDType = new IDType();
				print_r($IDType->types);
				return;
				$confirmation_code = str_random(30); // Tao confimation code cho New User
				$ID = new ID();
		    	$ID->id_type_id = IDType::where('id_type_name','=','User')->first()->id;
		    	$ID->save();
		    	$ID = $ID->id;

		    	// avatar
		    	$id_content_type_album_avatar = ContentType::where('content_type_name','=','album')->first()->id;
		        $Content_album_avatar = new Content();
				$Content_album_avatar->content_type_id = $id_content_type_album_avatar;
				$Content_album_avatar->save();
				$Content_album_avatar = $Content_album_avatar->id;

				$ActivityType_album_avatar = ActivityType::where('activity_type_name','=','create_album')->first()->id;

				$IDVsContent =  new IDVsContent();
				$IDVsContent->from_id = $ID;
				$IDVsContent->activity_type_id = $ActivityType_album_avatar;
				// $IDVsContent->coordinate_id = $Coordinate;
				$IDVsContent = Content::find($Content_album_avatar)->idVsContent()->save($IDVsContent);

				$album_avatar = new Album();
				$album_avatar->id = $Content_album_avatar;
				$album_avatar->name = 'avatar';
				$album_avatar->created_by = $ID;
				$album_avatar->place_id = 120;
				$album_avatar->save();
				$album_avatar = $Content_album_avatar;

				$path_full = Image::resize('assets/image/default/avatar/avatar_icon.png','uploads/media/'.$ID.'/photos/albums/avatar/full')['targetUrl'];
				$path_130 = Image::resize('assets/image/default/avatar/avatar_icon.png','uploads/media/'.$ID.'/photos/albums/avatar/130x130',130,130,90)['targetUrl'];
				$path_90 = Image::resize('assets/image/default/avatar/avatar_icon.png','uploads/media/'.$ID.'/photos/albums/avatar/90x90',90,90,90)['targetUrl'];
				$path_40 = Image::resize('assets/image/default/avatar/avatar_icon.png','uploads/media/'.$ID.'/photos/albums/avatar/40x40',40,40,90)['targetUrl'];
				$path_32 = Image::resize('assets/image/default/avatar/avatar_icon.png','uploads/media/'.$ID.'/photos/albums/avatar/32x32',32,32,90)['targetUrl'];
				$path_260 = Image::resize('assets/image/default/avatar/avatar_icon.png','uploads/media/'.$ID.'/photos/albums/avatar/260x260',260,260,90)['targetUrl'];

				// full
				$array = explode('/', $path_full);
				$name_img = end($array);
				$path_full = $this->get_path_url_img($array);

    			$array = explode('/', $path_130);
				$path_130 = $this->get_path_url_img($array);

				$array = explode('/', $path_90);
				$path_90 = $this->get_path_url_img($array);

				$array = explode('/', $path_40);
				$path_40 = $this->get_path_url_img($array);

				$array = explode('/', $path_32);
				$path_32 = $this->get_path_url_img($array);

				$array = explode('/', $path_260);
				$path_260 = $this->get_path_url_img($array);


				$id_content_type_avatar = ContentType::where('content_type_name','=','media')->first()->id;
		        $Content = new Content();
				$Content->content_type_id = $id_content_type_avatar;
				$Content->save();
				$Content = $Content->id;

				$ActivityType_avatar = ActivityType::where('activity_type_name','=','create_media')->first()->id;

				$IDVsContent =  new IDVsContent();
				$IDVsContent->from_id = $ID;
				$IDVsContent->activity_type_id = $ActivityType_avatar;
				$IDVsContent = Content::find($Content)->idVsContent()->save($IDVsContent);

				$media_avatar = new Media();
				$media_avatar->id = $Content;
				$media_avatar->upload_by = $ID;
				$media_avatar->upload_to = $ID;
				$media_avatar->url    	= $path_full;
				$media_avatar->url_32    = $path_32;
				$media_avatar->url_40    = $path_40;
				$media_avatar->url_90    = $path_90;
				$media_avatar->url_130    = $path_130;
				$media_avatar->url_260    = $path_260;
				$media_avatar->media_name    = $name_img;
				$media_avatar->albums_id = $album_avatar;
				$media_avatar->save();
				$media_avatar = Media::find($Content);

				// cover
				$id_content_type_album_cover = ContentType::where('content_type_name','=','album')->first()->id;
		        $Content_album_cover = new Content();
				$Content_album_cover->content_type_id = $id_content_type_album_cover;
				$Content_album_cover->save();
				$Content_album_cover = $Content_album_cover->id;

				$ActivityType_album_cover = ActivityType::where('activity_type_name','=','create_album')->first()->id;

				$IDVsContent =  new IDVsContent();
				$IDVsContent->from_id = $ID;
				$IDVsContent->activity_type_id = $ActivityType_album_cover;
				// $IDVsContent->coordinate_id = $Coordinate;
				$IDVsContent = Content::find($Content_album_cover)->idVsContent()->save($IDVsContent);

				$album_cover = new Album();
				$album_cover->id = $Content_album_cover;
				$album_cover->name = 'cover';
				$album_cover->created_by = $ID;
				$album_cover->place_id = 120;
				$album_cover->save();
				$album_cover = $Content_album_cover;

				$id_content_type_cover = ContentType::where('content_type_name','=','media')->first()->id;
		        $Content = new Content();
				$Content->content_type_id = $id_content_type_cover;
				$Content->save();
				$Content = $Content->id;

				$ActivityType_cover = ActivityType::where('activity_type_name','=','create_media')->first()->id;

				$IDVsContent =  new IDVsContent();
				$IDVsContent->from_id = $ID;
				$IDVsContent->activity_type_id = $ActivityType_cover;
				$IDVsContent = Content::find($Content)->idVsContent()->save($IDVsContent);

				$path_full_cover = Image::resize('assets/image/default/cover/default.jpg','uploads/media/'.$ID.'/photos/albums/cover/full')['targetUrl'];
				$path_mini_cover = Image::crop('assets/image/default/cover/default.jpg','uploads/media/'.$ID.'/photos/albums/cover/mini',0,0,798,322,90)['targetUrl'];

				$array = explode('/', $path_full_cover);
				$name_img = end($array);
				$path_full_cover = $this->get_path_url_img($array);

    			$array = explode('/', $path_mini_cover);
				$path_mini_cover = $this->get_path_url_img($array);

				$media_cover = new Media();
				$media_cover->id = $Content;
				$media_cover->upload_by = $ID;
				$media_cover->upload_to = $ID;
				$media_cover->cover_url    	= $path_full_cover;
				$media_cover->mini_cover_url    = $path_mini_cover;
				$media_cover->media_name    = $name_img;
				$media_cover->albums_id = $album_cover;
				$media_cover->save();
				$media_cover = Media::find($Content);
				// end

		        $user = new User();
		        $user->nickname = Input::get('firstname') .' '. Input::get('lastname');
		        $user->username = strtolower(Input::get('username'));
		        $user->email 	= Input::get('email');
		        $user->avatar_id = $media_avatar->id;
		        $user->cover_id = $media_cover->id;
				$user->password = Hash::make(Input::get('password'));
		    	$user->confirmation_code = $confirmation_code;
		        $user = ID::find($ID)->user()->save($user); // Save du lieu vao database tables 'User'

		        $userInformation = new UserInformation();
		        $userInformation->firstname = Input::get('firstname');
		        $userInformation->lastname  = Input::get('lastname');
		        $userInformation->gender 	= Input::get('gender');
			    $userInformation = User::find($ID)->userInformation()->save($userInformation); // Save du lieu vao database tables 'userInformation'

			    // $data = array('key' => $confirmation_code, 'username' => Input::get('username'));
			        // Mail::send('emails.verify', $data, function($message) {
			        //     $message->to(Input::get('email'), Input::get('username'))
			        //         ->subject('Verify your email address');
			        // });
	        Auth::loginUsingId($ID);
	    	return true;
		} else
			return false;
	}

	function postLogin() {
		$rules = array(
			'user_type' => 'required',
			'password' => 'required'
		);
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return "Vui lòng điền thông tin đăng nhập";
		} else {

			$userdata = array(
				'username' 	=> strtolower(Input::get('user_type')),
				'password' 	=> Input::get('password')
			);
			if (Auth::attempt($userdata)) {
				$this->bonus_online_user(Auth::user()->id);
				return true;
			} else {
				$userdata = array(
					'email' 	=> Input::get('user_type'),
					'password' 	=> Input::get('password')
				);
				if (Auth::attempt($userdata)) {
					$this->bonus_online_user(Auth::user()->id);
					return true;
				} else {
					return "Tên đăng nhập hoặc mật khẩu không đúng";
				}
			}
		}
	}

	public function bonus_online_user($user_id){
		// bonus o day 
		$user = User::find($user_id);
		$now =  floor(strtotime(\Carbon\Carbon::now()->toDateTimeString())/86400);
		$last_visited_time =  floor(strtotime($user->last_visited_time)/86400);
		if( $now > $last_visited_time){
			$GutloPoint = GutloPoint::find($user->id);
	        $GutloPoint->has_brick = $GutloPoint->has_brick + 8;
	        $GutloPoint->save();
		}
		$user->last_visited_time = \Carbon\Carbon::now()->toDateTimeString() ;
		$user->save();
	}

	public function getLoginfb(){
    	return Redirect::to($this->fb->getUrlLogin());
	}
	public function share_facebook () {
        $user = null;
        if(Auth::check()){
            $user = User::where('id','=',Auth::user()->id)->where('username','=',Auth::user()->username)->first();
            if( empty( $user ) ) {
               return Redirect::route('login');
            }
        } else return Redirect::route('login');
        return Redirect::to($this->fb->getUrlShare());
    }
    public function share_facebook_callback() {
        if( !$this->fb->ganeraSessionFromRedirectShare() ) {
            return Redirect::to('/')->with('message','Có lỗi phát sinh vui lòng liên hệ quản trị viên để biết thêm chi tiết');
        }
        $array = explode('/', URL::previous());
        $id = end($array);
        $Gutloposts = GutloPosts::find($id);
        $GutloIdVsContent = GutloIdVsContent::where('user_id','=',Auth::user()->id)->where('content_id','=',$id)->where('content_type','=',0)->first();

        if(empty( $GutloIdVsContent )) {
            $GutloIdVsContent = new GutloIdVsContent();
            $GutloIdVsContent->user_id = Auth::user()->id;
            $GutloIdVsContent->content_id = $id;
            $GutloIdVsContent->content_type = 0;
            $GutloIdVsContent->share_facebook = true;
            $GutloIdVsContent->save();
        } else {
	        $GutloIdVsContent_data = array('share_facebook' => true );
	        $GutloIdVsContent = GutloIdVsContent::where('user_id','=',Auth::user()->id)->where('content_id','=',$id)->where('content_type','=',0)->update($GutloIdVsContent_data);
	    }
        $this->fb->share_facebook(URL::previous(),$Gutloposts->title);
        return Redirect::back();
    }

	public function getLoginFBcallback() {
		if( !$this->fb->ganeraSessionFromRedirect() ) {
			return Redirect::to('/')->with('message','Có lỗi phát sinh vui lòng liên hệ quản trị viên để biết thêm chi tiết');
		}
		$user_fb = $this->fb->getGraph();


		if(!$user_fb){
			return Redirect::to('/')->with('message','Có lỗi phát sinh vui lòng liên hệ quản trị viên để biết thêm chi tiết');
		}
		$user = User::where('facebook_id', '=', $user_fb->getProperty('id'))->first();
		$path_avatar_facebook = 'http://graph.facebook.com/'.$user_fb->getProperty('id').'/picture?width=320&height=320';

		if (empty($user)) {

			$ID = new ID();
	    	$ID->id_type_id = 1;
	    	$ID->save();
	    	$ID = $ID->id;

	    	$username = '';
	    	$edited_username = null;
	    	$edit_code = null;
	    	// Neu nick name la ky tu chu nuoc nogai khong phai la latin thi cho randome username khong thi lay fname lname lam nick name
	    	if(preg_match('/[^\\p{Common}\\p{Latin}]/u',$this->convert_vi_to_en($user_fb->getProperty('first_name').$user_fb->getProperty('last_name')))){
	    		$edited_username = \Carbon\Carbon::now()->toDateTimeString();
	    		$edit_code = str_random(30);
	    		$username = 'username'. DB::table('users')->count();
	    	}else $username = $this->convert_vi_to_en($user_fb->getProperty('first_name').$user_fb->getProperty('last_name'));

	        $fileName = str_random(10).'.jpg';
			$path = 'uploads/users/'.$ID.'/';
			if(!is_dir($path)) {
				$Oldmask = umask ( 0 );
	            mkdir($path,0777);
				umask ( $Oldmask );
	        }
	        copy($path_avatar_facebook, $path.$fileName);

	        $media = new Media();
	        $media->media_name = $fileName;
	        $media->media_url = $path;
	        $media->save();
	        $media_id = $media->id;

	        $user = new User();
	        $user->id = $ID;
	        $user->nickname 	= $user_fb->getProperty('first_name') .' '.$user_fb->getProperty('last_name');
	        $user->username 	= $username;
	        $user->facebook_id 	= $user_fb->getProperty('id');
	        $user->email 		= $user_fb->getProperty('email');
	        $user->last_visited_time = \Carbon\Carbon::now()->toDateTimeString();
	        if($edited_username != null ){
	        	$user->edited_username 	= $edited_username;
	        	$user->edit_code = $edit_code;
	        }
	        $user->avatar_id = $media_id;
	        $user->save();
	        $user = User::find($ID);

	        if ($user_fb->getProperty('gender') == 'male') $gender = 1;
	        else if ($user_fb->getProperty('gender') == 'other') $gender = 3;
	        else $gender = 2;

	        $GutloPoint = new GutloPoint();
	        $GutloPoint->user_id = $ID;
	        $GutloPoint->has_brick = 8 ;
	        $GutloPoint->save();

	        $userInformation = new UserInformation();
	        $userInformation->id = $ID;
	        $userInformation->firstname = $user_fb->getProperty('first_name');
	        $userInformation->lastname = $user_fb->getProperty('last_name');
	        $userInformation->birthday = date(strtotime($user_fb->getProperty('birthday')));
	        $userInformation->gender = $gender;
	        $userInformation->save();

		} else {
			$media_id = $user->avatar_id;
			$media = Media::find($media_id);

			$file_facebook = file_get_contents($path_avatar_facebook);
			$file_gutlo = file_get_contents(url('/'.$media->media_url.$media->media_name));

			if($file_facebook != $file_gutlo) {
				$fileName = str_random(10).'.jpg';
				$path = 'uploads/users/'.$user->id.'/';
				if(!is_dir($path)) {
		            mkdir($path);
		        }
		        copy($path_avatar_facebook, $path.$fileName);

				$media = new Media();
		        $media->media_name = $fileName;
		        $media->media_url = $path;
		        $media->save();
		        $media_id = $media->id;

			}
			$now =  floor(strtotime(\Carbon\Carbon::now()->toDateTimeString())/86400);
			$last_visited_time =  floor(strtotime($user->last_visited_time)/86400);
			if( $now > $last_visited_time){
				$GutloPoint = GutloPoint::find($user->id);
		        $GutloPoint->has_brick = $GutloPoint->has_brick + 8;
		        $GutloPoint->save();
			}
			$userInformation = UserInformation::find($user->id);
			if ($user_fb->getProperty('gender') == 'male') $gender = 1;
	        else if ($user_fb->getProperty('gender') == 'other') $gender = 3;
	        else $gender = 2;
	        $userInformation->gender = $gender;
	        $userInformation->save();

			$user->avatar_id = $media_id;
			$user->last_visited_time = \Carbon\Carbon::now()->toDateTimeString() ;
			$user->save();
		}


		Auth::login($user);
		    return Redirect::back();
	}
	public function load_profile(){
		$id = 0;
		if(Input::get('id') != null){
			$id = Input::get('id');
			$data = $this->get_profile_user($id);
			return Response::json(array('error'=>'false','msg'=>'','data'=>$data));
		}else {
			return Response::json(array('error'=>'true','msg'=>'Có lỗi sảy ra vui lòng thử lại !'));
		}
	}
	public function get_profile_user($id){
		$User = new User();
		$data = $User->get_profile_user($id);
		$level_user = $data->user_level;
		$GutloLevel = new GutloLevel();
		$data_next_lv = $GutloLevel->get_next_level($level_user);
		$upLevel = false;
		if($data->exp >= $data_next_lv->exp && $data->real_point >= $data_next_lv->gold){
			$upLevel = true;
		}
		$data->upLevel = $upLevel;
        return $data;
	}

	public function levelUpUser () {
		$user_id = 0;
		if(Auth::check()) $user_id = Auth::user()->id;
		else return array('error'=>'true','msg'=>'bạn cần đăng nhập để sử dụng chức năng này ');

		$User_profile = new User();
		$data = $User_profile->get_profile_user($user_id);
		$level_user = $data->user_level;

		$GutloLevel = new GutloLevel();
		$data_next_lv = $GutloLevel->get_next_level($level_user);
		$upLevel = false;

		if($data->exp >= $data_next_lv->exp && $data->real_point >= $data_next_lv->gold){
			$upLevel = true;
		}
		if($upLevel){
			$User = User::find($user_id);
			$User->user_level = $User->user_level + 1;
			$User->save();

			$GutloPoint = GutloPoint::find($user_id);
			$GutloPoint->used_point_for_lv = $GutloPoint->used_point_for_lv + $data_next_lv->gold;
			$GutloPoint->real_point = $GutloPoint->real_point - $data_next_lv->gold;
			$GutloPoint->save();

			$GutloActivityLog = new GutloActivityLog();
			$GutloActivityLog->new_log($user_id,$user_id,$user_id,3,31);// log huy link
		}else {
			return array('error'=>'true','msg'=>'Bạn không đủ '.$data_next_lv->exp.' exp và '.$data_next_lv->gold.' gold để up level !');
		}
		return array('error'=>'false','msg'=>'');
	}

	public function searchUser($key) {
		if($key == '' || $key == ' ') return array();
		$data = DB::table('users')->select('id','username')->where('username','LIKE','%'.$key.'%')->get();
		return $data;
	}

	public function get_data_report_user(){
		if(Input::get('username') == null) return array('error'=>'true','msg'=>'Vui lòng kiểm tra lại username');
		$username = Input::get('username');
		$User = new User();
		$BaneList = new BaneList();
		$BlockContent = new BlockContent();

		$data = $User->get_data_report($username);
		$data_bane = $BaneList->get_data_by_user($username);
		$data_blockContent = $BlockContent->get_data_by_user($username);

		$length_data_bane = COUNT($data_bane);
		$length_data_blockContent = COUNT($data_blockContent);

		for ($i=0; $i < $length_data_bane; $i++) { 
			$data_bane[$i]->created_time = strtotime($data_bane[$i]->created_time);
			$data_bane[$i]->end_time = strtotime($data_bane[$i]->end_time);
		}
		
		for ($i=0; $i < $length_data_blockContent; $i++) { 
			$data_blockContent[$i]->created_time = strtotime($data_blockContent[$i]->created_time);
			$data_blockContent[$i]->end_time = strtotime($data_blockContent[$i]->end_time);
		}

		return array('error'=>'false','msg'=>'','data'=>array('data_user'=>$data,'data_bane'=>$data_bane,'data_blockContent'=>$data_blockContent));

	}
	function convert_vi_to_en($str) {
		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
		$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
		$str = preg_replace("/(đ)/", 'd', $str);
		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
		$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
		$str = preg_replace("/(Đ)/", 'D', $str);
		$str = str_replace(" ", "", str_replace("&*#39;","",$str));

		return $str;
	}

	public function accounts(){
	    $accounts = $this->ga->accounts();
	 
	    return $accounts;
	}//accounts
}