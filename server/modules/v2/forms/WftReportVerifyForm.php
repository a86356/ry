<?php

namespace app\modules\v1\forms;

use app\models\Business;
use app\models\ReportVerify;
use app\models\ReportVerifyLog;
use app\modules\v1\utils\ResponseMap;
use yii\base\Model;
use yii\base\UserException;

class WftReportVerifyForm extends Model
{
    public $bus_id;
    public $open_bank;
    public $bank_account;
    public $bank_account_name;

    /**
     * @var Business $_business
     */
    private $_business;
    private $_report_verify;

    public function __construct($id)
    {
        if(!empty($id))
        {
            $this->_report_verify =  ReportVerify::findOne(['bus_id'=>\Yii::$app->getUser()->getId(),'report_id'=>$id]);
            if(!$this->_report_verify)
            {
                throw new UserException(ResponseMap::Map('600002'),'600002');
            }
            //判断是否被驳回
            if($this->_report_verify->status != 4)
            {
                throw new UserException(ResponseMap::Map("600001"),"600001");
            }
        }
        else
        {
            $this->_report_verify = new ReportVerify();
        }
        parent::__construct([]);
    }

    public function init()
    {
         $business = Business::findOne(\Yii::$app->getUser()->getId());
        if(!$business)
        {
            throw new UserException("商户不存在","900001");
        }
        $this->bus_id = $business->bus_id;
        $this->_business = $business;
    }

    //返回审核ID
    public function getReportId()
    {
        return $this->_report_verify->report_id;
    }

    public function rules()
    {
        return [
            ['open_bank','required','message'=>'开户行不能为空'],
            ['bank_account','required','message'=>'银行帐号不能为空'],
            ['bank_account_name','required','message'=>'银行账户名不能为空'],
        ];
    }

    public function save()
    {
        if($this->_report_verify->getIsNewRecord())
        {
            return $this->validate() && $this->insert();
        }
        else
        {
            return $this->validate() && $this->update();
        }
    }

    public function update()
    {
        $transaction = \Yii::$app->getDb()->beginTransaction();

        $this->_report_verify->open_bank = $this->open_bank;
        $this->_report_verify->bank_account = $this->bank_account;
        $this->_report_verify->bank_account_name = $this->bank_account_name;
        $this->_report_verify->status = 1;
        //保存签约信息
        if($this->_report_verify->save() === false)
        {
            throw new UserException("系统错误","900001");
        }

        //插入审核信息
        $report_verify_log = new ReportVerifyLog();
        $report_verify_log->report_id = $this->_report_verify->report_id;
        $report_verify_log->add_time = time();
        $report_verify_log->msg = "重新提交配置";
        $report_verify_log->status = 1;
        if($report_verify_log->save() === false)
        {
            $transaction->rollBack();
            throw new UserException("系统错误","900001");
        }
        $transaction->commit();
        return true;
    }

    public function insert()
    {
        $transaction = \Yii::$app->getDb()->beginTransaction();

        $this->_report_verify->bus_id = $this->bus_id;
        $this->_report_verify->channel_type = '5';
        $this->_report_verify->franchis_id = 0;
        $this->_report_verify->add_time = time();
        $this->_report_verify->is_public = 1;
        $this->_report_verify->open_bank = $this->open_bank;
        $this->_report_verify->bank_account = $this->bank_account;
        $this->_report_verify->bank_account_name = $this->bank_account_name;
        $this->_report_verify->bus_name = $this->_business->bus_name;
        $this->_report_verify->bus_reg_name = $this->_business->businessInfo->bus_reg_name;
        $this->_report_verify->reg_addr = $this->_business->businessInfo->reg_addr;
        $this->_report_verify->email = $this->_business->email;
        $this->_report_verify->service_mobile = $this->_business->service_mobile;
        $this->_report_verify->legal_person = $this->_business->businessInfo->legal_person;
        $this->_report_verify->legal_person_idcard = $this->_business->businessInfo->legal_person_idcard;
        $this->_report_verify->legal_person_mobile = $this->_business->businessInfo->legal_person_mobile;
        $this->_report_verify->rate_obj = json_encode(['wft'=>['calc_rate'=>'0.38']]);
        $this->_report_verify->status = 1;

        //保存签约信息
        if($this->_report_verify->save() === false)
        {
            throw new UserException("系统错误","900001");
        }

        //插入审核信息
        $report_verify_log = new ReportVerifyLog();
        $report_verify_log->report_id = $this->_report_verify->report_id;
        $report_verify_log->add_time = time();
        $report_verify_log->msg = "提交审核";
        $report_verify_log->status = 1;
        if($report_verify_log->save() === false)
        {
            $transaction->rollBack();
            throw new UserException("系统错误","900001");
        }
        $transaction->commit();
        return true;
    }
}