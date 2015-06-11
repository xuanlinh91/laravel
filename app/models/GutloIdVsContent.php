<?php

class GutloIdVsContent extends Eloquent{

	protected $table = 'gutlo_id_vs_content';
	protected $primaryKey = 'user_id';
	public $timestamps = false;

	public function insert_new_record($user_id,$content_id,$content_type,$like_content,$brick_content) {
		$GutloIdVsContent = new GutloIdVsContent();
        $GutloIdVsContent->user_id = $user_id;
        $GutloIdVsContent->content_id = $content_id;
        $GutloIdVsContent->content_type = $content_type;
        $GutloIdVsContent->like_content = $like_content;
        $GutloIdVsContent->brick_content = $brick_content;
        $GutloIdVsContent->save();
	}

	public function update_recode($user_id,$content_id,$content_type,$like_content,$brick_content) {
		
		$GutloIdVsContent_data = array('like_content'=>$like_content,'brick_content'=>$brick_content);

        $GutloIdVsContent = GutloIdVsContent::where('user_id','=',$user_id)
                            ->where('content_id','=',$content_id)->where('content_type','=',$content_type)->update($GutloIdVsContent_data);
	}
	
}