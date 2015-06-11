
<?php
 
class GutloReports extends Eloquent{

	protected $table = 'gutlo_reports';
	protected $primaryKey = 'id';
	const CREATED_AT = 'created_time';
	const UPDATED_AT = 'updated_time'; 


	public function report($from_id,$to_content_id,$type,$content) {

		if($from_id == null || $from_id == 0 ) return  array('error'=>'true','msg'=> 'Có lỗi phát sinh vui lòng liên hệ Quản trị viên để biết thêm thông tin ' ) ;
		if($to_content_id == null || $to_content_id == 0 ) return array('error'=>'true','msg'=> 'Có lỗi phát sinh vui lòng liên hệ Quản trị viên để biết thêm thông tin ' ) ;
		if($type == null ) return array('error'=>'true','msg'=> 'Có lỗi phát sinh vui lòng liên hệ Quản trị viên để biết thêm thông tin ' ) ;

		$report = GutloReports::where('from_id','=',$from_id)->where('to_content_id','=',$to_content_id)->where('type','=',$type)->first();

		if(empty($report)) {
			$report = new GutloReports();
			$report->from_id = $from_id;
			$report->to_content_id = $to_content_id;
			$report->type = $type;
			$report->content = $content;
			$report->save();
			$report = $report->id;	
			return 	array('error'=>'false','msg'=> '','id'=>$report );	
		} else return array('error'=>'true','msg'=> 'Không thể thực hiện tác vụ này do bạn đ report rồi . ' );

	}
}