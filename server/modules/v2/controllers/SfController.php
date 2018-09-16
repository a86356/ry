<?php

namespace app\modules\v1\controllers;

use app\models\BusinessPayConfig;
use app\modules\v1\common\BaseController;

class SfController extends BaseController
{
    //是否开通申孚快捷支付
    public function actionIsOpen()
    {
        $is_open =BusinessPayConfig::find()->select(['sf'])->where(['bus_id'=>\Yii::$app->getUser()->getId()])->scalar() ;
        return $is_open == 1 ? ['result'=>'F'] : ['result'=>'T'];
    }
}