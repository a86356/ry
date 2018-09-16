<?php

namespace app\modules\v1\behaviors\business;

use app\modules\v1\componments\mq\RabbitMQ;
use app\modules\v1\events\business\AfterCreateBusinessEvent;
use yii\base\Behavior;
use yii\helpers\Json;

class BusinessBehavior extends Behavior {

    const AFTER_CREATE_BUSINESS = "afterCrateBusiness";

    public function events()
    {
        return [
            self::AFTER_CREATE_BUSINESS=>[$this,'afterCrateBusiness']
        ];
    }

    public function afterCrateBusiness(AfterCreateBusinessEvent $event){
        $send_msg = [
            'bus_id'=>$event->bus_id,
        ];
        \Yii::$app->rabbitmq->publishMessage(Json::encode($send_msg),"create","business",false,true,false,RabbitMQ::EXCHANGE_TYPE_DIRECT);

    }
}