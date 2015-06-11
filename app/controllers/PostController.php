<?php
use Shaphira\Common\HashtagMention;
use Shaphira\Common\Common;
use Shaphira\Common\Notification;
class PostController extends BaseController{

    protected $notification;
    protected $GutloPosts;
    public function __construct () {
        $this->notification = new Notification();
        $this->GutloPosts = new GutloPosts();
    }
     public function newPost2() {
        $Common = new Common();
        $id_media = 0;
        if(Input::get('inputLink') != '' ){
            if($Common->urlType(Input::get('inputLink')) == 'youtube') {
                $videoId =Youtube::parseVIdFromURL(Input::get('inputLink'));
                $abc = Youtube::getVideoInfo($videoId);
                echo '<pre>';
                print_r($abc);
                echo '</pre>';
                $thumbnails = $abc->snippet->thumbnails->high->url;
                $title = $abc->snippet->title;
                echo $videoId;
            }
        }
    }

    public function newPost() {
        $user = null;
        $Common = new Common();
        $GutloActivityLog = new GutloActivityLog();
        if(Auth::check()){
            $Auth = Auth::user();
            $user = User::where('id','=',$Auth->id)->where('username' , '=', $Auth->username)->get()->first();
            if(empty($user)) {
                Auth::logout();
                Redirect::to('/login');
            }
        }else Redirect::to('/login');
        $DataInput = array(
            'content'   => trim(Input::get('content')) ,
            'title'     => trim(Input::get('title')),
            'categories'=> Input::get('category'),
            'inputLink' => trim(Input::get('inputLink')),
            'image_link' => trim(Input::get('img'))
        );
        $rule = array(
            'content'   => 'required|min:3|max:500',
            'title'     => 'required|min:11|max:111',
            'categories'=> 'required'
        );
        $messes = array(
            'required' => ' :attribute không được để trống .',
            'min'     => ' :attribute quá ngắn vui lòng nhập lại',
            'max'=> ' :attribute quá dài vui lòng nhập lại'

        );
        $validation = Validator::make($DataInput,$rule,$messes);
        if($DataInput['inputLink'] != "") {
            $partern = '/(http|https)/';
            $subject = $DataInput['inputLink'];
            if (!preg_match($partern, $subject)) $DataInput['inputLink'] = 'http://'.$DataInput['inputLink'];
            if (!preg_match('_^(?:(?:https?|http)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS', $DataInput['inputLink'])) {
                return array('error'=>'true','msg'=>'Link không chính xác vui lòng nhập lại đầy đủ " http://example.com " ','data' => array(''));
            }
        }

        if($validation->fails()){
            return array('error'=>'true','msg'=>$validation->messages()->first(),'data' => array(''));
        } else {
            $id_media = 0;
           
            if(Input::get('inputLink') != '' ){
                $type_link = $Common->urlType(Input::get('inputLink'));
                switch ($type_link) {
                    case 'youtube':
                        $videoId =Youtube::parseVIdFromURL(Input::get('inputLink'));
                        $info_video = Youtube::getVideoInfo($videoId);
                        $title = $info_video->snippet->title;
                        $thumbnails = $info_video->snippet->thumbnails->high->url;
                        $array = explode('/', $thumbnails);
                        $name_img = end($array);
                        $extension_file = explode('.', $name_img);
                        $name_img_thumb = uniqid() . end($extension_file);
                        $array[count($array)-1] = '';
                        $url_img = implode('/', $array);
                        $path = 'uploads/post/thumb';
                        if(!is_dir($path)) {
                            $Oldmask = umask ( 0 );
                            mkdir($path,0777);
                            umask ( $Oldmask );
                        }
                        copy($thumbnails, 'uploads/post/thumb/'.$name_img);
                        $url_thumb = Image::resize('uploads/post/thumb/'.$name_img,'uploads/post/thumb',$name_img_thumb, 120,120,90)['targetFilePath'];
                        $Media = new Media();
                        $Media->media_name = $name_img;
                        $Media->media_url = $url_img;
                        $Media->media_url_thumb = $url_thumb;
                        $Media->media_name_thumb = $name_img_thumb;
                        $Media->media_api_id = $videoId;
                        $Media->title = $title;
                        $Media->type = Config::get('Common.media_type.video');
                        $Media->upload_time = \Carbon\Carbon::now()->toDateTimeString();
                        $Media->save();
                        $id_media = $Media->id;

                        break;
                    case 'facebook':
                        $videoId = $this->get_facebook_video_id(Input::get('inputLink'));
                        $content = json_decode(file_get_contents('http://graph.facebook.com/'. $videoId));
                        $info_video = $content->description;
                        $title = $info_video;
                        $thumbnails = $content->picture;
                        $array = explode('/', $thumbnails);
                        $name_img = $thumbnails;
                        $extension_file = 'jpg';
                        $name_img_thumb = uniqid() .'.'. $extension_file;
                        $name_img = uniqid() . $extension_file;
                        $url_img = '';
                        $path = 'uploads/post/thumb';
                        if(!is_dir($path)) {
                            $Oldmask = umask ( 0 );
                            mkdir($path,0777);
                            umask ( $Oldmask );
                        }
                        copy($thumbnails, 'uploads/post/thumb/' . '.' . $name_img);
                        $url_thumb = Image::resize('uploads/post/thumb/'.'.'.$name_img,'uploads/post/thumb',$name_img_thumb, 120,120,90)['targetFilePath'];

                        $Media = new Media();
                        $Media->media_name = $name_img_thumb;
                        $Media->media_url = Config::get('constants.site_url') . '/uploads/post/thumb/';
                        $Media->media_url_thumb = $name_img_thumb;
                        $Media->media_name_thumb = Config::get('constants.site_url') . '/uploads/post/thumb/';
                        $Media->media_api_id = $videoId;
                        $Media->title = $title;
                        $Media->type = Config::get('Common.media_type.video');
                        $Media->upload_time = \Carbon\Carbon::now()->toDateTimeString();
                        $Media->save();
                        $id_media = $Media->id;

                        break;

                    case 'other':
                        if(Input::get('img') != ''){
                            $name_img = '';
                            $url_img = '';
                            $array = explode('/', Input::get('img'));
                            $name_img = end($array);
                            $extension_file = explode('.', $name_img);
                            $name_img_thumb = uniqid() . end($extension_file);
                            $array[count($array)-1] = '';
                            $url_img = implode('/', $array);

                            $file_image = file_get_contents(Input::get('img'));
                            $path = 'uploads/post/thumb';
                            if(!is_dir($path)) {
                                $Oldmask = umask ( 0 );
                                mkdir($path,0777);
                                umask ( $Oldmask );
                            }
                            copy(Input::get('img'), 'uploads/post/thumb/'.$name_img);
                            $url_thumb = Image::resize('uploads/post/thumb/'.$name_img,'uploads/post/thumb',$name_img_thumb, 120,120,90)['targetFilePath'];

                            $Media = new Media();
                            $Media->media_name = $name_img;
                            $Media->media_url = $url_img;
                            $Media->media_url_thumb = $url_thumb;
                            $Media->media_name_thumb = $name_img_thumb;
                            $Media->title = $DataInput['title'];
                            $Media->type = Config::get('Common.media_type.image');
                            $Media->upload_time = \Carbon\Carbon::now()->toDateTimeString();
                            $Media->save();
                            $id_media = $Media->id;
                        }
                        break;
                    }
                }
            $GutloPosts = new GutloPosts();
            $GutloPosts->from_id = $user->id;
            $GutloPosts->content= $DataInput['content'];
            $GutloPosts->title = $DataInput['title'];
            $GutloPosts->media_id = $id_media;
            $GutloPosts->category_id = $DataInput['categories'];
            $GutloPosts->link = $DataInput['inputLink'];
            $GutloPosts->created_time= \Carbon\Carbon::now()->toDateTimeString();
            $GutloPosts->save();

            $HashtagMention = new HashtagMention();
            $hashtag_id = $HashtagMention->get_hashtag_id($DataInput['content'],Config::get('Common.post_type'),$GutloPosts->id,$DataInput['categories']);
            $mention_id = $HashtagMention->get_mentions_id($DataInput['content']);

            $GutloActivityLog->new_log($user->id,0,$GutloPosts->id,0,10);

            $data_update = array('hashtag_id'=>$hashtag_id,'mention_id' => $mention_id );
            DB::table('gutlo_posts')->where('id','=',$GutloPosts->id)->update($data_update);

            $GutloPoint = new GutloPoint();
            $GutloPoint = $GutloPoint->update_real_point_user($user->id,0,0,1,0);

            $array_mention = explode(',', $mention_id);
            $GutloNotifications = new GutloNotifications();

            for($i = count($array_mention) - 1 ; $i >=0 ; $i--){
                if($user->id != $array_mention[$i] && $array_mention[$i] != ''){
                    $ava_user = DB::table('gutlo_media')
                                ->select(DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) as ava'))
                                ->where('id','=',$user->avatar_id)->first();
                    $userinfomation = UserInformation::find($user->id);
                    $user_gender_msg = Config::get('Common.gender_user_msg_notifi.'.$userinfomation->gender);
                    $id_notifi = $GutloNotifications->new_notifi($user->id ,$array_mention[$i] ,' Thêm bạn vào bài viết của '.$user_gender_msg, $GutloPosts->id,$GutloPosts->content,Config::get('Common.content_type.post'),Config::get('Common.notifi_type.post'));
                    $this->notification->Notification_relationship($array_mention[$i],array(
                                                            'msg'=>' Thêm bạn vào bài viết của '.$user_gender_msg
                                                            ,'user'=>$user->username
                                                            ,'nickname'=>$user->nickname
                                                            ,'id_notifi'=>$id_notifi
                                                            ,'ava'=>url('/'.$ava_user->ava)
                                                            ,'notifi_type'=>Config::get('Common.notifi_type.post')
                                                            ,'content'=>$GutloPosts->content
                                                            ));
                }
            }
            $this->notification->Notification_relationship('post_fresh',array());
            $Categories = Categories::find($DataInput['categories']);
            $this->notification->Notification_relationship('post_fresh_'.$Categories->cate_code,array());
            $ava_user = DB::table('gutlo_media')
                        ->select(DB::raw('CONCAT(gutlo_media.media_url,gutlo_media.media_name) as ava'))
                        ->where('id','=',$user->avatar_id)->first();

            return array('error'=>'false','msg'=>'','data'=>$this->listPost());
        }
    }

