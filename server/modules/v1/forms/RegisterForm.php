<?php

namespace app\modules\v1\forms;

use app\models\Business;
use app\models\Sale;
use yii\base\Model;

class RegisterForm extends Model
{
    public $bus_no;     //商户号
    public $bus_name;   //商户名称
    public $cat_id;     //分类ID
    public $service_mobile; //客服电话
    public $email;      //邮箱
    public $fax;        //传真
    public $invite_id;    //邀请码
    public $status;     //商户状态:1准备阶段,2上线运营,3暂停运营

    private $_business;

    /**
     * BusinessForm constructor.
     * @param array $id 商户ID
     */
    public function __construct()
    {
        parent::__construct();

        $this->_business = new Business();
    }

    public function rules()
    {
        return [
            [['bus_name'],'filter','filter'=>'trim'],
            ['bus_name','required','message'=>'商户名称不能为空'],
            ['bus_name','unique','targetClass'=>'app\models\Business','when'=>function($model)
            {
                //在添加的时候验证
                return $model->_business->getIsNewRecord();
            },'message'=>"该商户名称已存在"],
            ['email','email','skipOnEmpty'=>false,'message'=>'邮箱错误'],
            [['invite_id'],'safe']
        ];
    }

    //保存商户信息
    public function save()
    {
        return $this->validate() && $this->insert();
    }

    //添加商户
    protected function insert()
    {
        $this->_business->bus_no = makeBusinessNumber();
        $this->_business->sign_key = md5(\Yii::$app->getSecurity()->generateRandomString());
        $this->_business->bus_name = $this->bus_name;
        $this->_business->bus_level = 3;
        $this->_business->service_mobile = \Yii::$app->getRequest()->post('username');
        $this->_business->email = $this->email ? $this->email : '';
        $this->_business->from_to = 2;
        $saleInfo = Sale::findOne(['username'=>$this->invite_id]);
        if($saleInfo){
            $this->_business->franchis_id = $saleInfo->franchis_id;
            $this->_business->sale_id = $saleInfo->sale_id;
        }
        $this->_business->add_time = time();
        $this->_business->status= 1;

        return $this->_business->save();
    }

    //返回刚插入的主键ID
    public function getBusId()
    {
        return $this->_business->bus_id;
    }
}