<?php

namespace app\modules\v1;

/**
 * v1 module definition class
 */
class v1 extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\v1\controllers';

    //控制器映射
    public $controllerMap=[
       // 'sts'=>'app\modules\v1\componments\sts\StsController',
    ];

    public function init()
    {
        parent::init();




        //加载模块配置文件
        \Yii::configure($this, require(__DIR__ . '/config/config.php'));
    }
}
