<?php

class BaneList extends Eloquent{

	protected $table = 'bane_list';
	protected $primaryKey = 'id';
	public $timestamps = false;	

	public function get_data_by_user ($username){
		$data = DB::table('bane_list')->select('a.nickname as staff_name ','bane_list.staff_id','bane_list.reason','bane_list.created_time','bane_list.end_time')
									->join('users','users.id','=','bane_list.user_id')
									->join('users as a','a.id','=','bane_list.staff_id')
									->where('users.username','=',$username)->get();
		return $data;
	}
}