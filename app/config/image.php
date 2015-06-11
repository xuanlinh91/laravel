<?php 
return array(
    'library'     => 'gd',
    'upload_path' => public_path() . '/uploads/',
    'upload_dir'  => 'uploads',
    'quality'     => 90,
 
    'dimensions' => array(
        'thumb'  => array(100, 100, false, 90),
        'medium' => array(600, 400, false, 90),

    'avatar'	=> array('30x30' => array(30,30, 90),
    					 '40X40' => array(40,40, 90),
    					 '90X90' => array(90,90, 90),
    					 '130X130' => array(130,130, 90),
    					 'full'		=> ''
    					),
    'cover'		=> '',
    ),
);
