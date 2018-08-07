<?php

namespace app\modules\v1\forms\userAuth;


use app\models\Auth;
use app\models\User;
use app\models\UserGroup;
use yii\base\Model;
use app\modules\v1\forms\CommonForm;

class UserAuthDeleteForm extends CommonForm
{
    public $auth_id;



    public function rules()
    {
        return [
            ['auth_id','required','message'=>'id不能为空'],
            ['auth_id', 'exist','targetClass' => '\app\models\Auth', 'message' => '权限id不存在'],

        ];
    }


    public function delete(){

        Auth::deleteAll(['auth_id'=>$this->auth_id]);

    }

}