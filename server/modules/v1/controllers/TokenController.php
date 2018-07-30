<?php

namespace app\modules\v1\controllers;
use app\models\Business;
use app\models\BusinessAccount;
use app\modules\v1\behaviors\business\BusinessBehavior;
use app\modules\v1\behaviors\token\SyncTokenBehavior;
use app\modules\v1\events\business\AfterCreateBusinessEvent;
use app\modules\v1\forms\BusinessAccountForm;
use app\modules\v1\forms\BusinessInfoForm;
use app\modules\v1\forms\BusinessPayConfigForm;
use app\modules\v1\forms\ForgetForm;
use app\modules\v1\forms\RegisterForm;
use app\modules\v1\utils\Constants;
use app\modules\v1\utils\ResponseMap;
use yii\base\UserException;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\filters\VerbFilter;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UserEvent;

class TokenController extends Controller
{
    public function behaviors()
    {
        return [
            //设置返回的格式
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],
            //设置访问时的类型
            'verbFilter' => [
                'class' => VerbFilter::className(),
                'actions' => $this->verbs(),
            ],
            'cors'=>[
                'class' => Cors::className(),
            ],
            'syncToken'=>[
                'class'=>SyncTokenBehavior::className(),
            ],
            'syncBusiness'=>[
                'class'=>BusinessBehavior::className(),
            ],
        ];
    }

    public function verbs()
    {
        return [
            'login'=>['POST'],
            'wx-login'=>['POST'],
            'register'=>['POST'],
        ];
    }

    public function test(){
    /*    $send_msg = [
            'bus_id'=>'s',
        ];
        \Yii::$app->rabbitmq->publishMessage(Json::encode($send_msg),"create","business",false,true,false,RabbitMQ::EXCHANGE_TYPE_DIRECT);*/

    }


    public function beforeLogin(BusinessAccount $user){

        $event = new UserEvent();

        $event->identity = $user;

        $this->trigger(Constants::BEFORE_USER_LOGIN,$event);

    }

    public function afterCreateBusiness($bus_id){
        $event = new AfterCreateBusinessEvent();
        $event->bus_id = $bus_id;
        $this->trigger(BusinessBehavior::AFTER_CREATE_BUSINESS,$event);
    }

    //需要发送商户帐号和密码来验证
    public function actionLogin()
    {

        $username = \Yii::$app->getRequest()->post('username');
        $password = \Yii::$app->getRequest()->post('password');


        $user = BusinessAccount::findByUsername($username);


        if(!$user)
        {
            throw new UserException(ResponseMap::Map("200001"),"200001");
        }

        if(!$user->validatePassword($password))
        {

            throw new UserException(ResponseMap::Map("200002"),"200002");
        }

        //$this->beforeLogin($user);


        $user->auth_key = \Yii::$app->getSecurity()->generateRandomString();

        $before_is_new = $user->business->is_new;
        $_business = Business::findOne($user->business->bus_id);
        $_business->is_new = 0;
        $_business->save(); // 等同于 $User->update();
        $user->save();
        return [
            'access_token'=>$user->getAuthKey(),
            'ip'=>\Yii::$app->getRequest()->getUserIP(),
            'bus_name'=>$user->business->bus_name,
            'bus_no'=>$user->business->bus_no,
            'is_new'=>$before_is_new,
        ];
    }

    //微信登录
    public function actionWxLogin()
    {
        $openid = \Yii::$app->getRequest()->post('unionid');
        $user = BusinessAccount::findByUnionId($openid);

        if(!$user)
        {
            throw new UserException(ResponseMap::Map("200001"),"200001");
        }
        $this->beforeLogin($user);
        return [
            'access_token'=>$user->getAuthKey(),
            'ip'=>\Yii::$app->getRequest()->getUserIP(),
            'bus_name'=>$user->business->bus_name,
            'acc_name'=>$user->username,
            'bus_no'=>$user->business->bus_no
        ];
    }

    //获取IP地址
    public function actionIp()
    {
        return ['ip'=>\Yii::$app->getRequest()->getUserIP()];
    }

    //商户注册
    public function actionRegister()
    {
        $mobile = \Yii::$app->getRequest()->post('username');
        $verify =\Yii::$app->getRequest()->post('verify');
        $client = new Client();
        $response = $client->get(\Yii::$app->params['sms_url']."sms/check-verify",['mobile'=>$mobile,'code'=>$verify])->send();
        if(!$response->getIsOk())
        {
            throw new UserException("验证过程发生错误","900001");
        }
        $verify_resp_data = $response->getData();

        if($verify_resp_data['data']['isEqual'] == "F")
        {
            throw new UserException("验证码错误","400000");
        }

        $transaction = \Yii::$app->getDb()->beginTransaction();

        //商户基本信息处理
        $business_form = new RegisterForm();
        if($business_form->load(\Yii::$app->getRequest()->post(),'') && $business_form->save()===false)
        {
            $transaction->rollBack();
            throw new UserException(current($business_form->getFirstErrors()),"400000");
        }

        //获取商户ID
        $bus_id = $business_form->getBusId();
        //商户支付信息处理
        $business_pay_config_form = new BusinessPayConfigForm($bus_id);
        if($business_pay_config_form->save() === false){
            $transaction->rollBack();
            throw new UserException(current($business_form->getFirstErrors()),"400000");
        }

        //商户入网信息处理
        $business_info_form = new BusinessInfoForm($bus_id);
        if($business_info_form->load(\Yii::$app->getRequest()->post(),'') && $business_info_form->save() === false)
        {
            $transaction->rollBack();
            throw new UserException(current($business_info_form->getFirstErrors()),"400000");
        }

        //商户帐号处理
        $business_account_form = new BusinessAccountForm($bus_id);
        if($business_account_form->load(\Yii::$app->getRequest()->post(),'') && $business_account_form->save()===false)
        {
            $transaction->rollBack();
            throw new UserException(current($business_account_form->getFirstErrors()),"400000");
        }

        $transaction->commit();
        $this->afterCreateBusiness($bus_id);
        return ['result'=>'T'];
    }

    //获取验证码
    public function actionVerify($mobile)
    {
        $client = new Client();
        $response = $client->get(\Yii::$app->params['sms_url']."sms/send-verify-message",['mobile'=>$mobile,'seconds'=>300])->send();
        if(!$response->getIsOk())
        {
            throw new UserException($response->getData()['msg'],"900001");
        }
        return ['result'=>'T'];
    }

    //检测用户名是否已经存在
    public function actionCheckUsername($username)
    {
        return BusinessAccount::findByUsername($username) ? ['result'=>'F'] : ['result'=>'T'];
    }

    //忘记密码重置
    public function actionForget()
    {
        $form = new ForgetForm();
        if($form->load(\Yii::$app->getRequest()->post(),'') && $form->save() === false)
        {
            throw new UserException(current($form->getFirstErrors()),"400000");
        }

        return ['result'=>'T'];
    }
}