    public function listPost() {
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        $offset = Input::get('count');
        $limit = 10;
        if($offset == null){
            $offset = 0 ;
        }

        $GutloPosts = $this->GutloPosts->list_post($user_id,$limit,$offset);
        $GutloPosts = $this->SelectionSortDescending($GutloPosts);
        $activity = new ActivityController();
        $GutloPosts = $activity->replateEmoticon_on_post($GutloPosts);
        return $GutloPosts;
    }

    public function listPost_Hot() {
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        $offset = Input::get('count');
        $limit = 10;
        if($offset == null){
            $offset = 0 ;
        }

        $GutloPosts = $this->GutloPosts->list_post_hot($user_id,$limit,$offset);
        $GutloPosts = $this->SelectionSortDescending($GutloPosts);
        $activity = new ActivityController();
        $GutloPosts = $activity->replateEmoticon_on_post($GutloPosts);
        return $GutloPosts;
    }

    public function load_more_post_fresh() {
        $posts = $this->listPost();
        if(empty($posts)) return Response::json(array('error'=>'false','msg'=>'','data'=>array()));
        else return Response::json(array('error'=>'false','msg'=>'','data'=>$posts));
    }

    public function load_more_post_hot() {
        $posts = $this->listPost_Hot();
        if(empty($posts)) return Response::json(array('error'=>'false','msg'=>'','data'=>array()));
        else return Response::json(array('error'=>'false','msg'=>'','data'=>$posts));
    }

