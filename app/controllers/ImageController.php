<?php

class ImageController extends BaseController {
	// dkm thieu validate 
		function crop() {
			return Image::crop(Input::get('url'), Input::get('x'), Input::get('y'), Input::get('width'), Input::get('height'),90);
		}

		function upload_avatar() {
			$file_name = Input::file('file')->getClientOriginalName();
			$array_name = explode('.', $file_name);
			$file_name = end($array_name);
			if(!preg_match('(gif|GIF|jpg|JPG|JPEG|jpeg|PNG|png)',$file_name)){
				return Response::json(array('error'=>'true','msg'=>'file ban upload không phù hợp vui lòng chọn lại  ','path'=>'','resize'=> ''));
			}
			$image_info = getimagesize(Input::file('file'));

			$resize = '';
			if($image_info[0] < 100 || $image_info[1] < 100){
				return Response::json(array('error'=>'true','msg'=>'Ảnh bạn đưa chọn có kích cỡ không phù hợp vui lòng chọn ảnh khác ','path'=>'','resize'=> ''));
			}
			$path = Image::upload(Input::file('file'), 'media/'.Auth::user()->id.'/photos/RecycleBin', false);
			// Th1 W = H
			if($image_info[0] > $image_info[1]) {
				// W > h
				$Wdif = $image_info[0] - $image_info[1];
				$wmax = $image_info[1];
				$x0 = $Wdif/2;
				$y0 = 0;
				$resize = Image::crop($path,'uploads/media/'.Auth::user()->id.'/photos/RecycleBin', $x0,$y0,$wmax,$wmax,90)['targetUrl'];
			} else if($image_info[0] < $image_info[1]){
				// W < h
				$hdif = $image_info[1] - $image_info[0];
				$hmax = $image_info[0];
				$y0 = $hdif/2;
				$x0 = 0;
				$resize = Image::crop($path,'uploads/media/'.Auth::user()->id.'/photos/RecycleBin', $x0,$y0,$hmax,$hmax,90)['targetUrl'];
			}
			return  Response::json(array('error'=>'false','msg'=>'','path'=>$path,'resize'=> $resize));					
		}

		function upload_status() {
			$file_name = Input::file('file')->getClientOriginalName();
			$array_name = explode('.', $file_name);
			$file_name = end($array_name);
			if(!preg_match('(gif|GIF|jpg|JPG|JPEG|jpeg|PNG|png)',$file_name)){
				return Response::json(array('error'=>'true','msg'=>'file ban upload không phù hợp vui lòng chọn lại  ','path'=>'','resize'=> ''));
			}
			$image_info = getimagesize(Input::file('file'));
			if($image_info[0] < 100 || $image_info[1] < 100){
				return Response::json(array('error'=>'true','msg'=>'Ảnh bạn đưa chọn có kích cỡ không phù hợp vui lòng chọn ảnh khác ','path'=>'','resize'=> ''));
			}
			$path = Image::upload(Input::file('file'), 'media/'.Auth::user()->id.'/photos/RecycleBin', false);
			$a = $image_info[0];
			$b = $image_info[1];
			$resize = '';
			if($a > $b) {
				$p = 180/260*100;//69%
				$c = $a*$p/100;
				if ($b <= $c){
					$a_new = ($b*100)/$p;
					$b_new = $b;
					$dif = $a-$a_new;
					$x0 =$dif/2;
					$y0=0;
					$resize = Image::crop($path,'uploads/media/'.Auth::user()->id.'/photos/RecycleBin', $x0,$y0,$a_new,$b_new,90)['targetUrl'];
				}else {
					$dif = $a - $b;
					$a_new = $a - $dif;
					$b_new = $b;
					$x0 = $dif/2;
					$y0 = 0;
					$resize = Image::crop($path,'uploads/media/'.Auth::user()->id.'/photos/RecycleBin', $x0,$y0,$a_new,$b_new,90)['targetUrl'];
				}
			}else if($a < $b) {
				$dif = $b - $a;
				$a_new = $a;
				$b_new = $b - $dif;
				$x0 = 0;
				$y0 = $dif/2;
				$resize = Image::crop($path,'uploads/media/'.Auth::user()->id.'/photos/RecycleBin', $x0,$y0,$a_new,$b_new,90)['targetUrl'];
			}
			$resize  = Image::resize($path,'uploads/media/'.Auth::user()->id.'/photos/RecycleBin/resize', 100,100,90)['targetUrl'];
			return Response::json(array('error'=>'false','msg'=>'','path'=>$path,'resize'=> $resize));				
		}

		function upload_cover() {
			$file_name = Input::file('file')->getClientOriginalName();
			$array_name = explode('.', $file_name);
			$file_name = end($array_name);
			if(!preg_match('(gif|GIF|jpg|JPG|JPEG|jpeg|PNG|png)',$file_name)){
				return Response::json(array('error'=>'true','msg'=>'file ban upload không phù hợp vui lòng chọn lại  ','path'=>'','resize'=> ''));
			}

			$image_info = getimagesize(Input::file('file'));
			if($image_info[0] >= 3600 || $image_info[1] >= 3600) {
				return Response::json(array('error'=>'true','msg'=>'Ảnh bạn chọn có kích cỡ không phù hợp vui lòng chọn ảnh khác ','path'=>'','resize'=> ''));
			}
			$path = Image::upload(Input::file('file'), 'media/'.Auth::user()->id.'/photos/RecycleBin', false);

			$a = $image_info[0];
			$b = $image_info[1];
			$width = 798;
			if($a >= 798 ){
				$re = $a/$b*100 ;
				$new_height = $width/$re *100;
				$new_width = $width;
				Image::resize($path,'uploads/media/'.Auth::user()->id.'/photos/RecycleBin', $new_width,$new_height,90)['targetUrl'];
			}else {
				if($a < ((55 * $width)/100)){
					return Response::json(array('error'=>'true','msg'=>'Ảnh bạn chọn có kích cỡ không phù hợp vui lòng chọn ảnh khác ','path'=>'','resize'=> ''));	
				}else {
					$re = $a/$width;
					$new_width = $a / $re;
					$new_height = $b/$re;
					Image::resize($path,'uploads/media/'.Auth::user()->id.'/photos/RecycleBin', $new_width,$new_height,90)['targetUrl'];
				}
			}
			return Response::json(array('error'=>'false','msg'=>'','path'=>$path,'resize'=> ''));				
		}
}