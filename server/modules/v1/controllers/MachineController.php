<?php

namespace app\modules\v1\controllers;

use app\models\Machine;
use app\modules\v1\common\BaseController;
use app\modules\v1\forms\MachineForm;
use app\modules\v1\utils\ResponseMap;
use yii\base\UserException;

class MachineController extends BaseController
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

    public function actionIndex($store_id=null)
    {
        $query = Machine::find()->where(['bus_id'=>\Yii::$app->getUser()->getId()]);

        if(!empty($store_id))
        {
            $query->andWhere(['store_id'=>$store_id+0]);
        }

        $data = $query->all();

        $items = [];
        foreach($data as $v)
        {
            $items[] = ['mach_no'=>$v->mach_no,
                'mach_name'=>$v->mach_name,
                'store_name'=>$v->store->store_name,
                'mach_id'=>$v->mach_id,
                'store_id'=>$v->store_id,
                //'mach_bg' => 'https://appapi.zjhylh.com/upload/admin/20180427/7128f4d084764b489935ed738d1801fe.png',
                //'mach_bg' => 'https://appapi.zjhylh.com/upload/admin/20180427/2a7af08dd20cfadc4f1288ea16b8ba15.jpg'
                //'mach_bg' => 'https://appapi.zjhylh.com/upload/admin/20180427/16796a9a86853b24d4ea87b0b5820408.png',
                'mach_bg'=> 'https://appapi.zjhylh.com/upload/admin/20180502/ab8a800d12ed84b4001d3b320d87b635.png',
            ];
        }

        return $items;
    }

    //返回商户的指定设备
    public function actionView($id)
    {
        $machine = Machine::findOne(['bus_id'=>\Yii::$app->getUser()->getId(),'mach_id'=>$id]);

        if(!$machine)
        {
            throw new UserException(ResponseMap::Map("410001"),"410001");
        }

        $data = [
            'mach_no'=>$machine->mach_no,
            'mach_name'=>$machine->mach_name,
            'store_name'=>$machine->store->store_name,
            'mach_id'=>$machine->mach_id,
            'store_id'=>$machine->store_id
        ];

        return $data;
    }

    //创建设备
    public function actionCreate()
    {
        $form = new MachineForm(null);
        if($form->load(\Yii::$app->getRequest()->post(),'') && $form->save())
        {
            return $form->getMachine();
        }
        else
        {
            throw new UserException(current($form->getFirstErrors()),"400000");
        }
    }

    //更新设备信息
    public function actionUpdate($id)
    {
        $form = new MachineForm($id);
        if($form->load(\Yii::$app->getRequest()->post(),'') && $form->save())
        {
            return $form->getMachine();
        }
        else
        {
            throw new UserException(current($form->getFirstErrors()),"400000");
        }
    }

    //删除设备
    public function actionDelete($id)
    {
        $machine = Machine::findOne(['mach_id'=>$id,'bus_id'=>\Yii::$app->getUser()->getId()]);
        if(!$machine)
        {
            throw new UserException(ResponseMap::Map("410001"),"410001");
        }

        if($machine->delete())
        {
            return ['result'=>'T'];
        }
        else
        {
            throw new UserException("系统错误",'900001');
        }
    }
}