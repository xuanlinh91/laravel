<?php namespace Shaphira\Common;

use User;
use GutloPoint;
use GutloRank;
use Media;
use Input;
use Config;
use Event;
use UpdateScoreEventHandler;
class RealTimeData {


	/** 
	 * Send data to hot page
	 *
	 * 
	 */
	public function Activity_log ($data) {
		Event::fire(UpdateScoreEventHandler::_EVENT, array(
			array(	'action'=>'Activity_log',
					'data' =>json_encode($data)
				)
		));
	}

	/**
	 * Send data to show when comment or reply new
	 *
	 * 
	 */
	public function RealTime_reply_comment ($id_public,$data) {
		Event::fire(UpdateScoreEventHandler::_EVENT, array(
			array(	'action'=>'data',
					'id_public'=>$id_public, 
					'data' =>json_encode($data)
				)
		));
	}
}