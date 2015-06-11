<?php


	return array(
		'analytics' => array(
			'google' => array(
				'url-site' => 'http://52.68.136.170'
			)
		),
		'coe_brick'		=> -2,
		'coe_like'		=> 1,
		'coe_post'		=> 1,
		'count_mention' => 5,
		'count_hashtag'	=> 3,
		'post_type'		=> '0',
		'comment_type'	=> '1',
		'reply_type'	=> '2',
		'delete'		=> array(
							'comment'=>'10',
							'point'	=>'20',
							'like'	=>'50',
							'brick'	=>'10'
						),
		'secret_key'	=> 'shaphira',
		'secret_iv'		=> 'gutlo',
		'content_type'	=>array(
							'post'=>'0',
							'comment'=>'1',
							'reply'	=>'2'
		),
		'notifi_type'	=> array(
							'post'=>'0',
							'comment'=>'0',
							'reply'	=>'0',
							'like'=>'1',
							'brick'=>'2',
							'mention'=>'3',
		),
		'gender'		=> array(
							'male'=>'1',
							'other'=>'3',
							'female'=>'2',

		),
		'gender_user_msg_notifi' => array(
							'1'=>'của anh ấy',
							'3'=>'của thím ấy',
							'2'=>'của cô ấy',

		),
		'max_length_content_in_list_post' => 300,
		'fillter_hot_post'	=> array(
						'min_user'	=> 3,
						'min_comment' 	=> 6,
						'hashtag'	=>true,
						'min_like_brick' => 1,
						'coefficient_of_interest' => 20,
						'min_rate_hot'=>20
		),
		'count_load_more_reply' => 10,
		'count_load_more_reply_first' => 2,
		'count_load_more_comment' => 10,
		'media_type'	=> array(
					'image'	=> 0,
					'video'	=> 1
		)
	);







?>