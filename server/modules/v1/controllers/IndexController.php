<?php

namespace app\modules\v1\controllers;

use app\componments\auth\QueryParamAuth;
use app\models\Menu;
use app\models\User;
use app\modules\v1\common\BaseController;
use app\modules\v1\forms\LoginForm;
use app\utils\ResponseMap;
use yii\web\Controller;

class IndexController extends BaseController
{
    public function verbs()
    {
        return [
         //  'index'=>['POST']
        ];
    }



    //
    public function actionIndex()
    {
        $z=Menu::getMenu();
        p($z);
    }


}