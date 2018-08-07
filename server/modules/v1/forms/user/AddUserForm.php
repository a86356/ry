<?php

namespace app\modules\v1\forms\user;


use app\models\User;
use app\models\UserGroup;
use yii\base\Model;
use app\modules\v1\forms\CommonForm;

class AddUserForm extends CommonForm
{
    public $username;
    public $password;
    public $nickname;
    public $phone;
    public $group_id;

    private $_user;


    public function rules()
    {
        return [
            ['username','required','message'=>'用户名不能为空'],
            ['password','required','message'=>'密码不能为空'],
            ['group_id','required','message'=>'管理组不能为空'],
            ['nickname','required','message'=>'昵称不能为空'],
            ['phone','required','message'=>'手机号不能为空'],
            ['username', 'unique','targetClass' => '\app\models\User', 'message' =>'用户名已存在'],
            ['group_id', 'exist','targetClass' => '\app\models\Group', 'message' => '管理组不存在'],
            ['password','string','length'=>[6,15],'tooShort'=>'密码不能少于6个字符','tooLong'=>'密码不能多于15个字符'],
        ];
    }


    //登录
    public function save(){

        $transaction = \Yii::$app->getDb()->beginTransaction();

        $user=new User();

        //用户表
        $user->username=$this->username;
        $user->password=md5($this->password.\Yii::$app->params['salt']);
        $user->group_id=$this->group_id;
        $user->nickname=$this->nickname;
        $user->phone=$this->phone;
        $user->auth_key=getRandom();
        if(!$user->insert()){
            $transaction->rollBack();
            ApiException("添加错误","900000");
        }



        //用户和用户组关联
        $model=User::findOne(['username'=>$this->username]);
        $user_id=$model->user_id;

        $group=new UserGroup();
        $group->user_id=$user_id;
        $group->group_id=$this->group_id;

        if(!$group->insert()){
            $transaction->rollBack();
            ApiException("添加错误","900000");
        }

        $transaction->commit();

    }

}