<?php

namespace app\modules\v1\controllers;

use app\modules\v1\forms\StoreForm;
use app\models\Store;
use app\modules\v1\common\BaseController;
use app\modules\v1\utils\ResponseMap;
use yii\base\UserException;

class StoreController extends BaseController
{
    public function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionIndex()
    {
        return Store::findAll(['bus_id'=>\Yii::$app->getUser()->getId()]);
    }

    //返回商户指定的门店信息
    public function actionView($id)
    {
        $store = Store::findOne(['bus_id'=>\Yii::$app->getUser()->getId(),'store_id'=>$id]);

        if(!$store)
        {
            throw new UserException(ResponseMap::Map("420001"),"420001");
        }

        return $store;
    }

    //添加门店
    public function actionCreate()
    {
        $form = new StoreForm(null);
        if($form->load(\Yii::$app->getRequest()->post(),'') && $form->save())
        {
            return $form->getStore();
        }
        else
        {
            throw new UserException(current($form->getFirstErrors()),"400000");
        }
    }

    //更新设备信息
    public function actionUpdate($id)
    {
        $form = new StoreForm($id);
        if($form->load(\Yii::$app->getRequest()->post(),'') && $form->save())
        {
            return $form->getStore();
        }
        else
        {
            throw new UserException(current($form->getFirstErrors()),"400000");
        }
    }

    public function actionDelete($id)
    {
        $store = Store::findOne(['bus_id'=>\Yii::$app->getUser()->getId(),'store_id'=>$id]);
        if(!$store)
        {
            throw new UserException(ResponseMap::Map("420001"),"420001");
        }

        if($store->delete())
        {
            return ['result'=>'T'];
        }
        else
        {
            throw new UserException("系统错误",'900001');
        }
    }
}