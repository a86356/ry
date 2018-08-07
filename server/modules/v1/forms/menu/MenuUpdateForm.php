<?php

namespace app\modules\v1\forms\userAuth;


use app\models\Auth;
use app\models\User;
use app\models\UserGroup;
use yii\base\Model;
use app\modules\v1\forms\CommonForm;

class MenuUpdateForm extends CommonForm
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
            ['auth_id', 'exist','targetClass' => '\app\models\Auth', 'message' => '权限id不存在'],
            ['auth_name','required','message'=>'权限名不能为空'],
            ['module_name','required','message'=>'模块名不能为空'],
            ['auth_c','required','message'=>'控制器名不能为空'],
            ['auth_a','required','message'=>'方法名不能为空'],
            ['sort_order','required','message'=>'排序不能为空'],
            ['sort_order','match','pattern'=>'/^\d+$/'],
            ['module_name','required','message'=>'模块名不能为空'],
        ];
    }




    //
    public function save(){

        $obj=Auth::findOne(['auth_id',$this->auth_id]);
        $obj->auth_id=$this->auth_id;
        $obj->auth_name=$this->auth_name;
        $obj->module_name=$this->module_name;
        $obj->auth_c=$this->auth_c;
        $obj->auth_a=$this->auth_a;
        $obj->sort_order=$this->sort_order;
        $obj->save(false);

    }

}