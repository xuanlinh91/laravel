<?php namespace Shaphira\Composers\view;

use User;
use GutloPoint;
use GutloRank;
use Media;
use DB;
use GutloComment;
use Shaphira\Common\CustomSql;

class TrendComposers {

	function compose($view) {
        $GutloComment_count = new GutloComment();
        $TopsTrend = DB::table('gutlo_hashtag')
                ->select(['gutlo_hashtag.id','gutlo_hashtag.hashtag',DB::raw('SUM(gutlo_hashtag.total_post) as total_post ')])
                ->join('gutlo_posts','gutlo_hashtag.id','=','gutlo_hashtag.id');
       //../Shaphira/Common/CustomSql.php
        $CustomSql = new CustomSql();
        $TopsTrend = $CustomSql->find_in_set($TopsTrend,'gutlo_hashtag.id','gutlo_posts.hashtag_id');
        // end
        $TopsTrend = $TopsTrend
                ->whereNull('gutlo_posts.deleted_time')
                ->groupBy('gutlo_hashtag.hashtag')->skip(0)->take(8)->get();

        $posts = DB::table('gutlo_posts')->select(['gutlo_posts.id','gutlo_hashtag.hashtag',DB::raw('SUM(total_like) as total_like ,SUM(total_brick) as total_brick ,SUM(total_point) as total_point ')])
                                            ->join('gutlo_hashtag','gutlo_hashtag.id','=','gutlo_posts.hashtag_id')
                                            ->whereNull('deleted_time')
                                            ->groupBy('gutlo_hashtag.hashtag')
                                            ->get();
        $length = COUNT($TopsTrend);
        for($i = $length - 1 ; $i >= 0 ; $i --){
            $length_post = count($posts);
            $total_point = 0; $total_like = 0; $total_brick = 0;$total_comment = 0;
            for($j = $length_post - 1 ; $j >=0; $j--){
                if($TopsTrend[$i]->hashtag == $posts[$j]->hashtag ) {
                    if( $posts[$j]->total_point != null ) $total_point = $total_point + $posts[$j]->total_point;
                    if( $posts[$j]->total_like != null ) $total_like = $total_like + $posts[$j]->total_like;
                    if( $posts[$j]->total_brick != null ) $total_brick = $total_brick + $posts[$j]->total_brick;
                    $total_comment - $total_comment + $GutloComment_count->get_total_comment($posts[$j]->id);
                }
            }

            $TopsTrend[$i]->total_point = $total_point;
            $TopsTrend[$i]->total_like = $total_like;
            $TopsTrend[$i]->total_brick = $total_brick;
            $TopsTrend[$i]->total_comment = $total_comment;
        }
        $view->with('trends', $TopsTrend);
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

