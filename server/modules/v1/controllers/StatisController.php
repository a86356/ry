<?php

namespace app\modules\v1\controllers;

use app\models\Msyh;
use app\models\PayLog;
use app\modules\v1\common\BaseController;
use yii\helpers\ArrayHelper;

class StatisController extends BaseController
{
    public function verbs()
    {
        return [
            'thirty-trade-charts'=>['GET','HEAD'],
            'history-trade'=>['GET','HEAD']
        ];
    }

    //获取近N天的交易金额和笔数
    public function actionThirtyTradeCharts()
    {
        //近30天
        $day = 30;

        $data = PayLog::find()->asArray()
            ->select(['FROM_UNIXTIME(start_time,"%Y-%m-%d") as date','sum(total_fee) as total_fee','count(*) as total_count'])
            ->where("bus_id=:bus_id and pay_status=2 and date_sub(curdate(), INTERVAL :day DAY) <=  FROM_UNIXTIME(start_time,'%Y-%m-%d')",[
                'day'=>$day,
                ':bus_id'=>\Yii::$app->getUser()->getId()
            ])
            ->groupBy("date")
            ->all();

        $data = ArrayHelper::index($data,'date');

        $items = [];
        for($i=0;$i<=$day;$i++)
        {
            $timestamp = strtotime('-'. $i . 'day');
            $d = date("Y-m-d", $timestamp);
            if(!ArrayHelper::keyExists($d,$data))
            {
                $items[] = ['date'=>$d,'total_fee'=>'0','total_count'=>'0','timestamp'=>strtotime($d)];
            }
            else
            {
                $data[$d]['timestamp']= strtotime($d);
                $data[$d]['total_fee'] = $data[$d]['total_fee'] / 100;
                $items[] = $data[$d];
            }
        }

        return $items;
    }

    //历史交易流水统计
    public function actionHistoryTrade($start_time=null,$end_time=null)
    {
        if(empty($start_time) || empty($end_time))
        {
            $start_time = strtotime(date("Y-m-d 00:00:00"));
            $end_time = $start_time + 86399;
        }

        $data = PayLog::find()->asArray()
            ->select([
                'count(log_id) as total_trade_count',
                'ifnull(sum(total_fee),0) as total_trade_fee',
                'count(if(pay_type=1,true,null)) as wechat_trade_count',
                'ifnull(sum(if(pay_type=1,total_fee,null)),0) as wechat_trade_fee',
                'count(if(pay_type=2,true,null)) as alipay_trade_count',
                'ifnull(sum(if(pay_type=2,total_fee,null)),0) as alipay_trade_fee',
                'count(if(pay_type=3,true,null)) as msyh_trade_count',
                'ifnull(sum(if(pay_type=3,total_fee,null)),0) as msyh_trade_fee',
                'count(if(pay_type=5,true,null)) as wft_trade_count',
                'ifnull(sum(if(pay_type=5,total_fee,null)),0) as wft_trade_fee',
                'count(if(pay_type=6,true,null)) as jd_trade_count',
                'ifnull(sum(if(pay_type=6,total_fee,null)),0) as jd_trade_fee',
            ])
            ->where("bus_id=:bus_id and pay_status=2 and start_time between :start_time and :end_time",[
                ':bus_id'=>\Yii::$app->getUser()->getId(),
                ':start_time'=>$start_time+0,
                ':end_time'=>$end_time+0
            ])
            ->one();

        //30以下免手续费
        $bus_msyh_conf = Msyh::findOne(['bus_id'=>\Yii::$app->getUser()->getId()]);
        if($bus_msyh_conf)
        {
            $year = date("Y");
            $month = date("m");
            $allday = date("t");
            $start_time = strtotime($year."-".$month."-1");
            $end_time = strtotime($year."-".$month."-".$allday." 23:59:59");
            $total_fee= PayLog::find()
                ->select(['ifnull(sum(total_fee),0)'])
                ->where([
                    'bus_id'=>\Yii::$app->getUser()->getId(),
                    'pay_status'=>2,
                    'pay_type'=>3
                ])
                ->andWhere("total_fee <= :total_fee",[':total_fee'=>3000])
                ->andWhere(['between','start_time',$start_time,$end_time])
                ->scalar();
            $msyh_coupon_fee = round(( $total_fee/ 100)*$bus_msyh_conf->t0_calc_rate,2);
        }
        else
        {
            $msyh_coupon_fee = 0;
        }

        $items = [
            'total_trade_count'=>$data['total_trade_count'],
            'total_trade_fee'=>$data['total_trade_fee'] / 100,
            'trade'=>[
                ['pay_type'=>1,'count'=>$data['wechat_trade_count'],'fee'=>$data['wechat_trade_fee']/100,'coupon_fee'=>0],
                ['pay_type'=>2,'count'=>$data['alipay_trade_count'],'fee'=>$data['alipay_trade_fee']/100,'coupon_fee'=>0],
                ['pay_type'=>3,'count'=>$data['msyh_trade_count'],'fee'=>$data['msyh_trade_fee']/100,'coupon_fee'=>$msyh_coupon_fee],
                ['pay_type'=>5,'count'=>$data['wft_trade_count'],'fee'=>$data['wft_trade_fee']/100,'coupon_fee'=>0],
                ['pay_type'=>6,'count'=>$data['jd_trade_count'],'fee'=>$data['jd_trade_fee']/100,'coupon_fee'=>0]
            ]
        ];

        return $items;
    }
}