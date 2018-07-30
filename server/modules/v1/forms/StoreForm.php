<?php

namespace app\modules\v1\forms;

use app\models\Store;
use app\modules\v1\utils\ResponseMap;
use yii\base\Model;
use yii\base\UserException;

class StoreForm extends Model
{
    public $store_name;

    private $_store;

    public function getStore()
    {
        return $this->_store;
    }

    public function __construct($id)
    {
        parent::__construct();

        if(empty($id))
        {
            $this->_store = new Store();
        }
        else
        {
            $this->_store = Store::findOne(['bus_id'=>\Yii::$app->getUser()->getId(),'store_id'=>$id+0]);
            if(!$this->_store)
            {
                throw new UserException(ResponseMap::Map("420001"),"420001");
            }
        }
    }

    public function rules()
    {
        return [
            ['store_name','filter','filter'=>'trim'],
            ['store_name','required','message'=>'门店名称不能为空'],
            ['store_name','string','max'=>15,'tooLong'=>'门店名称不能大于8个字符']
        ];
    }

    public function save()
    {
        if($this->_store->getIsNewRecord())
        {
            return $this->validate() && $this->insert();
        }
        else
        {
            return $this->validate() && $this->update();
        }
    }

    public function insert()
    {
        $this->_store->store_name = $this->store_name;
        $this->_store->store_no = '';
        $this->_store->bus_id = \Yii::$app->getUser()->getId();
        $this->_store->add_time = time();
        $this->_store->region_id = 0;
        $this->_store->store_status = 2;
        return $this->_store->save();
    }

    public function update()
    {
        $this->_store->store_name = $this->store_name;

        return $this->_store->save();
    }
}