<?php

namespace app\modules\v1\controllers;

use app\modules\v1\common\BaseController;

class TestController extends BaseController
{
    public function actionHot()
    {
        $data=[
            [
                'title'=>'玻璃清洁剂',
                'desc'=>'玻璃清洁剂',
                'cash'=>'9.8元',
                'thumb_img'=>'http://oss.51weipay.cn/app-img/20170801-2.png',
                'url'=>'',
            ],
            [
                'title'=>'玻璃清洁剂',
                'desc'=>'玻璃清洁剂',
                'cash'=>'9.8元',
                'thumb_img'=>'http://oss.51weipay.cn/app-img/20170801.jpg',
                'url'=>'',
            ],
        ];
        return $data;
    }
    //热门页
    public function actionHotPage()
    {
        $data=[
            [
                'title'=>'尊享e生百万医疗险',
                'desc'=>'突破医保限制 国民医保',
                'cash'=>'￥136.00起/年',
                'thumb_img'=>'http://appapi.zjhylh.com/upload/admin/20180518/a5aba6fd90acabda8e80cd09d174e65d.jpg',
                'url'=>'',
            ],
            [
                'title'=>'尊享e生百万医疗险',
                'desc'=>'突破医保限制 国民医保',
                'cash'=>'￥136.00起/年',
                'thumb_img'=>'http://appapi.zjhylh.com/upload/admin/20180518/e1627451578a1293a71c7b1a1ed2f59b.jpg',
                'url'=>'',
            ],
        ];
        return $data;
    }
    public function actionBanner()
    {
        return [
            ['thumb_img'=>'http://oss.51weipay.cn/app-img/3.jpg','url'=>'https://www.zhonglipay.com/user/register?type=mer&channelId=1000000030'],
            ['thumb_img'=>'http://oss.51weipay.cn/app-img/20170728.jpg','url'=>'http://www.zjljjn.com/4g/Index.aspx'],
            ['thumb_img'=>'http://oss.51weipay.cn/2.png','url'=>'http://www.wzweifu.com/'],
        ];
    }

    public function actionPayHelp()
    {
        return [
            [
                'cash'=>'18',
                'order_desc'=>'宝岛便当(黄龙店) 外卖订单',
                'pay_status'=>'2',
                'pay_msg'=>'支付成功',
                'time'=>'2017-07-15 11:06',
            ],
            [
                'cash'=>'12',
                'order_desc'=>'东池便当(黄龙店) 外卖订单',
                'pay_status'=>'2',
                'pay_msg'=>'支付成功',
                'time'=>'2017-07-14 11:06',
            ]
        ];
    }
}