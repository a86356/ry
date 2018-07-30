<?php

namespace app\modules\v1\controllers;

use yii\web\Controller;

class ParamsController extends Controller
{
    //获取支付地址
    public function actionPayAddress()
    {
        if(YII_DEBUG)
        {
            return [
                'url'=>'http://test.pays.zjhylh.com/v3/pay/gateway',
                'pay_view_url'=>'http://test.pay.zjhylh.com/',
                'service_code'=>'pay.xdl.micropay',   //默认支付代码
            ];
        }
        else
        {
            return [
                'url'=>'https://pays.zjhylh.com/v3/pay/gateway',
                'pay_view_url'=>'http://pay.zjhylh.com/',
                'service_code'=>'pay.xdl.micropay',    //默认支付代码
            ];
        }
    }
}