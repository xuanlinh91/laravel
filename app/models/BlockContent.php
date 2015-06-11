<?php

class BlockContent extends Eloquent{

	protected $table = 'block_content';
	protected $primaryKey = 'id';
	public $timestamps = false;	

	public function get_data_by_user ($username){
		$data = DB::table('block_content')->select('a.nickname as staff_name','block_content.type','block_content.staff_id','block_content.reason','block_content.created_time','block_content.end_time')
									->join('users','users.id','=','block_content.user_id')
									->join('users as a','a.id','=','block_content.staff_id')
									->where('users.username','=',$username)->get();
		return $data;
	}
}