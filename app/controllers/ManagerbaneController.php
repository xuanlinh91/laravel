<?php
use Shaphira\Common\Common;
use Shaphira\Common\HashtagMention;
class ManagerbaneController extends BaseController{

    public function __construct () {

    }

    public function bane_user(){
    	$user = null;
        $GutloActivityLog = new GutloActivityLog();
        if(Auth::check()){
            $Auth = Auth::user();
            $user = User::where('id','=',$Auth->id)->where('username' , '=', $Auth->username)->get()->first();
            if(empty($user)) {
                Auth::logout();
                Redirect::to('/login');
            }else if($user->permission_role <= 1){
            	return array('error' => 'true' ,'msg' => 'Bạn không dủ quyền để làm bane người khác !!!' );
            }
        }else Redirect::to('/login');

    	 $DataInput = array(
            'username'   => trim(Input::get('username')),
            'reason'   => trim(Input::get('reason')),
        );

        $rule = array(
            'username'   => 'required',
            'reason'	=> 'required'
        );
        $messes = array(
            'required'   => ':attribute Không được để trống nội dung',
            'min'       => ':attribute quá ngắn',
            'max'       => ':attribute quá dài'
        );
        $validation = Validator::make($DataInput,$rule,$messes);

        if($validation->fails()){
            return array('error'=>'true','msg'=>$validation->messages()->first(),'data' => array(''));
        } else {
        	$user_bane = User::where('username','=',$DataInput['username'])->first();
        	
    		$BaneList = new BaneList();
    		$BaneList->user_id = $user_bane->id;
    		$BaneList->staff_id = $user->id;
    		$BaneList->created_time = \Carbon\Carbon::now()->toDateTimeString();
    		$BaneList->reason = $DataInput['reason'];
    		if(Input::get('end_time') != null){
    			$time = date("Y-m-d H:i:s",Input::get('end_time'));
    			$BaneList->end_time = $time;
    		}
    		$BaneList->save();

    		$GutloActivityLog->new_log($user->id,$user_bane->id,$user_bane->id,3,27);

    		return array('error'=>'false','msg'=>'','data' => array(''));
        }

    }
}