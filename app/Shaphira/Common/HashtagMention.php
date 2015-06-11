<?php namespace Shaphira\Common;

use Shaphira\Common\SValidator;
use Shaphira\Common\Common;
use User;
use GutloHashtag;
use DB;
use Config;
class HashtagMention {

	function get_mentions_id ($content) {
		$content = htmlentities($content);
		$content = str_replace("&nbsp;", " ", $content);
		$mentions_id = '';
		if(isset($content) && $content != ''){
			$exp = explode('@',$content);
			$length_exp = count($exp);
			for($i = 0 ; $i < $length_exp;$i++){
				$length_string = strlen($exp[$i]);
				$key = '';
				$_key = '';
				if($i > 0 ){
					if(isset($exp[$i][strlen($exp[$i]) - 1])) $key = $exp[$i][strlen($exp[$i]) - 1];
					else $key = '';
					if($i+1 < $length_exp) {
						if($i+1 == $length_exp - 1) {
							$_key = '';
						}else {
							if(isset($exp[$i+1][strlen($exp[$i+1]) - 1])) $_key = $exp[$i+1][strlen($exp[$i+1]) - 1];
							else $_key = 's';
						}
					}
				}
				else {
					if(strlen($exp[$i]) - 1 >= 0) {
						$key = $exp[$i][strlen($exp[$i]) - 1];
						if($i+1 < $length_exp){
							if($i+1 == $length_exp - 1) {
								$_key = '';
							}else {
								if(isset($exp[$i+1][strlen($exp[$i+1]) - 1])) $_key = $exp[$i+1][strlen($exp[$i+1]) - 1];
								else $_key = 's';
							}
						}
					}
				}
				if(($key == '' || $key == ' ' || strstr($key, PHP_EOL)) && ($_key == '' || $_key == ' '|| strstr($_key, PHP_EOL)) ){
					if($i + 1 <= $length_exp-1) {
						$text = preg_split('/\s+/', $exp[$i+1])[0];
						$partern = '/^\w+$/';
						$validate = new SValidator();
						$value = $validate->validate_get_mentions($text,$partern);
						if($value['check']) {
					    	if (strpos($mentions_id,$value['user_tag']->id) === false) {
						    	$mentions_id = $mentions_id . $value['user_tag']->id . ',';
						    	$count = count( explode(',', $mentions_id) );
								if(( $count - 1 ) == Config::get('Common.count_mention')) break;
							}
					    }
					}
				}
			}
		}
		if(strlen($mentions_id) > 0){
			$mentions_id = trim(substr($mentions_id, 0,strlen($mentions_id) -1));
		}
		return $mentions_id;
	}
	function get_hashtag_id ($content,$type_content,$id,$category_id){
		$content = htmlentities($content);
		$content = str_replace("&nbsp;", " ", $content);
		$hashtag_id = '';
		if(isset($content) && $content != ''){
			$exp = explode('#',$content);
			$length_exp = count($exp);
			for($i = 0 ; $i < $length_exp;$i++){
				$length_string = strlen($exp[$i]);
				$key = '';
				$_key = '';
				if($i > 0 ){
					$key = $exp[$i][strlen($exp[$i]) - 1];
					if($i+1 < $length_exp) {
						if($i+1 == $length_exp - 1) {
							$_key = '';
						}else {
							$_key = $exp[$i+1][strlen($exp[$i+1]) - 1];
						}
					}
				}
				else {
					if(strlen($exp[$i]) - 1 >= 0) {
						$key = $exp[$i][strlen($exp[$i]) - 1];
						if($i+1 < $length_exp){
							if($i+1 == $length_exp - 1) {
								$_key = '';
							}else {
								$_key = $exp[$i+1][strlen($exp[$i+1]) - 1];
							}
						}
					}
				}
				if(($key == '' || $key == ' ' || strstr($key, PHP_EOL)) && ($_key == '' || $_key == ' '|| strstr($_key, PHP_EOL)) ){
					if($i + 1 <= $length_exp-1) {
						$text = preg_split('/\s+/', $exp[$i+1])[0];
						$partern = '/^\w+$/';
						$validate = new SValidator();
						$value = $validate->validate_get_hashtag($text,$partern);

						$data = DB::table('text_black_list')->select('text')->where('text','=',$text)->first();
						if($value && empty($data)){
							$hashtags = DB::table('gutlo_hashtag')->select('id')->where('hashtag','=',$text)->where('category_id','=',$category_id)->first();
							if(empty($hashtags)) {
					    		$hashtag = new GutloHashtag();
								$hashtag->hashtag = $text;
								$hashtag->created_time = \Carbon\Carbon::now()->toDateTimeString();
								$hashtag->category_id = $category_id;
								$hashtag->total_post = 1;
								$hashtag->save();
								$hashtag_id = $hashtag_id.$hashtag->id.',';
								$count = count( explode(',', $hashtag_id) );
								if( ( $count - 1 ) == Config::get('Common.count_hashtag')) break;
							}else {
								if (strpos($hashtag_id,$hashtags->id) === false) {

									$hashtag_id = $hashtag_id.$hashtags->id.',';
									$update_hashTag =  GutloHashtag::find($hashtags->id);
									$update_hashTag->total_post = $update_hashTag->total_post + 1;
									$update_hashTag->save();
									$count = count( explode(',', $hashtag_id) );
									if( ( $count - 1 ) == Config::get('Common.count_hashtag')) break;
								}
							}
						}
					}
				}
			}
		}
		if(strlen($hashtag_id) > 0){
			$hashtag_id = trim(substr($hashtag_id, 0,strlen($hashtag_id) -1));
		}
		return $hashtag_id;
	}

