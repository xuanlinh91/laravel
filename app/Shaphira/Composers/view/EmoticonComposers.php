<?php namespace Shaphira\Composers\view;

use User;
use GutloPoint;
use GutloRank;
use Media;
use DB;
use GutloComment;
use Cache;
use Shaphira\Common\CustomSql;

class EmoticonComposers {

	function compose($view) {
        $Group_Emoticons = DB::table('emoticon')->select('emo_group')->groupBy('emo_group')->get();
        $Emoticons = array();
        if (Cache::has('emoticons')) {
            $Emoticons = Cache::get('emoticons');
        }else {
            if(!empty($Group_Emoticons)){
                $length = count($Group_Emoticons);
                for ($i = $length - 1; $i >= 0 ; $i--) { 
                    if(trim($Group_Emoticons[$i]->emo_group) != ''  && $Group_Emoticons[$i]->emo_group != null){
                        $Emoticons = DB::table('emoticon')->select('char','url','emoticon')->where('emo_group','=',$Group_Emoticons[$i]->emo_group)->get();
                        break;
                    }
                }
            }else{
                $Group_Emoticons = array(); 
                $Emoticons = array();
            }
        }
        $view->with('group_emoticon_js', json_encode($Group_Emoticons));

        $view->with('group_emoticon', $Group_Emoticons);
        $indexedOnly = array();

        foreach ($Emoticons['default'] as $row) {
            $indexedOnly[] = array_values($row);
        }


        $view->with('emoticons', json_encode($indexedOnly));
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

