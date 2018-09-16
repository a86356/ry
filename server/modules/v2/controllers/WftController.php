<?php

namespace app\modules\v1\controllers;

use app\models\BusinessPayConfig;
use app\modules\v1\common\BaseController;

class WftController extends BaseController
{
    //是否开通中信渠道
    public function actionIsOpen()
    {
        $is_open = BusinessPayConfig::find()->select(['wft'])->where(['bus_id'=>\Yii::$app->getUser()->getId()])->scalar() ;
        return $is_open == 1 ? ['result'=>'F'] : ['result'=>'T'];
    }
}