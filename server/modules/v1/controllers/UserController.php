<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/7/24
 * Time: 13:50
 */

namespace app\modules\v1\controllers;


use app\componments\auth\QueryParamAuth;
use app\models\User;
use app\modules\v1\common\BaseController;
use app\modules\v1\forms\AddUserForm;
use app\modules\v1\forms\ChangePasswordForm;
use app\modules\v1\forms\LoginForm;

class UserController extends BaseController
{
    public function verbs()
    {
        return [
            'index'=>['POST'],
            'change-password'=>['POST'],
            'get-menu'=>['GET'],
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']=[
            'class'=>QueryParamAuth::className(),
            'optional' => [
                'login',
                'test',
                'index',
                'form'
            ],
        ];
        return $behaviors;
    }

    //创建表单
    public function actionForm()
    {

        if(YII_DEBUG){
            $form=\Yii::$app->getRequest()->post();
            $name=ucfirst($form['name']);

            $dest=__DIR__.'/../forms/'.$name.'Form.php';
            if(!file_exists($dest)){
                $tpl=__DIR__.'/../forms/LoginForm.php';
                $content=file_get_contents($tpl);
                $content= str_replace('LoginForm',$name.'Form',$content);
                file_put_contents($dest,$content);
                chmod($dest , 0777);   //0644 要修改成的权限值
            }
        }


    }

    //登录
    public function actionLogin()
    {

        $form = new LoginForm();
        if($form->load(\Yii::$app->getRequest()->post(),'') && !$form->validate())
        {
            ApiException($form->getError(),'900000');
        }
        $auth_key=$form->login();
        return ['access-token'=>$auth_key];
    }

    //修改密码
    public function actionChangePassword()
    {
        $form = new ChangePasswordForm();
        if($form->load(\Yii::$app->getRequest()->post(),'') && !$form->validate())
        {
            ApiException($form->getError(),'900000');
        }
        $form->changePassword();
        return "";
    }

    //添加管理员
    public function actionAdd()
    {

        $form = new AddUserForm();
        if($form->load(\Yii::$app->getRequest()->post(),'') && !$form->validate())
        {
            ApiException($form->getError(),'900000');
        }

        $form->save();
        return "";
    }


}