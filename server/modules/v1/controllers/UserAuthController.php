<?php

namespace app\modules\v1\controllers;


use app\models\Auth;
use app\models\Group;
use app\models\User;
use app\modules\v1\common\BaseController;
use app\modules\v1\forms\userAuth\MenuAddForm;
use app\modules\v1\forms\userAuth\MenuDeleteForm;
use app\modules\v1\forms\userAuth\MenuUpdateForm;
use vendor\pagination\Pagination;
class UserauthController extends BaseController
{
    public function verbs()
    {
        return [
            'get-auth-list'=>['get','options'],
            'update'=>['post'],
            'add'=>['post'],
            'read'=>['get'],
            'delete'=>['get'],
            'test'=>['get'],
        ];
    }

    //获得权限菜单列表
     public function actionGetAuthList()
     {
            $page=\Yii::$app->getRequest()->get('page');
            $name=\Yii::$app->getRequest()->get('name');
            $obj=Group::findOne(['group_id'=>Group::getGroupId()]);
            $query= $obj->getAuths()->asArray();

            if(!empty($name)){
                $query->andWhere(['like', 'auth_name', $name]);
            }
             $count=$query->count();

             $p = new Pagination(['totalCount' => $count]);

             $query->offset($p->getOffset());
             $query->limit($p->getLimit());

             $data = $query->all();

            return ['list'=>$data,'current_page'=>$page,'page_size'=>$p->getPageSize(),'total_count'=>$count];
     }


    public function actionUpdate()
    {
        $form = new MenuUpdateForm();
        if($form->load(\Yii::$app->getRequest()->post(),'') && !$form->validate())
        {
            ApiException($form->getError(),'900000');
        }

        $form->save();
        return "";
    }

    public function actionAdd()
    {
        $form = new MenuAddForm();
        if($form->load(\Yii::$app->getRequest()->post(),'') && !$form->validate())
        {
            ApiException($form->getError(),'900000');
        }

        $form->save();
        return "";
    }

    public function actionDelete()
    {

        $form = new MenuDeleteForm();
        if($form->load(\Yii::$app->getRequest()->get(),'') && !$form->validate())
        {
            ApiException($form->getError(),'900000');
        }

        $form->delete();
        return "";
    }

    public function actionTest()
    {
        $query = User::find()->where(['user_id'=>1]);

        // 输出SQL语句
        $commandQuery = clone $query;
        echo $commandQuery->createCommand()->getRawSql();
        return ;
    }
}
