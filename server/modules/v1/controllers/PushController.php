<?php

namespace app\modules\v1\controllers;

use app\modules\v1\common\BaseController;
use yii\base\DynamicModel;
use yii\base\UserException;

class PushController extends BaseController  {

    public function verbs()
    {
        return [
            'push-by-alias' => ['POST'],
        ];
    }

    public function actionPushByAlias(){

        $model = new DynamicModel(['title','content','platform','target','extras']);
        $model->addRule("extras",'default',['value'=>'']);
        $model->addRule("title",'required',['message'=>'标题不能为空']);
        $model->addRule("content",'required',['message'=>'内容不能为空']);
        $model->addRule("platform",'in',['range'=>['android','ios','all'],'allowArray'=>true,'skipOnEmpty'=>false,'message'=>'目标平台类型不在范围内']);
        $model->addRule("target",'required',['message'=>'目标商户号不能为空']);

        if($model->load(\Yii::$app->getRequest()->post(),'') && !$model->validate()){
            throw new UserException(current($model->getFirstErrors()),"900001");
        }
        try{
            \Yii::$app->jpush->pushByAlias($model->title,$model->content,$model->platform,$model->target,$model->extras);
        }
        catch(\Exception $e){
            throw new UserException($e->getMessage());
        }
        return ['result'=>'T'];
    }
}