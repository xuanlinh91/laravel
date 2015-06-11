<?php

class Media extends Eloquent{

	protected $table = 'gutlo_media';
	protected $primaryKey = 'id';
	const CREATED_AT = 'upload_time';
	const UPDATED_AT = 'updated_time';
	
}