<?php namespace Shaphira\Composers\sidebar;

use User;
use GutloPoint;
use GutloRank;
use Media;
use DB;
use Shaphira\Common\CustomSql;
use ActivityController;

class TopsComposers {

	function compose($view) {
        $ActivityController = new ActivityController();
		$TopsAHBP = DB::table('users')
                ->select(['gutlo_hashtag.hashtag as hashtag','user_informations.gender','users.confirmed','users.vip','users.blogger_level','users.shaphira_verified','users.dayOnline','gutlo_posts.created_time','gutlo_medals.medal_name','gutlo_medals.medal_icon_url','users.nickname','users.username','users.id','users.user_level'
                            ,'gutlo_point.real_point',DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) as ava')])
                ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
                ->join('user_informations','user_informations.id','=','users.id')
                ->join('gutlo_point','gutlo_point.user_id','=','users.id')
                ->join('gutlo_posts','gutlo_posts.from_id','=','users.id')
                ->join('gutlo_hashtag','gutlo_hashtag.id','=','gutlo_hashtag.id');
        $CustomSql = new CustomSql();
        $TopsAHBP = $CustomSql->find_in_set($TopsAHBP,'gutlo_hashtag.id','gutlo_posts.hashtag_id');
        $TopsAHBP = $TopsAHBP
                ->whereRaw('gutlo_posts.id = (SELECT max(id) from gutlo_posts p where p.from_id = users.id and p.hashtag_id <> "" and p.deleted_time IS NULL )')
                ->where('gutlo_point.real_point','>',0)
                ->leftJoin('gutlo_medals', function($join) {
                        $join->on('gutlo_point.real_point','>=','gutlo_medals.min_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.max_point')
                        ->orOn('gutlo_point.real_point','>=','gutlo_medals.max_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.min_point');
                    })
                ->groupBy('users.id')
                ->orderBy('users.blogger_level','DESC')
                ->orderBy('users.user_level','DESC')
                ->orderBy('gutlo_point.real_point','DESC')
                ->skip(0)->take(3)->get();
		// $TopsAHBP = $this->SelectionSort_DESC($TopsAHBP);
        $length_TopAHBP = COUNT($TopsAHBP);
        for($i = $length_TopAHBP - 1; $i >=0; $i-- ){
            $TopsAHBP[$i]->created_time = $ActivityController->time_stamp($TopsAHBP[$i]->created_time);
        }

        $TopsTrend = DB::table('gutlo_hashtag')
                ->select(['gutlo_hashtag.id','gutlo_hashtag.hashtag','gutlo_hashtag.total_post'
                    ,'gutlo_hashtag.created_time','gutlo_posts.id as id_post'])
                ->join('gutlo_posts','gutlo_hashtag.id','=','gutlo_hashtag.id');
       //../Shaphira/Common/CustomSql.php
        $CustomSql = new CustomSql();
        $TopsTrend = $CustomSql->find_in_set($TopsTrend,'gutlo_hashtag.id','gutlo_posts.hashtag_id');
        // end
        $TopsTrend = $TopsTrend
                ->whereNull('gutlo_posts.deleted_time')
                ->groupBy('gutlo_hashtag.hashtag')
                ->orderBy('gutlo_hashtag.total_post','DESC')->skip(0)->take(8)->get();

        $view->with('TopTrends', $TopsTrend);
        $view->with('TopsAHBP', $TopsAHBP);

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
                    $key =  $array1[$j]->real_point;
                    $key_2 =  $array1[$min]->real_point;
                    if ($key < $key_2){
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

	public function SelectionSort_DESC($array1)
    {
            // dem tong so phan tu cua mang
            $length = count($array1);
            // for de sap xep mang
            for ($i = 0; $i < $length - 1; $i++)
            {       
                // tim vi tri nho nhat theo tung for
                $max = $i;
                for ($j = $i + 1; $j < $length; $j++){
                    $key =  $array1[$j]->real_point;
                    $key_2 =  $array1[$max]->real_point;
                    if ($key > $key_2){
                        $max = $j;
                    }
                }
                // sau khi tim dc max thi hoan vi voi i
                // voi vi tri thu $i
                $temp = $array1[$i];
                $array1[$i] = $array1[$max];
                $array1[$max] = $temp;
            }
            return $array1;
    }
}

