<?php

namespace app\modules\v1\forms;


use app\models\User;
use yii\base\Model;

class LoginForm extends CommonForm
{
    public $username;
    public $password;
    public $auth_key;

    private $_user;


    public function rules()
    {
        return [
            ['username','required','message'=>'用户名不能为空'],
            ['password','required','message'=>'密码不能为空'],
            ['username', 'exist','targetClass' => '\app\models\User', 'message' => '用户不存在'],
            [['password'],'checkpwd','skipOnEmpty' => false, 'skipOnError' => false,'params'=>['wrong_pwd'=>"密码错误"]],
        ];
    }

    // 检测密码
    public function checkpwd($attribute, $params)
    {
        $user=User::findOne(['username'=>$this->username,'password'=>md5($this->password)]);

        if(!$user){
            $this->addError($attribute, $params['wrong_pwd']);
            return false;
        }
        $this->_user=$user;
        return true;
    }


    //登录
    public function login(){
        $auth_key=getRandom();

        User::updateAll([
          'auth_key'=>$auth_key
        ],[
            'username'=>$this->username
        ]);

        return $auth_key;
    }

}