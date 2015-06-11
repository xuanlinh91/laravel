<?php
use Shaphira\Common\Common;
use Shaphira\Common\HashtagMention;
use Shaphira\Common\Notification;
class GiftController extends BaseController{

    public function __construct () {

    }
    public function onlineGift() {
        $Notification = new Notification();
        $Notification->Notification_gift('4' ,array(
            'msg'=>'bonus'
        ));
    }
}