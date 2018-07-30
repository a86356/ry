<?php

namespace app\modules\v1\forms;


use app\models\User;
use yii\base\Model;

class ChangePasswordForm extends CommonForm
{
    public $username;
    public $password;

    private $_user;


    public function rules()
    {
        return [
            ['username','required','message'=>'用户名不能为空'],
            ['password','required','message'=>'密码不能为空'],
            ['username', 'exist','targetClass' => '\app\models\User', 'message' => '用户不存在'],
        ];
    }

    // 改密码
    public function changePassword()
    {
        User::updateAll([
            'password'=>md5($this->password)
        ],[
            'username'=>$this->username
        ]);

    }

}