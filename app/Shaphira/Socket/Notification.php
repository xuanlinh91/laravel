<?php namespace Shaphira\Common;

use User;
use GutloPoint;
use GutloRank;
use Media;
use Input;
use Config;
use Event;
use UpdateScoreEventHandler;
class Notification {

	/**
	 * Send notification to user relations
	 *
	 * 
	 */
	public function Notification_relationship ($id_public,$data) {
		Event::fire(UpdateScoreEventHandler::_EVENT, array(
			array(	'action'=>'notification',
					'id_public'=>$id_public, 
					'data' =>json_encode($data)
				)
		));
	}
	/**
	 * Send notification on action like, brick.
	 *
	 * 
	 */
	public function Notification_gift ($id_public,$data) {
		echo 'abc';
		Event::fire(UpdateScoreEventHandler::_EVENT, array(
			array(	'action'=>'gift',
					'id_public'=>$id_public, 
					'data' =>json_encode($data)
				)
		));
	}
}