<?php

namespace app\modules\v1\forms;

use app\models\Machine;
use app\modules\v1\utils\ResponseMap;
use yii\base\Model;
use yii\base\UserException;

class MachineForm extends Model
{
    public $mach_name;
    public $store_id;

    private $_machine;

    public function getMachine()
    {
        return $this->_machine;
    }

    public function __construct($id)
    {
        parent::__construct();

        if(empty($id))
        {
            $this->_machine = new Machine();
        }
        else
        {
            $this->_machine =Machine::findOne(['bus_id'=>\Yii::$app->getUser()->getId(),'mach_id'=> $id+0]);

            if(!$this->_machine)
            {
                throw new UserException(ResponseMap::Map("410001"),"410001");
            }
        }
    }

    public function rules()
    {
        return [
            ['mach_name','filter','filter'=>'trim'],
            ['mach_name','required','message'=>'设备名称不能为空'],
            ['mach_name','string','max'=>8,'tooLong'=>'设备名称不能大于15个字符'],
            ['store_id','required','when'=>function($model){
                return $this->_machine->getIsNewRecord();
            },'message'=>'门店ID不能为空']
        ];
    }

    public function save()
    {
        if($this->_machine->getIsNewRecord())
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
        $this->_machine->mach_no = makeMachineNo();
        $this->_machine->mach_name = $this->mach_name;
        $this->_machine->store_id = $this->store_id;
        $this->_machine->username = uniqid();
        $this->_machine->password = md5(\Yii::$app->getSecurity()->generateRandomString(6));
        $this->_machine->add_time = time();
        $this->_machine->bus_id = \Yii::$app->getUser()->getId();
        $this->_machine->status = 2;
        return $this->_machine->save();
    }

    public function update()
    {
        $this->_machine->mach_name = $this->mach_name;

        return $this->_machine->save();
    }
}