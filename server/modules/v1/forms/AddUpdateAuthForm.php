<?php

namespace app\modules\v1\forms;


use app\models\User;
use app\models\UserGroup;
use yii\base\Model;

class AddUpdateAuthForm extends CommonForm
{
    public $auth_id;
    public $auth_name;
    public $module_name;
    public $auth_c;
    public $auth_a;
    public $sort_order;



    public function rules()
    {
        return [
            ['auth_id','required','message'=>'id不能为空'],
            ['auth_name','required','message'=>'权限名不能为空'],
            ['module_name','required','message'=>'模块名不能为空'],
            ['auth_c','required','message'=>'控制器名不能为空'],
            ['auth_a','required','message'=>'方法名不能为空'],
            ['sort_order','required','message'=>'排序不能为空'],
            ['sort_order','match','pattern'=>'/^\d+$/'],
            ['module_name','required','message'=>'模块名不能为空'],
            ['module_name', 'unique','targetClass' => '\app\models\Auth', 'message' =>'模块名已存在'],
        ];
    }


    //登录
    public function save(){

      var_dump($this->isNewRecord);

    }

}