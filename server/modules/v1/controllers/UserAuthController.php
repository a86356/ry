<?php

namespace app\modules\v1\controllers;


use app\models\Auth;
use app\models\Group;
use app\modules\v1\common\BaseController;
use vendor\pagination\Pagination;
class UserauthController extends BaseController
{
    public function verbs()
    {
        return [
            'get-auth-list'=>['get'],
            'add-or-update'=>['post'],
        ];
    }

    //获得权限菜单列表
     public function actionGetAuthList()
     {
            $page=\Yii::$app->getRequest()->get('page');
            $name=\Yii::$app->getRequest()->get('name');
            $obj=Group::findOne(['group_id'=>1]);
            $query= $obj->getAuths()->asArray();

            if(!empty($name)){
                $query->andWhere(['like', 'auth_name', '权限']);
            }
             $count=$query->count();

             $p = new Pagination(['totalCount' => $count]);

             $query->offset($p->getOffset());
             $query->limit($p->getLimit());

             $data = $query->all();

            return ['list'=>$data,'current_page'=>$page,'page_size'=>$p->getPageSize(),'total_count'=>$count];
     }


     //添加或更新权限
    public function actionAddOrUpdate()
    {

        $auth=Auth::findOne(['auth_id',5555]);
        var_dump($auth->isNewRecord);

        $auth=new Auth();
            
      /* $form = new LoginForm();
       if($form->load(\Yii::$app->getRequest()->post(),'') && !$form->validate())
       {
            ApiException($form->getError(),'900000');
       }*/

    }

}