	function add_hashtag_to_content ($content,$hashtag_id){
			$content = str_replace("&nbsp;", " ", $content);
		 	$common = new Common();
			$hashtag_id = explode(',', $hashtag_id);
			$hash_tag = array();
			$link_tag = '';
			$trend_link = '';
			for($i = 0 ; $i < COUNT($hashtag_id); $i++){
				$hashtag_content = DB::table('gutlo_hashtag')->select('hashtag')->where('id','=',$hashtag_id[$i])->first();
				if(!empty($hashtag_content))
				{
					$hash_tag[$hashtag_content->hashtag] = $hashtag_content->hashtag;
					$hash_tag[strtolower($hashtag_content->hashtag)] = $hashtag_content->hashtag;
				}
			}
			if(!empty($hash_tag)){
				$exp = explode('#',$content);
				$length_exp = count($exp);
				$trend_link = '';
				for($i = 0 ; $i < $length_exp;$i++){
					$length_string = strlen($exp[$i]);
					$key = '';
					$_key = '';
					if($i > 0 ){
						if(isset($exp[$i][strlen($exp[$i]) - 1])) $key = $exp[$i][strlen($exp[$i]) - 1];
						else $key = '';
						if($i+1 < $length_exp) {
							if($i+1 == $length_exp - 1) {
								$_key = '';
							}else {
								$_key = $exp[$i+1][strlen($exp[$i+1]) - 1];
							}
						}
					}
					else {
						if(strlen($exp[$i]) - 1 >= 0) {
							$key = $exp[$i][strlen($exp[$i]) - 1];
							if($i+1 < $length_exp){
								if($i+1 == $length_exp - 1) {
									$_key = '';
								}else {
									$_key = $exp[$i+1][strlen($exp[$i+1]) - 1];
								}
							}
						}
					}
					if(($key == '' || $key == ' '|| strstr($key, PHP_EOL)) && ($_key == '' || $_key == ' '|| strstr($_key, PHP_EOL)) ){
						if($i + 1 <= $length_exp-1) {
							$hashtag_content = preg_split('/\s+/', $exp[$i+1])[0];
							$partern = '/^\w+$/';
							$validate = new SValidator();
							$value = $validate->validate_get_hashtag($hashtag_content,$partern);
							if($value){
								try{
									if(isset($hash_tag[strtolower($hashtag_content)]))
									{
										$link_hashtag = $hash_tag[strtolower($hashtag_content)];
										if (strpos($trend_link,$link_hashtag) === false) {

											$trend_link = $trend_link.$link_hashtag ;
											$link_tag = '<a href="'.url('/hashtag/'.$link_hashtag).'">#'.$link_hashtag.'</a>';
											$trend_link = $trend_link.',';
											$exp[$i+1] = str_replace($hashtag_content, $link_tag, $exp[$i+1]);
										}
									}
								}catch (Exception $e){

								}
							}
						}
					}
				}
				$content = implode("#",$exp);
				$content = str_replace('#<a', '<a', $content);
				if(strlen($trend_link) > 0){
					$trend_link = trim(substr($trend_link, 0,strlen($trend_link) -1));
				}
				$trend_link = explode(',', $trend_link);
			}
			return array('content' => $content,'link_tag' => $trend_link);
		}


