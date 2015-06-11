<?php

class GutloActionShow extends Eloquent{

	protected $table = 'gutlo_action_show';
	protected $primaryKey = 'user_id';
	const CREATED_AT = 'created_time';
	const UPDATED_AT = 'updated_time';
	
}