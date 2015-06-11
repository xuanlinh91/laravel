<?php namespace Shaphira\Composers\sidebar\news;

use DB;

class WhatHotComposers {

	function compose($view) {

		$news = DB::table('news')
                ->select(['news.id' ,'news.created_time','news.description_vi AS description','news.title_vi as title','news.tag','news.url_thumsbnail','users.id as user_id','users.firstname','users.lastname','news.skey',DB::raw('NULL AS type')])
                ->join('users', 'news.user_id', '=', 'users.id')
                ->whereNull('deleted_time')
                ->where('news.skey','>','0')
                ->where('is_published','=',true)
                ->where('is_onHome','=',true)
                ->where('news.skey','<',7)
                        ->orderBy('news.skey', 'asc');

                $media = DB::table('media')
                ->select('media.id' ,'media.created_time','media.description_vi as description','media.title_vi as title','media.tag','media.url_thumsbnail','users.id as user_id','users.firstname','users.lastname','media.skey','media.type')
                ->join('users', 'media.user_id', '=', 'users.id')
                ->whereNull('deleted_time')
                ->where('media.skey','>','0')
                ->where('is_published','=',true)
                ->where('is_onHome','=',true)
                ->where('media.skey','<',7)
                ->orderBy('media.skey', 'asc')->unionAll($news)->get();
                $items = $this->SelectionSort_ASC($media);
                $view->with('topHot', $items);
	}

        public function SelectionSort_ASC($array1)
        {
                // dem tong so phan tu cua mang
                $length = count($array1);
                // for de sap xep mang
                for ($i = 0; $i < $length - 1; $i++)
                {       
                    // tim vi tri nho nhat theo tung for
                    $min = $i;
                    for ($j = $i + 1; $j < $length; $j++){
                        $date =  $array1[$j]->skey;
                        $date_2 =  $array1[$min]->skey;
                        if ($date < $date_2){
                            $min = $j;
                        }
                    }
                    // sau khi tim dc min thi hoan vi voi i
                    // voi vi tri thu $i
                    $temp = $array1[$i];
                    $array1[$i] = $array1[$min];
                    $array1[$min] = $temp;
                }
                return $array1;
        }
}

