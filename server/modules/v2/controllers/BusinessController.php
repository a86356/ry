<?php

namespace app\modules\v1\controllers;

use app\models\Business;
use app\models\BusinessAccount;
use app\models\BusinessInfo;
use app\models\BusinessPayConfig;
use app\models\PayLog;
use app\models\Sale;
use app\modules\v1\common\BaseController;
use app\modules\v1\forms\BusinessForm;
use app\modules\v1\forms\UpdatePasswordForm;
use app\modules\v1\utils\ResponseMap;
use yii\base\UserException;

class BusinessController extends BaseController
{
    public function verbs()
    {
        return [
            'sign-key' => ['GET', 'HEAD'],
            'account-info'=>['GET','HEAD'],
            'internet-info' => ['GET', 'HEAD'],
            'update-internet-info' => ['PUT', 'PATCH'],
            'settle-info'=> ['GET', 'HEAD'],
            'update-password'=>['PUT', 'PATCH'],
            'bind-wechat'=>['PUT', 'PATCH'],
            'query-bind-wechat'=>['PUT', 'PATCH'],
        ];
    }

    //账户信息
    public function actionAccountInfo()
    {
        $bus_info = Business::find()->asArray()->select(['bus_name','bus_no'])->where([Business::tableName().'.bus_id'=>\Yii::$app->getUser()->getId()])->one();
        $bus_pay_info = BusinessPayConfig::find()->asArray()->select(['wechat','alipay','msyh','wft','jd'])->where(['bus_id'=>\Yii::$app->getUser()->getId()])->one();
        $bus_info = array_merge($bus_info,$bus_pay_info);
        $username = \Yii::$app->getUser()->getIdentity()->username;


        $start_time = strtotime(date("Y-m-d 00:00:00"));
        $end_time = $start_time + 86399;

        //获取今日成交额
        $today_toal_fee = PayLog::find()->select(['ifnull(sum(total_fee)/100,0)'])
            ->where(['bus_id'=>\Yii::$app->getUser()->getId()])
            ->andWhere(['pay_status'=>2])
            ->andWhere(['between','start_time',$start_time,$end_time])
            ->scalar();
        //获取今日成交笔数
        $today_trade_count = PayLog::find()
            ->where(['bus_id'=>\Yii::$app->getUser()->getId()])
            ->andWhere(['pay_status'=>2])
            ->andWhere(['between','start_time',$start_time,$end_time])
            ->count();

        return ['bus_info'=>$bus_info,'today_toal_fee'=>round($today_toal_fee,2),'today_trade_count'=>$today_trade_count,'username'=>$username];
    }

    //入网信息
    public function actionInternetInfo()
    {

        //基础信息
        $base_info = Business::find()->select(['service_mobile','email'])->where(['bus_id'=>\Yii::$app->getUser()->getId()])->one();
        //入网信息
        $internet_info = BusinessInfo::find()->where(['bus_id'=>\Yii::$app->getUser()->getId()])->one();
        unset($internet_info['bus_id']);
        return ['base'=>$base_info,'internet_info'=>$internet_info];
    }

    //修改入网信息
    public function actionUpdateInternetInfo()
    {
        $form = new BusinessForm(\Yii::$app->getUser()->getId());
        if($form->load(\Yii::$app->getRequest()->post(),'') && $form->save())
        {
            return $this->actionInternetInfo();
        }
        else
        {
            throw new UserException(current($form->getFirstErrors()),"400000");
        }
    }

    //结算信息
    public function actionSettleInfo()
    {
        $bus_info = Business::findOne(\Yii::$app->getUser()->getId());
        $bus_pay_conf = $bus_info->businessPayConfig;
        $data = [];
        if($bus_pay_conf->wechat == 2)
        {
            $info = $bus_info->getWechatConf()->select(['open_bank','bank_account_name','bank_account','calc_rate'])->asArray()->one();
            $info['pay_type'] = 1;
            $data[]=$info;
        }
        if($bus_pay_conf->msyh == 2)
        {
            $info = $bus_info->getMsyhConf()->select(['open_bank','bank_account_name','bank_account','t0_calc_rate','t0_simple_fee','t1_calc_rate','t1_simple_fee'])->asArray()->one();
            $info['pay_type'] = 3;
            $data[] = $info;
        }
        if($bus_pay_conf->wft == 2)
        {
            $info = $bus_info->getWftConf()->select(['open_bank','bank_account_name','bank_account','calc_rate'])->asArray()->one();
            $info['pay_type'] = 5;
            $data[]=$info;
        }
        if($bus_pay_conf->jd == 2)
        {
            $info = $bus_info->getJdConf()->select(['open_bank','bank_account_name','bank_account','calc_rate'])->asArray()->one();
            $info['pay_type'] = 6;
            $data[] = $info;
        }
        if($bus_pay_conf->sf == 2)
        {
            $info = $bus_info->getSfConf()->select(['open_bank','bank_account_name','bank_account','fee_rate','cre_rate','settel_fee'])->asArray()->one();
            $info['pay_type'] = 7;
            $data[] = $info;
        }
        if($bus_pay_conf->sb == 2){
            $info = $bus_info->getSbConf()->select(['open_bank','bank_account_name','bank_account'])->asArray()->one();
            $info['pay_type'] = 8;
            $data[] = $info;
        }
        if($bus_pay_conf->xdl == 2){
            $info = $bus_info->getXdlConf()->select(['open_bank','bank_account_name','bank_account','rate'])->asArray()->one();
            $info['pay_type'] = 9;
            $data[] = $info;
        }

        return $data;
    }

    //修改密码
    public function actionUpdatePassword()
    {
        $form = new UpdatePasswordForm(\Yii::$app->getUser()->getId());
        if($form->load(\Yii::$app->getRequest()->post(),'') && $form->save())
        {
            return ['access_token'=>$form->getUser()->auth_key];
        }
        else
        {
            throw new UserException(current($form->getFirstErrors()),"400000");
        }
    }

    //查询是否绑定了微信
    public function actionQueryWechatBind()
    {
        return !empty(\Yii::$app->getUser()->getIdentity()->getUnionid()) ? ['isBind'=>'Y'] : ['isBind'=>'N'];
    }

    //绑定微信
    public function actionBindWechat()
    {
        $openid = \Yii::$app->getRequest()->post('openid');
        $unionid = \Yii::$app->getRequest()->post('unionid');

        //查询当前的微信号是否已经绑定帐号
        $user = BusinessAccount::findByUnionId($unionid);

        if($user && $user->getId() != \Yii::$app->getUser()->getId())
        {
            throw new UserException(ResponseMap::Map("200003"),"200003");
        }
        $user = BusinessAccount::findIdentity(\Yii::$app->getUser()->getId());
        $user->unionid = $unionid;
        if($user->update(false) !== false)
        {
            return ['unionid'=>$unionid];
        }
        else
        {
            throw new UserException("系统错误","900000");
        }
    }

    //返回邀请码
    public function actionInvite(){
        $info = Business::findOne(['bus_id'=>\Yii::$app->getUser()->getId()]);
        if($info->sale_id!=0){
            $sale_info = Sale::findOne(['sale_id'=>$info->sale_id]);
            return ['invite_code'=>$sale_info->username];
        }
        else{
            return ['invite_code'=>''];
        }
    }
}