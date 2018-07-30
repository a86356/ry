<?php

namespace app\modules\v1\behaviors\token;

use app\modules\v1\utils\Constants;
use yii\base\Behavior;
use yii\httpclient\Client;
use yii\web\UserEvent;

class SyncTokenBehavior extends Behavior {

    public function events()
    {
        return [
            Constants::BEFORE_USER_LOGIN =>'beforeUserLogin',
        ];
    }

    public function beforeUserLogin(UserEvent $event){
        //同步token
        $post_data=[
            'mch_no'=>$event->identity->business->bus_no,
            'access_token'=>$event->identity->getAuthKey()
        ];

        $client = new Client();
        $client->post("https://appapi.zjhylh.com/api/index/syncAccessToken",$post_data)->send();
//        $client->post("https://api.ckcomking.com/ApiBian/Index/syncAccessToken",$post_data)->send();
    }
}