    public function showPost($id) {
        // $common = new Common();
        // $id = $common->encryptor('decrypt',$id);
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
         $GutloPosts = DB::table('gutlo_posts')
                    ->select(['mp.type as type_media', 'mp.media_api_id', 'gutlo_posts.link','gutlo_posts.mention_id','users.confirmed', 'users.vip','users.blogger_level', 
                        'users.shaphira_verified','users.dayOnline', 'gutlo_posts.hashtag_id','gutlo_medals.medal_name','gutlo_medals.medal_icon_url', 'gutlo_id_vs_content.like_content', 
                        'gutlo_id_vs_content.brick_content','gutlo_posts.*','users.id as user_id','users.nickname','users.username', 'gutlo_embed.VALUE as embed_id'
                        ,DB::raw('CONCAT(mp.media_url,mp.media_name) AS image
                            ,CONCAT(mp.media_url_thumb,mp.media_name_thumb) AS image_thumb
                            ,CONCAT(gutlo_media.media_url,gutlo_media.media_name) as ava')])
                    ->join('users', 'gutlo_posts.from_id', '=', 'users.id')
                    ->join('gutlo_media','users.avatar_id','=','gutlo_media.id')
                    ->leftJoin('gutlo_media as mp','mp.id','=','gutlo_posts.media_id')
                    ->leftJoin('gutlo_embed', 'gutlo_embed.ID_EMBED', '=', 'mp.media_embed')
                    ->leftJoin('gutlo_id_vs_content', function($join) use($user_id) {
                        $join->on('gutlo_id_vs_content.content_id', '=', 'gutlo_posts.id')
                        ->where('gutlo_id_vs_content.user_id','=',$user_id)
                        ->where('gutlo_id_vs_content.content_type','=',0);
                    })
                    ->join('gutlo_point','users.id','=','gutlo_point.user_id')
                    ->leftJoin('gutlo_medals', function($join) {
                        $join->on('gutlo_point.real_point','>=','gutlo_medals.min_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.max_point')
                        ->orOn('gutlo_point.real_point','>=','gutlo_medals.max_point')
                        ->on('gutlo_point.real_point','<=','gutlo_medals.min_point');
                    })
                    ->whereRaw('NOT gutlo_posts.id IN (select b.content_id from block_content as b where b.content_id = gutlo_posts.id and b.type = 0)')
                    ->whereNull('gutlo_posts.deleted_time')
                    ->where('gutlo_posts.id','=',$id)
                    ->whereNull('gutlo_id_vs_content.report_id')
                    ->orderBy('gutlo_posts.created_time','DESC')->first();

        $activity = new ActivityController();
        if(empty($GutloPosts)) return array();
        $GutloPosts = $activity->replateEmoticon_on_post(array($GutloPosts));

        $array_update = array('reach_time'=>\Carbon\Carbon::now()->toDateTimeString());
        DB::table('gutlo_notifications')
                        ->where('to_id','=',$user_id)
                        ->where('content_id','=',$id)
                        ->whereNull('reach_time')
                        ->update($array_update);

        return $GutloPosts;
    }

    public function next_post($id) {
        $hot = array(); $id_next = 0;
        if(Cache::has('hot-Post'))  $hot = Cache::get('hot-Post');
        $count = COUNT($hot);
        for($i = $count -1; $i >= 0; $i--){
           if($hot['"'.$i.'"'] == $id) {
                try{
                    $id_next = $hot['"'.( $i+1 ).'"'];
                }catch(Exception $e) {
                    $id_next = 0 ;
                }
           }
        }
        if($id_next != 0 ) return url('/posts/'.$id_next);
        else return url('/posts/1');
    }

    public function listPost_of_users() {
        $user_id = 0;
        if(Auth::check()) $user_id = Auth::user()->id;
        // doan nay can sua lai , bi chap va
        if(Input::get('id') != null && Input::get('id') != '0') $user_id = Input::get('id');
        $GutloPosts = $this->get_data_of_users($user_id);
        return $GutloPosts;
    }

    public function get_data_of_users($user_id){
        $offset = Input::get('count');
        $limit = 10;
        if($offset == null){
            $offset = 0 ;
        }

        $GutloPosts = $this->GutloPosts->post_of_users($user_id,$limit,$offset);
        $GutloPosts = $this->SelectionSortDescending($GutloPosts);
        $activity = new ActivityController();
        $GutloPosts = $activity->replateEmoticon_on_post($GutloPosts);
        return $GutloPosts;
    }

    public function load_more_post_of_users() {
        $posts = $this->listPost_of_users();
        if(empty($posts)) return array('error'=>'false','msg'=>'','data'=>array());
        else return array('error'=>'false','msg'=>'','data'=>$posts);
    }

    public function listPost_related_users() {
        $user_id = 0;
        if(Input::get('id') != null && Input::get('id') != '0') $user_id = Input::get('id');
        else if(Auth::check()) $user_id = Auth::user()->id;
        $offset = Input::get('count');
        $limit = 10;
        if($offset == null){
            $offset = 0 ;
        }

        $GutloPosts = $this->get_data_related_users($user_id);
        return $GutloPosts;
    }

    public function get_data_related_users($user_id){
        $offset = Input::get('count');
        $limit = 10;
        if($offset == null){
            $offset = 0 ;
        }

        $GutloPosts = $this->GutloPosts->post_related_users($user_id,$limit,$offset);
        $GutloPosts = $this->SelectionSortDescending($GutloPosts);
        $activity = new ActivityController();
        $GutloPosts = $activity->replateEmoticon_on_post($GutloPosts);
        return $GutloPosts;
    }

    public function load_more_post_related_users() {

        $posts = $this->listPost_related_users();
        if(empty($posts)) return array('error'=>'false','msg'=>'','data'=>array());
        else return array('error'=>'false','msg'=>'','data'=>$posts);
    }

    public function rate_post() {
        if(Input::get('point') == null ) return array('error'=>'true','msg'=>'Không thể xác định point rate vui lòng thử lại','data'=>array());
        if(Input::get('id') == null ) return array('error'=>'true','msg'=>'Không thể xác định bài post vui lòng thử lại','data'=>array());
        $point = Input::get('point');
        $id = Input::get('id');
        $data_rate = RateContent::where('staff_id','=',Auth::user()->id)
                                ->where('content_id','=',$id)
                                ->where('type_content','=',Config::get('Common.post_type'))
                                ->first();
        if(empty($data_rate)){
            $RateContent = new RateContent();
            $RateContent->staff_id = Auth::user()->id;
            $RateContent->content_id = $id;
            $RateContent->type_content = Config::get('Common.post_type');
            $RateContent->point = $point;
            $RateContent->created_time = \Carbon\Carbon::now()->toDateTimeString();
            $RateContent->save();
        }else {
            $RateContent = RateContent::find($data_rate->id);
            $RateContent->point = $point;
            $RateContent->updated_time = \Carbon\Carbon::now()->toDateTimeString();
            $RateContent->save();
        }

        $data_rate = DB::table('rate_content')->select(DB::raw('avg(rate_content.point) AS rate_point,count(*) as count_rate'))->where('content_id','=',$id)
                                ->where('type_content','=',Config::get('Common.post_type'))
                                ->first();
        return array('error'=>'false','msg'=>'','data'=>$data_rate);

    }

    public function get_data_link () {
        $url = '';
        if(Input::get('link') != null) $url = Input::get('link');
        else return array('error'=>'true','msg'=>'');
        $Common = new Common();
        $html = $Common->file_get_contents_curl($url);
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $metas = $doc->getElementsByTagName('meta');
        $title = '';
        $description = '';
        $url = '';
        $site_name = '';
        $type = '';
        $image = '';
        $image_width = '';
        $image_height = '';

        for ($i = 0; $i < $metas->length; $i++)
        {
            $meta = $metas->item($i);
            if($meta->getAttribute('property') == 'og:title')
                $title = $meta->getAttribute('content');
            if($meta->getAttribute('property') == 'og:description')
                $description = $meta->getAttribute('content');
            if($meta->getAttribute('property') == 'og:url')
                $url = $meta->getAttribute('content');
            if($meta->getAttribute('property') == 'og:site_name')
                $site_name = $meta->getAttribute('content');
            if($meta->getAttribute('property') == 'oog:type')
                $type = $meta->getAttribute('content');
            if($meta->getAttribute('property') == 'og:image')
                $image = $meta->getAttribute('content');
            if($meta->getAttribute('property') == 'og:image:width')
                $image_width = $meta->getAttribute('content');
            if($meta->getAttribute('property') == 'og:image:height')
                $image_height = $meta->getAttribute('content');
        }
        $array = array(
            'title'  => $title,
            'description'  => $description,
            'url'  => $url,
            'site_name'  => $site_name,
            'type'  => $type,
            'image'  => $image,
            'image_width'  => $image_width,
            'image_height'  => $image_height
        );
        return array('error'=>'false','msg'=>'','data'=>$array);
    }

    public function SelectionSortDescending($array1)
    {
        // dem tong so phan tu cua mang
        $length = count($array1);
        // for de sap xep mang
        for ($i = 0; $i < $length - 1; $i++)
        {
            // tim vi tri lon nhat theo tung for
            $max = $i;
            for ($j = $i + 1; $j < $length; $j++){
                $date =  strtotime($array1[$j]->created_time);
                $date_2 =  strtotime($array1[$max]->created_time);
                if ($date > $date_2){
                    $max = $j;
                }
            }
            // sau khi tim dc max thi hoan vi voi i
            // voi vi tri thu $i
            $temp = $array1[$i];
            $array1[$i] = $array1[$max];
            $array1[$max] = $temp;
        }
        return $array1;
    }


    public function getEmoticon () {
        $emoticon = Emoticon::All();
        return $emoticon;
    }

    public function replateEmoticon_on_array ($array) {
        $emoticon = $this->getEmoticon();
        $length_array = count($array);
        $length_emoticon = count($emoticon);

        for($i = 0; $i < $length_array; $i++) {
            for($j = 0; $j < $length_emoticon; $j++) {
                $array[$i]->content = str_replace($emoticon[$j]->char,"<img title=".$emoticon[$j]->char." src='".url('/'.$emoticon[$j]->url.$emoticon[$j]->emo_group.'/'.$emoticon[$j]->emoticon)."'></img>",$array[$i]->content);
            }
        }
        return $array;
    }

    public function SelectionSortByDateTime_ASC($array1)
    {
        // dem tong so phan tu cua mang
        $length = count($array1);
        // for de sap xep mang
        for ($i = 0; $i < $length - 1; $i++)
        {
            // tim vi tri nho nhat theo tung for
            $min = $i;
            for ($j = $i + 1; $j < $length; $j++){
                $date =  strtotime($array1[$j]->created_time);
                $date_2 =  strtotime($array1[$min]->created_time);
                if ($date < $date_2){
                    $min = $j;
                }
            }
            // sau khi tim dc min thi hoan vi voi i
            // voi vi tri thu $i
            $temp = $array1[$i];
            $array1[$i] = $array1[$min];
            $array1[$min] = $temp;
        }
        return $array1;
    }

    public function get_facebook_video_id($input_link){
        $array = explode('/', $input_link);
        foreach ($array as $key => $value) {
            if (preg_match('/^videos/', $value)) {
                    $temp = $array[$key+1];
                    $check  = preg_match('/^vb./', $temp);
                    if ($check == true) {
                        $result = $array[$key+2];
                    } else {
                        $result = $temp;
                    }
            } elseif(preg_match('/^video.php/', $value)){
                $temp = explode('&', $value);
                var_dump($temp);
                exit;
            }
        }
        return $result;
    }
}