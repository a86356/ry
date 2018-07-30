<?php

namespace app\componments\sts;

use app\modules\v1\common\BaseController;
use DefaultAcsClient;
use DefaultProfile;
use Sts\Request\V20150401\AssumeRoleRequest;
use yii\base\UserException;

class StsController extends BaseController
{
    //授权上传
    public function actionAuthUpload()
    {
        $content = \Yii::$app->getModule("v1")->params['oss'];


    }
}