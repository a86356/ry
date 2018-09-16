<?php

namespace app\modules\v1\forms\user;


use app\models\User;
use app\models\UserGroup;
use yii\base\Model;
use app\modules\v1\forms\CommonForm;

class UpdateUserForm extends CommonForm
{
    public $username;
    public $password;
    public $nickname;
    public $phone;
    public $group_id;
    public $status;

    private $_user;


    public function rules()
    {
        return [
            ['username','required','message'=>'用户名不能为空'],
            ['nickname','required','message'=>'昵称不能为空'],
            ['phone','required','message'=>'手机号不能为空'],
            ['group_id','required','message'=>'管理组不能为空'],
            ['status','required','message'=>'状态不能为空'],
            ['username', 'exist','targetClass' => '\app\models\User', 'message' =>'用户名不存在'],
            ['group_id', 'exist','targetClass' => '\app\models\Group', 'message' => '管理组不存在'],
        ];
    }



    public function save(){

        $obj=User::findOne(['username'=>$this->username]);

        $obj->password=md5($this->password.\Yii::$app->params['salt']);
        $obj->nickname=$this->nickname;
        $obj->phone=$this->phone;
        $obj->status=$this->status;

        $obj->save(false);

    }

}