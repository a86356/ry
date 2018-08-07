<?php

namespace app\modules\v1\forms\user;


use app\models\Group;
use app\models\User;
use yii\base\Model;
use app\modules\v1\forms\CommonForm;

class AddGroupForm extends CommonForm
{
    public $name;

    private $_user;


    public function rules()
    {
        return [
            ['name','required','message'=>'组名不能为空'],
            ['name', 'unique','targetClass' => '\app\models\Group', 'message' =>'组名已存在'],
        ];
    }

    //登录
    public function save(){


        $model=new Group();
        $model->name=$this->name;
        $model->save();


    }
}