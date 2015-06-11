<?php

class IDType extends Eloquent{
	protected $table = 'gutlo_id_types';
	protected $primaryKey = 'id';
	public static $types = array('User' => 1, 'Place' => 2);

}