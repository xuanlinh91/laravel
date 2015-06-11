<?php

class GutloLevel extends Eloquent{
	protected $table = 'gutlo_level';
	protected $primaryKey = 'id';

	public function get_next_level($level){
		$data = DB::table('gutlo_level')->select('exp','gold')
										->where('level','=',$level )
										->first();
		return $data;
	}
}