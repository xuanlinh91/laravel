<?php
use Shaphira\Common\Common;
use Shaphira\Common\HashtagMention;
use Shaphira\Common\Notification;
class EmoticonController extends BaseController{

    public function __construct () {

    }
    public function getAllEmoticon() {
    	$data = DB::table('emoticon')->select('*')->get();
    	return $data;
    }
    public function addNewEmoticon() {
        $DataInput = array(
            '_char'   => trim(Input::get('_char')) ,
            'emoticon'     => trim(Input::get('emoticon')),
            'group' => Input::get('group')
        );
        $rule = array(
            '_char'   => 'required',
            'emoticon'     => 'required',
            'group'=> 'required'
        );
        $messes = array(
            'required' => ' :attribute không được để trống .',
            'min'     => ' :attribute quá ngắn vui lòng nhập lại',
            'max'=> ' :attribute quá dài vui lòng nhập lại'

        );
        $validation = Validator::make($DataInput,$rule,$messes);

        if($validation->fails()){
            return array('error'=>'true','msg'=>$validation->messages()->first(),'data' => array(''));
        } else {
        	$data = DB::table('emoticon')->select('*')->where('char','=',$DataInput['_char'])->orWhere('emoticon','=',$DataInput['emoticon'])
        	->orWhere('url','=','assets/image/emoticons/'.$DataInput['group'])->get();
            $id = 0;

        		if(Input::get('id') != ''){
        			$Emoticon = Emoticon::find(Input::get('id'));
        			$Emoticon->char= $DataInput['_char'];
		            $Emoticon->emoticon = $DataInput['emoticon'];
		            $Emoticon->emo_group = $DataInput['group'];
		            $Emoticon->url = 'assets/image/emoticons/';
		            $Emoticon->save();
                    $id = Input::get('id');
        		}else {
        			if(empty($data)) {
			            $Emoticon = new Emoticon();
			            $Emoticon->char= $DataInput['_char'];
			            $Emoticon->emoticon = $DataInput['emoticon'];
			            $Emoticon->emo_group = $DataInput['group'];
			            $Emoticon->url = 'assets/image/emoticons/';
			            $Emoticon->save();
                        $id = $Emoticon->id;
			        }else {
		           		return array('error'=>'true','msg'=>'emoticon đã tồn tại vui long kiểm tra lại');
		            }
		        }

            if (Cache::has('emoticons')) {
                Cache::forget('emoticons');
            }

            $data_emoticons = DB::table('emoticon')->select('*')->get();
            $arrayDefault_id = array('4','33','6','7','8','9','10','11','12','13','14','55','58','62','64','74','75','76','78','79','82','83','86','88','90','91','99','104','110','113','114','116','118','119','120','121','122','123','124','127','128','129','133','134','135','137','138','139','142','143','145','148','149','151','152','153','154','155','157','158','159','160','161','162','163','164','165','166','201','203','204','206','209','210','211','214','218','219.220','221','225','229','231','238','246','248','250','261','271','275');
            $arraydefault = array();
            $group = array();
            for ($i= COUNT($data_emoticons) - 1; $i >= 0; $i--) { 
                for ($j= COUNT($arrayDefault_id) -1; $j >=0 ; $j--) { 
                    if($arrayDefault_id[$j] == $data_emoticons[$i]->id){
                        array_push($arraydefault, array(
                                            'char'=>$data_emoticons[$i]->char
                                            ,'emoticon'=>$data_emoticons[$i]->emoticon
                                            ,'emo_group'=>$data_emoticons[$i]->emo_group
                                            ,'url'=>$data_emoticons[$i]->url
                                            ,'id'=>$data_emoticons[$i]->id
                                            ));
                    }
                }
                if(!in_array($data_emoticons[$i]->emo_group, $group) ) {
                    array_push($group,$data_emoticons[$i]->emo_group);
                }
            }
            $emoticons  = array('default'=>$arraydefault,
                                    // 'emoticons' => $data_emoticons,
                                    'group'=>$group
                                );
            Cache::forever('emoticons', $emoticons);
            return array('error'=>'false','msg'=>'','data'=>array('char'=>$DataInput['_char'],'emoticon'=>$DataInput['emoticon'],'emo_group'=>$DataInput['group'],'url'=>'assets/image/emoticons/','id'=>$id ));
        }
    }

    public function loadEmoticon () {
        $group = 'All';
        if(Input::get('group') != null ){
            $group = Input::get('group');
        }
        $data = array();
        if($group != 'All'){
            $data = DB::table('emoticon')->select('*')->where('emo_group','=',$group)->get();
        }else {
            $data = $this->getAllEmoticon();
        }
        return array('error'=>'false','msg'=>'','data'=>$data);
    }
}