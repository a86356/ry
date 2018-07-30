<?php

namespace app\modules\v1\events\business;

use yii\base\Event;

class AfterCreateBusinessEvent extends Event {
    public $bus_id;
}