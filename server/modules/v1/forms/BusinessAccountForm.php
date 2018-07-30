<?php
namespace app\modules\v1\forms;

use app\models\BusinessAccount;
use yii\base\Model;

class BusinessAccountForm extends Model
{
    public $username;  //用户名
    public $password;   //密码

    public $auth_key;   //密钥

    private $_business_account;

    /**
     * BusinessAccountForm constructor.
     * @param array $id 商户ID
     */
    public function __construct($id)
    {
        parent::__construct();

        $this->_business_account = new BusinessAccount();
        $this->_business_account->bus_id = $id;
    }

    public function rules()
    {
        return [
            [['username','password'],'filter','filter'=>'trim'],
            ['username','required','message'=>'用户名不能为空'],
            ['username','unique','targetClass'=>'app\models\BusinessAccount','when'=>function($model)
            {
                //在添加的时候验证
                return $model->_business_account->getIsNewRecord();
            },'message'=>'手机号已存在'],
            ['password','required','message'=>'密码不能为空'],
            ['password','string','length'=>[6,15],'tooShort'=>'密码不能少于6个字符','tooLong'=>'密码不能多于15个字符'],
        ];
    }

    //保存商户信息
    public function save()
    {
        return $this->validate() && $this->insert();
    }

    //添加商户账户信息
    public function insert()
    {
        $this->_business_account->username = $this->username;
        $this->_business_account->password = $this->password ? md5($this->password) : md5(\Yii::$app->params['defaultPassword']);
        $this->_business_account->auth_key = \Yii::$app->getSecurity()->generateRandomString();
        $this->_business_account->add_time = time();
        $this->_business_account->openid = '';
        $this->_business_account->unionid = '';

        return $this->validate() && $this->_business_account->save();
    }
}