		function add_mentions_to_content ($content,$mentions_id){
			$content = str_replace("&nbsp;", " ", $content);
			$mentions_id = explode(',', $mentions_id);
			$user_tag = array();
			$list_mentions_id='';
			for($i = 0 ; $i < COUNT($mentions_id); $i++){
				$username_tag = DB::table('users')->select('username')->where('id','=',$mentions_id[$i])->first();
				if(!empty($username_tag))
				{
					$user_tag[$username_tag->username] = $username_tag->username;
					$user_tag[strtolower($username_tag->username)] = $username_tag->username;

				}
			}
		//	$content = htmlentities($content);
		//	$content = str_replace("&nbsp;", " ", $content);
			if(!empty($user_tag)){
				$exp = explode('@',$content);
				$length_exp = count($exp);
				for($i = 0 ; $i < $length_exp;$i++){
					$length_string = strlen($exp[$i]);
					$key = '';
					$_key = '';
					if($i > 0 ){
						if(strlen($exp[$i]) - 1 != -1) $key = $exp[$i][strlen($exp[$i]) - 1];
						if($i+1 < $length_exp) {
							if($i+1 == $length_exp - 1) {
								$_key = '';
							}else {
								if(strlen($exp[$i+1]) - 1 != -1) $_key = $exp[$i+1][strlen($exp[$i+1]) - 1];
							}
						}
					}
					else {
						if(strlen($exp[$i]) - 1 >= 0) {
							$key = $exp[$i][strlen($exp[$i]) - 1];
							if($i+1 < $length_exp){
								if($i+1 == $length_exp - 1) {
									$_key = '';
								}else {
									if(strlen($exp[$i+1]) - 1 != -1) $_key = $exp[$i+1][strlen($exp[$i+1]) - 1];
								}
							}
						}
					}
					if(($key == '' || $key == ' '|| strstr($key, PHP_EOL)) && ($_key == '' || $_key == ' '|| strstr($_key, PHP_EOL)) ){
						if($i + 1 <= $length_exp-1) {
							$text = preg_split('/\s+/', $exp[$i+1])[0];
							$partern = '/^\w+$/';
							$validate = new SValidator();
							$value = $validate->validate_get_mentions($text,$partern);
							if($value['check']) {
								try{
									if(isset($user_tag[strtolower($text)])  ){
					    				if (strpos($list_mentions_id,$user_tag[strtolower($text)]) === false) {
					    					$list_mentions_id = $list_mentions_id.','.$user_tag[strtolower($text)];
											$val = '<a href="'.url('/'.$user_tag[strtolower($text)]).'">@'.$user_tag[strtolower($text)].'</a>';
											$exp[$i+1] = str_replace($text, $val, $exp[$i+1]);
										}
									}
								}catch (Exception $e){

								}
							}
						}
					}
				}
				$content = implode("@",$exp);
				$content = str_replace('@<a', '<a', $content);
			}
			return $content;
		}

