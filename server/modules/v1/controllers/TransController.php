<?php

namespace app\modules\v1\controllers;

use app\models\Alipay;
use app\models\JdPayLog;
use app\models\Machine;
use app\models\MsyhPayLog;
use app\models\PayLog;
use app\models\SfPayLog;
use app\models\Store;
use app\models\WechatPayLog;
use app\models\WftPayLog;
use app\modules\v1\common\BaseController;
use vendor\pagination\Pagination;
use yii\base\UserException;
use yii\helpers\ArrayHelper;

class TransController extends BaseController
{
    public function verbs()
    {
        return [
            'history-trade'=>['GET','HEAD'],
            'trade-detail'=>['GET','HEAD']
        ];
    }

    //获取最新的一笔交易数据
    public function actionTopTrade()
    {
        $data = PayLog::find()->where(['bus_id'=>\Yii::$app->getUser()->getId()])->orderBy("log_id desc")->one();
        if($data)
        {
            return ['total_fee'=>$data->total_fee/100,'time'=>date("Y-m-d H:i:s"),'status'=>$data->pay_status,'goods_name'=>$data->good_name];
        }
        return [];
    }

    //历史交易数据
    public function actionHistoryTrade($pay_type=null,$pay_status=null,$start_time=null,$end_time=null,$pagesize=null)
    {
        $query=PayLog::find()->asArray()
            ->select([
                'log_id','bus_trade_no','sys_trade_no','good_name',
                'total_fee','cash_fee','mach_id','device_no',
                'pay_status','pay_type',"start_time",
                "end_time"
            ])
            ->andWhere(['bus_id'=>\Yii::$app->getUser()->getId()])->orderBy("log_id desc");

        if(!empty($pay_type))
        {
            $query->andWhere(['pay_type'=>explode(",",$pay_type)]);
        }
        if(!empty($pay_status))
        {
            $query->andWhere(['pay_status'=>explode(",",$pay_status)]);
        }
        if(!empty($start_time))
        {
            $query->andWhere('start_time > :start_time',[':start_time'=>$start_time]);
        }
        if(!empty($end_time))
        {
            $query->andWhere("start_time < :end_time",[':end_time'=>$end_time]);
        }

        $page = new Pagination(['totalCount' => $query->count(), 'pageSize' => $pagesize]);
        $query->offset($page->getOffset());
        $query->limit($page->getLimit());

        $data = $query->all();



        //如果时间没有筛选就默认当天
        if(empty($start_time) || empty($end_time))
        {
            $start_time = strtotime(date("Y-m-d 00:00:00"));
            $end_time = $start_time + 86399;
        }

        $query = PayLog::find()
            ->where(['bus_id'=>\Yii::$app->getUser()->getId()])
            ->andWhere(['pay_status'=>2])
            ->andWhere(['between','start_time',$start_time,$end_time]);
        if(!empty($pay_type))
        {
            $query->andWhere(['pay_type'=>explode(",",$pay_type)]);
        }

        //获取今日成交额
        $today_toal_fee = $query->select(['ifnull(sum(total_fee)/100,0)']) ->scalar();
        //获取今日成交笔数
        $today_trade_count = $query->select([])->count();

        $items = [];
        //格式化时间
        foreach($data as $k=>$v)
        {
            $date = date("Y-m-d",$v['start_time']);
            $data[$k]['date'] = $date;
            $data[$k]['total_fee'] = $v['total_fee'] / 100;
            $data[$k]['cash_fee'] = $v['cash_fee'] / 100;
        }
        $data = ArrayHelper::index($data,null,"date");

        foreach($data as $k=>$v)
        {
            $items[]=['time'=>$k,'timestamps'=>strtotime($k),'childs'=>$v];
        }

        return ['data'=>$items,'today_toal_fee'=>round($today_toal_fee,2),'today_trade_count'=>$today_trade_count,'page'=>$page->Out()];
    }

    /**
     * 交易详情
     * @param $id 日志ID
     */
    public function actionTradeDetail($id)
    {

        $a = PayLog::find()->select([
            'log_id','bus_trade_no','sys_trade_no','good_name',
            'total_fee','mach_id','device_no',
            'pay_status','pay_type','start_time',
            'end_time'
        ])->where(['log_id'=>$id+0])->one()->toArray();

        $b = [];
        if($a['pay_type'] == 1)
        {
            $b = WechatPayLog::find()->select([
                'wechat_trade_no','coupon_fee','bank_type',
                'fee_type','trade_type','trade_status'
            ])->where(['log_id'=>$id+0])->one()->toArray();

        }
        else if($a['pay_type'] == 2)
        {
            $b = Alipay::find()->select([
                'coupon_fee','alipay_trade_no','trade_type','trade_status'
            ])->where(['log_id'=>$id+0])->one()->toArray();
        }
        else if($a['pay_type'] == 3)
        {
            $b=MsyhPayLog::find()->select([
                'channel_no' ,'channel_type','channel_pay_type',
                'coupon_fee','buyer_pay_type','channel_status'
            ])->where(['log_id'=>$id+0])->one()->toArray();
        }
        else if($a['pay_type'] == 5)
        {
            $b = WftPayLog::find()->select([
                'channel_trade_no','channel_type','coupon_fee',
                'bank_type','fee_type','trade_type',
                'trade_status'
            ])->where(['log_id'=>$id+0])->one()->toArray();
        }
        else if($a['pay_type'] == 6)
        {
            $b= JdPayLog::find()->select(['coupon_fee','trade_type','trade_status'])->where(['log_id'=>$id+0])->one()->toArray();
        }

        //填充设备信息
        if($a['mach_id'] != 0)
        {
            $mach_info = Machine::find()->select(['mach_name','store_id'])->where(['mach_id'=>$a['mach_id']])->one();
            unset($a['mach_id']);
            $a['device_no'] = $mach_info->mach_name;
            //设置门店信息
            $a['store_name'] = Store::find()->select(['store_name'])->where(['store_id'=>$mach_info->store_id])->scalar();
        }

        $data = ArrayHelper::merge($a,$b);
        //转换金额
        $data['total_fee'] = $data['total_fee'] / 100;
        $data['coupon_fee'] = $data['coupon_fee'] / 100;

        return $data;
    }
}