<?php

namespace app\modules\v1\forms;

use app\models\BusinessAccount;
use app\modules\v1\utils\ResponseMap;
use yii\base\Model;
use yii\base\UserException;

class UpdatePasswordForm extends Model
{
    public $password;

    private $_user;

    public function getUser()
    {
        return $this->_user;
    }

    public function __construct($id)
    {
        parent::__construct();

        $this->_user = BusinessAccount::findOne($id);
        if(!$this->_user)
        {
            throw new UserException(ResponseMap::Map("430001"),"430001");
        }
    }

    public function rules()
    {
        return [
            ['password','filter','filter'=>'trim'],
            ['password','string','min'=>6,'skipOnEmpty'=>false,'tooShort'=>'密码长度必须大于6位'],
        ];
    }

    public function save()
    {
        return $this->validate() && $this->update();
    }

    public function update()
    {
        $this->_user->password = md5($this->password);
        $this->_user->auth_key = \Yii::$app->getSecurity()->generateRandomString();
        return $this->_user->save();
    }

}