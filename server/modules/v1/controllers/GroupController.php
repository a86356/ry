<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/7/24
 * Time: 15:31
 */

namespace app\modules\v1\controllers;


use app\componments\auth\QueryParamAuth;
use app\models\Auth;
use app\models\AuthGroup;
use app\models\Group;
use app\models\Menu;
use app\models\User;
use app\modules\v1\common\BaseController;
use app\modules\v1\forms\AddGroupForm;

class GroupController extends BaseController
{
    public function verbs()
    {
        return [
            'index'=>['POST'],
            'change-password'=>['POST'],
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']=[
            'class'=>QueryParamAuth::className(),

        ];
        return $behaviors;
    }

    //添加管理组
    public function actionAdd()
    {

        $form = new AddGroupForm();
        if($form->load(\Yii::$app->getRequest()->post(),'') && !$form->validate())
        {
            ApiException($form->getError(),'900000');
        }

        $form->save();
        return "";
    }

    //获得用户组菜单
    public function actionGetMenu()
    {
        $z=Menu::getMenu(\Yii::$app->getUser()->getId());
       return $z;
    }

    public function actionAuth()
    {

        var_dump( 222);
    }


}