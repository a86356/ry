<?php

namespace app\modules\v1\controllers;


use app\models\Auth;
use app\modules\v1\common\BaseController;

class UserauthController extends BaseController
{
    public function verbs()
    {
        return [
            'get-auth-list'=>['GET'],
        ];
    }

    //获得权限菜单列表
     public function actionGetAuthList()
     {
            $page=\Yii::$app->getRequest()->get('page');
            $result=Auth::getAuthList();
            return $result;
     }
}