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
use app\modules\v1\forms\user\AddUserForm;
use app\modules\v1\forms\user\DeleteUserForm;
use app\modules\v1\forms\user\LoginForm;
use app\modules\v1\forms\user\UpdateUserForm;
use yii\data\Pagination;


class UserController extends BaseController
{
    public function verbs()
    {
        return [
            'index'=>['POST'],
            'change-password'=>['POST'],
            'get-menu'=>['GET'],
            'read'=>['GET'],
            'logout'=>['GET'],
            'update'=>['post'],
            'add'=>['post'],
            'delete'=>['get'],
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']=[
            'class'=>QueryParamAuth::className(),
            'optional' => [
                'login',
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
                $tpl= __DIR__ . '/../forms/LoginForm.php';
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
        return ['accessToken'=>$auth_key];
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

    public function actionDelete()
    {

        $form = new DeleteUserForm();
        if($form->load(\Yii::$app->getRequest()->get(),'') && !$form->validate())
        {
            ApiException($form->getError(),'900000');
        }

        $form->delete();
        return "";
    }

    public function actionUpdate()
    {
        $form = new UpdateUserForm();
        if($form->load(\Yii::$app->getRequest()->post(),'') && !$form->validate())
        {
            ApiException($form->getError(),'900000');
        }

        $form->save();
        return "";
    }


    //获得用户信息
    public function actionGetUserinfo(){
        $uid=\Yii::$app->getUser()->getId();

        $user=User::findOne($uid);
        return $user;
    }

    //退出登录
    public function actionlogout()
    {
        return "";
    }

    //列表
    public function actionRead()
    {
        $page=\Yii::$app->getRequest()->get('page');
        $username=\Yii::$app->getRequest()->get('username');

        $query=User::find()->asArray();

        if(!empty($username)){
            $query->andWhere(['like', 'username', $username]);
        }

        $count=$query->count();

        $p = new Pagination(['totalCount' => $count]);

        $query->offset($p->getOffset());
        $query->limit($p->getLimit());

        $data = $query->all();

        return ['list'=>$data,'current_page'=>$page,'page_size'=>$p->getPageSize(),'total_count'=>$count];
    }
}