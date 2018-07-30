<?php

namespace app\modules\v1\controllers;

use app\models\ReportVerify;
use app\modules\v1\common\BaseController;
use app\modules\v1\forms\WftReportVerifyForm;
use app\modules\v1\forms\SfReportVerifyForm;
use app\modules\v1\forms\YbReportVerifyForm;
use yii\base\UserException;

/**
 * 渠道开通业务
 * Class ChannelController
 * @package app\modules\v1\controllers
 */
class ChannelController extends BaseController
{
    public function verbs()
    {
        return [
            'sf-report-verify'=>['POST'],
            'yb-report-verify'=>['POST'],
            'wft-report-verify'=>['POST'],
            'report-verify-list'=>['GET'],
            'update-wft-report-verify'=>['POST'],
            'update-sf-report-verify'=>['POST'],
            'verify-info-by-id'=>['GET']
        ];
    }

    //返回审核列表
    public function actionReportVerifyList($channel_type)
    {
        return ReportVerify::find()->select(['report_id','channel_type','status'])
            ->where(['bus_id'=>\Yii::$app->getUser()->getId()])
            ->andWhere(['like','channel_type',$channel_type])
            ->orderBy("report_id desc")->all();
    }

    //中信渠道开通审核
    public function actionWftReportVerify()
    {
        $form = new WftReportVerifyForm(null);

        if($form->load(\Yii::$app->getRequest()->post(),'') && $form->save())
        {
            return ['report_id'=>$form->getReportId()];
        }
        else
        {
            throw new UserException(current($form->getFirstErrors()),"400000");
        }
    }

    //申孚快捷支付开通审核
    public function actionSfReportVerify()
    {
        $form = new SfReportVerifyForm(null);

        if($form->load(\Yii::$app->getRequest()->post(),'') && $form->save())
        {
            return ['report_id'=>$form->getReportId()];
        }
        else
        {
            throw new UserException(current($form->getFirstErrors()),"400000");
        }
    }

    //驳回的商户中信银行修改信息
    public function actionUpdateWftReportVerify()
    {
        $form = new WftReportVerifyForm(\Yii::$app->getRequest()->post('report_id'));
        if($form->load(\Yii::$app->getRequest()->post(),'') && $form->save() !== false)
        {
            return ['result'=>'T'];
        }
        else
        {
            return $form->getFirstErrors();
        }
    }

    //驳回的商户申孚快捷支付修改信息
    public function actionUpdateSfReportVerify()
    {
        $form = new SfReportVerifyForm(\Yii::$app->getRequest()->post('report_id'));
        if($form->load(\Yii::$app->getRequest()->post(),'') && $form->save() !== false)
        {
            return ['result'=>'T'];
        }
        else
        {
            return $form->getFirstErrors();
        }
    }

    //易宝快捷支付开通审核
    public function actionYbReportVerify()
    {

        $form = new YbReportVerifyForm(null);

        if($form->load(\Yii::$app->getRequest()->post(),'') && $form->save())
        {
            return ['report_id'=>$form->getReportId()];
        }
        else
        {
            throw new UserException(current($form->getFirstErrors()),"400000");
        }
    }

    //驳回的商户易宝快捷支付修改信息
    public function actionUpdateYbReportVerify()
    {
        $form = new YbReportVerifyForm(\Yii::$app->getRequest()->post('report_id'));
        if($form->load(\Yii::$app->getRequest()->post(),'') && $form->save() !== false)
        {
            return ['result'=>'T'];
        }
        else
        {
            return $form->getFirstErrors();
        }
    }
    //返回审核信息
    public function actionVerifyInfoById($report_id)
    {
        return ReportVerify::verifyInfoById($report_id);
    }
}