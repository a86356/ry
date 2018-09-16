<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/8/7
 * Time: 16:20
 */

namespace app\modules\v1\controllers;


use app\modules\v1\common\BaseController;

class MenuController extends BaseController
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