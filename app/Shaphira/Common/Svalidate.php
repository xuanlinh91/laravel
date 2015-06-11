<?php namespace Shaphira\Common;

use User;
use GutloPoint;
use Auth;
use Cache;
use \Carbon\Carbon;
class SValidator {
 	

 	//function validate  hash tag

 	public function validate_get_hashtag ($text,$partern) {
 		if(preg_match($partern, $text,$matches)){
 			if(count($matches == 1) && strlen($text) <= 18 ){
 				return true;
 			} else return false;
 		}else return false;
 	}

 	// function validate mentions
 	public function validate_get_mentions($text,$partern) {
 		if(preg_match($partern, $text,$matches)){
 			if(count($matches == 1) && strlen($text) <= 18 ){
 				$user_tag = User::where('username','=',$text)->first();
				if(!empty($user_tag)){
					return array('check' => true,'user_tag'=>$user_tag);
				}
 			} else return array('check' => false,'user_tag'=>array());
 		}else return array('check' => false,'user_tag'=>array());
 	}

 	public function cut_br_in_content($content) {
 		$content = trim($content, " \t\n\r\0\x0B");
 		$length = strlen($content);
 		$count = 0 ;
 		$newContent = '';
 		for ($i=0; $i < $length; $i++) { 
 			if(strstr($content[$i], PHP_EOL)){
 				if($count == 0){
 					$newContent = $newContent.$content[$i];
 				}
 				$count = $count+1;
 			}else {
 				$newContent = $newContent.$content[$i];
 				$count = 0;
 			}
 		}
 		return $newContent;
 	}
 	public function cut_space_in_content($content) {
 		$content = trim($content, " \t\n\r\0\x0B");
 		$length = strlen($content);
 		$count = 0 ;
 		$newContent = '';
 		for ($i=0; $i < $length; $i++) { 
 			if($content[$i] == ' '){
 				if($count < 1){
 					$newContent = $newContent.$content[$i];
 				}
 				$count = $count+1;
 			}else {
 				$newContent = $newContent.$content[$i];
 				$count = 0;
 			}
 		}
 		return $newContent;
 	}

 	public function validate_user() {
 		$user = User::where('id','=',Auth::user()->id)->where('username','=',Auth::user()->username)->first();
        if( empty( $user ) ) {
            return false;
        }else  return true;
 	}

 	public function validate_has_brick ($user_id) {
 		$GutloPoint = GutloPoint::find( $user_id );
 		if($GutloPoint->has_brick <= 0 ) {
            return false;   
        }else return true;
 	}

 	public function validate_spam_action ($user_action) {
 		if(!Cache::has($user_action)) 
		{
			$cur_time=\Carbon\Carbon::now()->toDateTimeString();
			
			$expiresAt = Carbon::now()->addMinutes(1);
			Cache::put($user_action, strtotime($cur_time), $expiresAt);
			return true;
		} else {
			$time_ago = Cache::get($user_action);
			$cur_time=\Carbon\Carbon::now()->toDateTimeString();
			if(( strtotime($cur_time) - $time_ago ) > 1 ){
				Cache::forget($user_action);

				$expiresAt = Carbon::now()->addMinutes(1);
				Cache::put($user_action, strtotime($cur_time), $expiresAt);

				return true;	
			} 
			else return false;
		}
 	}
}
?>