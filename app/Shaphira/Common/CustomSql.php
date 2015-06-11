<?php namespace Shaphira\Common;

use User;
use GutloPoint;
use Auth;
use Cache;
use \Carbon\Carbon;
use DB;
class CustomSql {

 	/*
    |---------------------------------------------------------
    | select find_in_set in mysql
    |---------------------------------------------------------
    | @param $query query
    | @param $key tu khoa can tim kiem
    |
    |
    | ../Shaphira/Common/CustomSql.php
    */
    public function find_in_set ($query,$key,$index) {
        return $query->whereRaw('FIND_IN_SET ('.$key.','.$index.') ' );
    }

    public function or_find_in_set ($query,$key,$index) {
        return $query->orWhere(DB::raw(' FIND_IN_SET ('.$key.','.$index.') ' ));
    }

    public function find_not_in_set ($query,$key,$index) {
        return $query->whereRaw('NOT FIND_IN_SET ('.$key.','.$index.') ' );
    }

    public function or_find_not_in_set ($query,$key,$index) {
        return $query->whereRaw('OR NOT FIND_IN_SET ('.$key.','.$index.') ' );
    }
}
?>