		function add_hashtag_to_content_test ($content,$mentions_id){
			$content = htmlentities($content);
			$content = str_replace("&nbsp;", " ", $content);
			$hashtag_id = '';
			if(isset($content) && $content != ''){
				$exp = explode('#',$content);
				$length_exp = count($exp);
				for($i = 0 ; $i < $length_exp;$i++){
					$length_string = strlen($exp[$i]);
					$key = '';
					$_key = '';
					if($i > 0 ){
						$key = $exp[$i][strlen($exp[$i]) - 1];
						if($i+1 < $length_exp) {
							if($i+1 == $length_exp - 1) {
								$_key = '';
							}else {
								$_key = $exp[$i+1][strlen($exp[$i+1]) - 1];
							}
						}
					}
					else {
						if(strlen($exp[$i]) - 1 >= 0) {
							$key = $exp[$i][strlen($exp[$i]) - 1];
							if($i+1 < $length_exp){
								if($i+1 == $length_exp - 1) {
									$_key = '';
								}else {
									$_key = $exp[$i+1][strlen($exp[$i+1]) - 1];
								}
							}
						}
					}
					if(($key == '' || $key == ' ' || strstr($key, PHP_EOL)) && ($_key == '' || $_key == ' '|| strstr($_key, PHP_EOL)) ){
						if($i + 1 <= $length_exp-1) {
							$text = preg_split('/\s+/', $exp[$i+1])[0];
							$partern = '/^\w+$/';
							$validate = new SValidator();
							$value = $validate->validate_get_hashtag($text,$partern);
							if($value){
								$hashtags = DB::table('gutlo_hashtag')->select('id')->where('hashtag','=',$text)->where('category_id','=',$category_id)->first();
								if(empty($hashtags)) {
						    		$hashtag = new GutloHashtag();
									$hashtag->hashtag = $text;
									$hashtag->created_time = \Carbon\Carbon::now()->toDateTimeString();
									$hashtag->category_id = $category_id;
									$hashtag->total_post = 1;
									$hashtag->save();
									$hashtag_id = $hashtag_id.$hashtag->id.',';
									$count = count( explode(',', $hashtag_id) );
									if( ( $count - 1 ) == Config::get('Common.count_hashtag')) break;
								}else {
									if (strpos($hashtag_id,$hashtags->id) === false) {

										$hashtag_id = $hashtag_id.$hashtags->id.',';
										$update_hashTag =  GutloHashtag::find($hashtags->id);
										$update_hashTag->total_post = $update_hashTag->total_post + 1;
										$update_hashTag->save();
										$count = count( explode(',', $hashtag_id) );
										if( ( $count - 1 ) == Config::get('Common.count_hashtag')) break;
									}
								}
							}
						}
					}
				}
			}
			if(strlen($hashtag_id) > 0){
				$hashtag_id = trim(substr($hashtag_id, 0,strlen($hashtag_id) -1));
			}
			return $hashtag_id;
		}

		public function fillter_content_post() {
			// $length = strlen($content);
			// $count = 0;
			// $pre_enter = 0;
			// $partern = '/^\w+$/';
			// $validate = new SValidator();
			// for ($i=0; $i < $length; $i++) { 
			// 	# code...
			// 	echo $content[$i];
			// 	if($content[$i] != ' '){
			// 		$value = $validate->validate_get_hashtag($content[$i],$partern);
			// 		if(!$value) {
			// 			echo ';;;;;;;;;;';
			// 		}
			// 		if(strstr($content[$i], PHP_EOL)) {
			// 			// echo $i.' i ';
			// 			// echo $count.'count ';
			// 			if($pre_enter != 0 && $pre_enter == $i-1 && $count >=  1){
			// 				echo 'abc';
			// 			}
			// 			$count = $count+1 ;
			// 			$pre_enter = $i;
			// 		}else {
			// 			$count = 0;
			// 			$pre_enter = 0;
			// 		}
			// 	}
			// }
		}
}


?>