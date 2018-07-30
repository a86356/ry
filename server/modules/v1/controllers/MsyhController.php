<?php

namespace app\modules\v1\controllers;

use app\models\MsyhDrawLog;
use app\modules\v1\common\BaseController;

class MsyhController extends BaseController
{
    //返回民生银行提现记录
    public function actionDrawLog()
    {
        $draw_pay_type = \Yii::$app->getRequest()->get('draw_pay_type');
        $draw_type = \Yii::$app->getRequest()->get('draw_type');
        $draw_status = \Yii::$app->getRequest()->get('draw_status');
        $start_time = \Yii::$app->getRequest()->get('start_time');
        $end_time = \Yii::$app->getRequest()->get('end_time');
        $pagesize = \Yii::$app->getRequest()->get('pagesize');

        //如果时间没有筛选就默认当天
        if(empty($start_time) || empty($end_time))
        {
            $start_time = strtotime(date("Y-m-d 00:00:00"));
            $end_time = $start_time + 86399;
        }

        $items = MsyhDrawLog::search(\Yii::$app->getUser()->getId(),$draw_pay_type,$draw_type,$draw_status,$start_time,$end_time,$page,$pagesize);
        foreach($items as $v)
        {
            $v->draw_calc_fee = $v->draw_calc_fee / 100;
            $v->draw_fee = $v->draw_fee / 100;
            $v->draw_trade_fee = $v->draw_trade_fee / 100;
        }
        return ['data'=>$items,'page'=>$page->Out()];
    }
}