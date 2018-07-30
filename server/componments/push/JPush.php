<?php

namespace app\componments\push;

use JPush\Client;
use yii\base\Component;

class JPush extends Component {
    
    private $appid;
    private $secret;

    /**
     * @var Client $client
     */
    private $client;

    public function init()
    {
        $this->client = new Client($this->appid,$this->secret,null);
    }

    public function pushByAlias($title,$content,$platform,$alias,$extras=null){

        $push = $this->client->push();
        $push->options(['apns_production'=>true,'time_to_live'=>7200]);
        $push->setPlatform($platform);
        $push->addAlias($alias);
        if($platform == "all"){
            $push->androidNotification($content,['title'=>$title,'extras'=>$extras]);
            $push->iosNotification($content,['content-available'=>true,'extras'=>$extras]);
        }else if($platform == "android"){
            $push->androidNotification($content,['title'=>$title,'extras'=>$extras]);
        }else{
            $push->iosNotification($content,['content-available'=>true,'extras'=>$extras]);
        }
        return $push->send();
    }

    public function setAppid($appid)
    {
        $this->appid = $appid;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;
    }


}