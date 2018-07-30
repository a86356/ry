<?php

namespace app\modules\v1\forms;

use yii\base\Model;
use yii\base\UserException;


class ForgetForm extends CommonForm
{
    public $mobile;
    public $password;
    public $verify;

    public function rules()
    {
        return [
            ['mobile','required','message'=>'手机号不能为空'],
            ['verify','required','message'=>'验证码不能为空'],
            ['verify','checkVerify'],
            ['password','required','message'=>'密码不能为空'],
            ['password','string','length'=>[6,15],'tooShort'=>'密码不能少于6个字符','tooLong'=>'密码不能多于15个字符']
        ];
    }

    //检查验证码
    public function checkVerify($attribute,$params)
    {
        $client = new Client();
        $response = $client->get(\Yii::$app->params['sms_url']."sms/check-verify",['mobile'=>$this->mobile,'code'=>$this->verify])->send();
        if(!$response->getIsOk())
        {
            throw new UserException("验证过程发生错误","900001");
        }
        $verify_resp_data = $response->getData();
        if($verify_resp_data['data']['isEqual'] == "F")
        {
            $this->addError("verify",'验证码错误');
        }
    }

    public function save()
    {
        return $this->validate() && $this->update();
    }

    public function update()
    {
        $business_account = BusinessAccount::findByUsername($this->mobile);
        $business_account->password = md5($this->password);
        $business_account->auth_key = \Yii::$app->getSecurity()->generateRandomString();
        return $business_account->save();
    }
}