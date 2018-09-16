<?php

namespace app\modules\v1\forms;

use app\models\BusinessPayConfig;
use yii\base\Model;

class BusinessPayConfigForm extends Model
{
    public $payments;   //支付方式：1扫码支付,2刷卡支付,3公众号支付,4APP支付
    public $alipay;     //支付宝
    public $wechat;     //微信
    public $msyh;       //民生银行
    public $wft;        //威付通
    public $jd;         //京东

    /**
     * @var BusinessPayConfig $_business_pay_config;
     */
    private $_business_pay_config;

    public function __construct($bus_id)
    {
        parent::__construct([]);

        $this->_business_pay_config = new BusinessPayConfig();
        $this->_business_pay_config->bus_id = $bus_id;
    }

    public function save()
    {
        return $this->validate() && $this->insert();
    }

    public function insert()
    {
        $this->_business_pay_config->payments =  "2,3,5,8";
        $this->_business_pay_config->wechat = 1;
        $this->_business_pay_config->alipay = 1;
        $this->_business_pay_config->msyh = 1;
        $this->_business_pay_config->wft = 1;
        $this->_business_pay_config->jd = 1;

        return $this->_business_pay_config->save();
    }
}