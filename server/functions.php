<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/7/23
 * Time: 15:36
 */

use yii\base\UserException;

function ApiException($msg='',$code=''){

    throw new UserException($msg,$code);
    //exit();
}
//获得随机数
function getRandom($length=32){
    $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
        'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',
        'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    // 在 $chars 中随机取 $length 个数组元素键名
    $keys = array_rand($chars, $length);
    $password = '';
    for($i = 0; $i < $length; $i++)
    {
        // 将 $length 个数组元素连接成字符串
        $password .= $chars[$keys[$i]];
    }
    return $password;
}

//调试
function p($data)
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    die;
}

function table_prefix(){
    return "tk_";
}