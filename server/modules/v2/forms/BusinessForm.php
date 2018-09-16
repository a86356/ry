<?php

namespace app\modules\v1\forms;

use app\models\Business;
use app\modules\v1\utils\ResponseMap;
use yii\base\Model;
use yii\base\UserException;

class BusinessForm extends Model
{
    public $service_mobile;
    public $email;

    private $_business;

    public function __construct($id)
    {
        parent::__construct();

        $this->_business = Business::findOne($id);
        if(!$this->_business)
        {
            throw new UserException(ResponseMap::Map("430001"),"430001");
        }
    }

    public function rules()
    {
        return [
            ['email','email','message'=>'邮箱格式不正确'],
            [['service_mobile'],'safe']
        ];
    }

    public function save()
    {
        return $this->validate() && $this->update();
    }

    public function update()
    {
        $this->_business->service_mobile = !empty($this->service_mobile) ? $this->service_mobile : $this->_business->service_mobile;
        $this->_business->email = !empty($this->email) ? $this->email : $this->_business->email;
        return $this->_business->save();
    }
}