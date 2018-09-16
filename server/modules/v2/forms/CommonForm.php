<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/7/23
 * Time: 17:20
 */

namespace app\modules\v1\forms;


use yii\base\Model;

class CommonForm extends Model
{
    //返回第一个错误信息
    public function getError()
    {
        if(empty($this->errors))
        {
            return "";
        }
        $errors = $this->errors;
        $error = array_shift($errors);
        return $error[0];
    }
}