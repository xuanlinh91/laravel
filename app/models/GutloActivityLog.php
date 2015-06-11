<?php
use Shaphira\Common\RealTimeData;
class GutloActivityLog extends Eloquent{

	protected $table = 'gutlo_activity_log';
	protected $primaryKey = 'id';
	const CREATED_AT = 'created_time';
	const UPDATED_AT = 'updated_time';
	
	public function new_log($to_id,$from_id,$content_id,$content_type,$activity_types_id) {
		$post_id = $content_id ;
		switch ($content_type) {
			case '1':
			case 1 :
				$GutloComment = DB::table('gutlo_comment')->select('to_post_id')->where('id','=',$content_id)->first();
				$post_id = $GutloComment->to_post_id;
				break;
			case '2':
			case 2 :
				$GutloReply = DB::table('gutlo_reply')->select('comment_id')->where('id','=',$content_id)->first();
				$GutloComment = DB::table('gutlo_comment')->select('to_post_id')->where('id','=',$GutloReply->comment_id)->first();
				$post_id = $GutloComment->to_post_id;
				break;
		}
		$GutloActivityLog = new GutloActivityLog();
        $GutloActivityLog->to_id = $to_id;
        $GutloActivityLog->from_id = $from_id;
        $GutloActivityLog->post_id = $post_id;
        $GutloActivityLog->activity_types_id = $activity_types_id;
        $GutloActivityLog->content_id = $content_id;
        $GutloActivityLog->content_type = $content_type;
        $GutloActivityLog->save();

        $hashtag_id = '';$cate_id = '';
        $GutloPosts = GutloPosts::find($post_id);
        if(!empty($GutloPosts)){
	        $hashtag_id = $GutloPosts->hashtag_id;
	        $cate_id = $GutloPosts->category_id;
        }
        $data_log = array(
        				'created_time' => \Carbon\Carbon::now()->toDateTimeString()
        				,'to_id' => $to_id
        				,'from_id' => $from_id
        				,'activity_types_id' => $activity_types_id
        				,'content_id' => $content_id
        				,'content_type' => $content_type
        				,'post_id'=> $post_id
        				,'hashtag_id'=>$hashtag_id
        				,'cate_id' => $cate_id
        );
        $RealTimeData = new RealTimeData();
        $RealTimeData->Activity_log($data_log);
	}

	public function get_last_log($from_id,$content_id,$activity_types_id,$sub_activity_types_id,$to_id){
		$activity =  null;
		if($sub_activity_types_id == null ){
			$activity =  DB::table('gutlo_activity_log')
	                    ->where('from_id','=',$from_id)
	                    ->where('content_id','=',$content_id)
	                    ->where('activity_types_id','=',$activity_types_id)
	                    ->where('to_id','=',$to_id)
	                    ->orderBy('created_time','DESC')
	                    ->first(); 
        } else {
			$activity =  DB::table('gutlo_activity_log')
	                    ->where('from_id','=',$from_id)
	                    ->where('content_id','=',$content_id)
	                    ->where('activity_types_id','=',$activity_types_id)
	                    ->orWhere('activity_types_id','=',$sub_activity_types_id)
	                    ->where('to_id','=',$to_id)
	                    ->orderBy('created_time','DESC')
	                    ->first(); 
	    }
        return $activity;
	}

	public function get_log_post_in_ontime ($start_time,$end_time) {
		$gutlo_activity_log = DB::table('gutlo_activity_log')->select('post_id','content_id','content_type','activity_types_id')
                                         ->where('created_time','>=',$start_time)
                                         ->where('created_time','<',$end_time)
                                         ->where('content_type','<',3)
                                         ->orderBy('created_time', 'ASC')->get();
        return $gutlo_activity_log;
	}
}