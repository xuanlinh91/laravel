<?php namespace Shaphira\Common;

use User;
use GutloPoint;
use GutloRank;
use Media;
use Input;
use Config;
use curl_init;
use curl_setopt;
use curl_exec;
use curl_close;
class Common {

	/*
	* Phương thức chống Xss trên tất cả dữ liệu vào và ra.
	*/
	public static function globalXssClean()
	{
		// Sử dụng cho toàn bộ dữ liệu nhập vào
		$sanitized = static::arrayStripTags(Input::get());
		Input::merge($sanitized);
	}

	public static function arrayStripTags($array)
	{
		$result = array();

		foreach ($array as $key => $value) {

			//Loại bỏ các thẻ đặc biệt, có thể hữu ích khi ta sử dụng form động.
			$key = htmlentities($key);

			//Nếu dữ liệu là một mảng thì ta đưa nó vào hàm arrayStripTags
			if (is_array($value)) {
				$result[$key] = static::arrayStripTags($value);
			} else {


			//hàm trim() dùng để loại bỏ các khoảng trắng dư thừa, nếu không muốn bạn có thể xóa nó.
				$result[$key] = trim(htmlentities($value));
			}
		}

		return $result;
	}
	//function validate  hash tag

 	public function validate_get_hashtag ($text,$partern) {
 		if(preg_match($partern, $text,$matches)){
 			if(count($matches == 1) && strlen($text) <= 32 ){
 				return true;
 			} else return false;
 		}else return false;
 	}

 	// function validate mentions
 	public function validate_get_mentions($text,$partern) {
 		if(preg_match($partern, $text,$matches)){
 			if(count($matches == 1) && strlen($text) <= 17 ){
 				$user_tag = User::where('username','=',$text)->first();
				if(!empty($user_tag)){
					return array('check' => true,'user_tag'=>$user_tag);
				}
 			} else return array('check' => false,'user_tag'=>array());
 		}else return array('check' => false,'user_tag'=>array());
 	}

 	function encryptor($action, $string) {
	    $output = false;

	    $encrypt_method = "AES-256-CBC";
	    //pls set your unique hashing key
	    $secret_key = Config::get('Common.secret_key');
	    $secret_iv = Config::get('Common.secret_iv');

	    // hash
	    $key = hash('sha256', $secret_key);

	    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	    $iv = substr(hash('sha256', $secret_iv), 0, 16);

	    //do the encyption given text/string/number
	    if( $action == 'encrypt' ) {
	        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	        $output = base64_encode($output);
	    }
	    else if( $action == 'decrypt' ){
	    	//decrypt the given text/string/number
	        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	    }

	    return $output;
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

 	public function urlType($url) {
	    if (strpos($url, 'youtube') > 0 || strpos($url, 'youtu.be') > 0 ) {
	        return 'youtube';
	    } elseif (strpos($url, 'facebook') > 0) {
	        return 'facebook';
	    } else {
	        return 'other';
	    }
	}

 	public function file_get_contents_curl($url)
	{
	    $ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
	}
}

