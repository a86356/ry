<?php

namespace app\modules\v1\forms\user;


use app\models\Auth;
use app\models\User;
use app\models\UserGroup;
use yii\base\Model;
use app\modules\v1\forms\CommonForm;

class DeleteUserForm extends CommonForm
{
    public $user_id;



    public function rules()
    {
        return [
            ['user_id','required','message'=>'id不能为空'],
            ['user_id', 'exist','targetClass' => '\app\models\User', 'message' => '用户id不存在'],

        ];
    }


    public function delete(){
        var_dump($this->user_id);
        User::deleteAll(['user_id'=>$this->user_id]);

    }

}